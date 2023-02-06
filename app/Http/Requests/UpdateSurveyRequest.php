<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSurveyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // User is authorized or not.
        // La méthode route renvoie les informations associées à la route actuelle dans l'application.
        $survey = $this->route('survey');
        if ($this->user()->id !== $survey->user_id) {
            return false;
        }
        return true;
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
