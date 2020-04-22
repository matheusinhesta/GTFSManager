<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('route_id');
            $table->unsignedBigInteger('service_id');
            $table->string('headsign')->nullable();
            $table->string('short_name')->nullable();
            $table->enum('direction_id', ['going', 'return'])->nullable();
            $table->integer('block_id')->nullable();
            $table->unsignedBigInteger('shape_id')->nullable();
            $table->enum('wheelchair_accessible', ['empty', 'has', 'hasnt'])->nullable();
            $table->enum('bikes_allowed', ['empty', 'has', 'hasnt'])->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('route_id')
                ->references('id')->on('routes')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('service_id')
                ->references('id')->on('services')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('shape_id')
                ->references('id')->on('shapes')
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
        Schema::dropIfExists('trips');
    }
}
