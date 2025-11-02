<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
Schema::create('mst_questions', function (Blueprint $table) {
    $table->id('question_id'); // question_idを主キーとして明示
    $table->unsignedBigInteger('category_id')->index();
    $table->string('q', 200);
    $table->text('memo')->nullable();
    $table->integer('order')->index();
    
    // 外部キー制約
    $table->foreign('category_id')->references('category_id')->on('mst_categories')->onDelete('restrict');
});
    }
};