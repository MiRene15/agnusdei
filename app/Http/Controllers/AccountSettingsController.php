<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountSettingsController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        return view('account.settings', [
            'user' => $user,
            'role' => $user->role,
            'routeName' => $user->role . '.settings.update',
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=(?:.*\d){2,})(?=.*[^A-Za-z0-9]).{8,}$/',
            ],
        ], [
            'password.regex' => 'Password must have at least 8 characters, 1 uppercase letter, 1 lowercase letter, 2 numbers, and 1 special character.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'The current password is incorrect.',
            ]);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        ActivityLog::record(
            $user->id,
            'account',
            'change_password',
            'User',
            $user->id,
            'Updated account password.',
            $request->ip()
        );

        return back()->with('success', 'Password updated successfully.');
    }
}
