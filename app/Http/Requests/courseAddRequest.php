<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class courseAddRequest extends FormRequest
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
        return [
            'CourseName' => 'required|string|max:255',
            'Difficulty' => 'required|string',
            'Description' => 'required|string|max:248',
        ];
    }
}
