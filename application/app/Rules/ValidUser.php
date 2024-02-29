<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidUser implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = $value;

        if (! ($user instanceof User)) {
            if (! is_int($user)) {
                $fail('The :attribute must be an integer.');

                return;
            }

            $user = User::find($user);
        }

        if (! $user) {
            $fail('The :attribute does not exist.');
        }
    }
}
