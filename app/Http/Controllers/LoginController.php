<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
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
        
        $credentials = [
            'email' => strtolower(trim($request->email)), 
            'password' => $request->password,
        ];
        $remember = $request->filled('remember');
        
        Log::info('Login attempted for user:', ['email' => $request->email]);

        // 手动获取用户来进行密码的调试
        $user = User::where('email', trim($request->email))->first();
        if ($user) {
            Log::info('User found in database:', ['email' => $user->email]);
            if (Hash::check($request->password, $user->password)) {
                Log::info('Manual password check succeeded for user:', ['email' => $user->email]);
            } else {
                Log::warning('Manual password check failed for user:', ['email' => $user->email]);
            }
        } else {
            Log::warning('No user found with email:', ['email' => trim($request->email)]);
        }

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            Log::info('User is authenticated', ['user' => $user]);

            // Check user status
            if ($user->status === 'rejected') {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Your account has been rejected. Please contact support for more information.');
            }

            if ($user->status === 'pending') {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Your account is currently pending approval by the superuser.');
            }

            // Student login reward logic
            if ($user->role === 'Student') {
                $lastLoginDate = $user->last_login_at ? Carbon::parse($user->last_login_at)->startOfDay() : null;
                $today = Carbon::today();
                $showPointModal = false;
            
                if (!$lastLoginDate || $lastLoginDate->lt($today)) {
                    $student = $user->student; // Ensure this relationship is defined
                    $student->points += 10; // Add 10 points
                    $student->save(); // Save the updated points
                    $showPointModal = true; // Flag to show the modal
                }
            
                $user->last_login_at = now();
                $user->save();
            
                if ($showPointModal) {
                    return redirect()->route('profile.collectPoint');
                }
            }

            return $this->redirectToRoleHome($user);
        } else {
            Log::info('User is not authenticated');
            Log::info('Authentication failed for user:', ['email' => $request->email]);

            return redirect()->back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->withInput();
        }
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
                // return redirect()->route('profile.student', ['email' => $user->email]);
                return redirect('/');
            case 'Teacher':
                return redirect()->route('profile.teacher', ['email' => $user->email]);
            case 'Moderator':
                //dump($user->email);
                return redirect()->route('profile.moderator', ['email' => $user->email]);
            case 'Superuser':
                Log::info('Superuser authenticated, redirecting to dashboard', ['email' => $user->email]);
                return redirect()->route('superuser.dashboard');      
            default:
                Auth::logout();
                return redirect()->route('login')->withErrors([
                    'role' => 'Invalid role specified.',
                ]);
        }
    }
}
