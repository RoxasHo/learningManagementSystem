<?php

namespace App\Http\Controllers;
use App\Models\Material;

use Illuminate\Http\Request;
use App\Http\Requests\MaterialRequest;
class MaterialController extends Controller
{
    
    public static function createMaterial($chapter_id){
        Material::create([
            'chapter_id' => $chapter_id,
            'content' => '',
        ]);
        
        return ;
    }
    
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
    public function getMaterialWithChapter($chapter_id){
        $material = Material::where('chapter_id',$chapter_id)->get();
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
    public function saveMaterial(MaterialRequest $request) {
    try {
        // Attempt to find the material by ID
        $material = Material::find($request->id);

        // Check if the material exists
        if (!$material) {
            return response()->json([
                'message' => 'Material not found!',
            ], 404);
        }

        // Update the content
        $material->content = $request->content;
        $material->save();

        return response()->json([
            'message' => 'Chapter updated successfully!',
        ]);

    } catch (\Exception $e) {
        // Log the error or return a detailed message
        return response()->json([
            'message' => 'An error occurred: ' . $e->getMessage(),
        ], 500);
    }
}
}
