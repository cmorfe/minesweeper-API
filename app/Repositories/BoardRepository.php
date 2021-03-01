<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BoardRepository
 * @package App\Repositories
 * @version February 23, 2021, 3:27 am -03
 */
class BoardRepository
{
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

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        /** @var User $user */
        $user = auth()->user();

        return $user->boards()->gameIsOn()->get();
    }

    /**
     * @param  int  $id
     * @return ?Model
     */
    public function find($id): ?Model
    {
        /** @var User $user */
        $user = auth()->user();

        return $user->boards()->find($id);
    }
}
