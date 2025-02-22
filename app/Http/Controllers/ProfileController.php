<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Moderator;
use App\Models\Progress;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserRejectionEmail;
use App\Mail\UserStatusUpdated;



class ProfileController extends Controller
{
    public function showStudentProfile($email)
    {
        $currentUser = Auth::user();
        $user = User::where('email', $email)->firstOrFail();
        $student = Student::where('userID', $user->id)->firstOrFail();

        // Check if the user has permission to view the profile
        if ($currentUser->role !== 'Student') {
            return redirect('/home')->withErrors('You do not have permission to access this page.');
        }

        // Check if the user is viewing their own profile
        if ($currentUser->email !== $email) {
            return redirect('/home')->withErrors('You can only view your own profile.');
        }

        // Fetch enrolled courses along with their categories and teachers
        $enrolledCourses = Enrollment::with(['course', 'course.teachers', 'course.categories'])
            ->where('student_id', $student->studentID)
            ->get();

        // Map through the enrolled courses to get their progress and additional details
        $coursesWithProgress = $enrolledCourses->map(function ($enrollment) use ($student) {
            $course = $enrollment->course;

            // Get the first category ID and set background color
            $categoryId = optional($course->categories->first())->id; 
            $category = Category::find($categoryId);
            
            // Assign the background color from the category attribute
            $course->backgroundColor = $category->color ?? '#ffffff'; // Default to white if not found

            // Get all chapters for the course
            $totalChapters = Chapter::where('course_id', $course->id)->count();

            // Get completed chapters from the progresses table
            $completedChapters = Progress::whereIn('chapter_id', function ($query) use ($course) {
                $query->select('id')
                    ->from('chapters_')
                    ->where('course_id', $course->id);
            })
            ->where('student_id', $student->studentID)
            ->where('status', 'Complete')
            ->count();

            // Calculate completion percentage
            $completionPercentage = ($totalChapters > 0) ? ($completedChapters / $totalChapters) * 100 : 0;

            // Add the completion percentage and other course details to the result
            return [
                'course' => $course,
                'completion_percentage' => round($completionPercentage, 2), // rounded to 2 decimal places
            ];
        });

        // Return the view with the calculated data
        return view('profile.student', compact('student', 'coursesWithProgress'));
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
    
        // Log the teacher's profile picture path
        Log::info('Teacher Picture Path in Controller:', ['teacherPicture' => $teacher->teacherPicture]);
    
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
        //dump($email);
        // Check if user is a Moderator
        if ($currentUser->role !== 'Moderator') {
            return redirect('/')->withErrors('You do not have permission to access this page.');
        }

        // Ensure the moderator is viewing their own profile
        if ($currentUser->email !== $email) {
            return redirect('/')->withErrors('You can only view your own profile.');
        }

        // Fetch the current user and moderator
        $user = User::where('email', $email)->firstOrFail();
        $moderator = Moderator::where('userID', $user->id)->firstOrFail();

        // Fetch courses with pending status
        $pendingCourses = DB::table('courses')
            ->join('teacher_courses', 'courses.id', '=', 'teacher_courses.course_id')
            ->join('teachers', 'teacher_courses.teacher_id', '=', 'teachers.id')
            ->select('courses.id', 'courses.course_name as course_name', 'teachers.name as teacher_name', 'courses.status')
            ->where('courses.status', '=', 'pending')
            ->get();

        // Pass the courses to the moderator view
        return view('profile.moderator', compact('moderator', 'pendingCourses'));
    }


    public function approve($id)
    {
        // Find the course by ID and update the status to 'active'
        $course = Course::findOrFail($id);
        $course->status = 'active';
        $course->save();

        // Redirect back to the moderator profile with a success message
        return redirect()->back()->with('success', 'Course approved successfully.');
    }

