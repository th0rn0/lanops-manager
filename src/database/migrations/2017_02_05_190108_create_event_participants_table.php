<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEventParticipantsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('event_participants', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned()->index();
			$table->integer('event_id')->unsigned()->index();
			$table->integer('ticket_id')->unsigned()->index()->nullable();
			$table->integer('purchase_id')->unsigned()->index()->nullable();
			$table->string('qrcode')->nullable();
			$table->boolean('signed_in')->default(false);
			$table->string('gift')->nullable();
			$table->string('gift_accepted')->nullable();
			$table->string('gift_accepted_url')->nullable();
			$table->string('gift_sendee')->nullable();
			$table->timestamps();

			## Foreign Keys
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
			$table->foreign('ticket_id')->references('id')->on('event_tickets');

		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		$table->dropForeign('event_participants_user_id_foreign');
		$table->dropForeign('event_participants_event_id_foreign');
		$table->dropForeign('event_participants_ticket_id_foreign');

		Schema::drop('event_participants');
	}

}
