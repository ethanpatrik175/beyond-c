<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('subcription_id')->nullable();
            $table->integer('age_start')->default(0);
            $table->integer('age_end')->default(0);
            $table->string('gender')->nullable();
            $table->string('interested_gender')->nullable();
            $table->string('body_type')->nullable();
            $table->string('relationship_status')->nullable();
            $table->string('have_kids')->nullable();
            $table->string('want_kids')->nullable();
            $table->string('education')->nullable();
            $table->string('do_smoke')->nullable();
            $table->string('do_drink')->nullable();
            $table->string('religion')->nullable();
            $table->string('passion')->nullable();
            $table->string('avatar')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('about')->nullable();
            $table->longText('subscription_details')->nullable();
            $table->string('subscription_ends_at')->nullable();
            $table->string('status')->default('active');
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
        Schema::dropIfExists('datings');
    }
}
