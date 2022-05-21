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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->index('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('type')->nullable();
            $table->boolean('seen')->default(false);
            $table->unsignedBigInteger('reservation_id');
            $table->index('reservation_id');
            $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade');
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
        
        Schema::table('notifications', function(Blueprint $table) {
            $table->dropForeign('notifications_user_id_foreign');
            $table->dropIndex('notifications_user_id_index');
            $table->dropColumn('user_id');
            $table->dropForeign('notifications_reservation_id_foreign');
            $table->dropIndex('notifications_reservation_id_index');
            $table->dropColumn('reservation_id');
        });
        Schema::dropIfExists('notifications');
        
    }
};
