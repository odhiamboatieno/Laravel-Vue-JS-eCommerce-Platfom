<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('provider');
            $table->text('client_id')->comment = "encryption_key for flutter";
            $table->text('client_secret')->comment = "secret_key for flutter";
            $table->text('public_key')->nullable()->comment('flutter public key');
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('live_status')->default(1);
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
        Schema::dropIfExists('payment_settings');
    }
}
