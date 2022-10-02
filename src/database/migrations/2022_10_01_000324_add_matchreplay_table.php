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
        Schema::create('matchreplay', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('matchmaking_id')->unsigned()->nullable()->index();
            $table->integer('challonge_match_id')->nullable();
            $table->timestamps();

            ## Foreign Keys
            $table->foreign('matchmaking_id')->references('id')->on('matchmaking');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('matchreplay', function (Blueprint $table) 
        {
            $table->dropForeign('matchreplay_matchmaking_id_foreign');
        });
           
        Schema::drop('matchreplay');
    }
};