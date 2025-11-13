<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Questions count: " . App\Models\Question::count() . "\n";
App\Models\Question::all(['id', 'q'])->each(function($q) {
    echo "ID: {$q->id}, Q: {$q->q}\n";
});
