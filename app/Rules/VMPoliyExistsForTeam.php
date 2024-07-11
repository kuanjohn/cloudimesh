<?php

namespace App\Rules;

use App\Models\vmspec;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class VMPoliyExistsForTeam implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */

     protected $teamId;

     /**
      * Create a new rule instance.
      *
      * @param  int  $teamId
      * @return void
      */
     public function __construct($teamId)
     {
         $this->teamId = $teamId;
     }
 
     /**
      * Run the validation rule.
      *
      * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
      */
     public function validate(string $attribute, mixed $value, Closure $fail): void
     {
         $VMPolicyExists = vmspec::where('name', trim($value))
             ->where('team_id', $this->teamId)
             ->exists();
 
         if ($VMPolicyExists) {
             $fail('The VM policy has already exist.');
         }
     }
}
