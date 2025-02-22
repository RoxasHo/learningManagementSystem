<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\QuestionnaireResponse;

class EnsureQuestionnaireCompleted
{
    public function handle($request, Closure $next)
    {
        // Ensure the user is authenticated
        if (!Auth::check()) {
            // If the user is not logged in, redirect to the login page
            return redirect()->route('login');
        }

        // Check if the authenticated user is a student
        if (Auth::user()->role === 'Student') {
            // Check if the student has completed the questionnaire
            $questionnaireCompleted = QuestionnaireResponse::where('user_id', Auth::id())->exists();

            // Redirect to the questionnaire if not completed
            if (!$questionnaireCompleted) {
                return redirect()->route('squestionnaire.show')->with('info', 'Please complete the questionnaire.');
            }
        }

        // Proceed for non-students without questionnaire check
        return $next($request);
    }
}

