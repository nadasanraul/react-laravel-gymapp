<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use JWTAuth;

class ExerciseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        ExerciseResource::withoutWrapping();

        return [
            'type' => 'exercises',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'type' => $this->type,
                'category' => $this->category
            ],
            'links' => [
                'self' => route('exercises.show', ['id' => $this->id]),
                'category' => route('categories.show', ['id' => $this->category_id])
            ],
        ];
    }
}
