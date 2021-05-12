<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOdooSyncsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('odoo_syncs', function (Blueprint $table) {
            $table->id();
            $table->enum('state', ['IN_PROGRESS', 'SUCCEED', 'FAILED'])->nullable()->default('IN_PROGRESS');
            $table->boolean('displayed')->nullable()->default(false);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::table('odoo_syncs', function (Blueprint  $table){
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('odoo_syncs');
    }
}
