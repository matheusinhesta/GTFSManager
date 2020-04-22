<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agency_id');
            $table->string('short_name');
            $table->string('long_name');
            $table->text('desc')->nullable();
            $table->enum('type', ['vlt', 'subway', 'train', 'bus', 'ferry', 'tram', 'cable_car', 'cable_railway']);
            $table->string('url')->nullable();
            $table->string('color')->nullable();
            $table->string('text_color')->nullable();
            $table->integer('short_order')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('agency_id')
                ->references('id')->on('agency')
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
        Schema::dropIfExists('routes');
    }
}
