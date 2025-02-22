<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
{
    public function rules()
{
    return [
        'course_name' => 'required|string|max:255',
        'category' => 'required|string|max:255',
        'course_description' => 'required|string',
        'difficulty' => 'required|integer|min:0|max:10',
        'rating' => 'required|numeric|min:0|max:5',
        'teacher_name' => 'required|string|max:255',
        'clicks' => 'required|integer|min:0',
    ];
}
}
