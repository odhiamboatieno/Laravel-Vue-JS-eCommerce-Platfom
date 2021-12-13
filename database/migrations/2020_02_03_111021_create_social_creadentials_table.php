<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialCreadentialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_creadentials', function (Blueprint $table) {
            $table->increments('id');
            $table->string('provider');
            $table->text('app_id');
            $table->text('app_secret');
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
        Schema::dropIfExists('social_creadentials');
    }
}
