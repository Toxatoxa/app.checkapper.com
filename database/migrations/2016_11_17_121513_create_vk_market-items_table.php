<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVkMarketItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vk_market_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vk_market_id')->unsigned()->index();
            $table->integer('vk_id')->unsigned()->index();
            $table->string('title');
            $table->text('description');
            $table->decimal('price');
            $table->char('currency', 3);
            $table->string('thumb_photo');
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
        Schema::drop('vk_market_items');
    }
}
