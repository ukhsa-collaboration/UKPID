<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Get all users.
     */
    public function index()
    {
        return UserResource::collection(User::all());
    }

    /**
     * Create a new user.
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $password = Str::password(10);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'location' => $validated['location'],
            'password' => $password,
        ]);

        return response([
            'user' => new UserResource($user),
            'password' => $password,
        ], 201);
    }

    /**
     * Get a user.
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update a user.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Delete a user.
     */
    public function destroy(string $id)
    {
        //
    }
}
