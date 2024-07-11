<?php

namespace App\Rules;

use App\Models\AppService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AppServiceExistsForTeam implements ValidationRule
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
        $departmentExists = AppService::where('name', trim($value))
            ->where('team_id', $this->teamId)
            ->exists();

        if ($departmentExists) {
            $fail('The Application Service has already exist.');
        }
    }
}