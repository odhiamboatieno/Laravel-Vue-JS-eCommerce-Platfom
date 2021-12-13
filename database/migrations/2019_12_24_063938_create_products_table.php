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
            $table->increments('id');
            $table->integer('category_id');
            $table->integer('sub_category_id');
            $table->integer('sub_sub_category_id')->nullable()->default(0);
            $table->integer('brand_id')->nullable()->default(0);
            $table->string('product_name');
            $table->string('product_native_name')->nullable();
            $table->string('product_image');
            $table->string('product_thumb')->nullable();
            $table->text('product_description')->nullable();
            $table->text('product_keyword')->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->integer('current_quantity')->default(0);
            $table->string('quantity_unit')->nullable();
            $table->double('buying_price');
            $table->double('selling_price');
            $table->integer('total_view')->default(0);
            $table->integer('total_sold')->default(0);
            $table->double('price')->default(0);
            $table->tinyInteger('discount_type')->default(0)->comment = "1 is amount 2 is percent";
            $table->tinyInteger('discount_status')->default(0)->comment = "0 = no discount , 1= discount";
            $table->double('discount')->default(0);
            $table->double('discount_amount')->default(0);
            $table->integer('campaign_id')->default(0)->comments = "if it has offer the id will be non zero";
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->tinyInteger('home_view')->default(1);
            $table->tinyInteger('hot_deal')->default(0);
            $table->tinyInteger('trialable')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
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
