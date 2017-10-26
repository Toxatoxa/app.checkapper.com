<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUpdationToFilmThumbsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('film_thumbs', function (Blueprint $table) {
            $table->boolean('is_updating')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('film_thumbs', function (Blueprint $table) {
            $table->dropColumn('is_updating');
        });
    }
}
