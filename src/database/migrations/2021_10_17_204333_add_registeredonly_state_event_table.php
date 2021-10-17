<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRegisteredonlyStateEventTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE events MODIFY status ENUM('DRAFT','PREVIEW','PUBLISHED', 'PRIVATE', 'REGISTEREDONLY') NOT NULL");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE events MODIFY status ENUM('DRAFT','PREVIEW','PUBLISHED', 'PRIVATE') NOT NULL");


    }
}

