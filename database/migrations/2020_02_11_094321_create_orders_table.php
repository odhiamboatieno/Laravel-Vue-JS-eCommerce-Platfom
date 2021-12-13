<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->primary('id');
            $table->integer('user_id')->comment = "customer id";
            $table->integer('location_id')->nullable()->default(0)->comment = "location id";
            $table->string('customer_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->integer('shipping_area_id')->nullable()->comment = "shipping area id";
            $table->double('shipping_amount')->default(0);
            $table->integer('total_item')->default(0);
            $table->double('total_amount');
            $table->double('total_buying_amount')->default(0);
            $table->string('cupon')->nullable();
            $table->double('cash_back')->nullable();
            $table->double('discount')->default(0);
            $table->double('coupon_discount')->default(0);
            $table->text('payment_id')->nullable();
            $table->text('payer_id')->nullable();
            $table->tinyInteger('payment_status')->default(0)->comment = "0 not paid 1 = paid";
            $table->tinyInteger('payment_method')->default(0)->comments = "0 not paid , 1 cash , 2 paypal, 3 stripe , 4 SSL";
            $table->string('card_type')->nullable();
            $table->string('validation_id')->nullable();
            $table->string('order_date');
            $table->string('customer_delivery_date')->nullable();
            $table->string('customer_delivery_time')->nullable();
            $table->string('payment_date')->nullable();
            $table->string('delivery_date')->nullable();
            $table->integer('delivered_by')->default(0);
            $table->integer('confirmed_by')->default(0);
            $table->text('note')->nullable();
            $table->tinyInteger('status')->default(0)->comment = "0 pending,1=on process,2=on delivery,3 = delivered";
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
        Schema::dropIfExists('orders');
    }
}
