<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('firstname');
			$table->string('surname');
			$table->string('username')->nullable()->unique();
			$table->string('username_nice')->nullable()->unique();
			$table->string('steamname')->nullable()->unique();
			$table->string('email')->nullable()->unique();
			$table->string('password', 60)->nullable();
			$table->string('avatar')->nullable();
			$table->string('steamid')->nullable()->unique();
			$table->boolean('admin')->default(0);
			$table->string('remember_token', 100)->nullable();
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
		Schema::drop('users');
	}

}
