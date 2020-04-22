<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFareAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fare_attributes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agency_id');
            $table->decimal('price', 8, 2);
            $table->string('currency_type');
            $table->boolean('payment_method')->default(FALSE);
            $table->enum('transfers', ['unallowed', 'one', 'two'])->nullable();
            $table->integer('transfer_duration')->nullable();
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
        Schema::dropIfExists('fare_attributes');
    }
}
