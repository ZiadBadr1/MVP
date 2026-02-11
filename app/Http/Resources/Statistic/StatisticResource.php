<?php

namespace App\Http\Resources\Statistic;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StatisticResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this['id'],
            'date' => $this['date'],
            'total_users_created' => $this['total_users_created'],
            'total_users_updated' => $this['total_users_updated'],
            'total_users_deleted' => $this['total_users_deleted'],
        ];
    }
}
