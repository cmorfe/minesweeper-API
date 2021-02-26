<?php

namespace Database\Factories;

use App\Models\Board;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BoardFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Board::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'width' => $this->faker->numberBetween(2, 20),
            'height' => $this->faker->numberBetween(2, 20),
            'mines' => function (array $attributes) {
                return $this->faker->numberBetween(1, $attributes['width'] * $attributes['height']);
            },
            'time' => 0,
            'game_state' => Board::GAME_STATE_ON,
        ];
    }
}
