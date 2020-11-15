<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveSeatingEventTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('seating_columns');
            $table->dropColumn('seating_rows');
            $table->dropColumn('seating_headers');
            $table->dropColumn('seating_image');
            $table->dropColumn('seating_locked');
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
			$table->integer('seating_columns')->after('allow_spectators');
			$table->integer('seating_rows')->after('seating_columns');
			$table->string('seating_headers')->after('seating_rows');
			$table->string('seating_image')->after('seating_headers');
            $table->boolean('seating_locked')->default(false)->after('seating_image');
            
        });
    }
}
