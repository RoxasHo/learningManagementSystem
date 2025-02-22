<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Moderator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    // Register Student
    public function registerStudent(Request $request)
    {
        try {
            Log::info('Creating new student.');

            $user = User::create([
                'email' => $request->input('email'),
                'name' => $request->input('name'),
                'password' => Hash::make($request->input('password')),
                'gender' => $request->input('gender'),
                'dateOfBirth' => $request->input('dateOfBirth'),
                'contactNumber' => $request->input('contactNumber'),
                'role' => 'Student',
                'status' => 'active',
            ]);

            $student = Student::create([
                'userID' => $user->id,
                'name' => $user->name,
            ]);

            if ($student) {
                Log::info('Student profile created successfully', ['studentID' => $student->studentID]);
            } else {
                Log::warning('Failed to create student profile.');
            }

            return redirect()->route('login')->with('success', 'Student registered successfully. Please log in.');
        } catch (\Exception $e) {
            Log::error('Student registration failed: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Failed to register student. Error: ' . $e->getMessage());
        }
    }

    // Validate Student Field
    public function validateStudentField(Request $request)
    {
        $field = $request->input('field');
        $rules = [];

        switch ($field) {
            case 'email':
                $rules['email'] = 'required|email|unique:users,email';
                break;
            case 'name':
                $rules['name'] = 'required|string|max:255|regex:/^[a-zA-Z\s]+$/';
                break;
            case 'contactNumber':
                $rules['contactNumber'] = 'required|string|regex:/^\d{10,15}$/|unique:users,contactNumber';
                break;
            case 'password':
                $rules['password'] = 'required|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/|min:8';
                break;
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return response()->json(['success' => true]);
    }

    // Register Teacher
    public function registerTeacher(Request $request)
    {
        Log::info('Teacher registration attempt started.');

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'unique:users,email'],
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'contactNumber' => 'required|string|regex:/^\d{10,15}$/|unique:users,contactNumber',
            'gender' => 'required|string',
            'dateOfBirth' => 'required|date',
            'password' => 'required|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/|min:8',
            'certification' => 'required|file|mimes:pdf|max:5120',
            'identityProof' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'teacherPicture' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'yearsOfExperience' => 'required|integer|min:0|max:50',
        ]);

        if ($validator->fails()) {
            Log::warning('Teacher registration validation failed.', ['errors' => $validator->errors()]);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            Log::info('Creating new teacher.');

            $user = User::create([
                'email' => $request->input('email'),
                'name' => $request->input('name'),
                'password' => Hash::make($request->input('password')),
                'gender' => $request->input('gender'),
                'dateOfBirth' => $request->input('dateOfBirth'),
                'contactNumber' => $request->input('contactNumber'),
                'role' => 'Teacher',
                'status' => 'pending',
            ]);

            // Store files and log the paths
            $certificationPath = $request->file('certification')->storeAs(
                'certifications', $user->id . '_certification_' . now()->format('Ymd_His') . '.' . $request->file('certification')->getClientOriginalExtension(), 'public'
            );
            Log::info('Certification Path:', ['path' => $certificationPath]);

            $identityProofPath = $request->file('identityProof')->storeAs(
                'identity_proofs', $user->id . '_identityProof_' . now()->format('Ymd_His') . '.' . $request->file('identityProof')->getClientOriginalExtension(), 'public'
            );
            Log::info('Identity Proof Path:', ['path' => $identityProofPath]);

            $teacherPicturePath = $request->file('teacherPicture')->storeAs(
                'teacher_pictures', $user->id . '_teacherPicture_' . now()->format('Ymd_His') . '.' . $request->file('teacherPicture')->getClientOriginalExtension(), 'public'
            );
            Log::info('Teacher Picture Path:', ['path' => $teacherPicturePath]);

            Teacher::create([
                'userID' => $user->id,
                'name' => $user->name,
                'certification' => $certificationPath,
                'identityProof' => $identityProofPath,
                'teacherPicture' => $teacherPicturePath,
                'yearsOfExperience' => $request->input('yearsOfExperience'),
            ]);

            Log::info('Teacher created successfully. Redirecting to login page.');
            return redirect()->route('login')->with('success', 'Teacher registered successfully. Please log in.');
        } catch (\Exception $e) {
            Log::error('Teacher registration failed: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Failed to register teacher. Error: ' . $e->getMessage());
        }
    }

    // Validate Teacher Field
    public function validateTeacherField(Request $request)
    {
        $field = $request->input('field');
        $rules = [];

        switch ($field) {
            case 'email':
                $rules['email'] = 'required|email|unique:users,email';
                break;
            case 'name':
                $rules['name'] = 'required|string|max:255|regex:/^[a-zA-Z\s]+$/';
                break;
            case 'contactNumber':
                $rules['contactNumber'] = 'required|string|regex:/^\d{10,15}$/|unique:users,contactNumber';
                break;
            case 'password':
                $rules['password'] = 'required|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/|min:8';
                break;
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return response()->json(['success' => true]);
    }

    // Register Moderator
    public function registerModerator(Request $request)
    {
        Log::info('Moderator registration attempt started.');

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'contactNumber' => 'required|string|regex:/^\d{10,15}$/|unique:users,contactNumber',
            'gender' => 'required|string',
            'dateOfBirth' => 'required|date',
            'password' => 'required|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/|min:8',
            'certification' => 'required|file|mimes:pdf|max:5120',
            'identityProof' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'moderatorPicture' => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            Log::warning('Moderator registration validation failed.', ['errors' => $validator->errors()]);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            Log::info('Creating new moderator.');

            $user = User::create([
                'email' => $request->input('email'),
                'name' => $request->input('name'),
                'password' => Hash::make($request->input('password')),
                'gender' => $request->input('gender'),
                'dateOfBirth' => $request->input('dateOfBirth'),
                'contactNumber' => $request->input('contactNumber'),
                'role' => 'Moderator',
                'status' => 'pending',
            ]);

            // Store files and log the paths
            $certificationPath = $request->file('certification')->storeAs(
                'certifications', $user->id . '_certification_' . now()->format('Ymd_His') . '.' . $request->file('certification')->getClientOriginalExtension(), 'public'
            );
            Log::info('Certification Path:', ['path' => $certificationPath]);

            $identityProofPath = $request->file('identityProof')->storeAs(
                'identity_proofs', $user->id . '_identityProof_' . now()->format('Ymd_His') . '.' . $request->file('identityProof')->getClientOriginalExtension(), 'public'
            );
            Log::info('Identity Proof Path:', ['path' => $identityProofPath]);

            $moderatorPicturePath = $request->file('moderatorPicture')->storeAs(
                'moderator_pictures', $user->id . '_moderatorPicture_' . now()->format('Ymd_His') . '.' . $request->file('moderatorPicture')->getClientOriginalExtension(), 'public'
            );
            Log::info('Moderator Picture Path:', ['path' => $moderatorPicturePath]);

            Moderator::create([
                'userID' => $user->id,
                'name' => $user->name,
                'certification' => $certificationPath,
                'identityProof' => $identityProofPath,
                'moderatorPicture' => $moderatorPicturePath,
            ]);

            Log::info('Moderator created successfully. Redirecting to login page.');
            return redirect()->route('login')->with('success', 'Moderator registered successfully. Please log in.');
        } catch (\Exception $e) {
            Log::error('Moderator registration failed: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Failed to register moderator. Error: ' . $e->getMessage());
        }
    }

    // Validate Moderator Field
    public function validateModeratorField(Request $request)
    {
        $field = $request->input('field');
        $rules = [];

        switch ($field) {
            case 'email':
                $rules['email'] = 'required|email|unique:users,email';
                break;
            case 'name':
                $rules['name'] = 'required|string|max:255|regex:/^[a-zA-Z\s]+$/';
                break;
            case 'contactNumber':
                $rules['contactNumber'] = 'required|string|regex:/^\d{10,15}$/|unique:users,contactNumber';
                break;
            case 'password':
                $rules['password'] = 'required|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/|min:8';
                break;
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return response()->json(['success' => true]);
    }
}
