<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validate the input data
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            Log::info('User is authenticated', ['user' => $user]);

            // 如果用户是 Moderator，检查状态
            if ($user->role == 'Moderator') {
                $moderator = $user->moderator;

                if ($moderator->status == 'pending') {
                    Auth::logout();
                    return redirect()->route('login')->withErrors(['email' => 'Your account is currently pending approval by the superuser.']);
                }

                if ($moderator->status == 'rejected') {
                    Auth::logout();
                    return redirect()->route('login')->withErrors(['email' => 'Your account has been rejected by the superuser.']);
                }
            }

         if ($user->role == 'Student') {
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
        } else {
            // Update the last login time for non-students as well
            $user->last_login_at = now();
            $user->save();
        }

        return $this->redirectToRoleHome($user);
    } else {
        Log::info('User is not authenticated');
    }

        return redirect()->back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput();
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
