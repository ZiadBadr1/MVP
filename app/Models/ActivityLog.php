<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = ['action_type', 'user_id', 'metadata', 'model'];
    protected $casts = [
        'metadata' => 'array',
    ];
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
