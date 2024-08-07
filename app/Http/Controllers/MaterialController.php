<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function getMaterial($material_id){
        $material = Material::find($material_id);
        if(!$material){
         return response()->json([
            'message'=>'Course Material Not Found.'
         ],404);
       }
       // Return Json Response
       return response()->json([
          'material' => $material
       ],200);
    }
    public function saveMaterial(MaterialRequest $request){
        
    }
}
