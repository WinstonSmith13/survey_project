<?php

namespace App\Http\Controllers;


use App\Models\Survey;
use App\Models\SurveyQuestionAnswer;
use Illuminate\Http\Request;

class SurveyQuestionAnswerController extends Controller
{
    public function index(Request $request, $id)
    {
        $user = $request->user();

        $answers = SurveyQuestionAnswer::query()
            ->join('survey_answers', 'survey_question_answers.survey_answer_id', '=', 'survey_answers.id')
            ->join('surveys', 'survey_answers.survey_id', '=', 'surveys.id')
            ->select('survey_question_answers.survey_question_id', 'survey_question_answers.answer')
            ->where('surveys.id', $id)
            ->where('surveys.user_id', $user->id)
            ->get();

        //All Surveys

        $allDataSurvey = Survey::query()
            ->select('surveys.*')
            ->where('surveys.id', $id)
            ->where('surveys.user_id', $user->id)
            ->get();

        return [
            'answers' => $answers,
            'allSurveys' => $allDataSurvey
        ];
    }
}
