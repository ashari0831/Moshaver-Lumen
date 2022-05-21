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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->index('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('advisor_user_id');
            $table->index('advisor_user_id');
            $table->foreign('advisor_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('reservation_datetime');
            $table->timestamp('end_session_datetime');
            $table->string('advising_case')->nullable();
            $table->unsignedBigInteger('chat_id');
            $table->index('chat_id');
            $table->foreign('chat_id')->references('id')->on('chats')->onDelete('cascade');
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
        Schema::table('reservations', function(Blueprint $table) {
            $table->dropForeign('reservations_user_id_foreign');
        $table->dropIndex('reservations_user_id_index');
        $table->dropColumn('user_id');
        $table->dropForeign('reservations_advisor_user_id_foreign');
        $table->dropIndex('reservations_advisor_user_id_index');
        $table->dropColumn('advisor_user_id');
        $table->dropForeign('reservations_chat_id_foreign');
        $table->dropIndex('reservations_chat_id_index');
        $table->dropColumn('chat_id');
        });
        Schema::dropIfExists('reservations');
        
    }
};
