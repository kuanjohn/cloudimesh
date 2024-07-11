<?php

namespace App\Rules;

use App\Models\Project;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ProjectExistsForTeam implements ValidationRule
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
         $storageExists = Project::where('name', trim($value))
             ->where('team_id', $this->teamId)
             ->exists();
 
         if ($storageExists) {
             $fail('The Project name has already exist.');
         }
     }
}
