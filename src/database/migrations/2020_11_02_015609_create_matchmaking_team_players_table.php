<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchMakingTeamPlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matchmaking_team_players', function (Blueprint $table) {
            $table->integer('matchmaking_team_id')->unsigned();;
            $table->integer('user_id')->unsigned();;
            $table->timestamps();

            $table->foreign('matchmaking_team_id')->references('id')->on('matchmaking_teams');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matchmaking_team_players');
    }
}
