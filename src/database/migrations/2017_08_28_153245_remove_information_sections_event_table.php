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
            $table->dropColumn([
                'venue_name', 'venue_address', 'venue_image',
                'section_1_title', 'section_1_text', 'section_1_image',
                'section_2_title', 'section_2_text', 'section_2_image',
                'section_3_title', 'section_3_text', 'section_3_image',
                'section_4_title', 'section_4_text', 'section_4_image',
                'section_5_title', 'section_5_text', 'section_5_image',
                'section_6_title', 'section_6_text', 'section_6_image',
            ]);
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
