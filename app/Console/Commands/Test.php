<?php

namespace App\Console\Commands;

use App\Customer;
use Carbon\Carbon;
use Illuminate\Console\Command;

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
        $odoo = new \Edujugon\Laradoo\Odoo();
        $connection = $odoo
            ->username('williamtoribio@gmail.com')
            ->password('WT@ribi@2019!')
            ->db('toribiotejadaimport')
            ->host('https://toribiotejadaimport.odoo.com')
            ->connect();
        dd($connection
            ->where('customer', '=', true)
            ->where('active', '=', true)
            ->fields([
                'name',
                'contact_address',
                'phone'
            ])
            ->limit(1)
            ->get('res.partner'));

    }
}
