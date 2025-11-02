<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * mst_categoryテーブル（質問カテゴリマスタ）を作成します。
     */
    public function up(): void
    {
        Schema::create('mst_category', function (Blueprint $table) {
            // カラム名: category_id, 型: INT(11), 主キー, 自動採番
            $table->increments('category_id'); // INT(11)の主キー、自動採番

            // カラム名: slug, 型: VARCHAR(200), カテゴリ識別スラッグ（URL用）
            $table->string('slug', 200)->unique();

            // カラム名: category_name, 型: VARCHAR(200), カテゴリ名称
            $table->string('category_name', 200);

            // カラム名: category_order, 型: INT(11), 並び順
            $table->integer('category_order')->default(0);

            // created_at, updated_at は不要（マスタテーブルのため）
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_category');
    }
};
