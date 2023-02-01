<?php

namespace App\Http\Controllers;

use App\Http\Resources\SurveyAnswerResource;
use App\Http\Resources\SurveyResourceDashboard;
use App\Models\Survey;
use App\Models\SurveyAnswer;
use Illuminate\Http\Request;

/**
 * DashboardController Class
 * Handles the dashboard functionalities for the User.
 */
class DashboardController extends Controller
{
    /**
     * Get the data for the User's dashboard
     *
     * @param Request $request
     *
     * @return array Dashboard data
     */
    public function index(Request $request)
    {
        // Get the authenticated user
        $user = $request->user();

        // Get the total number of Surveys for the user
        $total = Survey::query()->where('user_id', $user->id)->count();

        // Get the latest Survey for the user
        $latest = Survey::query()->where('user_id', $user->id)->latest('created_at')->first();

        // Get the total number of answers for the user's Surveys
        $totalAnswers = SurveyAnswer::query()
            ->join('surveys', 'survey_answers.survey_id', '=', 'surveys.id')
            ->where('surveys.user_id', $user->id)
            ->count();

        // Get the latest 5 answers for the user's Surveys
        $latestAnswers = SurveyAnswer::query()
            ->join('surveys', 'survey_answers.survey_id', '=', 'surveys.id')
            ->where('surveys.user_id', $user->id)
            ->orderBy('end_date', 'DESC')
            ->limit(5)
            ->getModels('survey_answers.*');

        // Get all Surveys for the user
        $allSurveys = Survey::query()->where('user_id', $user->id)->get();

        // Return dashboard data
        return [
            'totalSurveys' => $total,
            'latestSurvey' => $latest ? new SurveyResourceDashboard($latest) : null,
            'totalAnswers' => $totalAnswers,
            'allSurveys' => $allSurveys,
            'latestAnswers' => SurveyAnswerResource::collection($latestAnswers)
        ];
    }
}
