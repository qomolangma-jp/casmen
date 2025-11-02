<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
// database/migrations/..._create_mst_categories_table.php

Schema::create('mst_categories', function (Blueprint $table) {
    $table->id('category_id'); // category_idを主キーとして明示
    $table->string('slug', 200)->index();
    $table->string('category_name', 200);
    $table->integer('category_order')->index();
});        
    }
};