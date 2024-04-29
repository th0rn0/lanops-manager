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
        Schema::table('event_announcements', function (Blueprint $table) {
            $table->dropForeign('event_annoucements_event_id_foreign');
            $table->dropColumn('event_id');
        });
        Schema::drop('event_announcements');   
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_announcements', function (Blueprint $table) {
            //
        });
    }
};
