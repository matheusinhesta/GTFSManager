<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStopTimeUpdateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stop_time_update', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stop_sequence')->nullable();
            $table->unsignedBigInteger('stop_id')->nullable();
            $table->bigInteger('arrival')->nullable();
            $table->bigInteger('departure')->nullable();
            $table->unsignedBigInteger('schedule_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stop_time_update');
    }
}
