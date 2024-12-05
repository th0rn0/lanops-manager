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
        Schema::create('tournament_teams', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('tournament_id')->unsigned()->index();
			$table->string('name');
			$table->timestamps();
            $table->string('password')->nullable();

			## Foreign Key
			$table->foreign('tournament_id')->references('id')->on('tournaments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tournament_teams', function (Blueprint $table) {
			$table->dropForeign('tournament_teams_tournament_id_foreign');
        });
        Schema::dropIfExists('tournament_teams');
    }
};
