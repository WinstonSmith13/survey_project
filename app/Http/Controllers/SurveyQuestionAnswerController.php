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

        $answersId = SurveyAnswer::query()
            ->where('survey_id', $id)
            ->value('id');
        $answers = SurveyQuestionAnswer::query()
            ->where('survey_answer_id', $answersId)
            ->get();

        return $answers
        ;
    }
}
