<?php

namespace App\Rules;

use App\Models\UserAlertLevel;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CanAccessLevel implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $level = UserAlertLevel::find($value);

        if (!$level) {
            $fail('The :attribute does not exist.');
        }
    }
}
