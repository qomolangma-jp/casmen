<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $table = 'mst_questions';
    protected $primaryKey = 'question_id';
    public $timestamps = false;

    protected $fillable = [
        'category_id',
        'q',
        'memo',
        'order'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }
}
