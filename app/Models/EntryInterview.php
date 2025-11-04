<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntryInterview extends Model
{
    use HasFactory;

    protected $table = 'entry_interviews';
    protected $primaryKey = 'interview_id';
    public $timestamps = false;

    const CREATED_AT = 'created';
    const UPDATED_AT = null;

    protected $fillable = [
        'entry_id',
        'question_id',
        'file_path'
    ];

    protected $casts = [
        'created' => 'datetime',
    ];

    public function entry()
    {
        return $this->belongsTo(Entry::class, 'entry_id', 'entry_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id', 'question_id');
    }
}
