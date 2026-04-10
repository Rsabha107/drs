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
        Schema::create('master_guests_list', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email_address', 100);
            $table->string('phone_number', 50)->nullable();
            $table->integer('event_id')->nullable();
            $table->integer('attendance_flag')->nullable()->comment('1 yes 0 no');
            $table->string('reference_number', 50)->nullable();
            $table->integer('type_id')->nullable();
            $table->integer('active_flag')->default(1);
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
        Schema::dropIfExists('master_guests_list');
    }
};
