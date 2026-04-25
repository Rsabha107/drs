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
        Schema::create('sheet_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique(); // e.g., 'MD-3', 'MD-2', 'MD-1', 'MD'
            $table->string('title', 100); // display name
            $table->text('description')->nullable();
            $table->boolean('available_to_customer')->default(true); // can customer use this type?
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index('code');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sheet_types');
    }
};
