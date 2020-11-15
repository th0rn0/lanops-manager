<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePurchasesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('purchases', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned()->index();
			$table->integer('ticket_id')->unsigned()->nullable()->index();
			$table->integer('item_id')->unsigned()->nullable()->index();
			$table->string('type');
			$table->string('transaction_id');
			$table->string('token');
			$table->string('status');
			$table->timestamps();

			## Foreign Keys
			$table->foreign('ticket_id')->references('id')->on('event_tickets');
			$table->foreign('user_id')->references('id')->on('users');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('purchases', function(Blueprint $table)
		{
			$table->dropForeign('purchases_ticket_id_foreign');
			$table->dropForeign('purchases_user_id_foreign');
		});
		Schema::drop('purchases');
	}

}
