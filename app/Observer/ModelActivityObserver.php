<?php

namespace App\Observer;

use App\Models\ActivityLog;
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
        unset($oldData['password'], $newData['password']);

        ActivityLog::create([
            'action_type' => $actionType,
            'user_id' => $model->id,
            'metadata' => [
                'old' => $oldData,
                'new' => $newData,
                'performed_by' => Auth::id(),
            ],
        ]);
    }

    protected function getActionType($model, $action):string
    {
        $class = class_basename($model);
        return strtoupper("{$class}_$action");
    }
}
