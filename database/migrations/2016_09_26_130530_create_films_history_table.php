<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilmsHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('films_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('film_id')->unsigned();
            $table->char('itunes_country', 2);

            $table->decimal('collection_price', 5, 2)->unsigned()->nullable()->default(null);
            $table->decimal('collection_hd_price', 5, 2)->unsigned()->nullable()->default(null);
            $table->decimal('track_price', 5, 2)->unsigned()->nullable()->default(null);
            $table->decimal('track_hd_price', 5, 2)->unsigned()->nullable()->default(null);
            $table->decimal('track_rental_price', 5, 2)->unsigned()->nullable()->default(null);
            $table->decimal('track_hd_rental_price', 5, 2)->unsigned()->nullable()->default(null);

            $table->decimal('collection_price_last', 5, 2)->unsigned()->nullable()->default(null);
            $table->decimal('collection_hd_price_last', 5, 2)->unsigned()->nullable()->default(null);
            $table->decimal('track_price_last', 5, 2)->unsigned()->nullable()->default(null);
            $table->decimal('track_hd_price_last', 5, 2)->unsigned()->nullable()->default(null);
            $table->decimal('track_rental_price_last', 5, 2)->unsigned()->nullable()->default(null);
            $table->decimal('track_hd_rental_price_last', 5, 2)->unsigned()->nullable()->default(null);

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
        Schema::drop('films_history');
    }
}
