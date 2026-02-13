<?php

namespace App\Helper;

use App\Models\ActivityLog;

class ActivityLogger
{
    public static function logCreated($model, ?int $performedBy = null): void
    {
        $data = $model->getAttributes();

        unset($data['password']);

        ActivityLog::create([
            'action_type' => strtoupper(class_basename($model) . '_CREATED'),
            'user_id'     => $performedBy,
            'metadata'    => [
                'new'          => $data,
            ],
            'tenant_id' => $model->tenant_id
        ]);
    }
}