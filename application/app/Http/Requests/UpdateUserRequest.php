<?php

namespace App\Http\Requests;

use App\Rules\CanAssignRole;
use App\Rules\ValidLocation;
use App\Rules\ValidRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $loggedInUser = $this->user();
        $targetUser = $this->user;

        return $loggedInUser->can('user.update_outside_location')
            || ($loggedInUser->can('user.update') && $loggedInUser->location->id === $targetUser->location->name);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string'],
            'email' => ['sometimes', 'email', 'unique:users'],
            'location' => [
                'sometimes', 'string', new ValidLocation,
                Rule::prohibitedIf($this->user()->cannot('user.update_outside_location')),
            ],
            'role' => ['sometimes', 'integer', new ValidRole, new CanAssignRole],
        ];
    }
}
