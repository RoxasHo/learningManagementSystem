<?php

namespace App\Http\Controllers;
use App\Models\Quizz;
use Illuminate\Http\Request;

class QuizzController extends Controller
{
   public static  createMaterial($chapter_id){
        Quizz::create([
            'chapter_id' => $chapter_id,
            'content' => '',
        ]);
        
        return ;
    }
}
