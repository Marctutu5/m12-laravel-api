<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\AbstractPaginator;

class PaginateCollection extends ResourceCollection
{
    public $resourceClass = null;

    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     * @return void
     */
    public function __construct($resource, $resourceClass)
    {
        parent::__construct($resource);
        $this->resourceClass = $resourceClass;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $collection = $this->resourceClass::collection($this->collection);

        /** @see https://laravel.com/docs/9.x/eloquent-resources#pagination */
        if ($this->resource instanceof AbstractPaginator) {
            return [
                "collection" => $collection,
                "links" => $this->linkCollection(),
                "meta" => [
                    "current_page" => $this->currentPage(),
                    "last_page" => $this->lastPage(),
                    "per_page" => $this->perPage(),
                    "from" => $this->firstItem(),
                    "to" => $this->lastItem(),
                    "total" => $this->total(),
                    "path" => $this->path(),
                ]
            ];
        } else {
            return $collection;
        }
    }
}
