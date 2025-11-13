<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Questions count: " . App\Models\Question::count() . "\n";
$questions = App\Models\Question::all();
foreach ($questions as $q) {
    echo "Question ID: {$q->question_id}, Q: {$q->q}\n";
}
