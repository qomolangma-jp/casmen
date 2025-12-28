<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    use HasFactory;

    protected $table = 'entries';
    protected $primaryKey = 'entry_id';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'tel',
        'interview_url',
        'interview_uuid',
        'status',
        'video_path',
        'completed_at',
        'retake_count',
        'interrupt_retake_count',
        'decision_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'completed_at' => 'datetime',
        'decision_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // interview_tokenのエイリアスとしてinterview_uuidを使用
    public function getInterviewTokenAttribute()
    {
        return $this->interview_uuid;
    }

    // 有効期限チェック（expires_atがない場合はcreated_atから30日後として計算）
    public function getExpiresAtAttribute()
    {
        return $this->created_at ? $this->created_at->addDays(30) : null;
    }

    // 期限切れチェック
    public function getIsExpiredAttribute()
    {
        if (!$this->expires_at) {
            return false;
        }
        return now()->isAfter($this->expires_at);
    }

    // 完了チェック
    public function getIsCompletedAttribute()
    {
        return !empty($this->video_path) || $this->status === 'completed' || !empty($this->completed_at);
    }

    // 年齢を計算（仮の実装 - 実際にはbirthdateカラムが必要）
    public function getAgeAttribute()
    {
        // 実際の年齢計算が必要な場合はbirthdateカラムを追加してください
        return null;
    }

    // 電話番号のエイリアス
    public function getPhoneAttribute()
    {
        return $this->tel;
    }
}
