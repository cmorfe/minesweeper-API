<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BoardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'width' => $this->width,
            'height' => $this->height,
            'mines' => $this->mines,
            'time' => $this->time,
            'game_state' => $this->game_state,
            'game_squares' => $this->when($this->hasAppended('game_squares'), $this->game_squares),
            'created_at' => $this->created_at,
        ];
    }
}
