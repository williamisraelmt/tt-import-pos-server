<?php

namespace App\Console\Commands;

use App\Customer;
use App\DebtCollector;
use App\Http\Controllers\Grid;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     * @throws \Edujugon\Laradoo\Exceptions\OdooException
     */
    public function handle()
    {

        dd(User::find(1)->hasRole('customer'));

        $odoo = new \Edujugon\Laradoo\Odoo();
        $connection = $odoo
            ->username('williamtoribio@gmail.com')
            ->password('WT@ribi@2019!')
            ->db('toribiotejadaimport')
            ->host('https://toribiotejadaimport.odoo.com')
            ->connect();

        dd($connection
            ->limit(1)
//            ->fields([
//                'id',
//                'amount',
//                'partner_id',
//                'invoice_ids',
//                'payment_date',
//            ])
//            ->where('payment_type', '=', 'inbound')
//            ->where('has_invoices', '=', true)
            ->get('account.invoice'));

    }
}
