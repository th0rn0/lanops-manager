<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGalleryAlbumsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('gallery_albums', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('slug')->unique();
			$table->string('description')->nullable();
			$table->string('album_cover_id')->nullable();
			$table->integer('event_id')->unsigned()->nullable()->index();
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
		$table->dropForeign('gallery_albums_event_id_foreign');
		Schema::drop('gallery_albums');
	}

}
