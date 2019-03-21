<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePollTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('polls', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('status', array('DRAFT','PREVIEW','PUBLISHED'))->default('DRAFT');
            $table->boolean('allow_options_multi')->default(0);
            $table->boolean('allow_options_user')->default(0);
            $table->integer('user_id')->unsigned()->index();
            $table->dateTime('start');
            $table->dateTime('end');
            $table->timestamps();

            ## Foregin Keys
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
        $table->dropForeign('polls_user_id_foreign');
        Schema::dropIfExists('polls');
    }
}
