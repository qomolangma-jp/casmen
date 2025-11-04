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
        Schema::table('entries', function (Blueprint $table) {
            // entry_idの次にuser_idを追加
            $table->unsignedBigInteger('user_id')->nullable()->after('entry_id');

            // interview_urlの次にinterview_uuidを追加
            $table->string('interview_uuid', 64)->nullable()->unique()->after('interview_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entries', function (Blueprint $table) {
            $table->dropColumn(['user_id', 'interview_uuid']);
        });
    }
};
