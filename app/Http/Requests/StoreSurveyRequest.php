<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * StoreSurveyRequest class definition.
 * 
 * This class defines the validation rules for creating a survey request.
 */
class StoreSurveyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Pre-validation process to merge the user_id into the request.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'user_id' => $this->user()->id
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:1000', // The survey title is required and must be a string with a maximum length of 1000 characters.
            'image' => 'nullable|string', // The image is optional and must be a string if present.
            'user_id' => 'exists:users,id', // The user_id must exist in the 'id' column of the 'users' table.
            'status' => 'required|boolean', // The status is required and must be a boolean value.
            'description' => 'nullable|string', // The description is optional and must be a string if present.
            'expire_date' => 'nullable|date|after:tomorrow', // The expire_date is optional and must be a date after tomorrow if present.
            'questions' => 'array', // The questions must be an array.
        ];
    }
}
