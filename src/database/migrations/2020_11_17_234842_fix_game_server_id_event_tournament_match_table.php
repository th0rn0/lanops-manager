<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixGameServerIdEventTournamentMatchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_tournament_match_server', function (Blueprint $table) {
            $table->dropForeign('event_tournament_match_server_game_server_id_foreign');
            ## Foreign Keys
            $table->foreign('game_server_id')->references('id')->on('game_servers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_tournament_match_server', function (Blueprint $table) {

        });
    }
}
