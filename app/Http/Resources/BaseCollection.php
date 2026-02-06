<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BaseCollection extends ResourceCollection
{
    protected string $resourceClass;

    public function __construct($resource, string $resourceClass)
    {
        parent::__construct($resource);
        $this->resourceClass = $resourceClass;
    }

    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($item) use ($request) {
                $class = $this->resourceClass;
                return new $class($item);
            }),
        ];
    }
}
