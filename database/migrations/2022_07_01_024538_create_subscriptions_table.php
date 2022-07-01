<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('added_by')->nullable();//added by belongs to user with user_id
            $table->unsignedBigInteger('updated_by')->nullable();//added by belongs to user with user_id
            $table->unsignedBigInteger('deleted_by')->nullable();//deleted by belongs to user with user_id
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->longText('description')->nullable();
            $table->string('icon')->nullable();
            $table->decimal('price_per_month', 12, 2)->default(0)->nullable();
            $table->decimal('discount_per_month', 12, 2)->default(0)->nullable();
            $table->decimal('price_per_year', 12, 2)->default(0)->nullable();
            $table->decimal('discount_per_year', 12, 2)->default(0)->nullable();
            $table->enum('charge_type', ['monthly', 'yearly'])->default('monthly');
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
        Schema::dropIfExists('subscriptions');
    }
}
