<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "全質問データ:\n";
$questions = App\Models\Question::all();
foreach ($questions as $q) {
    echo "ID: {$q->question_id}, Category: {$q->category_id}, Order: {$q->order}, Q: {$q->q}\n";
}

echo "\nCategory ID = 2 の質問:\n";
$filteredQuestions = App\Models\Question::where('category_id', 2)->orderBy('order')->take(3)->get();
echo "カウント: " . $filteredQuestions->count() . "\n";
foreach ($filteredQuestions as $q) {
    echo "ID: {$q->question_id}, Q: {$q->q}\n";
}
