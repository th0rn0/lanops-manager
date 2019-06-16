<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnCreditAppliedEventTournamentParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_tournament_participants', function (Blueprint $table) {
            $table->boolean('credit_applied')->after('final_score')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_tournament_participants', function (Blueprint $table) {
            $table->boolean('credit_applied')->after('final_score')->default(0);
        });
    }
}
