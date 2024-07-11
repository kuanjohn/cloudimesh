<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NumIncvCpuRule implements ValidationRule
{
    protected $min_int;
    
    public function __construct($min_int)
    {
        $this->min_int = $min_int;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {           
        $previousv = 0;
        // $value = explode(",", $value);
        // $value = array_map('intval', $value);

        if (is_array($value)) {
            foreach ($value as $v) {
                    
                if (!is_int($v) || $v <= 0) {
                    $fail(
                        'The value must be a number greater than 0 or an array containing valid values.'
                    );
                }

                if ($v < $this->min_int) {
                    $fail(
                        'The value must be a number greater than min vCPU values.'
                    );
                }
                // dd($v);
                if ($v <= $previousv) {
                    $fail(
                        'The value must be a integer greater than previous number in the array.'
                    );
                }

                $previousv = $v;
            }
        } else {
            $fail (
                'The format is not supported.'
            );
        }
        
    }


    
}
