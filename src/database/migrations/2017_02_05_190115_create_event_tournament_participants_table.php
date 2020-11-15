<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEventTournamentParticipantsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('event_tournament_participants', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('event_participant_id')->nullable()->unsigned()->index();
			$table->string('challonge_participant_id')->nullable();
			$table->integer('event_tournament_team_id')->nullable()->unsigned()->index();
			$table->integer('event_tournament_id')->unsigned()->index();
			$table->timestamps();

			## Foreign Keys
			$table->foreign('event_participant_id')->references('id')->on('event_participants')->onDelete('cascade');
			$table->foreign('event_tournament_team_id')->references('id')->on('event_tournament_teams')->onDelete('cascade');
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
		Schema::table('event_tournament_participants', function(Blueprint $table)
		{
			$table->dropForeign('event_tournament_participants_event_participant_id_foreign');
			$table->dropForeign('event_tournament_participants_event_tournament_team_id_foreign');
			$table->dropForeign('event_tournament_participants_event_tournament_id_foreign');	
		});		
		Schema::drop('event_tournament_participants');
	}

}
