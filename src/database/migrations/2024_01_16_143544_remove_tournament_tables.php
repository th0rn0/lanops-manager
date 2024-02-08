<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveTournamentTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_tournaments', function (Blueprint $table) {
            // $table->dropForeign('event_tournaments_event_id_foreign');
            // $table->dropForeign('event_tournaments_game_id_foreign');
            // $table->dropColumn('event_id');
            // $table->dropColumn('game_id');
        });

        Schema::table('event_tournament_teams', function (Blueprint $table) {
            $table->dropForeign('event_tournament_teams_event_tournament_id_foreign');
            $table->dropColumn('event_tournament_id');
            $table->dropColumn('event_tournament_team_id');
        });

        Schema::table('event_tournament_participants', function (Blueprint $table) {
            $table->dropForeign('event_tournament_participants_event_participant_id_foreign');
            $table->dropForeign('event_tournament_participants_event_tournament_id_foreign');
            $table->dropForeign('event_tournament_participants_event_tournament_team_id_foreign');

            $table->dropColumn('event_participant_id');
            $table->dropColumn('event_tournament_team_id');
            $table->dropColumn('event_tournament_id');
        });

        Schema::drop('event_tournaments');   
        Schema::drop('event_tournament_participants');       
        Schema::drop('event_tournament_teams');       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
