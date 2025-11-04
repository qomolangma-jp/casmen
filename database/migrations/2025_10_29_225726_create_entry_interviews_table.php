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
// database/migrations/..._create_entry_interviews_table.php

Schema::create('entry_interviews', function (Blueprint $table) {
    $table->id('interview_id'); // interview_idを主キーとして明示
    $table->unsignedBigInteger('entry_id')->index(); // entry_idの型に注意
    $table->unsignedBigInteger('question_id')->index(); // question_idの型をBIGINTに修正
    $table->string('file_path', 255)->nullable();
    $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();

    // 外部キー制約 (mst_questionsはまだ作成されていませんが、先に定義)
    $table->foreign('entry_id')->references('entry_id')->on('entries')->onDelete('cascade');
    $table->foreign('question_id')->references('question_id')->on('mst_questions')->onDelete('restrict');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entry_interviews');
    }
};
