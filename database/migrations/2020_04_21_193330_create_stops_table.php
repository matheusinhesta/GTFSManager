<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stops', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->string('desc')->nullable();
            $table->double('lat', 10, 8)->nullable();
            $table->double('lon', 10, 8)->nullable();
            $table->unsignedBigInteger('zone_id');
            $table->string('url')->nullable();
            $table->enum('location_type', ['platform', 'station', 'entrance_exit', 'generic_node', 'boarding_area'])->nullable();
            $table->integer('parent_station')->nullable();
            $table->string('timezone')->nullable();
            $table->enum('wheelchair_boarding', ['empty', 'has', 'hasnt'])->nullable();
            $table->integer('level_id')->nullable();
            $table->string('platform_code')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('zone_id')
                ->references('id')->on('zones')
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
        Schema::dropIfExists('stops');
    }
}
