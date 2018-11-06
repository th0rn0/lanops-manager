<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusEventSeatingPlans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_seating_plans', function (Blueprint $table) {
            $table->enum('status', array('DRAFT','PUBLISHED'))->default('DRAFT')->after('image_path');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_seating_plans', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
