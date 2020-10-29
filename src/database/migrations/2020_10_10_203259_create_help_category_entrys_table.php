<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHelpCategoryEntrysTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('help_category_entrys', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('display_name');
			$table->string('nice_name');
			$table->string('content', 4294967295)->nullable()->default(null);
			$table->integer('help_category_id')->unsigned()->nullable()->index();
			$table->timestamps();

			## Foreign Keys
			$table->foreign('help_category_id')->references('id')->on('help_category')->onDelete('cascade');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		$table->dropForeign('help_category_entrys_help_category_id_foreign');
		Schema::drop('help_category_entrys');
	}

}
