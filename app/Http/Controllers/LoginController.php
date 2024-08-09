<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Carbon\Carbon;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $email = $request->input('email');
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            // Check if the user is eligible for daily login bonus
            $lastLoginDate = $user->last_login_at ? Carbon::parse($user->last_login_at)->startOfDay() : null;
            $today = Carbon::today();
            $showPointModal = false;
            if (!$lastLoginDate || $lastLoginDate->lt($today)) {
                $user->point += 10; // Add 10 points
                $showPointModal = true; // Flag to show the modal
            }

            // Update the last login time
            $user->last_login_at = now();
            $user->save();

            if ($showPointModal) {
                return redirect()->route('profile.collectPoint');
            }

            return $this->redirectToRoleHome($user);
        }

        return redirect()->back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function collectPoint()
    {
        return view('profile.collectPoint');
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $this->clearRememberToken();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logout successful.');
    }

    private function clearRememberToken()
    {
        Cookie::queue(Cookie::forget(Auth::getRecallerName()));
    }

    private function redirectToRoleHome($user)
    {
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
}
