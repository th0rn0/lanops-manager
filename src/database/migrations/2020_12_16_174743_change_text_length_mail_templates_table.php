<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTextLengthMailTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mail_templates', function (Blueprint $table) {
            DB::statement('ALTER TABLE mail_templates MODIFY COLUMN html_template VARCHAR(16777216)');
            DB::statement('ALTER TABLE mail_templates MODIFY COLUMN text_template VARCHAR(16777216)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mail_templates', function (Blueprint $table) {
            //
        });
    }
}
