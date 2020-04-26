<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntitySelectorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entity_selector', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agency_id')->nullable();
            $table->unsignedBigInteger('route_id')->nullable();
            $table->integer('route_tipe')->nullable();
            $table->unsignedBigInteger('trip_descriptor_id')->nullable();
            $table->unsignedBigInteger('stop_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('agency_id')
                ->references('id')->on('agency')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('route_id')
                ->references('id')->on('routes')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('trip_descriptor_id')
                ->references('id')->on('trip_descriptor')
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
        Schema::dropIfExists('entity_selector');
    }
}
