<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$interviews = App\Models\EntryInterview::where('entry_id', 7)
    ->orderBy('question_id')
    ->orderBy('created_at')
    ->get(['interview_id', 'entry_id', 'question_id', 'file_path', 'created_at']);

echo "Entry ID 7 の面接データ:\n";
foreach ($interviews as $interview) {
    echo "ID: {$interview->interview_id}, Entry: {$interview->entry_id}, Question: {$interview->question_id}, Path: {$interview->file_path}, Created: {$interview->created_at}\n";
}

// 動画ファイルの実際の時間を確認
foreach ($interviews as $interview) {
    $filePath = storage_path('app/public/' . $interview->file_path);
    if (file_exists($filePath)) {
        echo "\nファイル確認: {$interview->file_path}\n";
        echo "ファイルサイズ: " . filesize($filePath) . " bytes\n";
    }
}
