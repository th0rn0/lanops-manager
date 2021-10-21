<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusFlagEventSeatingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_seating', function (Blueprint $table) {
            $table->enum('status', array('ACTIVE','INACTIVE'))->default('ACTIVE')->after('seat');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_seating', function (Blueprint $table) {
            $table->dropColumn('active');
        });
    }
}
