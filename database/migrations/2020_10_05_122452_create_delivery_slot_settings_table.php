<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliverySlotSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_slot_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('date_interval')->default(0)->comment('slot date start from after  this interval');
            $table->integer('date_end')->default(0)->comment('slot date will be disabled after this day');
            $table->tinyInteger('status')->default(1)->comment('date time slot will show = 1  or not = 0');
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
        Schema::dropIfExists('delivery_slot_settings');
    }
}
