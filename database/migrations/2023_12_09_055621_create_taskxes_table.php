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
        Schema::create('taskxes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('text');
            $table->integer('duration');
            $table->double('progress', 8, 2);
            $table->dateTime('start_date');
            $table->integer('parent');
            $table->string('color', 25);
            $table->timestamps();
            $table->string('type', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taskxes');
    }
};
