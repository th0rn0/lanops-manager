<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGameIdEventTournamentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_tournaments', function (Blueprint $table) {
            $table->integer('game_id')->unsigned()->index()->after('slug')->default(null)->nullable();

            ## Foreign Keys
            $table->foreign('game_id')->references('id')->on('games');
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
            $table->dropForeign('event_tournaments_game_id_foreign');
        });
    }
}
