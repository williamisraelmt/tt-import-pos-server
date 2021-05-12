<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDebtCollectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debt_collectors', function (Blueprint $table) {

            $table->id();

            $table->string('name')->unique();

            $table->float('commission');

            $table->boolean('status')->nullable()->default(true);

            $table->timestamps();

        });

        Schema::table('customers', function (Blueprint  $table){

            $table->unsignedBigInteger('debt_collector_id')->nullable()->after('note');

        });

        Schema::table('customers', function (Blueprint $table) {

            $table->foreign('debt_collector_id')->references('id')->on('debt_collectors');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('debt_collectors');
    }
}
