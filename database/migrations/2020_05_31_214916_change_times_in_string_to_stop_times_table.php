<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTimesInStringToStopTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stop_times', function (Blueprint $table) {
            $table->string('arrival_time')->nullable()->change();
            $table->string('departure_time')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stop_times', function (Blueprint $table) {
            $table->time('arrival_time')->nullable()->change();
            $table->time('departure_time')->nullable()->change();
        });
    }
}
