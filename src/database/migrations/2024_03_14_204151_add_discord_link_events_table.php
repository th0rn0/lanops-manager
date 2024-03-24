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
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('discord_link_enabled')->after('essential_info')->default(false);
            $table->string('discord_role_id')->after('discord_link_enabled')->nullable();
            $table->string('discord_channel_id')->after('discord_role_id')->nullable();
            $table->string('discord_event_id')->after('discord_channel_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('discord_link_enabled');
            $table->dropColumn('discord_role_id');
            $table->dropColumn('discord_channel_id');
            $table->dropColumn('discord_event_id');
        });
    }
};
