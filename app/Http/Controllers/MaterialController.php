<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Material;
use App\Http\Requests\MaterialRequest;
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
        
        $mat = Material::find($request->id);
        $mat->content = $request->content;
        $mat->save();
        return redirect()->back();
    }
}
