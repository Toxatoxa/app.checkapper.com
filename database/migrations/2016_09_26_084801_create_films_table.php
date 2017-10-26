<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('films', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('status', ['new', 'updating', 'done', 'error']);
            $table->integer('track_id')->unsigned()->index();
            $table->char('itunes_country', 2)->index();
            $table->string('track_name');
            $table->string('artist_name');
            $table->string('track_censored_name');
            $table->string('track_view_url');
            $table->string('preview_url');
            $table->string('artwork_url_30');
            $table->string('artwork_url_60');
            $table->string('artwork_url_100');

            $table->decimal('collection_price', 5, 2)->unsigned()->nullable()->default(null);
            $table->decimal('collection_hd_price', 5, 2)->unsigned()->nullable()->default(null);
            $table->decimal('track_price', 5, 2)->unsigned()->nullable()->default(null);
            $table->decimal('track_hd_price', 5, 2)->unsigned()->nullable()->default(null);
            $table->decimal('track_rental_price', 5, 2)->unsigned()->nullable()->default(null);
            $table->decimal('track_hd_rental_price', 5, 2)->unsigned()->nullable()->default(null);

//            $table->decimal('collection_price_last', 5, 2)->unsigned()->nullable()->default(null);
//            $table->decimal('collection_hd_price_last', 5, 2)->unsigned()->nullable()->default(null);
//            $table->decimal('track_price_last', 5, 2)->unsigned()->nullable()->default(null);
//            $table->decimal('track_hd_price_last', 5, 2)->unsigned()->nullable()->default(null);
//            $table->decimal('track_rental_price_last', 5, 2)->unsigned()->nullable()->default(null);
//            $table->decimal('track_hd_rental_price_last', 5, 2)->unsigned()->nullable()->default(null);


            $table->integer('track_time_millis')->unsigned();
            $table->char('country', 3);
            $table->char('currency', 3);
            $table->timestamp('release_date');
            $table->integer('primary_genre_name')->unsigned();
            $table->string('content_advisory_rating');
            $table->text('short_description');
            $table->text('long_description');
            $table->timestamps();

            $table->unique(['track_id', 'itunes_country']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('films');
    }
}
