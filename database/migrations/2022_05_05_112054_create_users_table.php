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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->timestamp('email_confirmed_at')->nullable();
            $table->string('gender');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone_number')->unique();
            $table->timestamp('year_born')->nullable();
            $table->boolean('is_advisor')->default(false);
            $table->string('image')->nullable();
            $table->string('status')->default('offline');
            $table->integer('wallet')->default(0);
            $table->timestamp('last_login')->nullable();
            $table->boolean('is_superuser')->default(false);
            $table->string('first_name');
            $table->string('last_name');
            $table->boolean('is_staff')->default(false);
            $table->boolean('is_active')->default(false);
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
        Schema::dropIfExists('users');
    }
};
