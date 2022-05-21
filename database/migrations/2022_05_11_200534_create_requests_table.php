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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id');
            $table->index('sender_id');
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('receiver_id');
            $table->index('receiver_id');
            $table->foreign('receiver_id')->references('id')->on('advisors')->onDelete('cascade');
            $table->string('request_content')->nullable();
            $table->boolean('is_checked')->default(false);
            $table->boolean('is_accepted')->nullable();
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
        Schema::table('requests', function(Blueprint $table){
            $table->dropForeign('requests_sender_id_foreign');
            $table->dropIndex('requests_sender_id_index');
            $table->dropColumn('sender_id');
            $table->dropForeign('requests_receiver_id_foreign');
            $table->dropIndex('requests_receiver_id_index');
            $table->dropColumn('receiver_id');
        });
        Schema::dropIfExists('requests');
    }
};
