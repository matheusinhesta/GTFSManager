<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStopTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stop_times', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trip_id');
            $table->unsignedBigInteger('stop_id');
            $table->time('arrival_time')->nullable();
            $table->time('departure_time')->nullable();
            $table->integer('stop_sequence');
            $table->string('stop_headsign')->nullable();
            $table->enum('pickup_type', ['regular', 'hasnt', 'call', 'combine'])->nullable();
            $table->enum('drop_off_type', ['regular', 'hasnt', 'call', 'combine'])->nullable();
            $table->decimal('shape_dist_traveled', 8, 2)->nullable();
            $table->boolean('timepoint')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('trip_id')
                ->references('id')->on('trips')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('stop_id')
                ->references('id')->on('stops')
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
        Schema::dropIfExists('stop_times');
    }
}
