<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameTableColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('films', function (Blueprint $table) {
            $table->dropColumn('primary_genre_name');
            $table->integer('genre_id')->unsigned()->index()->after('itunes_country');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('films', function (Blueprint $table) {
            $table->dropColumn('genre_id');
            $table->integer('primary_genre_name')->unsigned()->after('itunes_country');
        });
    }
}
