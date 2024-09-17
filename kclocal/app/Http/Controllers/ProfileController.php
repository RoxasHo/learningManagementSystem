<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Moderator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    // Show the student profile
    public function showStudentProfile($email)
    {
        $currentUser = Auth::user();

        if ($currentUser->role !== 'Student') {
            return redirect('/home')->withErrors('You do not have permission to access this page.');
        }

        if ($currentUser->email !== $email) {
            return redirect('/home')->withErrors('You can only view your own profile.');
        }

        $user = User::where('email', $email)->firstOrFail();
        $student = Student::where('userID', $user->id)->firstOrFail();

        return view('profile.student', compact('student'));
    }

    // Update the student's profile picture
    public function updateStudentPicture(Request $request)
    {
        $request->validate([
            'studentPicture' => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        $student = Auth::user()->student;

        if ($student) {
            Log::info('Student retrieved', ['studentID' => $student->studentID]);

            $studentPictureFile = $request->file('studentPicture');
            $userID = $student->userID;
            $timestamp = now()->format('Ymd_His');
            $studentPictureName = $userID . '_updated_' . $timestamp . '.' . $studentPictureFile->getClientOriginalExtension();
            $studentPicturePath = $studentPictureFile->storeAs('student_pictures', $studentPictureName, 'public');

            Log::info('Profile picture stored', ['path' => $studentPicturePath]);

            try {
                if ($student->studentPicture) {
                    Storage::disk('public')->delete($student->studentPicture);
                }

                $student->update(['studentPicture' => $studentPicturePath]);

                Log::info('Profile picture updated successfully', ['studentID' => $student->studentID]);

                return back()->with('success', 'Profile picture updated successfully.');
            } catch (\Exception $e) {
                Log::error('Failed to update profile picture', ['error' => $e->getMessage()]);

                return back()->with('error', 'Failed to update profile picture: ' . $e->getMessage());
            }
        }

        Log::warning('Student profile not found.');
        return back()->with('error', 'Student profile not found.');
    }

    // Edit Student
    public function editStudent($email)
    {
        $user = User::where('email', $email)->firstOrFail();
        $student = Student::where('userID', $user->id)->firstOrFail();

        return view('profile.editStudent', compact('student'));
    }

    // Update Student
    public function updateStudent(Request $request, $email)
    {
        Log::info('Attempting to update student profile', ['email' => $email]);

        $user = User::where('email', $email)->firstOrFail();
        $student = Student::where('userID', $user->id)->firstOrFail();

        Log::info('User and student records found', ['userID' => $user->id, 'studentID' => $student->studentID]);

        $request->validate([
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'dateOfBirth' => 'required|date|before:today',
            'contactNumber' => 'nullable|string|max:15|regex:/^\d{10,15}$/',
            'studentPicture' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            $user->update([
                'name' => $request->input('name'),
                'dateOfBirth' => $request->input('dateOfBirth'),
                'contactNumber' => $request->input('contactNumber'),
                'gender' => $request->input('gender'),
            ]);

            $student->update(['name' => $request->input('name')]);

            Log::info('Profile updated successfully', ['studentID' => $student->studentID]);

            return redirect()->route('profile.student', ['email' => $user->email])->with('success', 'Profile updated successfully');
        } catch (\Exception $e) {
            Log::error('Failed to update student profile', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to update profile: ' . $e->getMessage());
        }
    }

    // Show Teacher Profile
    public function showTeacherProfile($email)
    {
        $currentUser = Auth::user();

        if ($currentUser->role !== 'Teacher') {
            return redirect('/home')->withErrors('You do not have permission to access this page.');
        }

        if ($currentUser->email !== $email) {
            return redirect('/home')->withErrors('You can only view your own profile.');
        }

        $user = User::where('email', $email)->firstOrFail();
        $teacher = Teacher::where('userID', $user->id)->firstOrFail();

        return view('profile.teacher', compact('teacher'));
    }

    // Update Teacher Picture
    public function updateTeacherPicture(Request $request)
    {
        $request->validate([
            'teacherPicture' => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        $teacher = Auth::user()->teacher;

        if ($teacher) {
            Log::info('Teacher retrieved', ['teacherID' => $teacher->teacherID]);

            $teacherPictureFile = $request->file('teacherPicture');
            $userID = $teacher->userID;
            $timestamp = now()->format('Ymd_His');
            $teacherPictureName = $userID . '_updated_' . $timestamp . '.' . $teacherPictureFile->getClientOriginalExtension();
            $teacherPicturePath = $teacherPictureFile->storeAs('teacher_pictures', $teacherPictureName, 'public');

            Log::info('Profile picture stored', ['path' => $teacherPicturePath]);

            try {
                if ($teacher->teacherPicture) {
                    Storage::disk('public')->delete($teacher->teacherPicture);
                }

                $teacher->update(['teacherPicture' => $teacherPicturePath]);

                Log::info('Profile picture updated successfully', ['teacherID' => $teacher->teacherID]);

                return back()->with('success', 'Profile picture updated successfully.');
            } catch (\Exception $e) {
                Log::error('Failed to update profile picture', ['error' => $e->getMessage()]);

                return back()->with('error', 'Failed to update profile picture: ' . $e->getMessage());
            }
        }

        Log::warning('Teacher profile not found.');
        return back()->with('error', 'Teacher profile not found.');
    }

    // Update Teacher Certification
    public function updateTeacherCertification(Request $request)
    {
        $request->validate([
            'certification' => 'required|file|mimes:pdf|max:5120',
        ]);

        $teacher = Auth::user()->teacher;

        if ($teacher) {
            $certificationFile = $request->file('certification');
            $userID = $teacher->userID;
            $timestamp = now()->format('Ymd_His');
            $certificationName = $userID . '_updated_' . $timestamp . '.' . $certificationFile->getClientOriginalExtension();
            $certificationPath = $certificationFile->storeAs('certifications', $certificationName, 'public');

            if ($teacher->certification) {
                Storage::disk('public')->delete($teacher->certification);
            }

            $teacher->update(['certification' => $certificationPath]);

            return back()->with('success', 'Certification updated successfully.');
        }

        return back()->with('error', 'Failed to update certification.');
    }

    // Update Teacher Identity Proof
    public function updateTeacherIdentityProof(Request $request)
    {
        $request->validate([
            'identityProof' => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        $teacher = Auth::user()->teacher;

        if ($teacher) {
            $identityProofFile = $request->file('identityProof');
            $userID = $teacher->userID;
            $timestamp = now()->format('Ymd_His');
            $identityProofName = $userID . '_updated_' . $timestamp . '.' . $identityProofFile->getClientOriginalExtension();
            $identityProofPath = $identityProofFile->storeAs('identity_proofs', $identityProofName, 'public');

            if ($teacher->identityProof) {
                Storage::disk('public')->delete($teacher->identityProof);
            }

            $teacher->update(['identityProof' => $identityProofPath]);

            return back()->with('success', 'Identity Proof updated successfully.');
        }

        return back()->with('error', 'Failed to update Identity Proof.');
    }

    // Edit Teacher
    public function editTeacher($email)
    {
        $user = User::where('email', $email)->firstOrFail();
        $teacher = Teacher::where('userID', $user->id)->firstOrFail();

        return view('profile.editTeacher', compact('teacher'));
    }

    // Update Teacher
    public function updateTeacher(Request $request, $email)
    {
        try {
            DB::beginTransaction();

            $user = User::where('email', $email)->firstOrFail();
            $teacher = Teacher::where('userID', $user->id)->firstOrFail();

            $request->validate([
                'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
                'dateOfBirth' => 'required|date|before:today',
                'contactNumber' => 'required|string|max:15|regex:/^\d{10,15}$/',
                'yearsOfExperience' => 'required|integer|min:0|max:50',
                'certification' => 'nullable|file|mimes:pdf|max:5120',
                'identityProof' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            ]);

            // Update user information
            $user->update([
                'name' => $request->input('name'),
                'contactNumber' => $request->input('contactNumber'),
                'gender' => $request->input('gender'),
                'dateOfBirth' => $request->input('dateOfBirth'),
            ]);

            // Update teacher-specific fields
            $teacher->update([
                'name' => $request->input('name'),
                'yearsOfExperience' => $request->input('yearsOfExperience'),
            ]);

            // Handle file uploads
            if ($request->hasFile('certification')) {
                $certificationFile = $request->file('certification');
                $certificationName = $user->id . '_certification_' . now()->format('Ymd_His') . '.' . $certificationFile->getClientOriginalExtension();
                $certificationPath = $certificationFile->storeAs('certifications', $certificationName, 'public');

                if ($teacher->certification) {
                    Storage::disk('public')->delete($teacher->certification);
                }

                $teacher->update(['certification' => $certificationPath]);
            }

            if ($request->hasFile('identityProof')) {
                $identityProofFile = $request->file('identityProof');
                $identityProofName = $user->id . '_identityProof_' . now()->format('Ymd_His') . '.' . $identityProofFile->getClientOriginalExtension();
                $identityProofPath = $identityProofFile->storeAs('identity_proofs', $identityProofName, 'public');

                if ($teacher->identityProof) {
                    Storage::disk('public')->delete($teacher->identityProof);
                }

                $teacher->update(['identityProof' => $identityProofPath]);
            }

            DB::commit();

            return redirect()->route('profile.teacher', ['email' => $user->email])->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update teacher profile', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to update profile. Please try again.');
        }
    }

    // Show Moderator Profile
    public function showModeratorProfile($email)
    {
        $currentUser = Auth::user();

        if ($currentUser->role !== 'Moderator') {
            return redirect('/home')->withErrors('You do not have permission to access this page.');
        }

        if ($currentUser->email !== $email) {
            return redirect('/home')->withErrors('You can only view your own profile.');
        }

        $user = User::where('email', $email)->firstOrFail();
        $moderator = Moderator::where('userID', $user->id)->firstOrFail();

        return view('profile.moderator', compact('moderator'));
    }

    // Update Moderator Picture
    public function updateModeratorPicture(Request $request)
    {
        $request->validate([
            'moderatorPicture' => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        $moderator = Auth::user()->moderator;

        if ($moderator) {
            Log::info('Moderator retrieved', ['moderatorID' => $moderator->moderatorID]);

            $moderatorPictureFile = $request->file('moderatorPicture');
            $userID = $moderator->userID;
            $timestamp = now()->format('Ymd_His');
            $moderatorPictureName = $userID . '_updated_' . $timestamp . '.' . $moderatorPictureFile->getClientOriginalExtension();
            $moderatorPicturePath = $moderatorPictureFile->storeAs('moderator_pictures', $moderatorPictureName, 'public');

            Log::info('Profile picture stored', ['path' => $moderatorPicturePath]);

            try {
                if ($moderator->moderatorPicture) {
                    Storage::disk('public')->delete($moderator->moderatorPicture);
                }

                $moderator->update(['moderatorPicture' => $moderatorPicturePath]);

                Log::info('Profile picture updated successfully', ['moderatorID' => $moderator->moderatorID]);

                return back()->with('success', 'Profile picture updated successfully.');
            } catch (\Exception $e) {
                Log::error('Failed to update profile picture', ['error' => $e->getMessage()]);

                return back()->with('error', 'Failed to update profile picture: ' . $e->getMessage());
            }
        }

        Log::warning('Moderator profile not found.');
        return back()->with('error', 'Moderator profile not found.');
    }

    // Edit Moderator
    public function editModerator($email)
    {
        $user = User::where('email', $email)->firstOrFail();
        $moderator = Moderator::where('userID', $user->id)->firstOrFail();

        return view('profile.editModerator', compact('moderator'));
    }

    // Update Moderator
    public function updateModerator(Request $request, $email)
    {
        try {
            DB::beginTransaction();

            $user = User::where('email', $email)->firstOrFail();
            $moderator = Moderator::where('userID', $user->id)->firstOrFail();

            $request->validate([
                'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
                'dateOfBirth' => 'required|date|before:today',
                'contactNumber' => 'nullable|string|max:15|regex:/^\d{10,15}$/',
                'moderatorPicture' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
                'identityProof' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
                'certification' => 'nullable|file|mimes:pdf|max:5120',
            ]);

            // Update basic user information
            $user->update([
                'name' => $request->input('name'),
                'contactNumber' => $request->input('contactNumber'),
                'gender' => $request->input('gender'),
                'dateOfBirth' => $request->input('dateOfBirth'),
            ]);

            // Update moderator-specific fields
            $moderator->update(['name' => $request->input('name')]);

            // Handle file uploads
            if ($request->hasFile('moderatorPicture')) {
                $moderatorPictureFile = $request->file('moderatorPicture');
                $moderatorPictureName = $user->id . '_moderatorPicture_' . now()->format('Ymd_His') . '.' . $moderatorPictureFile->getClientOriginalExtension();
                $moderatorPicturePath = $moderatorPictureFile->storeAs('moderator_pictures', $moderatorPictureName, 'public');

                if ($moderator->moderatorPicture) {
                    Storage::disk('public')->delete($moderator->moderatorPicture);
                }

                $moderator->update(['moderatorPicture' => $moderatorPicturePath]);
            }

            if ($request->hasFile('certification')) {
                $certificationFile = $request->file('certification');
                $certificationName = $user->id . '_certification_' . now()->format('Ymd_His') . '.' . $certificationFile->getClientOriginalExtension();
                $certificationPath = $certificationFile->storeAs('certifications', $certificationName, 'public');

                if ($moderator->certification) {
                    Storage::disk('public')->delete($moderator->certification);
                }

                $moderator->update(['certification' => $certificationPath]);
            }

            if ($request->hasFile('identityProof')) {
                $identityProofFile = $request->file('identityProof');
                $identityProofName = $user->id . '_identityProof_' . now()->format('Ymd_His') . '.' . $identityProofFile->getClientOriginalExtension();
                $identityProofPath = $identityProofFile->storeAs('identity_proofs', $identityProofName, 'public');

                if ($moderator->identityProof) {
                    Storage::disk('public')->delete($moderator->identityProof);
                }

                $moderator->update(['identityProof' => $identityProofPath]);
            }

            DB::commit();

            return redirect()->route('profile.moderator', ['email' => $user->email])->with('success', 'Profile updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update moderator profile', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to update profile. Please try again.');
        }
    }
}
