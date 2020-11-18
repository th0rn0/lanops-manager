<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchMakingServerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matchmaking_server', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('match_id')->unsigned();
            $table->integer('game_server_id')->unsigned()->index();
            $table->timestamps();

            ## Foreign Keys
            $table->foreign('game_server_id')->references('id')->on('game_servers')->onDelete('cascade');
            $table->foreign('match_id')->references('id')->on('matchmaking')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('matchmaking_server', function (Blueprint $table) 
        {
            $table->dropForeign('matchmaking_server_match_id_foreign');
            $table->dropForeign('matchmaking_server_game_server_id_foreign');
        });
           
        Schema::dropIfExists('matchmaking_server');
    }
}
