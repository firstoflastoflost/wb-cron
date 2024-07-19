<?php

namespace App\Services;

use Yclients\YclientsApi;

class YclientsService
{
    public YclientsApi $yc;
    public string $token;
    public $companyId = '1081884';

    public function __construct()
    {
        $login = env('YC_LOGIN');
        $password = env('YC_PWD');
        $tokenPartner = env('YC_TOKEN');

        $this->yc = new YclientsApi($tokenPartner);

        // Включаем отладочный режим с логированием в файл
        $this->yc->debug = true;

        // Устанавливаем лог файл отладочного режима
        $this->yc->debugLogFile = storage_path('logs/debug_yclients_api.log');

        // Устанавливает максимальное число запросов к API YCLIENTS в секунду (значение 0 отключает троттлинг запросов к API)
        $this->yc->throttle = 3;

        try {
            $response = $this->yc->getAuth($login, $password);
            $this->token = $response['data']['user_token'];
        }catch (\Exception $e){
            throw new \Exception("error get user token: ". $e->getMessage() . " ");
        }
    }

    public function uploadClients(array $clients)
    {

    }
}
