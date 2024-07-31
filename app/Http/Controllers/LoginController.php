<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // Ensure this matches the path to your Blade file
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed
            $user = Auth::user();
            switch ($user->role) {
                case 'Student':
                    return redirect()->route('profile.student', ['email' => $user->email]);
                case 'Teacher':
                    return redirect()->route('profile.teacher', ['email' => $user->email]);
                case 'Moderator':
                    return redirect()->route('profile.moderator', ['email' => $user->email]);
                default:
                    Auth::logout();
                    return redirect()->route('login')->withErrors([
                        'role' => 'Invalid role specified.',
                    ]);
            }
        }

        // Authentication failed
        return redirect()->back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
