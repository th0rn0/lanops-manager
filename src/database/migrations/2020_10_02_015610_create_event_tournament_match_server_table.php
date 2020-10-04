<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventTournamentMatchServerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_tournament_match_server', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug');
            $table->integer('challonge_match_id');
            $table->integer('game_server_id')->unsigned()->index();
            $table->timestamps();

            ## Foreign Keys
            $table->foreign('game_server_id')->references('id')->on('game_servers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_tournament_match_server');
    }
}
