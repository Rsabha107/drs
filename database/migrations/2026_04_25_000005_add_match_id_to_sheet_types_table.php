<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sheet_types', function (Blueprint $table) {
            $table->unsignedBigInteger('match_id')->nullable()->after('venue_id');
            
            // Foreign key constraint
            $table->foreign('match_id')
                ->references('id')
                ->on('matches')
                ->onDelete('set null');
            
            // Index for filtering
            $table->index('match_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sheet_types', function (Blueprint $table) {
            $table->dropForeign(['match_id']);
            $table->dropColumn('match_id');
        });
    }
};
