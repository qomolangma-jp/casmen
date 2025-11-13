<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Entry ID 9 のデータをクリーンアップします...\n";

// 既存のentryを削除
$entry = App\Models\Entry::find(9);
if ($entry) {
    echo "Entry ID 9 を削除中...\n";
    $entry->delete();
}

// 関連するentry_interviewsも削除
$interviews = App\Models\EntryInterview::where('entry_id', 9)->get();
foreach ($interviews as $interview) {
    echo "EntryInterview ID {$interview->interview_id} を削除中...\n";
    // ファイルも削除
    if ($interview->file_path) {
        $filePath = storage_path('app/public/' . $interview->file_path);
        if (file_exists($filePath)) {
            unlink($filePath);
            echo "ファイル削除: {$interview->file_path}\n";
        }
    }
    $interview->delete();
}

// 結合済み動画も削除
$combinedFiles = glob(storage_path('app/public/interviews/combined_interview_9_*.mp4'));
foreach ($combinedFiles as $file) {
    if (file_exists($file)) {
        unlink($file);
        echo "結合動画ファイル削除: " . basename($file) . "\n";
    }
}

// 字幕付き動画も削除
$withTextFiles = glob(storage_path('app/public/interviews/*_with_text.mp4'));
foreach ($withTextFiles as $file) {
    if (file_exists($file)) {
        unlink($file);
        echo "字幕付き動画ファイル削除: " . basename($file) . "\n";
    }
}

echo "クリーンアップ完了\n";
