<?php

namespace App\Http\Controllers;


use App\Models\Survey;
use App\Models\SurveyQuestionAnswer;
use Illuminate\Http\Request;

/**
 * Class SurveyQuestionAnswerController
 *
 * This class handles the index action for the SurveyQuestionAnswer model.
 * It retrieves the survey question answers for the authenticated user based on the given survey ID.
 * It also retrieves all survey data for the authenticated user.
 */
class SurveyQuestionAnswerController extends Controller
{
    public function index(Request $request, $id)
    {
        $user = $request->user();

        // Query to retrieve survey question answers for the authenticated user
        $answers = SurveyQuestionAnswer::query()
            ->join('survey_answers', 'survey_question_answers.survey_answer_id', '=', 'survey_answers.id')
            ->join('surveys', 'survey_answers.survey_id', '=', 'surveys.id')
            ->select('survey_question_answers.survey_question_id', 'survey_question_answers.answer')
            ->where('surveys.id', '=', $id)
            ->where('surveys.user_id', '=', $user->id)
            ->get()
            ->groupBy('survey_question_id');


        // Query to retrieve all survey data for the authenticated user
        $allDataSurvey = Survey::query()
            ->select('surveys.*')
            ->where('surveys.id', $id)
            ->where('surveys.user_id', $user->id)
            ->get();

        // Return the answers and all survey data
        return [
            'answers' => $answers,
            'allSurveys' => $allDataSurvey
        ];
    }
}
