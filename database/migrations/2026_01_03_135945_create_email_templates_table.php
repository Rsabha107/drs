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
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('key');              // booking_confirmed
            $table->string('locale', 10)->default('en'); // en, ar
            $table->string('name');             // Friendly name for admin
            $table->string('subject');
            $table->longText('body');           // HTML from WYSIWYG
            $table->json('allowed_variables')->nullable(); // ["user_name","booking_ref"]
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->unique(['key', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_templates');
    }
};
