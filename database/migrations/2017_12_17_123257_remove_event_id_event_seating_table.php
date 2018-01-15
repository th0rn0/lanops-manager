<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveEventIdEventSeatingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_seating', function (Blueprint $table) {
            $table->dropForeign('event_seating_event_id_foreign');
            $table->dropColumn('event_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_seating', function (Blueprint $table) {
            //
        });
    }
}
