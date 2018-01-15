<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdminFreeEventParticipants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_participants', function (Blueprint $table) {
            $table->boolean('staff')->after('qrcode')->default(0);
            $table->boolean('free')->after('staff')->default(0);
            $table->integer('staff_free_assigned_by')->after('free')->nullable()->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_participants', function (Blueprint $table) {
            $table->dropColumn('staff');
            $table->dropColumn('free');
            $table->dropColumn('staff_free_assigned_by');
        });
    }
}
