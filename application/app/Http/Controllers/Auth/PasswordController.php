<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class PasswordController extends Controller
{
    /**
     * Display the password change view.
     */
    public function create(Request $request): View
    {
        $forcedChangeReason = null;

        if ($forcedChange = $request->user()->forcedPasswordChange) {
            $forcedChangeReason = __('forced-password-change.'.$forcedChange->reason);
        }

        return view('auth.change-password', [
            'request' => $request,
            'forcedChange' => (bool) $forcedChange,
            'forcedChangeReason' => $forcedChangeReason,
        ]);
    }

    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $rules = [
            'password' => ['required', Password::defaults(), 'confirmed'],
        ];

        $changeRequired = $request->user()->forcedPasswordChange()->exists();

        if (! $changeRequired) {
            $rules['current_password'] = ['required', 'current_password'];
        }

        $validated = $request->validate($rules);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        if ($changeRequired) {
            $request->user()->forcedPasswordChange()->delete();

            return redirect()->intended(route('password.change'))->with('status', __('Password changed.'));
        }

        return back()->with('status', __('Password changed.'));
    }
}
