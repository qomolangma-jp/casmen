<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'shop_id';

    /**
     * The table associated with the model.
     */
    protected $table = 'shops';
    public $timestamps = true; // 標準のcreated_at, updated_atを使用

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'shop_name',
        'shop_description',
        'login_date',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'login_date' => 'datetime',
        'created' => 'datetime',
        'updated' => 'datetime',
    ];

    /**
     * The name of the "created at" column.
     */
    const CREATED_AT = 'created';

    /**
     * The name of the "updated at" column.
     */
    const UPDATED_AT = 'updated';

    /**
     * Get the user that owns the shop.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
