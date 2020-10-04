<?php

namespace App\Console\Commands;

use App\Console\Commands\Models\OdooCustomer;
use App\Console\Commands\Traits\OdooDownload;
use App\Customer;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class DownloadCustomers extends Command
{
    use OdooDownload;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'download:customers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will download customers';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->table = "res.partner";
        $this->fields = [
            'id',
            'name',
            'contact_address',
            'phone'
        ];
        $this->where = [
            [
                "customer",
                "=",
                true
            ]
        ];
        $this->connection = new OdooConnectionModel(
            config("odoo.user"),
            config("odoo.password"),
            config("odoo.database"),
            config("odoo.host")
        );
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Starting to download customers");

        try {
            $this->download()
                ->map(function ($record) {
                    return new OdooCustomer(
                        $record["id"],
                        $record["name"],
                        $record['contact_address'],
                        $record['phone']
                    );
                })
                ->chunk(1000)->each(function (Collection $records) {
                    $this->info("Inserting/updating list {$records->count()} records to the database...");
                    Customer::insertOnDuplicateKey(
                        $records->map(function (OdooCustomer $customer) {
                            return $customer->toStoreAsArray();
                        })->values()->toArray()
                    );
                });
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            $this->error($e->getTraceAsString());
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
        }
    }
}
