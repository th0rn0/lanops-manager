<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewEnumSeatingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE event_seating_plans CHANGE COLUMN status status ENUM('DRAFT', 'PREVIEW', 'PUBLISHED') NOT NULL DEFAULT 'DRAFT'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE event_seating_plans CHANGE COLUMN status status ENUM('DRAFT', 'PUBLISHED') NOT NULL DEFAULT 'DRAFT'");
    }
}
