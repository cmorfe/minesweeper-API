<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @SWG\Definition(
 *      definition="Board",
 *      required={"user_id", "width", "height", "mines", "time", "game_state"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="user_id",
 *          description="user_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="width",
 *          description="width",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="height",
 *          description="height",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="mines",
 *          description="mines",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="time",
 *          description="time",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="game_state",
 *          description="game_state",
 *          type="string"
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
class Board extends Model
{

    use HasFactory;

    const GAME_STATE_ON = 'ON';

    protected $table = 'boards';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'width',
        'height',
        'mines',
        'time',
        'game_state'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'mines' => 'integer',
        'time' => 'integer',
        'game_state' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'width' => 'required|integer|min:2|max:20',
        'height' => 'required|integer|min:2|max:20'
    ];

    public static function boot()
    {
        parent::boot();

        self::created(function (Board $board) {
            $board->createSquares();

            $board->refresh();
        });
    }

    private function createSquares()
    {
        $squares = new Collection;

        $mined = false;

        for ($x = 0; $x < $this->width; $x++) {
            for ($y = 0; $y < $this->height; $y++) {
                $squares->add(compact('x', 'y', 'mined'));
            }
        }

        $squares = $squares->shuffle();

        $i = 1;
        foreach ($squares as $key => $value) {
            $value['mined'] = true;

            $squares[$key] = $value;

            if ($i++ >= $this->mines) {
                break;
            }
        }

        $this->squares()->createMany($squares);
    }

    /**
     * @return BelongsTo
     **/
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function scopeWithGameStateOn(Builder $query)
    {
        return $query->where('game_state', '=', self::GAME_STATE_ON);
    }

    public function squares()
    {
        return $this->hasMany(Square::class);
    }

}
