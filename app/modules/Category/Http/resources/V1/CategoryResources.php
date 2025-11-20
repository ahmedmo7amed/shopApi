<?php


namespace Modules\Category\Services\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Category\Repositories\CategoryRepository;


class CategoryResources extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'parent_id' => $this->parent_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
