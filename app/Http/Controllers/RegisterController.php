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

        return redirect()->route('register.student.page')->with('success', 'Student registered successfully');
    }

    public function registerTeacher(Request $request) {
        $validator = Validator::make($request->all(), [
                    'email' => 'required|email|unique:users,email',
                    'name' => 'required|string|max:255',
                    'contactNumber' => 'required|string|max:15',
                    'gender' => 'required|string',
                    'dateOfBirth' => 'required|date',
                    'password' => 'required|confirmed|min:6',
                    'certification' => 'required|file|mimes:pdf,doc,docx',
                    'identityProof' => 'required|file|mimes:pdf,doc,docx',
                    'picture' => 'required|file|image',
                    'yearsOfExperience' => 'required|integer',
        ]);
        //var_dump($request->email);


        if ($validator->fails()) {
            //var_dump($request->email);
            return redirect()->back()->withErrors($validator)->withInput();
        }
        var_dump($request->email);

        try {

            // 获取文件对象
            $certificationFile = $request->file('certification');
            $identityProofFile = $request->file('identityProof');
            $pictureFile = $request->file('picture');

            // 创建唯一的文件名
            $timestamp = time();
            $certificationName = 'certification_' . $timestamp . '.' . $certificationFile->getClientOriginalExtension();
            $identityProofName = 'identityProof_' . $timestamp . '.' . $identityProofFile->getClientOriginalExtension();
            $pictureName = 'picture_' . $timestamp . '.' . $pictureFile->getClientOriginalExtension();

            // 存储文件路径
            $certificationPath = Storage::putFileAs('certifications', $certificationFile, $certificationName);
            $identityProofPath = Storage::putFileAs('identity_proofs', $identityProofFile, $identityProofName);
            $picturePath = Storage::putFileAs('profile_pictures', $pictureFile, $pictureName);

            // 创建新用户
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
            // var_dump($user);
            // 创建教师记录
            Teacher::create([
                'userID' => $user->id,
                'name' => $user->name,
                'certification' => $certificationPath,
                'identityProof' => $identityProofPath,
                'picture' => $picturePath,
                'yearsOfExperience' => $request->input('yearsOfExperience'),
            ]);

            return redirect()->route('register.teacher.page')->with('success', 'Teacher registered successfully');
        } catch (\Exception $e) {
            //  var_dump(e);

            Log::error('Teacher registration failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to register teacher. Please try again.');
        }
    }

    public function showModeratorRegistrationForm() {
        return view('auth.registerModerator');
    }

    public function registerModerator(Request $request) {
        $validator = Validator::make($request->all(), [
                    'email' => 'required|email|unique:users,email',
                    'name' => 'required|string|max:255',
                    'password' => 'required|confirmed|min:6',
                    'gender' => 'required|string',
                    'dateOfBirth' => 'required|date',
                    'contactNumber' => 'nullable|string|max:15',
                    'preferredCode' => 'required|string|max:255',
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
                        'contactNumber' => $request->input('contactNumber'),
                        'dateOfBirth' => $request->input('dateOfBirth'),
                        'role' => 'Moderator',
                        'profile' => null,
                        'feedback' => null,
                        'point' => 0,
            ]);

            // Create the moderator entry
            Moderator::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'preferred_code' => $request->input('preferredCode'),
            ]);

            return redirect()->route('register.moderator.page')->with('success', 'Moderator registered successfully');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'There was a problem with the database. Please try again later.');
        }
    }
}
