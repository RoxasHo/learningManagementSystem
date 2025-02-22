<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class CategoryController extends Controller
{
    public static function getCategory(){
        $category =DB::select('select id,name from Categories');
        
        return $category;
    }
}
