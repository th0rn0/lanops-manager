<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchMakingTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matchmaking_teams', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('team_owner_id')->unsigned()->nullable();
            $table->integer('team_score')->unsigned()->default(0);
            $table->string('team_invite_tag');
            $table->timestamps();


            $table->foreign('team_owner_id')->references('id')->on('users');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matchmaking_teams');
    }
}
