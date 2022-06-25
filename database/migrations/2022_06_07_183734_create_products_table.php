<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('added_by')->nullable(); //added by belongs to user with user_id
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->string('title')->nullable();
            $table->longText('metaTitle')->nullable();
            $table->string('slug')->nullable();
            $table->string('type')->nullable();
            $table->string('sku')->nullable();
            $table->string('icon')->nullable();
            $table->float('regular_price', 8, 2)->nullable();
            $table->float('sale_price', 8, 2)->nullable();
            $table->string('stock_status')->nullable();
            $table->string('stock_alert_quantity')->nullable();
            $table->float('discount', 8, 2)->nullable();
            $table->unsignedBigInteger('quantity')->nullable();
            $table->longText('description')->nullable();
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('products');
    }
};
