<?php

namespace App\Http\Controllers;

use App\Models\SurveyQuestionAnswer;
use Illuminate\Http\Request;

class SurveyQuestionAnswerController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $answers = SurveyQuestionAnswer::query()
            ->join('survey_answers', 'survey_question_answers.survey_answer_id', '=', 'survey_answers.id')
            ->join('surveys', 'survey_answers.survey_id', '=', 'surveys.id')
            ->where('surveys.user_id', $user->id)
            ->select('survey_question_answers.answer')
            ->get();

        return [
            'answers' => $answers
        ];
    }
}
