<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('daily_run_sheets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('venue_id');
            $table->unsignedBigInteger('match_id')->nullable();
            $table->string('sheet_type', 50); // e.g. MD-1, MD FINAL, MD+1
            $table->date('run_date');
            $table->time('gates_opening')->nullable();
            $table->time('kick_off')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('daily_run_sheet_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('run_sheet_id');
            $table->string('title');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('functional_area')->nullable();
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->string('row_color', 20)->default('default'); // default, red, yellow, green
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('run_sheet_id')->references('id')->on('daily_run_sheets')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('daily_run_sheet_items');
        Schema::dropIfExists('daily_run_sheets');
    }
};
