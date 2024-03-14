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
        Schema::table('users', function (Blueprint $table) {
            $table->string('discord_id')->after('steamid')->nullable();
            $table->string('discord_username')->after('discord_id')->nullable();
            $table->string('discord_avatar')->after('discord_username')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('discord_id');
            $table->dropColumn('discord_username');
            $table->dropColumn('discord_avatar');
        });
    }
};
