<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveInformationSectionsEventTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('venue_name');
            $table->dropColumn('venue_address');
            $table->dropColumn('venue_image');
            $table->dropColumn('section_1_title');
            $table->dropColumn('section_1_text');
            $table->dropColumn('section_1_image');
            $table->dropColumn('section_2_title');
            $table->dropColumn('section_2_text');
            $table->dropColumn('section_2_image');
            $table->dropColumn('section_3_title');
            $table->dropColumn('section_3_text');
            $table->dropColumn('section_3_image');
            $table->dropColumn('section_4_title');
            $table->dropColumn('section_4_text');
            $table->dropColumn('section_4_image');
            $table->dropColumn('section_5_title');
            $table->dropColumn('section_5_text');
            $table->dropColumn('section_5_image');
            $table->dropColumn('section_6_title');
            $table->dropColumn('section_6_text');
            $table->dropColumn('section_6_image');

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
            //
        });
    }
}
