<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gallery_album_images', function (Blueprint $table) {
            //
        });

        Schema::table('gallery_album_images', function (Blueprint $table) {
            $table->dropForeign('gallery_album_images_gallery_album_id_foreign');
            $table->dropColumn('gallery_album_id');
        });
        Schema::drop('gallery_album_images');   
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gallery_album_images', function (Blueprint $table) {
            //
        });
    }
};
