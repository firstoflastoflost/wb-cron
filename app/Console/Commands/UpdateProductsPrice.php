<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Jobs\UpdateProductsPrice as UpdateProductsPriceJob;

class UpdateProductsPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-products:price';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update product price';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $lockName = 'update-products:price-running';
        $lock = Cache::lock($lockName, 300);

        if ($lock->get()) {
            sleep(10);
            try {
                UpdateProductsPriceJob::dispatch();
                $this->updateScheduleFile();
            } finally {
                $lock->release();
            }
        } else {
            $this->info('Command is already running');
            return 0;
        }

        return 0;
    }

    private function updateScheduleFile(): void
    {
        $filePath = 'schedule-updates.json';
        $currentData = [];

        if (Storage::exists($filePath)) {
            $content = Storage::get($filePath);
            $currentData = json_decode($content, true);
        }

        $currentDate = Carbon::now()->toDateTimeString();

        if (!isset($currentData['schedule_updates'])) {
            $currentData['schedule_updates'] = [];
        }

        $currentData['last_update'] = $currentDate;
        $currentData['schedule_updates'][] = $currentDate;

        Storage::put($filePath, json_encode($currentData, JSON_PRETTY_PRINT));
    }
}
