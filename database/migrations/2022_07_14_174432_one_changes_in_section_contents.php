<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OneChangesInSectionContents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section_contents', function (Blueprint $table) {
            $table->string('content_type')->nullable()->after('content');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section_contents', function (Blueprint $table) {
            $table->dropColumn('content_type');
        });
    }
}
