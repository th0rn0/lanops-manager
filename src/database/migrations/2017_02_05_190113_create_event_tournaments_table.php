<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEventTournamentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('event_tournaments', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('event_id')->unsigned()->index();
			$table->string('challonge_tournament_id');
			$table->string('challonge_tournament_url');
			$table->string('name');
			$table->string('slug');
			$table->string('game');
			$table->string('format')->nullable();
			$table->string('team_size')->nullable();
			$table->string('description');
			$table->boolean('allow_bronze')->default(false);
			$table->boolean('allow_player_teams')->default(false);
			$table->enum('status', array('DRAFT','OPEN','CLOSED','LIVE','COMPLETE'));
			$table->timestamps();

			## Foreign Keys
			$table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('event_tournaments', function(Blueprint $table)
		{
			$table->dropForeign('event_tournaments_event_id_foreign');
		});
		Schema::drop('event_tournaments');
	}

}
