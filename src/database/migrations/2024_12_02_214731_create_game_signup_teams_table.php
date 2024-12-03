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
        Schema::create('game_signup_list_teams', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('game_signup_list_id')->unsigned()->index();
			$table->string('name');
			$table->timestamps();

			## Foreign Key
			$table->foreign('game_signup_list_id')->references('id')->on('game_signup_lists')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('game_signup_list_teams', function (Blueprint $table) {
			$table->dropForeign('game_signup_list_teams_game_signup_list_id_foreign');
        });
        Schema::dropIfExists('game_signup_list_teams');
    }
};
