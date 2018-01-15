<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventVenueImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_venue_images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('event_venue_id')->unsigned()->index();
            $table->string('path');
            $table->string('description')->nullable();
            $table->timestamps();

            ## Foreign Keys
            $table->foreign('event_venue_id')->references('id')->on('event_venues')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_venue_images');
    }
}
