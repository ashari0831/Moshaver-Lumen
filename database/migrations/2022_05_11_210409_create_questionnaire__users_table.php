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
        Schema::create('questionnaire__users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('questionnaire_id');
            $table->index('questionnaire_id');
            $table->foreign('questionnaire_id')->references('id')->on('questionnaires')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->index('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('total')->default(-1);
            $table->integer('somatization')->default(-1);
            $table->integer('obsessive_compulsive')->default(-1);
            $table->integer('interpersonal_sensitivity')->default(-1);
            $table->integer('depression')->default(-1);
            $table->integer('anxiety')->default(-1);
            $table->integer('hostility')->default(-1);
            $table->integer('tophobic_anxietytal')->default(-1);
            $table->integer('paranoid_ideation')->default(-1);
            $table->integer('psychoticism')->default(-1);
            $table->integer('other')->default(-1);
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
        Schema::table('questionnaire__users', function(Blueprint $table) {
            $table->dropForeign('questionnaire__users_questionnaire_id_foreign');
            $table->dropIndex('questionnaire__users_questionnaire_id_index');
            $table->dropColumn('questionnaire_id');
        });
        Schema::dropIfExists('questionnaire__users');
    }
};
