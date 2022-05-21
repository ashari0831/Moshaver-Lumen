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
        Schema::create('advisor_documents', function (Blueprint $table) {
            $table->id();
            $table->timestamp('uploaded_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('doc_file')->nullable();
            $table->boolean('confirmed_at')->nullable()->default(null);
            $table->unsignedBigInteger('advisor_id');
            $table->index('advisor_id');
            $table->foreign('advisor_id')->references('id')->on('advisors')->onDelete('cascade');
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
        Schema::table('advisor_documents', function(Blueprint $table) {
            $table->dropForeign('advisor_documents_advisor_id_foreign');
        $table->dropIndex('advisor_documents_advisor_id_index');
        $table->dropColumn('advisor_id');
        });
        Schema::dropIfExists('advisor_documents');
        
    }
};
