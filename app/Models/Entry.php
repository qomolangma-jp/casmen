<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * entryテーブル（応募者情報）を作成します。
     */
    public function up(): void
    {
        Schema::create('entry', function (Blueprint $table) {
            // カラム名: entry_id, 型: BIGINT(11), 主キー, 自動採番
            $table->id('entry_id');

            // 備考: 企業単位で紐づくため、user_idを追加
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');

            // カラム名: name, 型: VARCHAR(200), 応募者氏名
            $table->string('name', 200);

            // カラム名: email, 型: VARCHAR(200), 応募者メールアドレス
            $table->string('email', 200);

            // カラム名: tel, 型: VARCHAR(200), 電話番号
            $table->string('tel', 200)->nullable();

            // カラム名: interview_url, 型: VARCHAR(200), 応募者専用面接URL（UUID付）
            $table->uuid('interview_uuid')->unique(); // UUIDを保存

            // カラム名: status, 型: VARCHAR(200), ステータス（例：未開始／録画中／完了）
            $table->string('status', 200)->default('未開始');

            // カラム名: created, updated
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entry');
    }
};
