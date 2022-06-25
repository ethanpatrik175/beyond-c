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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('added_by')->nullable(); //added by belongs to user with user_id
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('session_id')->nullable();
            $table->longText('token')->nullable();
            $table->string('status')->nullable();
            $table->float('subTotal', 8, 2)->nullable();
            $table->float('itemDiscount', 8, 2)->nullable();
            $table->float('tax', 8, 2)->nullable();
            $table->float('shipping', 8, 2)->nullable();
            $table->float('total', 8, 2)->nullable();
            $table->string('promo')->nullable();
            $table->float('discount', 8, 2)->nullable();
            $table->float('grandTotal', 8, 2)->nullable();
            $table->string('firstName')->nullable();
            $table->string('middleName')->nullable();
            $table->string('lastName')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->string('line1')->nullable();
            $table->string('line2')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('country')->nullable();
            $table->longText('content')->nullable();
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
        Schema::dropIfExists('orders');
    }
};
