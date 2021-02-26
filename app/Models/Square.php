<?php

namespace App\Models;

use Eloquent as Model;

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

    public $table = 'squares';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




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
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'board_id' => 'required',
        'x' => 'required|integer',
        'y' => 'required|integer',
        'mark' => 'required|string',
        'mined' => 'required|boolean',
        'open' => 'required|boolean',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return BelongsTo
     **/
    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class);
    }
}
