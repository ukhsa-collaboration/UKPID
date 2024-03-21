<?php

namespace App\Http\Requests;

use App\Rules\CanAssignRole;
use App\Rules\ValidLocation;
use App\Rules\ValidRole;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $loggedInUser = $this->user();

        if ($loggedInUser->can('user.create_outside_location')) {
            return true;
        }

        return $loggedInUser->can('user.create')
            && (
                ! $this->request->has('location')
                || $this->request->get('location') === $loggedInUser->location->name
            );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users', 'max:255'],
            'location' => ['sometimes', 'string', new ValidLocation],
            'role' => ['required', 'integer', new ValidRole, new CanAssignRole],
        ];
    }
}
