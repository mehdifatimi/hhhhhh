<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quizzes', function (Blueprint $table) {
            // Add user_id column if it doesn't exist
            if (!Schema::hasColumn('quizzes', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('created_by');
            }
        });

        // Update the existing records to set user_id equal to created_by
        DB::statement('UPDATE quizzes SET user_id = created_by');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
};
