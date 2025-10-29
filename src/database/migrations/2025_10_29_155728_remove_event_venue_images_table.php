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
        Schema::table('event_venue_images', function (Blueprint $table) {
            $table->dropForeign('event_venue_images_event_venue_id_foreign');
            $table->dropColumn('event_venue_id');
        });
        Schema::drop('event_venue_images');   

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_venue_images', function (Blueprint $table) {
            //
        });
    }
};
