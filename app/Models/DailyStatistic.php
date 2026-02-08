<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyStatistic extends Model
{
    protected $fillable = ['date', 'total_users_created', 'total_users_updated', 'total_users_deleted'];
}
