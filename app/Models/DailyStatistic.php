<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class DailyStatistic extends Model
{
    use BelongsToTenant;
    protected $fillable = ['date', 'total_users_created', 'total_users_updated', 'total_users_deleted', 'tenant_id'];
}
