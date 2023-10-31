<?php

namespace App\Rules;

use App\Enums\Locations;
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
        $locations = Locations::names();

        if (! in_array($value, $locations)) {
            $fail('The :attribute must be a valid location.');
        }
    }
}
