<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripDescriptorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trip_descriptor', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('trip_id');
            $table->unsignedBigInteger('route_id');
            $table->bigInteger('direction_id')->nullable();
            $table->time('start_time')->nullable();
            $table->date('start_date')->nullable();
            $table->unsignedBigInteger('schedule_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')
                ->references('id')->on('users');

            $table->foreign('trip_id')
                ->references('id')->on('trips')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('route_id')
                ->references('id')->on('routes')
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
        Schema::dropIfExists('trip_descriptor');
    }
}
