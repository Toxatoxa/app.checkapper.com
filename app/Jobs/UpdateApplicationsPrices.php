<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateApplicationsPrices extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $applications;

    /**
     * Create a new job instance.
     * @param $applications
     */
    public function __construct($applications)
    {
        $this->applications = $applications;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Запрос в itunes и обновление цены.
    }
}
