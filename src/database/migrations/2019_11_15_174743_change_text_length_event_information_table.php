<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTextLengthEventInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_information', function (Blueprint $table) {
            DB::statement('ALTER TABLE event_information MODIFY COLUMN text VARCHAR(99999)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_information', function (Blueprint $table) {
            //
        });
    }
}
