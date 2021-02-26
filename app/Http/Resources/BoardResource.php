<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BoardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'width' => $this->width,
            'height' => $this->height,
            'mines' => $this->mines,
            'time' => $this->time,
            'game_state' => $this->game_state,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
