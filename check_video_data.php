<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$token = 'kXoxboRx63q1fqulGai7hqjOlXFW9N0w';

$entry = \App\Models\Entry::where('interview_uuid', $token)->first();

if ($entry) {
    echo "Entry ID: {$entry->entry_id}\n";
    echo "Status: {$entry->status}\n";

    $interviews = \App\Models\EntryInterview::where('entry_id', $entry->entry_id)
        ->with('question')
        ->get();

    echo "EntryInterview count: " . $interviews->count() . "\n\n";

    foreach ($interviews as $interview) {
        echo "Question ID: {$interview->question_id}, ";
        echo "File: {$interview->file_path}\n";
    }
} else {
    echo "Entry not found for token: {$token}\n";
}
