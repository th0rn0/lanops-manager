<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_information', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('event_id')->unsigned()->index();
            $table->string('title');
            $table->string('text', 1000);
            $table->string('image', 1000)->nullable();
            $table->timestamps();

            ## Foreign Keys
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
        Schema::dropIfExists('event_information');
    }
}
