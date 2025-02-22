<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\UserRejectionEmail;
use App\Mail\UserStatusUpdated;
use App\Models\Moderator;
use Illuminate\Support\Facades\Auth;
use App\Models\Teacher;

class SuperuserController extends Controller
{
    // Check if the user is a superuser
    protected function checkSuperuser()
    {
        if (!Auth::check() || Auth::user()->role !== 'Superuser') {
            Log::warning('Unauthorized access attempt detected.');
            return redirect()->route('login')->with('error', 'You are not authorized to access this page.');
        }
    }

    // Display the superuser dashboard
    public function dashboard()
    {
        $this->checkSuperuser();
        Log::info('Accessed Superuser dashboard.');
        return view('superuser.dashboard');
    }

    // Display all students with pagination
    public function showStudents()
    {
        $this->checkSuperuser();
        Log::info('Fetching all students.');
        $students = User::where('role', 'Student')->paginate(5); // Paginate with 10 students per page
        return view('superuser.students', compact('students'))->with('status_type', 'all');
    }

    // Display active students with pagination
    public function showActiveStudents()
    {
        $this->checkSuperuser();
        Log::info('Fetching active students.');
        $students = User::where('role', 'Student')->where('status', 'active')->paginate(5);
        return view('superuser.students', compact('students'))->with('status_type', 'active');
    }

    // Display rejected students with pagination
    public function showRejectedStudents()
    {
        $this->checkSuperuser();
        Log::info('Fetching rejected students.');
        $students = User::where('role', 'Student')->where('status', 'rejected')->paginate(5);
        return view('superuser.students', compact('students'))->with('status_type', 'rejected');
    }

    // Display pending teachers with pagination
    public function showPendingTeachers()
    {
        $this->checkSuperuser();
        Log::info('Fetching pending teachers.');
        $teachers = User::where('role', 'Teacher')->where('status', 'pending')->paginate(5);
        return view('superuser.teachers', compact('teachers'))->with('status_type', 'pending');
    }

    // Display rejected teachers with pagination
    public function showRejectedTeachers()
    {
        $this->checkSuperuser();
        Log::info('Fetching rejected teachers.');
        $teachers = User::where('role', 'Teacher')->where('status', 'rejected')->paginate(5);
        return view('superuser.teachers', compact('teachers'))->with('status_type', 'rejected');
    }

    // Display active teachers with pagination
    public function showActiveTeachers()
    {
        $this->checkSuperuser();
        Log::info('Fetching active teachers.');
        $teachers = User::where('role', 'Teacher')->where('status', 'active')->paginate(5);
        return view('superuser.teachers', compact('teachers'))->with('status_type', 'active');
    }

    // Display pending moderators with pagination
    public function showPendingModerators()
    {
        $this->checkSuperuser();
        Log::info('Fetching pending moderators.');
        $moderators = User::where('role', 'Moderator')->where('status', 'pending')->paginate(5);
        return view('superuser.moderators', compact('moderators'))->with('status_type', 'pending');
    }

    // Display rejected moderators with pagination
    public function showRejectedModerators()
    {
        $this->checkSuperuser();
        Log::info('Fetching rejected moderators.');
        $moderators = User::where('role', 'Moderator')->where('status', 'rejected')->paginate(5);
        return view('superuser.moderators', compact('moderators'))->with('status_type', 'rejected');
    }

    // Display active moderators with pagination
    public function showActiveModerators()
    {
        $this->checkSuperuser();
        Log::info('Fetching active moderators.');
        $moderators = User::where('role', 'Moderator')->where('status', 'active')->paginate(2);
        return view('superuser.moderators', compact('moderators'))->with('status_type', 'active');
    }

    // Method to update user status
    public function updateUserStatus(Request $request)
    {   dump($request);
        $id= $request->id;
        $status = $request->status;
        $this->checkSuperuser();
        Log::info("Updating user with ID: $id to status: $status");

        $user = User::findOrFail($id);
        dump($user);
        $user->status = $status;
        $user->save();
        if ($status === 'active') {
            $user->rejection_reason = null; // 清空拒绝理由
        }
        $user->status="active";
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
    public function rejectUser(Request $request, $id)
    {
        $this->checkSuperuser();
        Log::info('Rejecting user with ID: ' . $id);

        $user = User::findOrFail($id);
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

    // Fetch detailed moderator information (for AJAX request)
    public function getModeratorInfo($id)
    {
        $user = User::where('role', 'Moderator')->findOrFail($id);
        $moderator = Moderator::where('userID', $user->id)->firstOrFail();
        
        return response()->json([
            'name' => $user->name,

            'email' => $user->email,
            'gender' => $user->gender,
            'dateOfBirth' => $user->dateOfBirth,
            'contactNumber' => $user->contactNumber,
            'status' => $user->status,
            'certification' => $moderator->certification,
            'identityProof' => $moderator->identityProof,
            'moderatorPicture' =>  $moderator->moderatorPicture,

        ]);
    }
    
    
}
