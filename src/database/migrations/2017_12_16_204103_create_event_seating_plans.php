<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventSeatingPlans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_seating_plans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('event_id')->unsigned()->index();
            $table->integer('columns');
            $table->integer('rows');
            $table->string('headers');
            $table->boolean('locked')->default(0);
            $table->string('image_path')->nullable();
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
        $table->dropForeign('event_seating_plans_event_id_foreign');
        Schema::dropIfExists('event_seating_plans');
    }
}
