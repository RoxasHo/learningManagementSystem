<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Moderator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class ProfileController extends Controller
{
     public function showStudentProfile($email)
{
    $user = User::where('email', $email)->firstOrFail();
    $student = Student::where('userID', $user->id)->firstOrFail();

    return view('profile.student', compact('student'));
}


public function updateProfilePhoto(Request $request)
{
    $request->validate([
        'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    $student = Auth::user()->student; // Assumes User has a one-to-one relationship with Student

    // Get the uploaded file
    $file = $request->file('profile_photo');

    // Create a unique filename based on the current timestamp and original extension
    $timestamp = time();
    $filename = 'profile_photo_' . $timestamp . '.' . $file->getClientOriginalExtension();

    // Store the file in the 'studentPhoto' directory
    $filePath = Storage::putFileAs('studentPhoto', $file, $filename);

    // Update the student profile photo URL in the database
    try {
        $student->profile_photo_url = $filename; // Corrected path
        $student->save();
    } catch (\Exception $e) {
        return back()->with('error', 'Failed to update profile photo: ' . $e->getMessage());
    }

    return back()->with('success', 'Profile photo updated successfully.');
}


    public function showTeacherProfile($email)
    {
        $user = User::where('email', $email)->firstOrFail();
        $teacher = Teacher::where('userID', $user->id)->firstOrFail(); // 根据 User ID 查找 Teacher 实例

        return view('profile.teacher', compact('teacher')); // 传递 Teacher 实例到视图
    }

    public function showModeratorProfile($email)
    {
        $user = User::where('email', $email)->firstOrFail();
        $moderator = Moderator::where('userID', $user->id)->firstOrFail(); // 根据 User ID 查找 Moderator 实例

        return view('profile.moderator', compact('moderator')); // 传递 Moderator 实例到视图
    }
}
