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
        Schema::table('attendances', function (Blueprint $table) {
            $table->decimal('check_out_lat', 10, 7)->nullable()->after('check_out_time');
            $table->decimal('check_out_lng', 10, 7)->nullable()->after('check_out_lat');
            $table->string('check_out_photo_path')->nullable()->after('check_out_lng');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['check_out_lat', 'check_out_lng', 'check_out_photo_path']);
        });
    }
};
