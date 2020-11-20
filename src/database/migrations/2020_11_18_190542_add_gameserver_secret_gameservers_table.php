<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGameserverSecretGameserversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('game_servers', function (Blueprint $table) {
            $table->string('gameserver_secret')->after('rcon_password');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('game_servers', function (Blueprint $table) {
            $table->dropColumn('gameserver_secret');
        });
    }
}
