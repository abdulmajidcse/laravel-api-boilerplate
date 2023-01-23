<?php

namespace App\Http\Resources\Category;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'created_at' => $this->created_at?->format('d M Y'),
            'updated_at' => $this->updated_at?->format('d M Y'),
            'parent_category' => new JoinCategoryResource($this->parentCategory),
            'sub_categories' => JoinCategoryResource::collection($this->subCategories),
        ];
    }
}
