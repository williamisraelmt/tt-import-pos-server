<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('name');
            $table->string('default_code');
            $table->boolean('status')->nullable()->default(true);
            $table->float('available_quantity')->nullable()->default(0);
            $table->float('lst_price')->nullable()->default(0);
            $table->float('list_price')->nullable()->default(0);
            $table->float('standard_price')->nullable()->default(0);
            $table->string('default_photo_url')->nullable();
            $table->unsignedBigInteger('product_category_id')->nullable();
            $table->unsignedBigInteger('product_brand_id')->nullable();
            $table->unsignedBigInteger('product_department_id')->nullable();
            $table->unsignedBigInteger('product_type_id')->nullable();
            $table->timestamps();
        });

        Schema::table('products', function(Blueprint $table){
            $table->foreign('product_category_id')->references('id')->on('product_categories');
            $table->foreign('product_brand_id')->references('id')->on('product_brands');
            $table->foreign('product_department_id')->references('id')->on('product_departments');
            $table->foreign('product_type_id')->references('id')->on('product_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
