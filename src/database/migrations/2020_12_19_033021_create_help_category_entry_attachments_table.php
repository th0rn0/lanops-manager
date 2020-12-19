<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHelpCategoryEntryAttachmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('help_category_entry_attachments', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('display_name');
			$table->string('nice_name');
			$table->string('path');
			$table->string('url');
			$table->string('desc')->nullable()->default(null);
			$table->integer('help_category_entry_id')->unsigned()->nullable()->index();
			$table->timestamps();

			## Foreign Keys
			$table->foreign('help_category_entry_id')->references('id')->on('help_category_entrys')->onDelete('cascade');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('help_category_entry_attachments', function(Blueprint $table)
		{
			$table->dropForeign('help_category_entry_attachments_help_category_entry_id_foreign');
		});
		Schema::drop('help_category_entry_attachments');
	}

}
