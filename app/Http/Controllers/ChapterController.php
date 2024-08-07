<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chapter;
use Illuminate\Support\Facades\DB;
class ChapterController extends Controller
{
    public function chapterIndex($courseId){  
       // Log the query
    DB::enableQueryLog();
    
    $chapters = Chapter::where('course_id', $courseId)->get();
    
    // Log the executed query
    dd(DB::getQueryLog());
       return response()->json([
          'chapters' => $chapters
       ],200);
    }
}
