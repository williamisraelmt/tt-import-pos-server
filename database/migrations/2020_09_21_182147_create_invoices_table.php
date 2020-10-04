<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();

            $table->string('name');

            $table->string('display_name');

            $table->string('number');

            $table->string('reference');

            $table->float('amount_total');

            $table->date('create_date');

            $table->date('date_invoice');

            $table->date('date_due');

            $table->date('date');

            $table->unsignedBigInteger('customer_id');

            $table->timestamps();
        });

        Schema::table('invoices', function(Blueprint $table){
           $table->foreign('customer_id')->references('id')->on('customers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
