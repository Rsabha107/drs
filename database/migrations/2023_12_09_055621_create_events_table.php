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
        Schema::create('events', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 100);
            $table->integer('category_id');
            $table->string('language', 50)->nullable();
            $table->integer('planner_id');
            $table->integer('audience_id');
            $table->integer('venue_id');
            $table->integer('location_id');
            $table->date('start_date');
            $table->time('start_time');
            $table->date('end_date');
            $table->time('end_time');
            $table->integer('duration')->nullable();
            $table->decimal('progress', 10)->nullable();
            $table->integer('parent')->nullable();
            $table->integer('color_id')->nullable();
            $table->integer('budget_allocation');
            $table->integer('attendance_forcast');
            $table->integer('time_zone')->nullable();
            $table->text('description');
            $table->integer('event_status');
            $table->string('type', 11)->nullable()->default('project');
            $table->boolean('open')->default(true);
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
        Schema::dropIfExists('events');
    }
};
