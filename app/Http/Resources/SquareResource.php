<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SquareResource extends JsonResource
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
            'board_id' => $this->board_id,
            'x' => $this->x,
            'y' => $this->y,
            'mark' => $this->mark,
            'mined' => $this->mined,
            'open' => $this->open,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
