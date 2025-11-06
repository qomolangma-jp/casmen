<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // usersテーブルにshop関連カラムを追加（既に存在する場合はスキップ）
        if (!Schema::hasColumn('users', 'logined_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('logined_at')->nullable()->after('updated_at');
                $table->string('shop_name', 200)->nullable()->after('logined_at');
                $table->text('shop_description')->nullable()->after('shop_name');
                $table->string('tel', 200)->nullable()->after('shop_description');
            });
        }

        // shopsテーブルのデータをusersテーブルに移行
        $shops = DB::table('shops')->get();
        foreach ($shops as $shop) {
            DB::table('users')
                ->where('id', $shop->user_id)
                ->update([
                    'logined_at' => $shop->login_date,
                    'shop_name' => $shop->shop_name,
                    'shop_description' => $shop->shop_description,
                    'created_at' => $shop->created_at,
                    'updated_at' => $shop->updated ?? now(),
                ]);
        }

        // shopsテーブルを削除
        Schema::dropIfExists('shops');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // shopsテーブルを再作成
        Schema::create('shops', function (Blueprint $table) {
            $table->id('shop_id');
            $table->unsignedBigInteger('user_id');
            $table->string('shop_name', 200)->index();
            $table->text('shop_description')->nullable();
            $table->dateTime('login_date')->nullable()->index();
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
            $table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // usersテーブルのデータをshopsテーブルに戻す
        $users = DB::table('users')->whereNotNull('shop_name')->get();
        foreach ($users as $user) {
            DB::table('shops')->insert([
                'user_id' => $user->id,
                'shop_name' => $user->shop_name,
                'shop_description' => $user->shop_description,
                'login_date' => $user->logined_at,
                'created_at' => $user->created_at,
                'updated' => $user->updated_at,
            ]);
        }

        // usersテーブルからshop関連カラムを削除
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['logined_at', 'shop_name', 'shop_description', 'tel']);
        });
    }
};
