<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * entry_interviewテーブル（応募者回答動画）を作成します。
     */
    public function up(): void
    {
        Schema::create('entry_interview', function (Blueprint $table) {
            // カラム名: interview_id, 型: BIGINT(11), 主キー, 自動採番
            $table->id('interview_id');

            // カラム名: entry_id, 型: BIGINT(11), 応募者ID
            // 外部キー制約: entry.entry_id 参照, 応募者削除時に動画も自動削除
            $table->foreignId('entry_id')
                  ->constrained('entry', 'entry_id')
                  ->onDelete('cascade'); 

            // カラム名: question_id, 型: INT(11), 質問ID
            // 外部キー制約: mst_question.question_id 参照
            $table->unsignedInteger('question_id');
            $table->foreign('question_id')
                  ->references('question_id')
                  ->on('mst_question')
                  ->onDelete('restrict'); // 質問削除は原則禁止

            // カラム名: file_path, 型: VARCHAR(255), 録画ファイルの保存パス
            $table->string('file_path', 255)->nullable();

            // カラム名: created, 型: DATETIME, 登録日時
            $table->timestamp('created')->useCurrent();
            // updated は不要
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entry_interview');
    }
};
