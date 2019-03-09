<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApprovedColumnsNewsFeedCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('news_feed_comments', function (Blueprint $table) {
            $table->boolean('reviewed')->after('comment')->default(0);
            $table->boolean('approved')->after('reviewed')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('news_feed_comments', function (Blueprint $table) {
            $table->dropColumn('reviewed');
            $table->dropColumn('approved');
        });
    }
}
