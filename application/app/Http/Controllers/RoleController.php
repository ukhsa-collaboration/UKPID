<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoleResource;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Get all roles.
     */
    public function index(Request $request)
    {
        $request->validate([
            /**
             * List permissions assigned to each role
             *
             * @example 1
             */
            'with_permissions' => ['boolean'],
        ]);

        if ($request->with_permissions) {
            $roles = Role::with(['permissions'])->get();
        } else {
            $roles = Role::all();
        }

        return RoleResource::collection($roles);
    }
}
