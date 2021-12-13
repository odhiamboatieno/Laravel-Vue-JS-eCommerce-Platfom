<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCouponTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_coupon', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('coupon_code');
            $table->string('valid_date');
            $table->tinyInteger('is_used')->default(0);
            $table->tinyInteger('is_applied')->default(0);
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
        Schema::dropIfExists('user_coupon');
    }
}
