<?php

namespace App\Http\Resources;

use App\Models\Board;
use App\Utils\Encoder;
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
        $encoder = new Encoder();

        return [
            'id' => $encoder->encode($this->id),
            'x' => $this->x,
            'y' => $this->y,
            'mark' => $this->mark,
            'mined' => $this->when($this->is_game_lost, $this->mined),
            'open' => $this->open,
            'should_reload' => $this->when($this->open, $this->should_reload),
            'adjacent_mines_count' => $this->when($this->open, $this->adjacent_mines_count)
        ];
    }
}
