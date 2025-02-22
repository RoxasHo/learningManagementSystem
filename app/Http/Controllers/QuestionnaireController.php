<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\QuestionnaireResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionnaireController extends Controller
{
    public function showQuestionnaire()
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return redirect('/login'); // Redirect to login if not authenticated
        }

        // Check if the current user is a student
        if (Auth::user()->role === 'Student') {
            $categories = Category::all(); // Fetch all categories
            return view('questionnaire', compact('categories')); // Show the questionnaire view
        }

        // Redirect teachers or other roles
        return redirect('/')->with('info', 'Questionnaire completion is not required for your role.');
    }


    public function storeResponses(Request $request)
    {
        // Check if the current user is a student
        if (Auth::user()->role === 'Student') {
            // Check if the user has already filled the questionnaire
            if (QuestionnaireResponse::where('user_id', Auth::id())->exists()) {
                return redirect('/')->with('info', 'You have already completed the questionnaire.');
            }

            $validatedData = $request->validate([
                'education_background' => 'required|string|max:255',
                'programming_experience' => 'required|boolean',
                'learned_languages' => 'nullable|string|max:500',
                'interested_categories' => 'required|array',
            ]);

            $validatedData['programming_experience'] = (bool)$validatedData['programming_experience'];
        
            QuestionnaireResponse::create([
                'user_id' => Auth::id(),
                'education_background' => $validatedData['education_background'],
                'programming_experience' => $validatedData['programming_experience'],
                'learned_languages' => $validatedData['learned_languages'], // This should now properly save
                'interested_categories' => json_encode($validatedData['interested_categories']), // Store as JSON if needed
            ]);

            return redirect('/')->with('success', 'Thank you for completing the questionnaire!');
        } else {
            return redirect('/')->with('info', 'Questionnaire completion is not required for your role.');
        }
    }


}

