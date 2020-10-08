<?php

namespace App\Console\Commands;

use App\Console\Commands\Traits\OdooDownload;
use App\Customer;
use App\Invoice;
use App\Odoo\OdooInvoice;
use Carbon\Carbon;
use Edujugon\Laradoo\Exceptions\OdooException;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class DownloadInvoices extends Command
{
    use OdooDownload;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'download:invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will download invoice data for last 7 days';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->table = "account.invoice";

        $this->fields = [
            "id",
            "name",
            "create_date",
            "display_name",
            "number",
            "reference",
            "date_invoice",
            "date_due",
            "partner_id",
            "date",
            "amount_total",
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
        $this->info("Starting to download invoices");

        try {
            $this->download()
                ->map(function ($record) {
                    return new OdooInvoice(
                        $record["id"],
                        trim($record["name"]),
                        trim($record["create_date"]),
                        trim($record["display_name"]),
                        trim($record["number"]),
                        trim($record["reference"]),
                        $record["date_invoice"],
                        $record["date_due"],
                        $record["partner_id"][0],
                        $record["date"],
                        $record["amount_total"]
                    );
                })
                ->filter(function (OdooInvoice $invoice) {
                    return Customer::find($invoice->getPartnerId()) !== null;
                })
                ->chunk(1000)->each(function (Collection $records) {
                    $this->info("Inserting/updating list {$records->count()} records to the database...");
                    Invoice::insertOnDuplicateKey(
                        $records
                            ->filter(function (OdooInvoice $invoice) {
                                $invoice->getName() !== "" && $invoice->getNumber() !== "" && $invoice->getDate() !== "";
                            })
                            ->map(function (OdooInvoice $invoice) {
                                return $invoice->toStoreAsArray();
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
