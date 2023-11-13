<?php

namespace App\Http\Requests;

use App\Rules\ValidLocation;
use App\Rules\ValidRole;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\Permission\Models\Role;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $role = Role::findOrFail($this->request->get('role'));

        return $this->user()->can('role.assign.'.str()->snake($role->name))
            && (
                $this->user()->can('user.create_outside_location')
                || ($this->user()->can('user.create') && (! $this->request->has('location') || $this->request->get('location') === $this->user()->location->id))
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
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users'],
            'location' => ['sometimes', 'string', new ValidLocation],
            'role' => ['required', 'integer', new ValidRole],
        ];
    }
}
