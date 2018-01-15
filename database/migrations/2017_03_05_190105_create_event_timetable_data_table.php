<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEventTimetableDataTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('event_timetable_data', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('event_timetable_id')->unsigned()->index();
			$table->dateTime('slot_timestamp');
			$table->string('slot')->nullable();
			$table->string('desc')->nullable();
			$table->timestamps();

			## Foreign Keys
			$table->foreign('event_timetable_id')->references('id')->on('event_timetables')->onDelete('cascade');

		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		$table->dropForeign('event_timetable_data_event_timetable_id_foreign');
		Schema::drop('event_timetable_data');
	}

}
