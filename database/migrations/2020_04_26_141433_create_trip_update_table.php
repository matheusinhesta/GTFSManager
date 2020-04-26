<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripUpdateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trip_update', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trip_descriptor_id');
            $table->unsignedBigInteger('vehicle_id')->nullable();
            $table->unsignedBigInteger('stop_time_update_id');
            $table->time('delay')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('trip_descriptor_id')
                ->references('id')->on('trip_descriptor');

            $table->foreign('stop_time_update_id')
                ->references('id')->on('stop_time_update');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trip_update');
    }
}
