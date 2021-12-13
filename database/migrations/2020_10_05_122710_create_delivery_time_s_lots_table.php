<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryTimeSLotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_time_s_lots', function (Blueprint $table) {
            $table->id();
            $table->string('slot_name');
            $table->time('expired_at')->comment('slot will be expired if current time greater then this time for same date');
            $table->tinyInteger('status')->default(1)->comment('active or not');
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
        Schema::dropIfExists('delivery_time_s_lots');
    }
}
