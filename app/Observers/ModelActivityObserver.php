<?php

namespace App\Observers;

use App\Models\ActivityLog;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class ModelActivityObserver
{
    public function created($model): void
    {
        $this->logActivity($model, $this->getActionType($model, 'created'), [], $model->getAttributes());
    }

    public function updated($model): void
    {
        $this->logActivity($model, $this->getActionType($model, 'updated'), $model->getOriginal(), $model->getChanges());
    }

    public function deleted($model): void
    {
        $this->logActivity($model, $this->getActionType($model, 'deleted'), $model->getAttributes());
    }

    protected function logActivity($model, string $actionType, array $oldData = [], array $newData = []):void
    {
        $metadata = $this->sanitizeLogData($model, $oldData, $newData);
        $this->createActivityLog($actionType, $model, $metadata,$model->tenant_id ?? null);
    }

    protected function getActionType($model, $action):string
    {
        $class = class_basename($model);
        return strtoupper("{$class}_$action");
    }
    protected function createActivityLog(string $actionType,$model, array $metadata,int $tenantId =null ): void {
        ActivityLog::create([
            'action_type' => $actionType,
            'user_id' => Auth::id() ?? null,
            'model' => get_class($model),
            'metadata' => $metadata,
            'tenant_id' => $tenantId
        ]);
    }
    protected function sanitizeLogData($model, array $oldData, array $newData): array
    {
        $sensitive = method_exists($model, 'getSensitiveLogData')
            ? $model->getSensitiveLogData()
            : [];

        return [
            'old' => Arr::except($oldData, $sensitive),
            'new' => Arr::except($newData, $sensitive),
        ];
    }
}
