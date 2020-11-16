<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePollOptionVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('poll_option_votes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->integer('poll_option_id')->unsigned()->index();
            $table->timestamps();

            ## Foregin Keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('poll_option_id')->references('id')->on('poll_options')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('poll_option_votes', function (Blueprint $table) {
            $table->dropForeign('poll_option_votes_user_id_foreign');
            $table->dropForeign('poll_option_votes_poll_option_id_foreign');
        });
        Schema::dropIfExists('poll_option_votes');
    }
}
