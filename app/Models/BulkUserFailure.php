<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BulkUserFailure extends Model
{
    protected $fillable = ['bulk_user_import_id', 'email', 'reason'];

    protected $casts = [
        'reason' => 'array'
    ];
    public function bulk():BelongsTo
    {
        return $this->belongsTo(BulkUserImport::class, 'bulk_user_import_id');
    }
}
