<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Moderator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;

class RegisterController extends Controller {

    public function registerStudent(Request $request) {
        $validator = Validator::make($request->all(), [
                    'email' => 'required|email|unique:users,email',
                    'name' => 'required|string|max:255',
                    'password' => 'required|confirmed|min:6',
                    'gender' => 'required|string',
                    'dateOfBirth' => 'required|date',
                    'contactNumber' => 'nullable|string|max:15',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Create a new user
        $user = User::create([
                    'email' => $request->input('email'),
                    'name' => $request->input('name'),
                    'password' => Hash::make($request->input('password')),
                    'gender' => $request->input('gender'),
                    'dateOfBirth' => $request->input('dateOfBirth'),
                    'contactNumber' => $request->input('contactNumber'),
                    'role' => 'Student', // Set the role for the user
                    'profile' => null,
                    'feedback' => null,
                    'point' => 0,
        ]);

        // Create the student entry
        Student::create([
            'userID' => $user->id,
            'name' => $user->name,
                // Fill in other fields if needed
        ]);

        return redirect()->route('login')->with('success', 'Student registered successfully. Please log in.');
    }

    public function registerTeacher(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string|max:255',
            'contactNumber' => 'required|string|max:15',
            'gender' => 'required|string',
            'dateOfBirth' => 'required|date',
            'password' => 'required|confirmed|min:6',
            'certification' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'identityProof' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'teacherPicture' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'yearsOfExperience' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Retrieve file objects
            $certificationFile = $request->file('certification');
            $identityProofFile = $request->file('identityProof');
            $teacherPictureFile = $request->file('teacherPicture');

            // Generate unique filenames using the user's name
            $userName = $request->input('name');
            $userNameSlug = Str::slug($userName);
            $timestamp = time();

            $certificationName = $userNameSlug . '_certification_' . $timestamp . '.' . $certificationFile->getClientOriginalExtension();
            $identityProofName = $userNameSlug . '_identityProof_' . $timestamp . '.' . $identityProofFile->getClientOriginalExtension();
            $teacherPictureName = $userNameSlug . '_teacherPicture_' . $timestamp . '.' . $teacherPictureFile->getClientOriginalExtension();

            // Store files in public directory and get paths
            $certificationPath = $certificationFile->storeAs('certifications', $certificationName, 'public');
            $identityProofPath = $identityProofFile->storeAs('identity_proofs', $identityProofName, 'public');
            $teacherPicturePath = $teacherPictureFile->storeAs('teacher_pictures', $teacherPictureName, 'public');

            // Create a new user
            $user = User::create([
                'email' => $request->input('email'),
                'name' => $request->input('name'),
                'password' => Hash::make($request->input('password')),
                'gender' => $request->input('gender'),
                'dateOfBirth' => $request->input('dateOfBirth'),
                'contactNumber' => $request->input('contactNumber'),
                'role' => 'Teacher',
                'profile' => null,
                'feedback' => null,
                'point' => 0,
            ]);

            // Create teacher record
            Teacher::create([
                'userID' => $user->id,
                'name' => $user->name,
                'certification' => $certificationPath,
                'identityProof' => $identityProofPath,
                'teacherPicture' => $teacherPicturePath,
                'yearsOfExperience' => $request->input('yearsOfExperience'),
            ]);

            return redirect()->route('login')->with('success', 'Teacher registered successfully. Please log in.');
        } catch (\Exception $e) {
            Log::error('Teacher registration failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to register teacher. Error: ' . $e->getMessage());
        }
    }
    

public function registerModerator(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email|unique:users,email',
        'name' => 'required|string|max:255',
        'password' => 'required|confirmed|min:6',
        'gender' => 'required|string',
        'dateOfBirth' => 'required|date',
        'contactNumber' => 'nullable|string|max:15',
        'referralCode' => 'required|string',
    ]);

       if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

    try {
        // Create a new user
        $user = User::create([
               'email' => $request->input('email'),
                    'name' => $request->input('name'),
                    'password' => Hash::make($request->input('password')),
                    'gender' => $request->input('gender'),
                    'dateOfBirth' => $request->input('dateOfBirth'),
                    'contactNumber' => $request->input('contactNumber'),

            'role' => 'Moderator',
            'profile' => null,
            'feedback' => null,
            'point' => 0,
        ]);

        // Create the moderator entry
        Moderator::create([
            'userID' => $user->id,
            'name' => $user->name,
            'referralCode' => $request->input('referralCode'),
        ]);

        return redirect()->route('login')->with('success', 'Moderator registered successfully. Please log in.');
    } catch (QueryException $e) {
        return redirect()->back()->with('error', 'Failed to register moderator. Error: ' . $e->getMessage());
    }
}
}
