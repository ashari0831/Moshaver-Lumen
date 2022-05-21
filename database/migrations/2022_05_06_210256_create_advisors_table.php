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
        Schema::create('advisors', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_mental_advisor')->default(false);
            $table->boolean('is_family_advisor')->default(false);
            $table->boolean('is_sport_advisor')->default(false);
            $table->boolean('is_healthcare_advisor')->default(false);
            $table->boolean('is_education_advisor')->default(false);
            $table->string('meli_code')->unique();
            $table->string('advise_method');
            $table->string('address');
            $table->string('telephone');
            $table->boolean('is_verified')->nullable();
            $table->time('daily_begin_time')->nullable();
            $table->time('daily_end_time')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->index('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('advisors', function(Blueprint $table) {
            $table->dropForeign('advisors_user_id_foreign');
            $table->dropIndex('advisors_user_id_index');
            $table->dropColumn('user_id');
        });
        Schema::dropIfExists('advisors');
        
    }
};
