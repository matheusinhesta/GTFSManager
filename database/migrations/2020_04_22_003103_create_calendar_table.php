<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalendarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendar', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_id');
            $table->boolean('monday')->default(FALSE);
            $table->boolean('tuesday')->default(FALSE);
            $table->boolean('wednesday')->default(FALSE);
            $table->boolean('thursday')->default(FALSE);
            $table->boolean('friday')->default(FALSE);
            $table->boolean('saturday')->default(FALSE);
            $table->boolean('sunday')->default(FALSE);
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('service_id')
                ->references('id')->on('services')
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
        Schema::dropIfExists('calendar');
    }
}
