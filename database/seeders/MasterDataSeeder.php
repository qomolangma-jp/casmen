<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // カテゴリマスタの作成
        $categories = [
            ['slug' => 'sales', 'category_name' => '営業', 'category_order' => 1],
            ['slug' => 'tech', 'category_name' => '技術', 'category_order' => 2],
            ['slug' => 'marketing', 'category_name' => 'マーケティング', 'category_order' => 3],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        // 質問マスタの作成
        $salesCategory = Category::where('slug', 'sales')->first();
        $techCategory = Category::where('slug', 'tech')->first();
        $marketingCategory = Category::where('slug', 'marketing')->first();

        if (!$salesCategory || !$techCategory || !$marketingCategory) {
            echo "カテゴリが見つかりません。\n";
            return;
        }

        $questions = [
            // 営業カテゴリ
            ['category_id' => $salesCategory->category_id, 'q' => 'あなたの営業経験について教えてください', 'memo' => '営業スキル確認', 'order' => 1],
            ['category_id' => $salesCategory->category_id, 'q' => '顧客との関係をどう築きますか？', 'memo' => '営業手法確認', 'order' => 2],

            // 技術カテゴリ
            ['category_id' => $techCategory->category_id, 'q' => 'プログラミング経験について教えてください', 'memo' => '技術力確認', 'order' => 1],
            ['category_id' => $techCategory->category_id, 'q' => '最近取り組んだ技術的な課題は何ですか？', 'memo' => '技術的思考確認', 'order' => 2],

            // マーケティングカテゴリ
            ['category_id' => $marketingCategory->category_id, 'q' => 'マーケティング戦略をどう考えますか？', 'memo' => 'マーケ思考確認', 'order' => 1],
        ];        foreach ($questions as $question) {
            Question::updateOrCreate(
                ['category_id' => $question['category_id'], 'q' => $question['q']],
                $question
            );
        }

        echo "マスターデータのシーディングが完了しました。\n";
    }
}
