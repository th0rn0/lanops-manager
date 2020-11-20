<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixMatchmaking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('matchmaking', function (Blueprint $table) {
            $table->dropForeign('matchmaking_game_server_id_foreign');
            $table->dropColumn('game_server_id');
            $table->dropForeign('matchmaking_game_id_foreign');
            $table->dropForeign('matchmaking_owner_id_foreign');


            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');

        });



        Schema::table('matchmaking_teams', function (Blueprint $table) {

        $table->dropForeign('matchmaking_teams_match_id_foreign');
        $table->dropForeign('matchmaking_teams_team_owner_id_foreign');
        $table->foreign('match_id')->references('id')->on('matchmaking')->onDelete('cascade');
        $table->foreign('team_owner_id')->references('id')->on('users')->onDelete('cascade');

        });

        Schema::table('matchmaking_team_players', function (Blueprint $table) {
            $table->dropForeign('matchmaking_team_players_matchmaking_team_id_foreign');
            $table->dropForeign('matchmaking_team_players_user_id_foreign');
 
            $table->foreign('matchmaking_team_id')->references('id')->on('matchmaking_teams')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('matchmaking', function (Blueprint $table) {
            $table->integer('game_server_id')->unsigned()->index()->default(null)->nullable()->after('game_id');
            $table->foreign('game_server_id')->references('id')->on('game_servers');
        });
    }
}
