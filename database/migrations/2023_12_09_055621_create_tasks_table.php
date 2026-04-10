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
        Schema::create('tasks', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 300);
            $table->date('start_date');
            $table->date('due_date');
            $table->integer('department_assignment_id');
            $table->integer('assignment_id');
            $table->integer('budget_allocation')->nullable();
            $table->integer('actual_budget_allocated')->nullable();
            $table->integer('event_id');
            $table->integer('status_id');
            $table->integer('duration')->nullable();
            $table->decimal('progress', 10)->nullable();
            $table->integer('parent')->nullable();
            $table->integer('color_id')->nullable();
            $table->text('description');
            $table->integer('type')->default(0);
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
