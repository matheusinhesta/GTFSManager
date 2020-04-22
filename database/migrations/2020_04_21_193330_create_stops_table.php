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
            $table->string('lat')->nullable();
            $table->string('lon')->nullable();
            $table->unsignedBigInteger('zone_id');
            $table->string('url')->nullable();
            $table->enum('location_type', ['platform', 'station', 'io', 'generic_node', 'boarding_area'])->nullable();
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
