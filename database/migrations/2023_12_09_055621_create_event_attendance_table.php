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
        Schema::create('event_attendance', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('event_id');
            $table->integer('guest_id');
            $table->integer('confirmed_attandance')->nullable();
            $table->dateTime('expected_date')->nullable();
            $table->integer('guest_attended')->nullable();
            $table->decimal('purchase_amount', 10, 0)->nullable();
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
        Schema::dropIfExists('event_attendance');
    }
};
