<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTravelPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('travel_packages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('added_by')->nullable(); 
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('package_type_id')->nullable();
            $table->string('name')->nullable();
            $table->float('price', 8, 2)->nullable();
            $table->float('sale_price', 8, 2)->nullable();
            $table->string('location')->nullable();
            $table->unsignedBigInteger('no_of_days')->nullable();
            $table->unsignedBigInteger('no_of_members')->nullable();
            $table->longText('description')->nullable();
            $table->longText('requirements')->nullable();
            $table->string('image')->nullable();
            $table->longText('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('travel_packages');
    }
}
