<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrialProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trial_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->integer('category_id');
            $table->integer('sub_category_id');
            $table->integer('sub_sub_category_id')->nullable()->default(0);
            $table->integer('brand_id')->default(0);
            $table->integer('product_id');
            $table->integer('color_id')->nullable()->default(0);
            $table->integer('size_id')->nullable()->default(0);
            $table->integer('user_id')->comment = "customer_id";
            $table->integer('quantity');
            $table->double('selling_price');
            $table->double('buying_price');
            $table->double('total_buying_price');
            $table->double('total_selling_price');
            $table->double('unit_discount')->default(0);
            $table->double('total_discount')->default(0);
            $table->tinyInteger('trialed')->default(0)->comment('0 = not trialed , 1 = trialed');
            $table->tinyInteger('invoiced')->default(0)->comment('0 = not invoiced, 1 = invoiced');
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('trial_products');
    }
}
