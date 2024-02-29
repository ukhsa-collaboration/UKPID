<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Spatie\Permission\Models\Role;

class CanAssignRole implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $role = Role::findOrFail($value);

        if (auth()->user()->cannot('role.assign.'.str()->snake($role->name))) {
            $fail('You are not permitted to assign the :attribute to the user.');
        }
    }
}
