<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BulkUserImport extends Model
{
    protected $fillable = ['requested_by', 'status', 'total_count', 'success_count', 'failed_count'];

    public function requestedBy():BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function failure():HasMany
    {
        return $this->hasMany(BulkUserFailure::class, 'bulk_user_import_id');
    }
}
