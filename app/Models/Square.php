<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @SWG\Definition(
 *      definition="Square",
 *      required={"board_id", "x", "y", "mark", "mined", "open"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="board_id",
 *          description="board_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="x",
 *          description="x",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="y",
 *          description="y",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="mark",
 *          description="mark",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="mined",
 *          description="mined",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="open",
 *          description="open",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="created_at",
 *          description="created_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="updated_at",
 *          description="updated_at",
 *          type="string",
 *          format="date-time"
 *      )
 * )
 */
class Square extends Model
{

    use HasFactory;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    const MARK_NONE = 'NONE';
    const MARK_FLAG = 'FLAG';
    const MARK_QUESTION = 'QUESTION';

    public $table = 'squares';

    public $fillable = [
        'x',
        'y',
        'mark',
        'mined',
        'open'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'board_id' => 'integer',
        'x' => 'integer',
        'y' => 'integer',
        'mark' => 'string',
        'mined' => 'boolean',
        'open' => 'boolean'
    ];

    /**
     * @return BelongsTo
     **/
    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class);
    }

    /**
     * @return Builder
     */
    public function adjacentSquaresQuery(): Builder
    {
        return Square::where('board_id', '=', $this->board_id)
            ->whereBetween('x', [$this->x - 1, $this->y + 1])
            ->whereBetween('y', [$this->y - 1, $this->y + 1]);
    }

    /**
     * @return int
     */
    public function getAdjacentMinesCountAttribute(): int
    {
        return $this->adjacentSquaresQuery()
            ->where('mined', '=', true)
            ->count();
    }

    /**
     * @return bool
     */
    public function getIsGameLostAttribute(): bool
    {
        return $this->board->game_state == Board::GAME_STATE_LOST;
    }

    /**
     * @return bool
     */
    public function getShouldReloadAttribute(): bool
    {
        return $this->adjacent_mines_count == 0 || $this->is_game_lost;
    }

    public function toggleMark()
    {
        switch ($this->mark) {
            case self::MARK_NONE:
                $this->mark = self::MARK_FLAG;
                break;
            case self::MARK_FLAG:
                $this->mark = self::MARK_QUESTION;
                break;
            case self::MARK_QUESTION:
                $this->mark = self::MARK_NONE;
                break;
        }
    }
}
