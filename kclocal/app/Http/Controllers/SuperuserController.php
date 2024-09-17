<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

use App\Models\Moderator;
use App\Models\User;
use App\Mail\ModeratorWelcomeEmail;
use App\Mail\ModeratorRejectionEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SuperuserController extends Controller
{
    public function showModeratorApprovalPage()
    {
        Log::info('Fetching all pending moderators.');
        
        $pendingModerators = Moderator::where('status', 'pending')->get();

        if ($pendingModerators->isEmpty()) {
            Log::info('No pending moderators found.');
        } else {
            Log::info('Pending moderators found: ' . $pendingModerators->count());
        }

        return view('superuser.moderatorApproval', compact('pendingModerators'));
    }

    public function approveModeratorByToken($token)
    {
        $moderator = Moderator::where('approval_token', $token)->first();

        if (!$moderator) {
            return redirect()->route('login')->with('error', 'Invalid or expired token.');
        }

        $moderator->status = 'approved';
        $moderator->approval_token = null;
        $moderator->save();

        // Notify moderator of approval
        $superuser = User::where('role', 'superuser')->first();
        Mail::to($moderator->user->email)->send(new ModeratorWelcomeEmail($moderator, $superuser));

        return redirect()->route('login')->with('success', 'Moderator has been approved successfully.');
    }

    public function rejectModeratorByToken($token)
    {
        $moderator = Moderator::where('approval_token', $token)->firstOrFail();
        $moderator->status = 'rejected';
        $moderator->rejection_reason = 'Your application does not meet our current requirements.';
        $moderator->save();
    
        Mail::to($moderator->user->email)->send(new ModeratorRejectionEmail($moderator, $moderator->rejection_reason));
    
        return redirect()->route('login')->with('error', 'Your moderator application was rejected.');
    }
    

    public function approveModerator($id)
    {
        Log::info('Generated CSRF Token: ' . csrf_token());
        Log::info('Approve Moderator: Starting process for Moderator ID: ' . $id);

        $moderator = Moderator::findOrFail($id);
        $moderator->status = 'approved';
        
        // Generate Referral Code
        $moderator->save();
    
        // Notify moderator of approval
        $superuser = User::where('role', 'superuser')->first();
        Mail::to($moderator->user->email)->send(new ModeratorWelcomeEmail($moderator, $superuser));
    
        return redirect()->back()->with('success', 'Moderator approved successfully.');
    }

    public function rejectModerator(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:255',
        ]);

        Log::info('Generated CSRF Token: ' . csrf_token());
        Log::info('Reject Moderator: Starting process for Moderator ID: ' . $id);

        $moderator = Moderator::findOrFail($id);
        $moderator->status = 'rejected';
        $moderator->rejection_reason = $request->input('rejection_reason');
        $moderator->save();

        // Notify moderator of rejection
        Mail::to($moderator->user->email)->send(new ModeratorRejectionEmail($moderator));

        return redirect()->back()->with('success', 'Moderator rejected successfully.');
    }
}
