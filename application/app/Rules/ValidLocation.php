<?php

namespace App\Rules;

use App\Constants\Locations;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidLocation implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $locations = Locations::all();

        if (! in_array($value, $locations)) {
            $fail('The :attribute must be a valid location.');
        }

        // TODO: add clause to check If user has permission to manage users outside of their trust
    }
}
