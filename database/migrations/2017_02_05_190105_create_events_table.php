<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEventsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('events', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('display_name');
			$table->string('nice_name')->unique();
			$table->string('slug')->unique();
			$table->dateTime('start');
			$table->dateTime('end');
			$table->string('desc_long', 500);
			$table->string('desc_short');
			$table->integer('cap_seating');
			$table->boolean('allow_spectators')->default(1);
			$table->integer('seating_columns');
			$table->integer('seating_rows');
			$table->string('seating_headers');
			$table->string('seating_image');
			$table->string('venue_name')->nullable();
			$table->string('venue_address')->nullable();
			$table->string('venue_image')->nullable();
			$table->string('section_1_title')->nullable();
			$table->string('section_1_text', 2000)->nullable();
			$table->string('section_1_image')->nullable();
			$table->string('section_2_title')->nullable();
			$table->string('section_2_text', 2000)->nullable();
			$table->string('section_2_image')->nullable();
			$table->string('section_3_title')->nullable();
			$table->string('section_3_text', 2000)->nullable();
			$table->string('section_3_image')->nullable();
			$table->string('section_4_title')->nullable();
			$table->string('section_4_text', 2000)->nullable();
			$table->string('section_4_image')->nullable();
			$table->string('section_5_title')->nullable();
			$table->string('section_5_text', 2000)->nullable();
			$table->string('section_5_image')->nullable();
			$table->string('section_6_title')->nullable();
			$table->string('section_6_text', 2000)->nullable();
			$table->string('section_6_image')->nullable();
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('events');
	}

}
