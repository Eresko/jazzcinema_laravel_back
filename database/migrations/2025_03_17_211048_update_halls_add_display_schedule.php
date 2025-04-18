<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateHallsAddDisplaySchedule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('halls', function (Blueprint $table) {
            $table->boolean('is_display_schedule')->default(true);
            $table->dropColumn('unloading schedule');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('halls', function (Blueprint $table) {
            $table->boolean('unloading schedule')->default(true);
            $table->dropColumn('is_display_schedule');
        });
    }
}
