<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOnlySignedinEventTournamentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_tournaments', function (Blueprint $table) {
            $table->boolean('only_signedin')->after('rules')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_tournaments', function (Blueprint $table) {
            $table->dropColumn('only_signedin');
        });
    }
}
