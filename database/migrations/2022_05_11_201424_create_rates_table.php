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
        Schema::create('rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->index('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('advisor_id');
            $table->index('advisor_id');
            $table->foreign('advisor_id')->references('id')->on('advisors')->onDelete('cascade');
            $table->text('text');
            $table->string('rate');
            $table->boolean('is_confirmed')->default(false);
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
        Schema::table('rates', function(Blueprint $table) {
            $table->dropForeign('rates_user_id_foreign');
            $table->dropIndex('rates_user_id_index');
            $table->dropColumn('user_id');
            $table->dropForeign('rates_advisor_id_foreign');
            $table->dropIndex('rates_advisor_id_index');
            $table->dropColumn('advisor_id');
        });
        Schema::dropIfExists('rates');
    }
};
