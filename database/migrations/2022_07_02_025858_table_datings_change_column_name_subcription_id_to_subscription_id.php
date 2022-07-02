<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableDatingsChangeColumnNameSubcriptionIdToSubscriptionId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('datings', function (Blueprint $table) {
            $table->renameColumn('subcription_id', 'subscription_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('datings', function (Blueprint $table) {
            $table->renameColumn('subscription_id', 'subcription_id');
        });
    }
}
