<?php

namespace App\Jobs;

use App\Utils\StateTypes;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

class OdooSync implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $odooSync;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(\App\OdooSync $odooSync)
    {
        $this->odooSync = $odooSync;
    }

    /**
     * Execute the job.
     *
     * @param
     * @return void
     */
    public function handle()
    {
        try {
            Artisan::call("download:all");
            $this->odooSync->completed_at = now()->toDateTimeString();
            $this->odooSync->state = StateTypes::SUCCEED;
            $this->odooSync->save();
        } catch (\Exception $e) {
            $this->odooSync->completed_at = now()->toDateTimeString();
            $this->odooSync->state = StateTypes::FAILED;
            $this->odooSync->save();
            $this->fail($e);
        }
    }

}
