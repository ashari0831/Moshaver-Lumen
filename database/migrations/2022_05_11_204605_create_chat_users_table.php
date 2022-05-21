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
        Schema::create('chat_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->index('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('chat_id');
            $table->index('chat_id');
            $table->foreign('chat_id')->references('id')->on('chats')->onDelete('cascade');
            $table->boolean('is_done')->default(false);
            $table->timestamp('chat_start_datetime');
            $table->timestamp('end_session_datetime');
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
        Schema::table('chat_users', function(Blueprint $table) {
            $table->dropForeign('chat_users_user_id_foreign');
            $table->dropIndex('chat_users_user_id_index');
            $table->dropColumn('user_id');
            $table->dropForeign('chat_users_chat_id_foreign');
            $table->dropIndex('chat_users_chat_id_index');
            $table->dropColumn('chat_id');
        });
        Schema::dropIfExists('chat_users');
    }
};
