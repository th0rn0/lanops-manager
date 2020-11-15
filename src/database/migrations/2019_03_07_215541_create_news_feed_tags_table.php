<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsFeedTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_feed_tags', function (Blueprint $table) {
            $table->increments('id');
            $table->text('tag');
            $table->integer('news_feed_id')->unsigned()->index();
            $table->timestamps();

            ## Foreign Keys
            $table->foreign('news_feed_id')->references('id')->on('news_feed')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('news_feed_tags', function (Blueprint $table) {
            $table->dropForeign('news_feed_tags_news_feed_id_foreign');
        });
        Schema::dropIfExists('news_feed_tags');
    }
}