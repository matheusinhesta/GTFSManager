<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEndTimesToTripDescriptorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trip_descriptor', function (Blueprint $table) {
            $table->string('start_time')->nullable()->change();
            $table->string('end_time')->after('start_date')->nullable();
            $table->date('end_date')->after('end_time')->nullable();
            $table->enum('trip_status', ['scheduled', 'started', 'closed', 'canceled'])->after('route_id')->default('scheduled');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trip_descriptor', function (Blueprint $table) {
            $table->time('start_time')->nullable()->change();
            $table->dropColumn(['end_time', 'end_date', 'trip_status']);
        });
    }
}
