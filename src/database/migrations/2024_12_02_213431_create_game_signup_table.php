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
        Schema::create('game_signup_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
			$table->integer('event_id')->unsigned()->index()->nullable();
			$table->string('slug');
			$table->integer('team_size')->nullable();
			$table->string('status');
            $table->timestamps();

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
        Schema::table('game_signup_lists', function (Blueprint $table) {
			$table->dropForeign('game_signup_lists_event_id_foreign');
        });
        Schema::dropIfExists('game_signup_lists');
    }
};
