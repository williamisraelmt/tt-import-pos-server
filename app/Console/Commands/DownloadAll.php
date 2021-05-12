<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DownloadAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'download:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download customers and invoices';

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
        $this->call(DownloadCustomers::class);
        $this->call(DownloadInvoices::class);
        $this->call(DownloadProducts::class);
        $this->call(DownloadPayments::class);
        return 0;
    }
}
