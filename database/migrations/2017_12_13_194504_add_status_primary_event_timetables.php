<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusPrimaryEventTimetables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_timetables', function (Blueprint $table) {
            $table->enum('status', array('DRAFT','PUBLISHED'))->default('DRAFT')->after('event_id');
            $table->boolean('primary')->default(false)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_timetables', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('primary');
        });
    }
}
