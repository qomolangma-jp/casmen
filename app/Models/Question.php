<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * mst_questionテーブル（質問マスタ）を作成します。
     */
    public function up(): void
    {
        Schema::create('mst_question', function (Blueprint $table) {
            // カラム名: question_id, 型: INT(11), 主キー, 自動採番
            $table->increments('question_id');

            // カラム名: category_id, 型: INT(11), カテゴリID
            // 外部キー制約: mst_category.category_id 参照
            $table->unsignedInteger('category_id')->index();

            // カラム名: q, 型: VARCHAR(200), 質問文
            $table->string('q', 200);

            // カラム名: memo, 型: TEXT, 質問に関する補足説明
            $table->text('memo')->nullable();

            // カラム名: order, 型: INT(11), 表示順序
            $table->integer('order')->default(0);

            // 外部キー定義
            $table->foreign('category_id')
                  ->references('category_id')
                  ->on('mst_category')
                  ->onDelete('cascade'); // カテゴリ削除時、質問も削除
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_question');
    }
};
