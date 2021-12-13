<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('shop_name');
            $table->string('shop_short_name')->nullable();
            $table->text('address');
            $table->text('footer_text');
            $table->text('phone');
            $table->text('email');
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('youtube')->nullable();
            $table->string('logo_header');
            $table->string('logo_footer')->nullable();
            $table->string('favicon');
            $table->string('theme_color');
            $table->tinyInteger('hot_deal_status')->nullable()->default(1);
            $table->tinyInteger('slider_status')->nullable()->default(1);
            $table->tinyInteger('onsale_status')->nullable()->default(1);
            $table->tinyInteger('sidemenu_status')->nullable()->default(1);
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
        Schema::dropIfExists('shop_settings');
    }
}
