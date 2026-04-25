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
            $table->unsignedBigInteger('event_id')->nullable()->after('available_to_customer');
            $table->unsignedBigInteger('venue_id')->nullable()->after('event_id');
            
            // Foreign key constraints
            $table->foreign('event_id')
                ->references('id')
                ->on('events')
                ->onDelete('cascade');
            
            $table->foreign('venue_id')
                ->references('id')
                ->on('venues')
                ->onDelete('cascade');
            
            // Indexes for filtering
            $table->index('event_id');
            $table->index('venue_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sheet_types', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
            $table->dropForeign(['venue_id']);
            $table->dropColumn(['event_id', 'venue_id']);
        });
    }
};
