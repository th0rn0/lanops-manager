<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEventSponsorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('event_sponsors', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('event_id')->unsigned()->index();
			$table->string('name');
			$table->string('image_path');
			$table->string('website')->nullable();
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
		$table->dropForeign('event_sponsors_event_id_foreign');
		Schema::drop('event_sponsors');
	}

}
