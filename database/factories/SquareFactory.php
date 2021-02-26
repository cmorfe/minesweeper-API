<?php

namespace Database\Factories;

use App\Models\Board;
use App\Models\Square;
use Illuminate\Database\Eloquent\Factories\Factory;

class SquareFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Square::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        /** @var Board $board */
        $board = Board::factory()->create();

        return [
            'board_id' => $board->id,
            'x' => $this->faker->numberBetween(0, $board->width - 1),
            'y' => $this->faker->numberBetween(0, $board->height - 1),
            'mark' => 'NONE',
            'mined' => false,
            'open' => false
        ];
    }
}
