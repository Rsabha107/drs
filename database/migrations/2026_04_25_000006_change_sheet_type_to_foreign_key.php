<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('daily_run_sheets', function (Blueprint $table) {
            // Drop the old string column and add new foreign key column
            $table->dropColumn('sheet_type');
            $table->unsignedBigInteger('sheet_type_id')->nullable()->after('match_id');
            $table->foreign('sheet_type_id')->references('id')->on('sheet_types')->onDelete('set null');
            $table->index('sheet_type_id');
        });
    }

    public function down()
    {
        Schema::table('daily_run_sheets', function (Blueprint $table) {
            $table->dropForeign(['sheet_type_id']);
            $table->dropColumn('sheet_type_id');
            $table->string('sheet_type', 50)->after('match_id'); // Restore the old column
        });
    }
};
