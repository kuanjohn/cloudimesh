<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MinMaxRule implements ValidationRule
{
    protected $max_int;

    /**
     * Create a new rule instance.
     *
     * @param  int  $teamId
     * @return void
     */
    public function __construct($max_int)
    {
        $this->max_int = $max_int;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //

        if ($value >= $this->max_int) {
            $fail('The value cannot be greater or equal to maximum value.');
        }

    }
}
