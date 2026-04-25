<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('md_template_items', function (Blueprint $table) {
            $table->id();
            $table->string('fa_code', 10); // CMP, SEC, VUM
            $table->string('title');
            $table->string('countdown_to_ko')->nullable(); // e.g., 'KO-19h', 'HT', 'KO+45m'
            $table->string('location')->nullable();
            $table->string('row_color', 20)->default('default'); // default, red, yellow, green
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index('fa_code');
            $table->index('sort_order');
        });
    }

    public function down()
    {
        Schema::dropIfExists('md_template_items');
    }
};
