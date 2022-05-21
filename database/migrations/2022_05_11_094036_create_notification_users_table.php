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
        Schema::create('notification_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->index('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('notification_id');
            $table->index('notification_id');
            $table->foreign('notification_id')->references('id')->on('notifications')->onDelete('cascade');
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
        Schema::table('notification_users', function(Blueprint $table) {
            $table->dropForeign('notification_users_user_id_foreign');
            $table->dropIndex('notification_users_user_id_index');
            $table->dropColumn('user_id');
            $table->dropForeign('notification_users_notification_id_foreign');
            $table->dropIndex('notification_users_notification_id_index');
            $table->dropColumn('notification_id');
        });
        Schema::dropIfExists('notification_users');
        
    }
};
