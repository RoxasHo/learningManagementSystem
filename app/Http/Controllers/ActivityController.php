<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\UserCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ActivityController extends Controller
{
    public function suggestCourses($userId)
    {
        // Fetch enrolled courses
        $enrolledCourses = Enrollment::where('user_id', $userId)->pluck('course_id')->toArray();

        // Fetch recently viewed courses
        $recentlyViewedCourses = UserCourse::where('user_id', $userId)
            ->orderBy('last_accessed', 'desc')
            ->pluck('course_id')
            ->toArray();

        // Combine course IDs
        $courseIds = array_merge($enrolledCourses, $recentlyViewedCourses);
        $courseIds = array_unique($courseIds); // Ensure unique values

        // Fetch categories of enrolled courses
        $categories = Course::whereIn('id', $enrolledCourses)
            ->pluck('category_id')
            ->toArray();

        // Fetch suggested courses from these categories excluding already enrolled courses
        $suggestedCourses = Course::whereNotIn('id', $courseIds)
            ->whereIn('category_id', $categories)
            ->orderBy('clicks', 'desc') // Sort by popularity or other criteria
            ->take(5) // Limit to top 5 suggestions
            ->get();

        return $suggestedCourses;
    }

    public function trackActivity($courseId, $action)
    {
        $userId = auth()->id(); // Get current logged-in user
        Activity::create([
            'user_id' => $userId,
            'course_id' => $courseId,
            'action' => $action,
        ]);
    }
    
}
