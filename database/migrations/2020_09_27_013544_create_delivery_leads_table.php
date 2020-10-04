<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_leads', function (Blueprint $table) {
            $table->id();
            $table->float('package_quantity');
            $table->unsignedBigInteger('customer_id');
            $table->timestamps();
        });

        Schema::table('delivery_leads', function(Blueprint $table){
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
        Schema::dropIfExists('delivery_leads');
    }
}
