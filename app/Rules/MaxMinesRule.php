<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MaxMinesRule implements Rule
{
    /**
     * @var
     */
    private $width;

    /**
     * @var
     */
    private $height;

    /**
     * Create a new rule instance.
     *
     * @param  $width
     * @param  $height
     */
    public function __construct($width, $height)
    {
        $this->width = $width;

        $this->height = $height;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return $value <= $this->maxMines();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return "The must must be less than or equal {$this->maxMines()}.";
    }

    /**
     * @return int
     */
    private function maxMines()
    {
        return $this->width * $this->height;
    }
}
