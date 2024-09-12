<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChapterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if(request()->isMethod('post')) {
            return [
                
                'chapter_name' => 'required|string|',
                'course_id' => 'required|integer',
                'chapter_number' => 'required|integer|',
          
                'chapter_description' => 'string|max:258'
            ];
        } else if(request()->isMethod('put')){ //edit the chapter record
            return [
                
                'chapter_id' => 'required|integer',
                
                'chapter_name' => 'sometimes|string|nullable',
                'course_id' => 'sometimes|integer|nullable',
                'chapter_number' => 'sometimes|integer|nullable',
                'chapter_description' => 'sometimes|string|max:258|nullable'
          
                
            ];
        }
        else{
            return [
                'chapter_id' => 'required|integer',
                'chapter_name' => 'sometimes|string|nullable',
                'course_id' => 'sometimes|integer|nullable',
                'chapter_number' => 'sometimes|integer|nullable',
                'chapter_description' => 'sometimes|string|max:258|nullable'
            ];
        }
    }
}
