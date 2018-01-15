<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEventTournamentTeamsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('event_tournament_teams', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('event_tournament_id')->unsigned()->index();
			$table->string('name');
			$table->string('event_tournament_team_id')->index();
			$table->timestamps();

			## Foreign Key
			$table->foreign('event_tournament_id')->references('id')->on('event_tournaments')->onDelete('cascade');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		$table->dropForeign('event_tournament_teams_event_tournament_id_foreign');
		Schema::drop('event_tournament_teams');
	}

}
