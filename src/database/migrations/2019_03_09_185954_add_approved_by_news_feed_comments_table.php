<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApprovedByNewsFeedCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('news_feed_comments', function (Blueprint $table) {
            $table->integer('approved_by')->unsigned()->index()->after('approved');
            $table->integer('reviewed_by')->unsigned()->index()->after('reviewed');
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
            $table->dropColumn('approved_by');
            $table->dropColumn('reviewed_by');
        });
    }
}
