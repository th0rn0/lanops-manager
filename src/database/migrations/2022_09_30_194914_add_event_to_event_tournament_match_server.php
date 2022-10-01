<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_tournament_match_server', function (Blueprint $table) {
            $table->integer('event_tournament_id')->unsigned()->index();

            ## Foreign Keys
            $table->foreign('event_tournament_id')->references('id')->on('event_tournaments');
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
            $table->dropForeign('event_tournament_match_server_event_tournament_id_foreign');
            $table->dropColumn('event_tournament_id');
        });
    }
};
