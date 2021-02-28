<?php

namespace App\Http\Resources;

use App\Models\Board;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SquareResource extends JsonResource
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
            'x' => $this->x,
            'y' => $this->y,
            'mark' => $this->mark,
            'mined' => $this->when($this->board->game_state != Board::GAME_STATE_ON, $this->mined),
            'open' => $this->open
        ];
    }
}
