<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockhistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stockhistories', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->string('product_name');
            $table->string('product_brand');
            $table->string('product_category');
            $table->string('product_sub_category');
            $table->string('added_new_stock');
            $table->string('date');
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
        Schema::dropIfExists('stockhistories');
    }
}
