<?php

namespace App\Http\Controllers;

use App\Events\UserCreated;
use App\Http\Requests\AuditRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\AuditResource;
use App\Http\Resources\UserResource;
use App\Models\Audit;
use App\Models\User;
use App\Traits\AuditableController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    use AuditableController;

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
     * Get the current user.
     */
    public function me(Request $request)
    {
        return new UserResource($request->user(), true);
    }

    /**
     * Update a user.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());

        return new UserResource($user);
    }

    /**
     * Delete a user.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Get audit logs related to users.
     *
     * @response \Illuminate\Http\Resources\Json\AnonymousResourceCollection<App\Http\Resources\AuditResource>
     */
    public function audits(AuditRequest $request)
    {
        $validated = $request->validated();

        $audits = self::auditFiltersAndOrder(Audit::where('auditable_type', User::class), $validated);

        return AuditResource::collection($audits->paginate());
    }

    /**
     * Get audit logs relating to a user.
     *
     * @response \Illuminate\Http\Resources\Json\AnonymousResourceCollection<App\Http\Resources\AuditResource>
     */
    public function audit(AuditRequest $request, User $user)
    {
        $validated = $request->validated();

        $audits = self::auditFiltersAndOrder($user->audits(), $validated);

        return AuditResource::collection($audits->paginate());
    }
}
