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
        Schema::table('advisors', function (Blueprint $table) {
            
            $table->unique('user_id');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('advisors', function(Blueprint $table) {
        //     $table->dropForeign('advisors_user_id_foreign');
        //     $table->dropIndex('advisors_user_id_index');
        //     $table->dropColumn('user_id');
        // });
        
    }
};
