<?php

namespace App\Console\Commands;

use App\Services\YclientsService;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yclients\YclientsApi;

class YclientsUpload extends Command
{
    protected $signature = 'yc:upload';
    protected $description = 'Парсинг Excel файла и формирование массива данных';

    public function handle()
    {
        $filePath = storage_path('app/uploads/clients.xls');

        if (!file_exists($filePath)) {
            $this->error("Файл не найден: " . $filePath);
            return;
        }

        $array = Excel::toArray(null, $filePath)[0];

        $clients = [];

        foreach ($array as $key => $row) {
            $name = trim($row[0]);
            $sex = $row[1];
            $phoneField = $row[2];
            $total = $row[3];
            $firstDate = $this->safeTransformDate($row[4]);
            $birthday = $this->safeTransformDate($row[5]);
            $card = $row[6] ? trim($row[6]) : '';

            if ($birthday > $firstDate) {
                $birthday = "";
            }

            list($phone, $comment) = $this->extractPhoneAndComment($phoneField);

            // Пропустить строку, если имя или телефон отсутствует или телефон некорректен
            if (empty($name) || empty($phone) || strlen($phone) != 11) {
                continue;
            }

            $existingClientKey = array_search($phone, array_column($clients, 'phone'));

            if ($existingClientKey !== false) {
                $clients[$existingClientKey]['comment'] .= " На этот телефон также привязан: " . $name;
                if (!empty($total)){
                    $clients[$existingClientKey]['comment'] .= ", с посещениями на сумму " . $total;
                }
                if (!empty($card)){
                    $clients[$existingClientKey]['comment'] .= ", с карточкой " . $card;
                }
            } else {
                // Создаем нового клиента
                $client = [
                    'name' => $name,
                    'phone' => $phone,
                    'spent' => $total,
                    'birth_date' => $birthday,
                    'comment' => $comment,
                    'card' => $card
                ];

                if (!empty($sex)) {
                    $client['sex'] = $sex == 'М' ? 1 : 2;
                }

                $clients[] = $client;
            }
        }

        try {
            $ycService = new YclientsService();
        }catch (\Exception $e){
            $this->output->writeln("error yc: {$e->getMessage()}");
            return 1;
        }

        $maxRequestsPerMinute = 200;
        $maxRequestsPerSecond = 5;
        $totalRequests = count($clients);
        $requestsSent = 0;
        $startMinute = time();
        $startSecond = time();

        foreach ($clients as $key => $client) {
            $currentTime = time();
            if ($requestsSent >= $maxRequestsPerMinute && ($currentTime - $startMinute) < 60) {
                sleep(60 - ($currentTime - $startMinute));
                $startMinute = time();
                $requestsSent = 0;
            }
            if ($requestsSent % $maxRequestsPerSecond == 0 && ($currentTime - $startSecond) < 1) {
                sleep(1 - ($currentTime - $startSecond));
                $startSecond = time();
            }


            try {
                $ycService->yc->postClients(
                    $ycService->companyId,
                    $client['name'],
                    $client['phone'],
                    $ycService->token,
                    [
                        'paid' => $client['spent'],
                        'spent' => $client['spent'],
                        'birth_date' => $client['birth_date'],
                        'comment' => $client['comment'],
                        'card' => $client['card']
                    ],
                );
                $requestsSent++;
            }catch (\Exception $e){
                $this->output->writeln("error upload client: {$e->getMessage()}. \n");
                continue;
            }
        }



    }

    private function safeTransformDate($value)
    {
        // Проверяем, что значение не пустое
        if (empty($value)) {
            return '';
        }

        // Если значение - число, то конвертируем серийное число Excel в дату
        if (is_numeric($value)) {
            $timestamp = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($value);
            return date('Y-m-d', $timestamp);
        }

        // Проверяем, что значение соответствует формату даты (dd-mm-yyyy)
        if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $value)) {
            // Разделяем дату на компоненты
            list($day, $month, $year) = explode('-', $value);

            // Проверяем, что дата корректна
            if (!checkdate($month, $day, $year)) {
                return '';
            }

            // Возвращаем дату в формате 'Y-m-d'
            return sprintf('%04d-%02d-%02d', $year, $month, $day);
        }

        // Если ничего не подошло, возвращаем пустую строку
        return '';
    }

    private function extractPhoneAndComment($phoneField)
    {
        // Извлекаем все номера телефонов
        preg_match_all('/\+?\d[\d\-\(\) ]+/', $phoneField, $matches);

        $phones = $matches[0];
        $mainPhone = '';
        $comment = $phoneField;

        if (!empty($phones)) {
            $mainPhone = $phones[count($phones) - 1]; // Последний номер телефона
            $comment = str_replace($mainPhone, '', $comment);
            $comment = trim($comment);

            // Удаление всех нецифровых символов из номера телефона
            $mainPhone = preg_replace('/\D/', '', $mainPhone);
        }

        return [$mainPhone, $comment];
    }

}
