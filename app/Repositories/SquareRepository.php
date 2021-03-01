<?php

namespace App\Repositories;

use App\Models\Board;
use App\Models\Square;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SquareRepository
 * @package App\Repositories
 * @version February 24, 2021, 10:27 pm -03
 */
class SquareRepository
{
    /**
     * @param  Square  $square
     * @return Square
     */
    public function open(Square $square): Square
    {
        /** @var Board $board */
        $board = $square->board;

        if ($square->open || $square->mark == Square::MARK_FLAG || $board->game_sate != Board::GAME_STATE_ON) {
            return $square;
        }

        $square->update(['open' => true]);

        if ($square->mined) {
            $board->update(['game_state' => Board::GAME_STATE_LOST]);

            return $square;
        }

        if ($square->adjacent_mines_count == 0) {
            $adjacentSquares = $square->adjacent_squares;

            $adjacentSquares->each(function (Square $adjacentSquare) use ($square){
                if ($adjacentSquare->id != $square->id) {
                    $this->open($adjacentSquare);
                }
            });
        }

        if ($board->not_mined_and_closed_squares_count = 0) {
            $board->update(['game_state' => Board::GAME_STATE_WON]);
        }

        return $square;
    }

    /**
     * @param  Square  $square
     * @return Square
     */
    public function mark(Square $square): Square
    {
        if ($square->board->game_state != Board::GAME_STATE_ON || $square->open) {
            return $square;
        }

        $mark = match ($square->mark) {
            Square::MARK_NONE => Square::MARK_FLAG,
            Square::MARK_FLAG => Square::MARK_QUESTION,
            Square::MARK_QUESTION => Square::MARK_NONE,
        };

        $square->update(['mark' => $mark]);

        return $square;
    }

    /**
     * @param $board_id
     * @param $id
     * @return ?Model
     */
    public function findByBoardAndId($board_id, $id): ?Model
    {
        return Square::leftJoinWhere('boards', 'user_id', '=', auth()->user()->id)
            ->where('board_id', '=', $board_id)
            ->where('squares.id', '=', $id)
            ->first('squares.*');
    }
}
