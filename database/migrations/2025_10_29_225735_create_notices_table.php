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
// database/migrations/..._create_notices_table.php

Schema::create('notice', function (Blueprint $table) {
    $table->id('notice_id'); // notice_idを主キーとして明示
    $table->string('title', 200);
    $table->text('text');
    $table->unsignedBigInteger('category_id')->index(); // category_idの型をBIGINTに修正
    
    // created, updated カラムは指定された命名とデフォルト値で定義
    $table->dateTime('created')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
    $table->dateTime('updated')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

    // 外部キー制約
    $table->foreign('category_id')->references('category_id')->on('mst_categories')->onDelete('restrict');
});        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};
