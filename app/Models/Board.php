<?php

namespace App\Models;

use App\Rules\MaxMinesRule;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;

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
     * @param  int  $width
     * @param  int  $height
     * @return array
     */
    public static function rules(int $width, int $height): array
    {
        return [
            'width' => 'required|integer|min:2|max:20',
            'height' => 'required|integer|min:2|max:20',
            'mines' => [
                'required', 'integer', 'min:1', new MaxMinesRule($width, $height)
            ]
        ];
    }

    public static function boot()
    {
        parent::boot();

        // On creating
        self::created(function () {
            $this->createSquares();
        });
    }

    private function createSquares() {

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

}
