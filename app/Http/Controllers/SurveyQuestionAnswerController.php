<?php

namespace App\Http\Controllers;

use App\Http\Resources\SurveyAnswerResource;
use App\Models\SurveyAnswer;
use App\Models\SurveyQuestionAnswer;
use Illuminate\Http\Request;

class SurveyQuestionAnswerController extends Controller
{
    public function index($id)
    {
        return SurveyQuestionAnswer::query()
            ->join('survey_answers', 'survey_question_answers.survey_answer_id', '=', 'survey_answers.id')
            ->join('surveys', 'survey_answers.survey_id', '=', 'surveys.id')
            ->select('survey_question_answers.answer')
            ->where('surveys.id', $id)
            ->get()
        ;
    }
}
