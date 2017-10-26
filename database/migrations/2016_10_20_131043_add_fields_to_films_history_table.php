<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToFilmsHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('films_history', function (Blueprint $table) {
            $table->boolean('available_hd')->after('track_hd_rental_price_last')->default(0)->index();
            $table->boolean('decreased_hd')->after('available_hd')->default(0)->index();
            $table->boolean('available_sd')->after('decreased_hd')->default(0)->index();
            $table->boolean('decreased_sd')->after('available_sd')->default(0)->index();
            $table->boolean('available_hd_rent')->after('decreased_sd')->default(0)->index();
            $table->boolean('decreased_hd_rent')->after('available_hd_rent')->default(0)->index();
            $table->boolean('available_sd_rent')->after('decreased_hd_rent')->default(0)->index();
            $table->boolean('decreased_sd_rent')->after('available_sd_rent')->default(0)->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('films_history', function (Blueprint $table) {
            $table->dropColumn('available_hd');
            $table->dropColumn('decreased_hd');
            $table->dropColumn('available_sd');
            $table->dropColumn('decreased_sd');
            $table->dropColumn('available_hd_rent');
            $table->dropColumn('decreased_hd_rent');
            $table->dropColumn('available_sd_rent');
            $table->dropColumn('decreased_sd_rent');
        });
    }
}
