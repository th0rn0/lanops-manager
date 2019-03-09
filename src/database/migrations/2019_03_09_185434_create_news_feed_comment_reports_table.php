<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsFeedCommentReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_feed_comment_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->text('comment', 65535);
            $table->integer('news_feed_comment_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index();
            $table->boolean('reviewed');
            $table->integer('reviewed_by')->unsigned()->index();
            $table->timestamps();

            ## Foreign Keys
            $table->foreign('news_feed_comment_id')->references('id')->on('news_feed')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->dropForeign('news_feed_comment_reports_news_feed_comment_id_foreign');
        $table->dropForeign('news_feed_comment_reports_user_id_foreign');
        Schema::dropIfExists('news_feed_comment_reports');
    }
}
