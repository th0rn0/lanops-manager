<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRulesEventTournamentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_tournaments', function (Blueprint $table) {
            #$table->longText('rules')->change();
            #$table->string('rules', 4294967295)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_tournaments', function (Blueprint $table) {
            #$table->string('rules')->change();
        });
    }
}
