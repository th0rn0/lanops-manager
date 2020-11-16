<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGalleryAlbumImagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('gallery_album_images', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('display_name');
			$table->string('nice_name');
			$table->string('path');
			$table->string('url');
			$table->string('desc')->nullable()->default(null);
			$table->integer('gallery_album_id')->unsigned()->nullable()->index();
			$table->timestamps();

			## Foreign Keys
			$table->foreign('gallery_album_id')->references('id')->on('gallery_albums')->onDelete('cascade');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('gallery_album_images', function(Blueprint $table)
		{
			$table->dropForeign('gallery_album_images_gallery_album_id_foreign');
		});
		Schema::drop('gallery_album_images');
	}

}
