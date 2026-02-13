<?php

namespace App\Traits;
use App\Services\Tenant\TenantService;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant()
    {
        static::creating(function ($model) {
            $tenantService = app(TenantService::class);

            if ($tenantService->hasTenant()) {
                $model->tenant_id = $tenantService->getTenant();
            }
        });

        static::addGlobalScope('tenant', function (Builder $builder) {
            $tenantService = app(TenantService::class);
            if ($tenantService->hasTenant()) {
                $builder->where(
                    $builder->getModel()->getTable() . '.tenant_id',
                    $tenantService->getTenant()
                );
            }
        });
    }
}
