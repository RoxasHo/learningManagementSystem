<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Material;
use App\Http\Requests\MaterialRequest;
use Carbon\Carbon;
class MaterialController extends Controller
{
    public function getMaterialWithChapter($chapter_id){
    //$materialId = Material::select('id')->where('chapter_id', $chapter_id) ->get();
        $material = DB::select('select * from materials where chapter_id=?' ,[$chapter_id]);

        
        return $material;
    }
    public function editMaterial($material_id){
        $material = DB::select('select * from materials where id=?',[$material_id]);
        $material = $material[0];  
       
        return view('material.edit',['material'=>$material]);
    } 
    public function saveMaterial(Request $request){
        try {
            // Fetch the material record by ID
            $mat = Material::find($request->id);
    
            // Check if the material exists
            if (!$mat) {
                return response()->json(['error' => 'Material not found.'], 404);
            }
    
            // Convert 'updated_at' from request to a Carbon instance
            $requestUpdatedAt = $request->last_update_time;
    
            // Check for timestamp conflict
            if ($mat->updated_at->ne($requestUpdatedAt )) {
                return response()->json([
                    'error' => 'Conflict detected. The material has been modified by another user.',
                    'latest' => $mat->content,
                    'updated_at' => $mat->updated_at,
                ], 409);
            }
    
            // Update the material content
            $mat->content = $request->content;
    
            // Save the material and automatically update the 'updated_at' timestamp
            $mat->save();
    
            return response()->json(['success' => 'Material saved successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }
}
