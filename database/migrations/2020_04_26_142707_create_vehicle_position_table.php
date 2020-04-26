<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclePositionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_position', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trip_descriptor_id');
            $table->unsignedBigInteger('vehicle_id');
            $table->float('latitude');
            $table->float('longitude');
            $table->float('bearing')->nullable();
            $table->double('odometer', 8,2)->nullable();
            $table->float('speed')->nullable();
            $table->bigInteger('current_stop_sequence')->nullable();
            $table->unsignedBigInteger('stop_id');
            $table->enum('current_status', ['INCOMING_AT', 'STOPPED_AT', 'IN_TRANSIT_TO'])->nullable();
            $table->enum('congestion_level', ['UNKNOWN_CONGESTION_LEVEL', 'RUNNING_SMOOTHLY', 'STOP_AND_GO', 'CONGESTION', 'SEVERE_CONGESTION'])->nullable();
            $table->enum('occupancy_status', ['EMPTY', '_MANY_SEATS_AVAILABLE_', '_FEW_SEATS_AVAILABLE_', '_STANDING_ROOM_ONLY_', '_CRUSHED_STANDING_ROOM_ONLY_', 'FULL', '_NOT_ACCEPTING_PASSENGERS_'])->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('trip_descriptor_id')
                ->references('id')->on('trip_descriptor')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('vehicle_id')
                ->references('id')->on('vehicle_descriptor')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_position');
    }
}
