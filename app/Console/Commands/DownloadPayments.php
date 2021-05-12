<?php

namespace App\Console\Commands;

use App\Console\Commands\Models\OdooInvoice;
use App\Console\Commands\Models\OdooPayment;
use App\Console\Commands\Models\OdooPaymentInvoice;
use App\Console\Commands\Traits\OdooDownload;
use App\Customer;
use App\Invoice;
use App\Payment;
use App\PaymentInvoice;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DownloadPayments extends Command
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

        $this->table = "account.payment";

        $this->fields = [
            'id',
            'name',
            'amount',
            'partner_id',
            'invoice_ids',
            'payment_date',
        ];

        $this->where = [
            ['payment_type', '=', 'inbound'],
            ['has_invoices', '=', true],
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
        $this->info("Starting to download payments");

        try {
            DB::beginTransaction();
            $this->download()
                ->map(function ($record) {
                    return new OdooPayment(
                        $record["id"],
                        $record["name"],
                        trim($record["amount"]),
                        $record["partner_id"][0],
                        collect($record["invoice_ids"])->map(function ($invoiceId) use ($record) {
                            return new OdooPaymentInvoice($invoiceId, $record['id']);
                        }),
                        $record["payment_date"]
                    );
                })
                ->filter(function (OdooPayment $payment) {
                    return Customer::find($payment->getPartnerId()) !== null;
                })
                ->map(function (OdooPayment $payment) {
                    $customer = Customer::with('debtCollector')->find($payment->getPartnerId());
                    if ($customer->debt_collector_id === null) {
                        return $payment;
                    }
                    $payment->setDebtCollector($customer->debtCollector);
                    return $payment;
                })
                ->chunk(200)->each(function (Collection $records) {

                    $this->info("Inserting/updating list {$records->count()} records to the database...");

                    Payment::insertOnDuplicateKey(
                        $records->map(function (OdooPayment $payment) {
                            return $payment->toStoreAsArray();
                        })->values()->toArray()
                    );

                    DB::table(PaymentInvoice::getTableName())->whereIn('payment_id', $records->flatMap(function (OdooPayment $payment) {
                        return $payment->getPaymentInvoices()
                            ->map(function (OdooPaymentInvoice $paymentInvoice) {
                                return $paymentInvoice->getPaymentId();
                            });
                    })->values())->delete();

                    $this->info("Deleting {$records->count()} records from Payment Invoices...");

                    $payment_invoices = $records->flatMap(function (OdooPayment $payment) {
                        return $payment->getPaymentInvoices()
                            ->map(function (OdooPaymentInvoice $paymentInvoice) {
                                return $paymentInvoice->toStoreAsArray();
                            });
                    })->values();

                    $this->info("Inserting/updating list of payment invoices {$payment_invoices->count()} records to the database...");

                    PaymentInvoice::insertOnDuplicateKey( $payment_invoices->toArray() );

                });
            DB::commit();
            Log::info('Downloaded payments');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error($e->getMessage());
            $this->error($e->getTraceAsString());
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
        }
    }
}
