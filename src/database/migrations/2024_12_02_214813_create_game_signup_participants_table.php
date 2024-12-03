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
        Schema::create('game_signup_list_participants', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('user_id')->nullable()->unsigned()->index();
			$table->integer('game_signup_list_id')->unsigned()->index();
			$table->integer('game_signup_list_team_id')->nullable()->unsigned()->index();
			$table->timestamps();

			## Foreign Keys
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('game_signup_list_id')->references('id')->on('game_signup_lists')->onDelete('cascade');
			$table->foreign('game_signup_list_team_id')->references('id')->on('game_signup_list_teams')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('game_signup_list_participants', function (Blueprint $table) {
			$table->dropForeign('game_signup_list_participants_user_id_foreign');
			$table->dropForeign('game_signup_list_participants_game_signup_list_id_foreign');
			$table->dropForeign('game_signup_list_participants_game_signup_list_team_id_foreign');
        });
        Schema::dropIfExists('game_signup_list_participants');
    }
};
