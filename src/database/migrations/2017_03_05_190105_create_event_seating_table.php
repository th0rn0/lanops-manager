<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEventSeatingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('event_seating', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('seat');
			$table->integer('event_id')->unsigned()->index();
			$table->integer('event_participant_id')->unsigned()->index();
			$table->boolean('gifted');
			$table->integer('gifted_user_id')->unsigned()->nullable()->index();
			$table->timestamps();

			## Foreign Keys
			$table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
			$table->foreign('event_participant_id')->references('id')->on('event_participants')->onDelete('cascade');

		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('event_seating', function(Blueprint $table)
		{
			$table->dropForeign('event_seating_event_id_foreign');
			$table->dropForeign('event_seating_event_participant_id_foreign');
		});
		Schema::drop('event_seating');
	}

}
