<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryLeadDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_lead_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('delivery_lead_id');
            $table->unsignedBigInteger('invoice_id');
            $table->timestamps();
        });

        Schema::table('delivery_lead_details', function (Blueprint $table) {
            $table->foreign('delivery_lead_id')->references('id')->on('delivery_leads');
            $table->foreign('invoice_id')->references('id')->on('invoices');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_lead_details');
    }
}
