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
// database/migrations/..._create_entries_table.php

Schema::create('entries', function (Blueprint $table) {
    $table->id('entry_id'); // entry_idを主キーとして明示
    $table->string('name', 200);
    $table->string('email', 200)->index();
    $table->string('tel', 200);
    $table->string('interview_url', 200);
    $table->string('status', 200)->index();
    
    // created_at, updated_at カラムは指定された命名とデフォルト値で定義
    $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
    $table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entries');
    }
};
