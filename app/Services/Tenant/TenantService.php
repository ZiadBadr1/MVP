<?php

namespace App\Services\Tenant;

class TenantService
{
    protected ?int $tenantId = null;

    public function setTenant(int $tenantId): void
    {
        $this->tenantId = $tenantId;
    }

    public function getTenant(): ?int
    {
        return $this->tenantId;
    }

    public function hasTenant(): bool
    {
        return ! is_null($this->tenantId);
    }
}
