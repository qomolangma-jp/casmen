<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
// database/migrations/..._create_shops_table.php

Schema::create('shops', function (Blueprint $table) {
    $table->id('shop_id'); // shop_idを主キーとして明示
    $table->unsignedBigInteger('user_id'); // 外部キー
    $table->string('shop_name', 200)->index();
    $table->text('shop_description')->nullable();
    $table->dateTime('login_date')->nullable()->index();
    
    // created_at, updated_at カラムは指定された命名とデフォルト値で定義
    $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
    $table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

    // 外部キー制約
    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
});
    }
};