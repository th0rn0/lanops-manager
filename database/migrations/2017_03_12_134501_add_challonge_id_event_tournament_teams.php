<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChallongeIdEventTournamentTeams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_tournament_teams', function (Blueprint $table) {
            $table->string('challonge_participant_id')->after('event_tournament_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_tournament_teams', function (Blueprint $table) {
            //
        });
    }
}
