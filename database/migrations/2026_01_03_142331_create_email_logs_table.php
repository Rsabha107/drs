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
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->string('template_key');
            $table->string('locale', 10)->default('en');

            $table->string('to')->nullable();
            $table->string('cc')->nullable();
            $table->string('bcc')->nullable();

            $table->string('subject')->nullable();
            $table->longText('body')->nullable(); // rendered HTML snapshot

            $table->json('payload')->nullable();  // variables data snapshot
            $table->json('attachments')->nullable();

            $table->string('status')->default('queued'); // queued|sent|failed
            $table->text('error')->nullable();

            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index(['template_key', 'locale']);
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};
