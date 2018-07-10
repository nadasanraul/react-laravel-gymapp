<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        CategoryResource::withoutWrapping();

        return [
            'type' => 'categories',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'exercises' => $this->exercises
            ],
            'links' => [
                'self' => route('categories.show', ['id' => $this->id])
            ]
        ];
    }
}
