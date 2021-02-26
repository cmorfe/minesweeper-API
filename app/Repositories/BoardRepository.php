<?php

namespace App\Repositories;

use App\Models\Board;
use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BoardRepository
 * @package App\Repositories
 * @version February 23, 2021, 3:27 am -03
*/

class BoardRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'width',
        'height',
        'mines',
        'time',
        'game_state'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Board::class;
    }

    /**
     * @param  array  $input
     * @return Model
     */
    public function create($input): Model
    {
        /** @var User $user */
        $user = auth()->user();

        return $user->boards()->create($input);
    }
}
