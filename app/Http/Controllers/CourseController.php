<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CategoryController;
use App\Http\Requests\ChapterRequest;
use App\Models\Category;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\Enrollment;
use App\Models\Progress;
use App\Models\QuestionnaireResponse;
use App\Models\Student;
use App\Models\TeacherCourse;
use App\Models\Teacher;
use App\Models\UserCourse;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CourseController extends Controller
{
    public function showHomePage() {

        $userId = auth()->id();
        $enrolledCourses = collect();

        if (auth()->check() && auth()->user()->role === 'Student') {
            if (!auth()->user()->hasCompletedQuestionnaire()) {
                return redirect()->route('questionnaire.show');
            }
        }
        
        if ($userId) {
            $student = Student::where('userID', $userId)->first();
    
            if ($student) {
                $enrolledCourseIds = Enrollment::where('student_id', $student->studentID)->pluck('course_id');
                
                $enrolledCourses = Course::with(['categories', 'teachers'])
                    ->whereIn('id', $enrolledCourseIds)
                    ->where('status', '!=', 'pending') // Exclude pending courses
                    ->where('status', '!=', 'rejected') // Exclude rejected courses
                    ->get();
    
                foreach ($enrolledCourses as $course) {
                    $categoryId = optional($course->categories->first())->id;
                    $category = Category::find($categoryId);
                    
                    $course->backgroundColor = $category->color ?? '#ffffff';
                    $course->enrollmentCount = Enrollment::where('course_id', $course->id)->count() ?: 0;

                    $course->averageRating = Enrollment::where('course_id', $course->id)
                    ->whereNotNull('rating')
                    ->avg('rating');
                }
            }
        }
        
        $trendingCourses = Course::with(['categories', 'teachers'])
            ->select('courses.id', 'courses.course_name', 'courses.created_at', 'courses.status',  DB::raw('COUNT(enrollments.id) as enroll_count'))
            ->leftJoin('enrollments', 'courses.id', '=', 'enrollments.course_id') // Join with enrollments
            ->whereNotIn('courses.id', $enrolledCourseIds ?? []) // Ensure the course is not in enrolled courses
            ->where('enrollments.status', 'enrolled') // Filter by enrollment status
            ->where('enrollments.created_at', '>=', Carbon::now()->subMonth()) // Consider only recent enrollments (last month)
            ->groupBy('courses.id', 'courses.course_name', 'courses.created_at', 'courses.status') // Group by required fields
            ->orderBy('enroll_count', 'DESC') // Order by number of enrollments
            ->take(5)
            ->get();

        foreach ($trendingCourses as $course) {
            $categoryId = optional($course->categories->first())->id; 
            $category = Category::find($categoryId);
            
            $course->backgroundColor = $category->color ?? '#ffffff'; 
            $course->enrollmentCount = Enrollment::where('course_id', $course->id)->count() ?: 0;

            $course->averageRating = Enrollment::where('course_id', $course->id)
            ->whereNotNull('rating')
            ->avg('rating');
        }

        $newlyCreatedCourses = Course::with(['categories', 'teachers'])
            ->whereNotIn('id', $enrolledCourseIds?? [])
            ->where('status', '!=', 'pending') // Exclude pending courses
            ->where('status', '!=', 'rejected') // Exclude rejected courses
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        foreach ($newlyCreatedCourses as $course) {
            $categoryId = optional($course->categories->first())->id; 
            $category = Category::find($categoryId);
            
            $course->backgroundColor = $category->color ?? '#ffffff'; 
            $course->enrollmentCount = Enrollment::where('course_id', $course->id)->count() ?: 0;

            $course->averageRating = Enrollment::where('course_id', $course->id)
            ->whereNotNull('rating')
            ->avg('rating');
        }
        
        $mostRatedCourses = Course::with(['categories', 'teachers'])
        ->select('courses.id', 'courses.course_name', 'courses.created_at', 'courses.status', DB::raw('AVG(enrollments.rating) as average_rating'))
        ->leftJoin('enrollments', 'courses.id', '=', 'enrollments.course_id') // Join with enrollments
        ->where('enrollments.rating', '!=', null) // Ensure ratings are not null
        ->groupBy('courses.id', 'courses.course_name', 'courses.created_at', 'courses.status') // Group by required fields
        ->orderBy('average_rating', 'DESC') // Order by average rating
        ->take(5)
        ->get();

        foreach ($mostRatedCourses as $course) {
            $categoryId = optional($course->categories->first())->id; 
            $category = Category::find($categoryId);
            
            $course->backgroundColor = $category->color ?? '#ffffff'; 
            $course->enrollmentCount = Enrollment::where('course_id', $course->id)->count() ?: 0;

            $course->averageRating = Enrollment::where('course_id', $course->id)
            ->whereNotNull('rating')
            ->avg('rating');
        }

        $userId = auth()->id();
        $suggestedCourses = $this->suggestCourses($userId);
        
        foreach ($suggestedCourses as $course) {
            $categoryId = optional($course->categories->first())->id; 
            $category = Category::find($categoryId);
            
            $course->backgroundColor = $category->color ?? '#ffffff'; 
            $course->enrollmentCount = Enrollment::where('course_id', $course->id)->count() ?: 0;

            $course->averageRating = Enrollment::where('course_id', $course->id)
            ->whereNotNull('rating')
            ->avg('rating');
        }

        \Log::info('Suggested Courses:', $suggestedCourses->toArray());

        return view('welcome', [
            'enrolledCourses' => $enrolledCourses,
            'trendingCourses' => $trendingCourses,
            'newlyCreatedCourses' => $newlyCreatedCourses,
            'mostRatedCourses' => $mostRatedCourses,
            'suggestedCourses' => $suggestedCourses,
        ]);
    }

    public function showCourseList(Request $request) {
        $categories = Category::all();
        $userId = auth()->id();
        $user = auth()->user();
        $student = null;
    
        if(optional(auth()->user())->role === 'Student'){
            $student = Student::where('userID', $userId)->first();
        }
    
        $selectedCategoryId = $request->input('category_id', '');
        $searchQuery = $request->input('search', '');
        $sortBy = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
    
        $query = Course::with(['categories', 'teachers']);
    
        // Filter by category if selected
        if ($selectedCategoryId) {
            $query->whereHas('categories', function ($q) use ($selectedCategoryId) {
                $q->where('categories.id', $selectedCategoryId);
            });
        }
    
        // Search courses by name
        if ($searchQuery) {
            $query->where('course_name', 'LIKE', '%' . $searchQuery . '%');
        }
    
        // Exclude pending and rejected courses
        $query->whereNotIn('status', ['pending', 'rejected']);
    
        // If not sorting by rating, apply the sorting in the database
        if ($sortBy !== 'rating') {
            $query->orderBy($sortBy, $sortDirection);
        }
    
        // Get courses from the database
        $courses = $query->get();
    
        // Process each course for additional data like ratings, background color, enrollment count
        foreach ($courses as $course) {
            $categoryId = optional($course->categories->first())->id; 
            $category = Category::find($categoryId);
    
            // Set background color based on category
            $course->backgroundColor = $category->color ?? '#ffffff';
    
            // Get enrollment count
            $course->enrollmentCount = Enrollment::where('course_id', $course->id)->count();
    
            // Determine if the student is enrolled in the course
            if ($student) {
                $course->isEnrolled = Enrollment::where('student_id', $student->studentID)
                    ->where('course_id', $course->id)
                    ->exists();
            } else {
                $course->isEnrolled = false; 
            }
    
            // Calculate the average rating for the course
            $course->averageRating = Enrollment::where('course_id', $course->id)
                ->whereNotNull('rating')
                ->avg('rating') ?? 0; // If no ratings, default to 0
        }
    
        // Manually sort by rating if specified
        if ($sortBy === 'rating') {
            $courses = $courses->sortBy(function($course) use ($sortDirection) {
                return $sortDirection === 'asc' ? $course->averageRating : -$course->averageRating;
            })->values();
        }
    
        // Return the view with the data
        return view('courses.index', compact('courses', 'categories', 'searchQuery', 'selectedCategoryId', 'sortBy', 'sortDirection'));
    }
    
    
    
    public function suggestCourses($userId)
    {
        // Fetch the student based on the user ID
        $student = Student::where('userID', $userId)->first();

        if (!$student) {
            return collect(); // Return an empty collection if no student record is found
        }

        // Get the IDs of courses the student is enrolled in
        $enrolledCourseIds = Enrollment::where('student_id', $student->studentID)
            ->pluck('course_id')
            ->toArray();

        // Get recently viewed course IDs
        $recentlyViewedCourseIds = UserCourse::where('user_id', $userId)
            ->orderBy('last_accessed', 'desc')
            ->pluck('course_id')
            ->toArray();

        // Get user's questionnaire responses
        $questionnaireResponses = QuestionnaireResponse::where('user_id', $userId)->first();

        // Initialize an empty array for selected categories
        $selectedCategories = [];

        if ($questionnaireResponses) {
            // Extract interested categories from questionnaire responses
            $selectedCategories = json_decode($questionnaireResponses->interested_categories, true) ?? [];
        }

        // Fetch suggested courses based on categories and other criteria
        $suggestedCourses = Course::with(['categories', 'teachers']) // Load categories and teachers relationships
            ->withCount('enrollments') // Count enrollments for each course
            ->whereNotIn('id', array_merge($enrolledCourseIds, $recentlyViewedCourseIds)) // Exclude enrolled and recently viewed courses
            ->where('status', '!=', 'pending') // Exclude pending courses
            ->where('status', '!=', 'rejected') // Exclude rejected courses
            ->when(!empty($selectedCategories), function($query) use ($selectedCategories) {
                // Filter by selected categories from the questionnaire responses
                return $query->whereHas('categories', function($categoryQuery) use ($selectedCategories) {
                    $categoryQuery->whereIn('categories.id', $selectedCategories); // Specify 'categories.id' to avoid ambiguity
                });
            })
            ->orderBy('clicks', 'desc') // Sort by popularity or other criteria
            ->take(5) // Limit to top 5 suggestions
            ->get();

        return $suggestedCourses;
    }

    private function generateCategoryColors($count) {
        $colors = [];
        
        for ($i = 1; $i <= $count; $i++) {
            $color = $this->generateRandomColor();
            $colors[$i] = $color;
        }
    
        return $colors;
    }
    
    private function generateRandomColor() {
        do {
            $red = rand(0, 255);
            $green = rand(0, 255);
            $blue = rand(0, 255);
    
            // Calculate brightness to reject pale colors
            $brightness = ($red * 299 + $green * 587 + $blue * 114) / 1000;
    
            // Calculate a saturation score (the color's distance from gray)
            $saturation = max($red, $green, $blue) - min($red, $green, $blue);
        } while ($brightness > 150 || $saturation < 60); // Reject too bright or pale colors
    
        return sprintf("#%02x%02x%02x", $red, $green, $blue);
    }

    public function show($id)
    {
        // Fetch the course by ID along with its teachers through the pivot table
        $course = Course::with('teachers')->findOrFail($id);
        
        // Get the user ID of the currently authenticated user
        $user_id = auth()->id();
        $student = Student::where('userID', $user_id)->first();

        // Determine if the user has a specific role
        $userRole = optional(auth()->user())->role; // Safely get the current user's role
        $isTeacherOrModerator = in_array($userRole, ['Moderator', 'Teacher', 'Superuser']);

        $enrolled = false; // Default to false

        if ($student) {
            $student_id = $student->studentID;

            // Check if the student is enrolled in the course
            $enrollment = DB::table('enrollments')
                ->where('course_id', $id)
                ->where('student_id', $student_id)
                ->first(); // Get the first result instead of a collection

            // Determine if the student is enrolled
            $enrolled = $enrollment && $enrollment->status === 'enrolled';
        }

        // Get the created_at timestamp and convert it to a Carbon instance
        $createdAt = Carbon::parse($course->created_at);
        $now = Carbon::now();

        // Determine the human-readable creation time
        $createdAtString = $this->formatCreatedAt($createdAt, $now);

        // Calculate the average rating for the course
        $averageRating = Enrollment::where('course_id', $course->id)
            ->whereNotNull('rating')
            ->avg('rating') ?? 0; // Default to 0 if no ratings are available

        // Count the number of enrollments for this course
        $enrollmentCount = Enrollment::where('course_id', $course->id)->count();

        // Return a view to display the course details
        return view('courses.show', [
            'student_id' => isset($student_id) ? $student_id : null, // Pass student_id if exists
            'course' => $course,
            'enrolled' => $enrolled,
            'isTeacherOrModerator' => $isTeacherOrModerator, // Pass role check to the view
            'createdAtString' => $createdAtString, // Pass the formatted creation time
            'enrollmentCount' => $enrollmentCount,
            'averageRating' => $averageRating, // Pass the calculated average rating
        ]);
    }


    public function rateCourse(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $user_id = auth()->id();
        $student = Student::where('userID', $user_id)->first();
        $studentId = $student->studentID;

        // Store the rating in the enrollments table
        DB::table('enrollments')->where('course_id', $request->course_id)
            ->where('student_id', $studentId)
            ->update(['rating' => $request->rating]);

        return response()->json(['success' => true]);
    }


    private function formatCreatedAt($createdAt, $now)
    {
        $diffInSeconds = $createdAt->diffInSeconds($now);

        if ($diffInSeconds < 60) {
            return 'just now';
        } elseif ($diffInSeconds < 3600) {
            $minutes = floor($diffInSeconds / 60);
            return "$minutes minute" . ($minutes > 1 ? 's' : '') . ' ago';
        } elseif ($diffInSeconds < 86400) {
            $hours = floor($diffInSeconds / 3600);
            return "$hours hour" . ($hours > 1 ? 's' : '') . ' ago';
        } elseif ($diffInSeconds < 604800) {
            $days = floor($diffInSeconds / 86400);
            return "$days day" . ($days > 1 ? 's' : '') . ' ago';
        } elseif ($diffInSeconds < 2620800) {
            $weeks = floor($diffInSeconds / 604800);
            return "$weeks week" . ($weeks > 1 ? 's' : '') . ' ago';
        } elseif ($diffInSeconds < 31536000) {
            $months = floor($diffInSeconds / 2620800);
            return "$months month" . ($months > 1 ? 's' : '') . ' ago';
        } else {
            $years = floor($diffInSeconds / 31536000);
            return "$years year" . ($years > 1 ? 's' : '') . ' ago';
        }
    }

    public function enroll($courseId)
    {
        $userId = auth()->id(); // Get current logged-in user
        Enrollment::create([
            'user_id' => $userId,
            'course_id' => $courseId,
        ]);
        // Increment course clicks
        $course = Course::find($courseId);
        $course->increment('clicks');
        return redirect()->back()->with('success', 'Enrolled successfully');
    }
    public function teacherIndex($teacherId){ 
        
        $category= CategoryController::getCategory();
        
        $course = DB::table('courses as C')
        ->join('teacher_courses as TC', 'C.id', '=', 'TC.course_id')
        ->select('C.id','C.course_name', 'C.difficulty', 'C.course_description', 'C.status','TC.role')
        ->where('TC.teacher_id', $teacherId)
        ->get();
        /*
        if(!$course){
         return response()->json([
            'message'=>'Course Not Found.'
         ],404);
       }
         */
       // Return Json Response
       /*
       return response()->json([
          'course' => $course
       ],200);
       */
       return view('courses/teacherIndex',['id'=>$teacherId,'course'=>$course,'category'=>$category]);
    }
    /*
            你说得对，
            但是《原神》是由米哈游自主研发的一款全新开放世界冒险游戏。
            游戏发生在一个被称作「提瓦特」的幻想世界，在这里，
            被神选中的人将被授予「神之眼」，引导元素之力。
            你将扮演一位名为「旅行者」的神秘角色，在自由的旅行中邂逅性格各异、
            能力独特的同伴们，和他们一起击败强敌，找回失散的亲人；
            同时，逐步发掘「原神」的真相

    */
    public function studentIndex($studentId){  
        //$course = Course::findWithTeacherId($studentId);
        if(auth()->user()->role=='Teacher' ){
            return redirect('/');
        }
        $course = DB::table('courses as C')->join ('chapters_ as Ch','Ch.course_id','=','C.id')
                    ->join('progresses as P','P.chapter_id','=','Ch.id')
                    ->select('C.course_name','C.course_detail','')
                    ->where('P.student_id',$studentId)
                    ->get();

        if(!$course){
         return response()->json([
            'message'=>'Course Not Found.'
         ],404);
       }
       // Return Json Response
       /*
       return response()->json([
          'course' => $course
       ],200);
       */
       return view('courses.index',['id'=>$studentId,'course'=>$course]);
    }
    public function viewCourse($course_id){
        $course = DB::table('courses as C')
                    ->select('C.id','C.CourseName','C.Difficulty','C.Description')
                    ->where('C.id',$course_id)->get();
        $course=$course[0];
        
        $rating = DB::table('enrollments')
        ->where('course_id', $course_id)
        ->avg('rating');

        $teachers = DB::table('teachers as T')
                    ->join('teacher_courses as TC','TC.teacher_id','=','T.teacherID')
                    ->select('T.name')
                    ->where('TC.course_id',$course_id)
                    ->get();
        $teachers=$teachers[0];

        
        return view('courses.view',['course'=>$course,'rating'=>$rating,'teachers'=>$teachers,'student_id'=>'1']);
    }
    public function courseTeamIndex($course_id){
        if(auth()->user()->role=='Student' ){
            return redirect('/');
        }
        $team = DB::table('teacher_courses as TC')
        ->join('courses as C', 'C.id', '=', 'TC.course_id')
        ->join('teachers as T','T.teacherId','=','TC.teacher_id')
        ->select('T.name','TC.role')
        ->where('TC.course_id', $course_id)
        ->get();
        
        return view('course-team',['team'=>$team]);
        
    }
    
    public function addCourse(Request $request){
        if(auth()->user()->role=='Student' ){
            return redirect('/');
        }
    try {   
        // Create the course
        $ch = Course::create([
            'course_name' => $request->course_name,
            'difficulty' => $request->difficulty,
            'course_description' => $request->course_description,
            'category_id' => $request->category_id,
            'status' => 'pending',
            'clicks'=>0
        ]);

        if ($request->category_id) {
            $ch->categories()->attach($request->category_id);
        }

        // Prepare data for the teacher_courses table
        $data = [
            'teacher_id' => $request->teacher_id,  // Replace with the actual teacher_id
            'course_id' => $ch->id,   // Replace with the actual course_id
            'role' => 'leader' // Replace with the actual status
        ];
        $request = new ChapterRequest([
            'chapter_name' => 'chapter 1',
            'course_id' => $ch->id,
            'chapter_number' => 1,
            'chapter_description' => 'chapter_1'
        ]);
    
        // Instantiate the target controller
        $chapterController = new ChapterController();
    
        // Call the method from the target controller
        $chapterController->addChapter($request);

        // Insert the data into the teacher_courses table
        DB::table('teacher_courses')->insert($data);

        // Redirect with success message
        return redirect()->back()->with('message', 'Course successfully created.');
        } catch (\Exception $e) {
        // Catch the error and return its message instead of the whole exception
        dump($e);
        //return redirect()->back()->with('error', $e->getMessage());
    }
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

    public function showContent($course_id,$chapter_id,$selectedType)
    {
        $chapters = DB::select('select * from chapters_ where course_id=? order by chapter_number ',[$course_id]);
        $materials = DB::select('select M.id as material_id,M.chapter_id,M.content from materials_ M,chapters_ C where M.chapter_id=C.id and C.course_id=?',[$course_id]);
        $quizzs  =   DB::select('select Q.id as quizz_id,Q.content,Q.chapter_id,Qs.question_number,Qs.statement,Qs.type from quizz_ Q,chapters_ C ,questions Qs where Q.chapter_id=C.id and Qs.quizz_id=Q.id and C.course_id=?',[$course_id]);
        
        if($chapter_id=='null')
        $chapter_id = $chapters[0]->id;
        if($selectedType=='null')
            $selectedType='Material';
            $groupedQuizzes = [];
        
            // Group quizzes by question_number
            foreach ($quizzs as $quiz) {
                $questionNumber = $quiz->question_number;
                if (!isset($groupedQuizzes[$questionNumber])) {
                    $groupedQuizzes[$questionNumber] = [
                        'questions' => [],
                        'options' => [],
                        'answers' => []
                    ];
                }
    
                if ($quiz->type === 'Question') {
                    $groupedQuizzes[$questionNumber]['questions'][] = $quiz;
                } elseif ($quiz->type === 'Option') {
                    $groupedQuizzes[$questionNumber]['options'][] = $quiz;
                } elseif ($quiz->type === 'Answer') {
                    $groupedQuizzes[$questionNumber]['answers'][] = $quiz;
                }
            } 
            $quizz_id = !empty($quizzs) ? $quizzs[0]->quizz_id : null;
        return view('courses.moderator_view',['chapters'=>$chapters,'materials'=>$materials,'quizzs'=>$groupedQuizzes,'course_id'=>$course_id,'chapter_id'=>$chapter_id,'selectedType'=>$selectedType,'content_id'=>'','quizz_id'=>$quizz_id]);

    }


    public function courseIndex($course_id, $chapter_id, $selectedType)
    {
        // Check user role
        if (auth()->user()->role == 'Student') {
            return redirect('/');
        }

        // Retrieve the course and associated teacher
        //$course = Course::with('teacher')->findOrFail($course_id); // Assuming you have a relationship set up
        $course=Course::find($course_id)->first();
        // Get the teacher's information
        $user_id = auth()->id();
        $User = User::find($user_id);
        $teacher = Teacher::where('userID',$User->id)->firstOrFail();
        

        // Retrieve chapters, materials, and quizzes
        $chapters = DB::select('SELECT * FROM chapters_ WHERE course_id = ? ORDER BY chapter_number', [$course_id]);
        $materials = DB::select('SELECT M.id AS material_id, M.chapter_id, M.content FROM materials_ M, chapters_ C WHERE M.chapter_id = C.id AND C.course_id = ?', [$course_id]);
        $quizzs = DB::select('SELECT Qs.question_number, Qs.statement, Qs.type, Q.id AS quizz_id
                            FROM quizz_ Q 
                            JOIN chapters_ C ON Q.chapter_id = C.id 
                            JOIN questions Qs ON Qs.quizz_id = Q.id 
                            WHERE C.course_id = ? AND C.id = ?', [$course_id, $chapter_id]);

        if ($chapter_id == 'null') {
            $chapter_id = $chapters[0]->id ?? null; // Handle case where chapters may be empty
        }

        // Create a quiz if none exists
        if ($quizzs == []) {
            //QuizzController::createQuizz($chapter_id);
            $quizzs = DB::select('SELECT Qs.question_number, Qs.statement, Qs.type, Q.id AS quizz_id
                                FROM quizz_ Q 
                                JOIN chapters_ C ON Q.chapter_id = C.id 
                                JOIN questions Qs ON Qs.quizz_id = Q.id 
                                WHERE C.course_id = ? AND C.id = ?', [$course_id, $chapter_id]);
        }

        $groupedQuizzes = [];
        // Group quizzes by question_number
        foreach ($quizzs as $quiz) {
            $questionNumber = $quiz->question_number;
            if (!isset($groupedQuizzes[$questionNumber])) {
                $groupedQuizzes[$questionNumber] = [
                    'questions' => [],
                    'options' => [],
                    'answers' => []
                ];
            }

            if ($quiz->type === 'Question') {
                $groupedQuizzes[$questionNumber]['questions'][] = $quiz;
            } elseif ($quiz->type === 'Option') {
                $groupedQuizzes[$questionNumber]['options'][] = $quiz;
            } elseif ($quiz->type === 'Answer') {
                $groupedQuizzes[$questionNumber]['answers'][] = $quiz;
            }
        }

        // Set chapter_id if it's 'null'
        if ($chapter_id == 'null') {
            $chapter_id = $chapters[0]->id ?? null; // Handle case where chapters may be empty
        }

        // Get the first quiz ID (if available)
        $quizz_id = $quizzs[0]->quizz_id ?? null;

        // Pass all necessary data to the view
        return view('courses.teacherContent', [
            'chapters' => $chapters,
            'materials' => $materials,
            'quizzs' => $groupedQuizzes,
            'course_id' => $course_id,
            'chapter_id' => $chapter_id,
            'selectedType' => $selectedType,
            'content_id' => '',
            'quizz_id' => $quizz_id,
            'teacher' => $teacher // Pass the teacher variable to the view
        ]);
    }

    
    public function enrollCourse(Request $request){
        if(auth()->user()->role=='Teacher' ){
            return redirect('/');
        }
        $user_id = auth()->id();
        $student = Student::where('userID',$user_id)->first();
        
        if (!$student) {
            return redirect()->back()->with('error', 'Student not found.');
        }
    
        $student_id = $student->studentID;
        $course_id = $request->course_id;

        $enrollment = Enrollment::where('course_id', $course_id)
        ->where('student_id', $student_id)
        ->first();

        if (!$enrollment) {
            Enrollment::create([
                'student_id' => $student_id,
                'course_id' => $course_id,
                'status' => 'Enrolled',
            ]);

            $course = Course::findOrFail($course_id);
            $course->increment('clicks');

            $chapters = DB::table('chapters_')->where('course_id', $course_id)->get();

            foreach ($chapters as $chapter) {
                Progress::create([
                    'chapter_id' => $chapter->id,
                    'student_id' => $student_id,
                    'status' => ($chapter->chapter_number <= 3) ? 'Uncomplete' : 'Locked',
                ]);
            }

            return redirect()->route('course-study', [
                'student_id' => $student_id,
                'course_id' => $course_id,
                'chapter_id' => 'null',
                'selectedType' => 'Material',
            ]);
        }

        return redirect()->back()->with('message', 'You are already enrolled in this course.');
    }
    
    // public function courseStudy($student_id,$course_id,$chapter_id,$selectedType){
        
    //     $chapters = DB::select('select distinct Ch.id,Ch.chapter_number,Ch.chapter_name,P.status from chapters_ Ch,progresses P where Ch.id=P.chapter_id and course_id=? order by chapter_number',[$course_id]);

    //     $materials = DB::select('select M.id as material_id,M.chapter_id,M.content from materials_ M,chapters_ C where M.chapter_id=C.id and C.course_id=?',[$course_id]);
    //     $quizzs  =   DB::select('select * from quizz_ Q,chapters_ C ,questions Qs where Q.chapter_id=C.id and Qs.quizz_id=Q.id and C.course_id=?',[$course_id]);
    //     $prog = DB::select('select * from progresses P where P.student_id=? and P.chapter_id=?',[$student_id,$chapter_id]);
        
        
    //     $groupedQuizzes = [];
    //     $quizz_id = $quizzs[0]->quizz_id;
    //     // Group quizzes by question_number
    //     foreach ($quizzs as $quiz) {
    //         $questionNumber = $quiz->question_number;
    //         if (!isset($groupedQuizzes[$questionNumber])) {
    //             $groupedQuizzes[$questionNumber] = [
    //                 'questions' => [],
    //                 'options' => [],
    //                 'answers' => []
    //             ];
    //         }

    //         if ($quiz->type === 'Question') {
    //             $groupedQuizzes[$questionNumber]['questions'][] = $quiz;
    //         } elseif ($quiz->type === 'Option') {
    //             $groupedQuizzes[$questionNumber]['options'][] = $quiz;
    //         } elseif ($quiz->type === 'Answer') {
    //             $groupedQuizzes[$questionNumber]['answers'][] = $quiz;
    //         }
    //     }

        
        
        
    //     if($chapter_id=='null')
    //     $chapter_id = $chapters[0]->id;
        
    //     return view('courses.content',['chapters'=>$chapters,'materials'=>$materials,'quizzs'=>$groupedQuizzes,'course_id'=>$course_id,'chapter_id'=>$chapter_id,'student_id'=>$student_id,'selectedType'=>$selectedType,'content_id'=>'','prog'=>$prog,'quizz_id'=>$quizz_id]);
       
    // }

    public function courseStudy($student_id, $course_id, $chapter_id, $selectedType){
        if(auth()->user()->role=='Teacher' ){
            return redirect('/');
        }
        // Fetch the course by ID along with its teachers through the pivot table
        $course = Course::with('teachers')->findOrFail($course_id);
        
        // Get the user ID of the currently authenticated user
        $user_id = auth()->id();

        $student_id = Student::where('userID', $user_id)->first();

        // Initialize enrollment status
        $enrolled = false;
        if ($student_id) {
            $student_id = $student_id->studentID;

            // Check if the student is enrolled in the course
            $enrollment = DB::table('enrollments')
                ->where('course_id', $course_id)
                ->where('student_id', $student_id)
                ->first();

            // Determine if the student is enrolled
            $enrolled = $enrollment && $enrollment->status === 'enrolled';
        }

        // Get created_at timestamp and convert it to a Carbon instance
        $createdAt = Carbon::parse($course->created_at);
        $now = Carbon::now();

        // Determine the human-readable creation time
        $createdAtString = $this->formatCreatedAt($createdAt, $now);

        // Count the number of enrollments for this course
        $enrollmentCount = Enrollment::where('course_id', $course->id)->count();

        // Fetch chapters
        $chapters = DB::select('SELECT Ch.id, Ch.chapter_number, Ch.chapter_name, P.status 
                                FROM chapters_ Ch 
                                LEFT JOIN progresses P ON Ch.id = P.chapter_id 
                                LEFT JOIN students S on S.studentID = P.student_id
                                WHERE Ch.course_id = ? and P.student_id=? 
                                ORDER BY chapter_number', [$course_id,$student_id]);

        // Fetch materials
        $materials = DB::select('SELECT M.id AS material_id, M.chapter_id, M.content 
                                FROM materials_ M 
                                JOIN chapters_ C ON M.chapter_id = C.id 
                                WHERE C.course_id = ?', [$course_id]);

        // Fetch quizzes
        $quizzs = DB::select('SELECT * 
                            FROM quizz_ Q 
                            JOIN chapters_ C ON Q.chapter_id = C.id 
                            JOIN questions Qs ON Qs.quizz_id = Q.id 
                            WHERE C.course_id = ? and C.id=?', [$course_id,$chapter_id]);

        // Fetch progress
        $prog = DB::select('SELECT * FROM progresses P WHERE P.student_id = ? AND P.chapter_id = ?', [$student_id, $chapter_id]);
        
        // Initialize grouped quizzes
        $groupedQuizzes = [];
        
        // Group quizzes by question_number
        foreach ($quizzs as $quiz) {
            $questionNumber = $quiz->question_number;
            if (!isset($groupedQuizzes[$questionNumber])) {
                $groupedQuizzes[$questionNumber] = [
                    'questions' => [],
                    'options' => [],
                    'answers' => []
                ];
            }

            if ($quiz->type === 'Question') {
                $groupedQuizzes[$questionNumber]['questions'][] = $quiz;
            } elseif ($quiz->type === 'Option') {
                $groupedQuizzes[$questionNumber]['options'][] = $quiz;
            } elseif ($quiz->type === 'Answer') {
                $groupedQuizzes[$questionNumber]['answers'][] = $quiz;
            }
        }

        // Set chapter_id if it's 'null'
        if ($chapter_id == 'null') {
            $chapter_id = $chapters[0]->id ?? null; // Handle case where chapters may be empty
        }

        // Get the first quiz ID (if available)
        $quizz_id = !empty($quizzs) ? $quizzs[0]->quizz_id : null;
        
        return view('courses.content', [
            'chapters' => $chapters,
            'materials' => $materials,
            'quizzs' => $groupedQuizzes,
            'course_id' => $course_id,
            'chapter_id' => $chapter_id,
            'student_id' => $student_id,
            'selectedType' => $selectedType,
            'content_id' => '',
            'prog' => $prog,
            'quizz_id' => $quizz_id,
            'course' => $course,
            'enrolled' => $enrolled,
            'createdAtString' => $createdAtString,
            'enrollmentCount' => $enrollmentCount,
        ]);
    }
    public function getCourseRating($course_id){
        $averageRating = CourseEnrollment::where('course_id', $course_id)->avg('rating');
        return $averageRating;
    }
    public function updateCourseRating(Request $request){
        $course_id = $request->course_id; // Replace with the actual course_id
        $student_id = $request->student_id; // Replace with the actual student_id
        $newRating = $request->rating; // Replace with the new rating value
        
        CourseEnrollment::where('course_id', $course_id)
            ->where('student_id', $student_id)
            ->update(['rating' => $newRating]);
    }
    public function getCourseCompletion($course_id){
       
        //$course_id = $request->course_id;
        // Get the total number of chapters in the course
        $totalChapters = Chapter::where('id', $course_id)->count();

        // Get the total number of enrolled students in the course
        $totalEnrolledStudents = CourseEnrollment::where('course_id', $course_id)->count();

        // Get the number of students who have completed all chapters in the course
        $studentsCompleted = Progress::select('student_id')
            ->join('chapters', 'progresses.chapter_id', '=', 'chapters.id')
            ->where('chapters.course_id', $course_id)
            ->where('progresses.status', 'Complete')
            ->groupBy('progresses.student_id')
            ->havingRaw('COUNT(DISTINCT progresses.chapter_id) = ?', [$totalChapters])
            ->count();

        // Calculate the percentage of completion
        $percentageOfCompletion = $totalEnrolledStudents > 0 ? ($studentsCompleted / $totalEnrolledStudents) * 100 : 0;

        return $percentageOfCompletion;

    }
    public static function getStudentProgress($course_id,$student_id){
        // Get the total number of chapters in the course
        $totalChapters = Chapter::where('course_id', $course_id)->count();

        // Get the number of chapters completed by the student in the course
        $completedChapters = Progress::join('chapters_', 'progresses.chapter_id', '=', 'chapter_id')
            ->where('chapters_.course_id', $course_id)
            ->where('progresses.student_id', $student_id)
            ->where('progresses.status', 'Complete') // Assuming 'completed' is the status for completion
            ->count();

        // Calculate the percentage of completion for the student
        $percentageOfCompletion = $totalChapters > 0 ? ($completedChapters / $totalChapters) * 100 : 0;
        return $percentageOfCompletion;
    }
    public static function getTeacherForCourse($course_id){

        $arr=DB::table('teachers as T')
            ->join('teacher_courses as TC','TC.teacher_id','T.id')
            ->select('T.id','T.name','T.teacherPicture')
            ->where('TC.course_id',$course_id)->get()->toArray();
        return $arr;    
    }
    // public static function getStudentAllProgress($student_id){
    //     $categoryColors = [
    //         1 => '#b71c1c',
    //         2 => '#880e4f',
    //         3 => '#4a148c',
    //         4 => '#311b92',
    //         5 => '#1a237e',
    //         6 => '#0d47a1',
    //         7 => '#01579b',
    //         8 => '#006064',
    //         9 => '#004d40',
    //         10 => '#1b5e20',
    //         11 => '#33691e',
    //         12 => '#827717',
    //     ];

    //     $categories = Category::all();

    //     $CourseProgressArray=DB::table('students as S')
    //     ->join('progresses as P','P.student_id','=','S.studentID')
    //     ->join('enrollments as E','E.student_id','=','S.studentID')
    //     ->join('courses as C','C.id','=','E.course_id')
    //     ->join('course_category as CC','CC.course_id','=','C.id')
    //     ->join('categories as CA','CA.id','=','CC.category_id')
    //     ->select('C.id','C.course_name','CA.name','CA.id as category_id','CA.image_url')
    //     ->where('S.studentID',$student_id)
    //     ->groupby('C.id','C.course_name','category_id','CA.name','CA.image_url')
    //     ->get()
    //     ->toArray();
        
    //     foreach ($CourseProgressArray as $course) {
    //         $course->progress = CourseController::getStudentProgress($course->id, $student_id);     
    //         $course->teachers= CourseController::getTeacherForCourse($course->id);
    //         $categoryId = $course->category_id;
    //         $course->backgroundColor = $categoryColors[$categoryId] ?? '#ffffff'; // Default to white if not found
    //         $course->categories = $categoryId;
    //     }
        
    //     return $CourseProgressArray;
    // }

    public static function getStudentAllProgress($studentId)
    {
        $progressData = Progress::where('student_id', $studentId)
        ->with('course') // Eager load the associated course
        ->get();

    $coursesWithProgress = [];

    //dd($progressData);

    foreach ($progressData as $progress) {
        Log::info('Progress entry:', ['progress' => $progress]);
        $course = $progress->course;

        // Check if the course is null
        if ($course) {
            // Assuming your chapters_ table has a foreign key 'course_id'
            $totalChapters = $course->chapters_()->count(); // Total chapters in the course
            $completedChapters = $progress->status === 'Complete' ? 1 : 0; // You may need to modify this depending on your data structure

            // Calculate completion percentage
            $completionPercentage = $totalChapters > 0 ? ($completedChapters / $totalChapters) * 100 : 0;
            Log::info($completedChapters . $totalChapters);
            $coursesWithProgress[] = [
                'course' => $course,
                'completion_percentage' => round($completionPercentage, 2) // Round to 2 decimal places
            ];
        } else {
            // Optionally handle cases where the course is not found
            Log::warning('Course not found for progress ID: ' . $progress->id);
        }
    }

        return $coursesWithProgress;
    }
    public function getTeacherTeam($course_id){
        $team= TeacherCourse::join('teachers as T', 'T.teacherID', '=', 'teacher_courses.teacher_id')
        ->select('T.teacherID', 'T.name as teacher_name')
        ->where('teacher_courses.course_id', $course_id)
        ->get();

        return $team;
    }
    public function addTeacher(Request $request){
        if(auth()->user()->role=='Student' ){
            return redirect('/');
        }
        $course_id = $request->course_id;
        $teacher_id = $request->teacher_id;
        TeacherCourse::create([
            'course_id'=>$course_id,
            'teacher_id'=>$teacher_id,
            'status'=>'member'
        ]);
        return redirect()->back();
    }
    public function removeTeacher(Request $request){
        if(auth()->user()->role=='Student' ){
            return redirect('/');
        }
        $course_id = $request->course_id;
        $teacher_id = $request->teacher_id;
        $tc=TeacherCourse::where('course_id',$course_id);
    }
    
    
}
