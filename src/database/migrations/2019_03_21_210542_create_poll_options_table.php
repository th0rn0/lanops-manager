<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePollOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('poll_options', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('poll_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index();
            $table->timestamps();
           
            ## Foregin Keys
            $table->foreign('poll_id')->references('id')->on('polls')->onDelete('cascade');
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
        Schema::table('poll_options', function (Blueprint $table) {
            $table->dropForeign('poll_options_poll_id_foreign');
            $table->dropForeign('poll_options_user_id_foreign');
        });  
        Schema::dropIfExists('poll_options');
    }
}
