<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_servers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->string('address', 1000)->nullable();
            $table->integer('game_port')->nullable();
            $table->string('game_password', 1000)->nullable();
            $table->integer('rcon_port')->nullable();
            $table->string('rcon_password', 1000)->nullable();
            $table->integer('game_id')->unsigned()->index();
            $table->timestamps();

            ## Foreign Keys
            $table->foreign('game_id')->references('id')->on('games');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_servers');
    }
}
