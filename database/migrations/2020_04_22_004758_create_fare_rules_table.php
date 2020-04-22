<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFareRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fare_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fare_id');
            $table->unsignedBigInteger('route_id')->nullable();
            $table->unsignedBigInteger('origin_id')->nullable();
            $table->unsignedBigInteger('destination_id')->nullable();
            $table->unsignedBigInteger('contains_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('fare_id')
                ->references('id')->on('fare_attributes')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('route_id')
                ->references('id')->on('routes')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('origin_id')
                ->references('id')->on('zones')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('destination_id')
                ->references('id')->on('zones')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('contains_id')
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
        Schema::dropIfExists('fare_rules');
    }
}
