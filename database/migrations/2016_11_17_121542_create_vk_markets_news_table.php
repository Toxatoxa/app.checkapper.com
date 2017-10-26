<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVkMarketsNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vk_markets_news', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vk_market_item_id');
            $table->decimal('price');
            $table->decimal('previous_price');
            $table->char('currency', 3);
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
        Schema::drop('vk_markets_news');
    }
}
