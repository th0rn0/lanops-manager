<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMatchStartCommandGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->integer('matchStartgameServerCommand')->unsigned()->nullable()->default(null);
            $table->foreign('matchStartgameServerCommand')->references('id')->on('game_server_commands')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropForeign('games_matchstartgameservercommand_foreign');
            $table->dropColumn('matchStartgameServerCommand');
        });
    }
}
