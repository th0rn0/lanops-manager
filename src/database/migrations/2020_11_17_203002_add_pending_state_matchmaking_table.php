<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPendingStateMatchMakingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE matchmaking MODIFY status ENUM('DRAFT','OPEN','CLOSED','PENDING','LIVE','COMPLETE') NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE matchmaking MODIFY status ENUM('DRAFT','OPEN','CLOSED','LIVE','COMPLETE') NOT NULL");
    }
}
