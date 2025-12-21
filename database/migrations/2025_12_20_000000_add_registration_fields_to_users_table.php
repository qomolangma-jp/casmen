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
        Schema::table('users', function (Blueprint $table) {
            $table->string('zip1', 3)->nullable()->after('tel');
            $table->string('zip2', 4)->nullable()->after('zip1');
            $table->string('address', 255)->nullable()->after('zip2');
            $table->string('job_url', 255)->nullable()->after('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['zip1', 'zip2', 'address', 'job_url']);
        });
    }
};
