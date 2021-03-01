<?php

namespace App\Repositories;

use App\Models\Square;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SquareRepository
 * @package App\Repositories
 * @version February 24, 2021, 10:27 pm -03
*/

class SquareRepository
{
    /**
     * @param $board
     * @param $square
     * @return ?Model
     */
    public function open($board, $square): ?Model
    {
        return Square::where('board_id', '=', $board)
        ->where('id', '=', $square)
        ->first();
    }

    /**
     * @param $board
     * @param $square
     * @return ?Model
     */
    public function mark($board, $square): ?Model
    {
        return Square::where('board_id', '=', $board)
        ->where('id', '=', $square)
        ->first();
    }
}
