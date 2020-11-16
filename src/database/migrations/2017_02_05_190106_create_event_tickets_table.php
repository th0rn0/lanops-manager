<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEventTicketsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('event_tickets', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->integer('event_id')->unsigned()->index();
			$table->string('type');
			$table->float('price', 10, 0);
			$table->boolean('seatable');
			$table->dateTime('sale_start')->nullable();
			$table->dateTime('sale_end')->nullable();
			$table->float('sale_price', 10, 0)->nullable();
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
		Schema::table('event_tickets', function(Blueprint $table)
		{
            $table->dropForeign('event_tickets_event_id_foreign');
		});        
		Schema::drop('event_tickets');
	}

}
