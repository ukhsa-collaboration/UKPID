<?php

namespace App\Http\Controllers;

use App\Events\UserCreated;
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
     *
     * This application allows managers to create users, rather than only invite them.
     * The rationale for this being implementing invitations creates a little more work,
     * and because this is a workplace application, it's fine to generate a temporary
     * password that can be distributed to the user via email or physically to allow them to log in.
     * Also, in the unlikely event that the email service goes down, the user won't be blocked
     * by having to wait for the invite to arrive.
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();
        $currentUser = auth()->user();

        $password = Str::password(8, symbols: false);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'location' => $currentUser->can('user.create_outside_location') && isset($validated['location']) ? $validated['location'] : $currentUser->location->name,
            'password' => $password,
        ]);

        $user->assignRole($validated['role']);

        UserCreated::dispatch($user, $password);

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
     * Get current user
     */
    public function me(Request $request)
    {
        return new UserResource($request->user());
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
