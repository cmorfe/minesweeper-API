<?php

namespace App\Repositories;

use App\Models\Square;
use App\Repositories\BaseRepository;

/**
 * Class SquareRepository
 * @package App\Repositories
 * @version February 24, 2021, 10:27 pm -03
*/

class SquareRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'board_id',
        'x',
        'y',
        'mark',
        'mined',
        'open'
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
        return Square::class;
    }
}
