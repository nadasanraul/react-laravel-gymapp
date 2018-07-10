<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        SetResource::withoutWrapping();

        return [
            'type' => 'sets',
            'id' => $this->id,
            'attributes' => [
                'weight' => $this->weight,
                'reps' => $this->reps,
                'created_at' => (string)$this->created_at,
                'exercise' => [
                    'id' => $this->exercise->id,
                    'name' => $this->exercise->name,
                    'type' => $this->exercise->type
                ],
                'user' => [
                    'id' => $this->user->id,
                    'name' => $this->user->name
                ]
            ],
            'links' => [
                'self' => route('sets.show', ['set' => $this->id]),
                'exercise' => route('exercises.show', ['id' => $this->exercise->id])
            ]
        ];
    }
}
