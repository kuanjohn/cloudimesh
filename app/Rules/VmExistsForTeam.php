<?php

namespace App\Rules;

use App\Models\VirtualMachine;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class VmExistsForTeam implements ValidationRule
{
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
         $vmExists = VirtualMachine::where('name', trim($value))
             ->where('team_id', $this->teamId)
             ->exists();
 
         if ($vmExists) {
             $fail('The Virtual Machine\'s name has already exist.');
         }
     }
}
