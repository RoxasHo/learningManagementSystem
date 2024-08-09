<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Moderator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{

     // Show the student profile
     public function showStudentProfile($email)
     {
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
 
             // Get the uploaded file
             $studentPictureFile = $request->file('studentPicture');
 
             // Get the student's name and create a slug
             $studentName = $student->name;
             $studentNameSlug = Str::slug($studentName);
 
             // Create a unique filename
             $timestamp = time();
             $studentPictureName = $studentNameSlug . '_studentPicture_' . $timestamp . '.' . $studentPictureFile->getClientOriginalExtension();
 
             // Store the file in the 'studentPicture' directory within 'storage/app/public'
             $studentPicturePath = $studentPictureFile->storeAs('student_pictures', $studentPictureName, 'public');
 
             Log::info('Profile picture stored', ['path' => $studentPicturePath]);
 
             // Update the student profile picture in the database
             try {
                 // Delete old picture if exists
                 if ($student->studentPicture) {
                     Storage::disk('public')->delete($student->studentPicture);
                 }
 
                 // Update the existing student record
                 $student->update([
                     'studentPicture' => $studentPicturePath, // Update file path in the database
                 ]);
 
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


     public function editStudent($email)
     {
         $user = User::where('email', $email)->firstOrFail();
         $student = Student::where('userID', $user->id)->firstOrFail();
 
         return view('profile.editStudent', compact('student'));
     }
 
     public function updateStudent(Request $request, $email)
     {
         $request->validate([
             'name' => 'required|string|max:255',
             'email' => 'required|email|max:255',
             'dateOfBirth' => 'required|date',
             'contactNumber' => 'nullable|string|max:15',
         ]);
 
         $user = User::where('email', $email)->firstOrFail();
         $student = Student::where('userID', $user->id)->firstOrFail();
 
         $user->name = $request->input('name');
         $user->email = $request->input('email');
         $user->dateOfBirth = $request->input('dateOfBirth');
         $user->contactNumber = $request->input('contactNumber');
         $user->save();
 
         $student->name = $request->input('name');
         $student->save();
 
         return redirect()->route('profile.student', ['email' => $user->email])->with('success', 'Profile updated successfully');
     }

     public function showTeacherProfile($email)
     {
         $user = User::where('email', $email)->firstOrFail();
         $teacher = Teacher::where('userID', $user->id)->firstOrFail();
     
         return view('profile.teacher', compact('teacher'));
     }
     
     public function updateTeacherPicture(Request $request)
     {
         $request->validate([
             'teacherPicture' => 'required|file|mimes:jpg,jpeg,png|max:2048',
         ]);
     
         $teacher = Auth::user()->teacher;
     
         if ($teacher) {
             Log::info('Teacher retrieved', ['teacherID' => $teacher->teacherID]);
     
             $teacherPictureFile = $request->file('teacherPicture');
             $teacherName = $teacher->name;
             $teacherNameSlug = Str::slug($teacherName);
             $timestamp = time();
             $teacherPictureName = $teacherNameSlug . '_teacherPicture_' . $timestamp . '.' . $teacherPictureFile->getClientOriginalExtension();
             $teacherPicturePath = $teacherPictureFile->storeAs('teacher_pictures', $teacherPictureName, 'public');
     
             Log::info('Profile picture stored', ['path' => $teacherPicturePath]);
     
             try {
                 if ($teacher->teacherPicture) {
                     Storage::disk('public')->delete($teacher->teacherPicture);
                 }
     
                 $teacher->teacherPicture = $teacherPicturePath;
                 $teacher->save();
     
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
     
     public function updateTeacherCertification(Request $request)
     {
         $request->validate([
             'certification' => 'required|file|mimes:pdf,doc,docx|max:2048',
         ]);
     
         $teacher = Auth::user()->teacher;
     
         if ($teacher) {
             $certificationFile = $request->file('certification');
             $certificationName = $teacher->name . '_certification_' . time() . '.' . $certificationFile->getClientOriginalExtension();
             $certificationPath = $certificationFile->storeAs('certifications', $certificationName, 'public');
     
             if ($teacher->certification) {
                 Storage::disk('public')->delete($teacher->certification);
             }
     
             $teacher->certification = $certificationPath;
             $teacher->save();
     
             return back()->with('success', 'Certification updated successfully.');
         }
     
         return back()->with('error', 'Failed to update certification.');
     }
     
     public function updateTeacherIdentityProof(Request $request)
     {
         $request->validate([
             'identityProof' => 'required|file|mimes:pdf,doc,docx|max:2048',
         ]);
     
         $teacher = Auth::user()->teacher;
     
         if ($teacher) {
             $identityProofFile = $request->file('identityProof');
             $identityProofName = $teacher->name . '_identityProof_' . time() . '.' . $identityProofFile->getClientOriginalExtension();
             $identityProofPath = $identityProofFile->storeAs('identity_proofs', $identityProofName, 'public');
     
             if ($teacher->identityProof) {
                 Storage::disk('public')->delete($teacher->identityProof);
             }
     
             $teacher->identityProof = $identityProofPath;
             $teacher->save();
     
             return back()->with('success', 'Identity Proof updated successfully.');
         }
     
         return back()->with('error', 'Failed to update Identity Proof.');
     }
     
 
     public function editTeacher($email)
     {
         $user = User::where('email', $email)->firstOrFail();
         $teacher = Teacher::where('userID', $user->id)->firstOrFail();
     
         return view('profile.editTeacher', compact('teacher'));
     }
     
     public function updateTeacher(Request $request, $email)
     {
         $user = User::where('email', $email)->firstOrFail();
         $teacher = Teacher::where('userID', $user->id)->firstOrFail();
     
         $request->validate([
             'name' => 'required|string|max:255',
             'contactNumber' => 'required|string|max:15',
             'gender' => 'required|string',
             'dateOfBirth' => 'required|date',
             'yearsOfExperience' => 'required|integer',
             'certification' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
             'identityProof' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
         ]);
     
         $user->name = $request->name;
         $user->contactNumber = $request->contactNumber;
         $user->gender = $request->gender;
         $user->dateOfBirth = $request->dateOfBirth;
         $user->save();
     
         $teacher->yearsOfExperience = $request->yearsOfExperience;
     
         if ($request->hasFile('certification')) {
             $certificationFile = $request->file('certification');
             $certificationName = $teacher->name . '_certification_' . time() . '.' . $certificationFile->getClientOriginalExtension();
             $certificationPath = $certificationFile->storeAs('certifications', $certificationName, 'public');
             
             if ($teacher->certification) {
                 Storage::disk('public')->delete($teacher->certification);
             }
             
             $teacher->certification = $certificationPath;
         }
     
         if ($request->hasFile('identityProof')) {
             $identityProofFile = $request->file('identityProof');
             $identityProofName = $teacher->name . '_identityProof_' . time() . '.' . $identityProofFile->getClientOriginalExtension();
             $identityProofPath = $identityProofFile->storeAs('identity_proofs', $identityProofName, 'public');
     
             if ($teacher->identityProof) {
                 Storage::disk('public')->delete($teacher->identityProof);
             }
     
             $teacher->identityProof = $identityProofPath;
         }
     
         $teacher->save();
     
         return redirect()->route('profile.teacher', ['email' => $user->email])->with('success', 'Profile updated successfully.');
     }
     
     

     public function showModeratorProfile($email)
     {
         $user = User::where('email', $email)->firstOrFail();
         $moderator = Moderator::where('userID', $user->id)->firstOrFail();
     
         return view('profile.moderator', compact('moderator'));
     }
     
     public function updateModeratorPicture(Request $request)
     {
         $request->validate([
             'moderatorPicture' => 'required|file|mimes:jpg,jpeg,png|max:2048',
         ]);
     
         $moderator = Auth::user()->moderator;
     
         if ($moderator) {
             Log::info('Moderator retrieved', ['moderatorID' => $moderator->moderatorID]);
     
             $moderatorPictureFile = $request->file('moderatorPicture');
             $moderatorName = $moderator->name;
             $moderatorNameSlug = Str::slug($moderatorName);
             $timestamp = time();
             $moderatorPictureName = $moderatorNameSlug . '_moderatorPicture_' . $timestamp . '.' . $moderatorPictureFile->getClientOriginalExtension();
             $moderatorPicturePath = $moderatorPictureFile->storeAs('moderator_pictures', $moderatorPictureName, 'public');
     
             Log::info('Profile picture stored', ['path' => $moderatorPicturePath]);
     
             try {
                 if ($moderator->moderatorPicture) {
                     Storage::disk('public')->delete($moderator->moderatorPicture);
                 }
     
                 $moderator->moderatorPicture = $moderatorPicturePath;
                 $moderator->save();
     
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

     public function editModerator($email)
{
    $user = User::where('email', $email)->firstOrFail();
    $moderator = Moderator::where('userID', $user->id)->firstOrFail();

    return view('profile.editModerator', compact('moderator'));
}

public function updateModerator(Request $request, $email)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'contactNumber' => 'nullable|string|max:15',
        'gender' => 'required|string',
        'dateOfBirth' => 'required|date',
    ]);

    $user = User::where('email', $email)->firstOrFail();
    $moderator = Moderator::where('userID', $user->id)->firstOrFail();

    $user->name = $request->input('name');
    $user->contactNumber = $request->input('contactNumber');
    $user->gender = $request->input('gender');
    $user->dateOfBirth = $request->input('dateOfBirth');
    $user->save();

    $moderator->name = $request->input('name');
    $moderator->save();

    return redirect()->route('profile.moderator', ['email' => $user->email])->with('success', 'Profile updated successfully');
}

     
}