    public function reject($id)
    {
        // Find the course by ID and update the status to 'rejected'
        $course = Course::findOrFail($id);
        $course->status = 'rejected';
        $course->save();

        // Redirect back to the moderator profile with a success message
        return redirect()->back()->with('success', 'Course rejected successfully.');
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
    public function getAllTeachers(){
        $teachers = Teacher::all();
        return $teachers;
    }


    protected function checkModerator()
    {
        if (!Auth::check() || Auth::user()->role !== 'Moderator') {
            Log::warning('Unauthorized access attempt detected.');
            return redirect()->route('login')->with('error', 'You are not authorized to access this page.');
        }
    }

    public function showStudents($email)
    {
        //dump('now at showStudents function');
        //dump($email);
        
        $this->checkModerator();
        Log::info('Fetching all students.');
        $moderatorEmail = Auth::user()->email;

        $students = User::where('role', 'Student')->paginate(5); // Paginate with 10 students per page
        //dump($students);
        return view('profile.students', compact('students','email'))->with('status_type', 'all');
    }

    public function showActiveStudents($email)
    {
        $this->checkModerator();
        Log::info('Fetching active students.');
        $moderatorEmail = Auth::user()->email;

        $students = User::where('role', 'Student')->where('status', 'active')->paginate(5);
        return view('profile.students', compact('students','email'))->with('status_type', 'active');
    }

    public function showRejectedStudents($email)
    {
        $this->checkModerator();
        Log::info('Fetching rejected students.');
        $moderatorEmail = Auth::user()->email;

        $students = User::where('role', 'Student')->where('status', 'rejected')->paginate(5);
        return view('profile.students', compact('students','email'))->with('status_type', 'rejected');
    }

    public function showRejectedTeachers()
    {
        $this->checkModerator();
        Log::info('Fetching rejected teachers.');
        $email = Auth::user()->email;

        $teachers = User::where('role', 'Teacher')->where('status', 'rejected')->paginate(5);
        return view('profile.teachers', compact('teachers','email'))->with('status_type', 'rejected');
    }

    public function showActiveTeachers()
    {
        $this->checkModerator();
        Log::info('Fetching active teachers.');
        $email = Auth::user()->email;

        $teachers = User::where('role', 'Teacher')->where('status', 'active')->paginate(5);
        return view('profile.teachers', compact('teachers','email'))->with('status_type', 'active');
    }

    public function updateUserStatus(Request $request)
    {
        
        $this->checkModerator();
        
        $id = $request->id;
        $status= $request->status;
        Log::info("Updating user with id: $id to status: $status");
        
        $user = User::find($id);
        $user->status = $status;
        dump($user);
        $user->save();
        if ($status === 'active') {
            $user->rejection_reason = null; // 清空拒绝理由
        }
        $user->save();

        try {
            Mail::to($user->email)->send(new UserStatusUpdated($user, 'Active'));
            Log::info('Status update email sent to: ' . $user->email);
        } catch (\Exception $e) {
            Log::error('Failed to send email: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'User status updated successfully.');
        
        }

    // Method to reject a user
    public function rejectUser(Request $request)
    {
        $this->checkModerator();
        $email=$request->user_id;
        Log::info('Rejecting user with email: ' . $email);

        $user = User::where('email',$email)->first();
        //dump($user);
        $user->status = 'rejected';
        $user->rejection_reason = $request->rejection_reason;
        $user->save();

        try {
            Mail::to($user->email)->send(new UserRejectionEmail($user, $request->rejection_reason));
            Log::info('Rejection email sent to: ' . $user->email);
        } catch (\Exception $e) {
            Log::error('Failed to send rejection email: ' . $e->getMessage());
        }

        return redirect()->back()->with('error', 'User rejected successfully.');
    }

    // Fetch detailed student information (for AJAX request)
    public function getStudentInfo($id)
    {
        $student = User::where('role', 'Student')->findOrFail($id);
        return response()->json([
            'email' => $student->email,
            'name' => $student->name,
            'gender' => $student->gender,
            'dateOfBirth' => $student->dateOfBirth,
            'contactNumber' => $student->contactNumber,
            'status' => $student->status,
        ]);
    }


    public function getTeacherInfo($id)
    {
        // Fetch the user with the given ID and role 'Teacher'
        $user = User::where('role', 'Teacher')->findOrFail($id);
        
        // Fetch the associated teacher details
        $teacher = Teacher::where('userID', $user->id)->firstOrFail();
        
        // Return the combined data
        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
            'gender' => $user->gender,
            'dateOfBirth' => $user->dateOfBirth,
            'contactNumber' => $user->contactNumber,
            'yearsOfExperience' => $teacher->yearsOfExperience,
            'certification' => $teacher->certification,
            'identityProof' => $teacher->identityProof,
            'teacherPicture' =>  $teacher->teacherPicture,
        ]);
    }
}
