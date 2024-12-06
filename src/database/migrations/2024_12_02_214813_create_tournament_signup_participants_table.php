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
        Schema::create('tournament_participants', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('user_id')->nullable()->unsigned()->index();
			$table->integer('tournament_id')->unsigned()->index();
			$table->integer('tournament_team_id')->nullable()->unsigned()->index();
			$table->timestamps();

			## Foreign Keys
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('tournament_id')->references('id')->on('tournaments')->onDelete('cascade');
			$table->foreign('tournament_team_id')->references('id')->on('tournament_teams')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tournament_participants', function (Blueprint $table) {
			$table->dropForeign('tournament_participants_user_id_foreign');
			$table->dropForeign('tournament_participants_tournament_id_foreign');
			$table->dropForeign('tournament_participants_tournament_team_id_foreign');
        });
        Schema::dropIfExists('tournament_participants');
    }
};
