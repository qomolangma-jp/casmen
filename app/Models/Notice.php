<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * noticeテーブル（お知らせ）を作成します。
     */
    public function up(): void
    {
        Schema::create('notice', function (Blueprint $table) {
            // カラム名: notice_id, 型: INT(11), 主キー, 自動採番
            $table->increments('notice_id');

            // カラム名: title, 型: VARCHAR(200), お知らせタイトル
            $table->string('title', 200);

            // カラム名: text, 型: TEXT, 本文内容
            $table->text('text');

            // カラム名: category_id, 型: INT(11), カテゴリ
            // 外部キー制約: mst_category.category_id 参照
            $table->unsignedInteger('category_id')->index()->nullable();
            $table->foreign('category_id')
                  ->references('category_id')
                  ->on('mst_category')
                  ->onDelete('set null'); // カテゴリ削除時、このカラムをNULLにする

            // カラム名: created, updated
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notice');
    }
};
