<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchMakingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matchmaking', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('team_size')->nullable();
            $table->integer('team_count')->nullable();
            $table->enum('status', array('DRAFT','OPEN','CLOSED','LIVE','COMPLETE'));
            $table->integer('owner_id')->unsigned()->index();
            $table->string('invite_tag');
            $table->boolean('ispublic')->default(false);
            $table->integer('game_id')->unsigned()->index()->default(null)->nullable();
            $table->integer('game_server_id')->unsigned()->index()->default(null)->nullable();
            $table->timestamps();

            ## Foreign Keys
            $table->foreign('game_id')->references('id')->on('games');
            $table->foreign('game_server_id')->references('id')->on('game_servers');
            $table->foreign('owner_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matchmaking');
    }
}
