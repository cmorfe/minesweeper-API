<?php

namespace App\Repositories;

use App\Models\Board;
use App\Models\User;
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
    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model(): string
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

    public function all($search = [], $skip = null, $limit = null, $columns = ['*'])
    {
        /** @var User $user */
        $user = auth()->user();

        return $user->boards()->withGameStateOn()->get();
    }

    public function find($id, $columns = ['*'])
    {
        /** @var User $user */
        $user = auth()->user();

        return $user->boards()->find($id);
    }
}
