<?php

namespace App\Rules;

use App\Models\Environment;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EnvironmentExistsForTeam implements ValidationRule
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
        $EnvironmentExists = Environment::where('name', trim($value))
            ->where('team_id', $this->teamId)
            ->exists();

        if ($EnvironmentExists) {
            $fail('The Environment has already exist.');
        }
    }
}
