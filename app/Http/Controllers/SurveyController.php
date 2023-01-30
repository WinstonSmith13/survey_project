<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSurveyRequest;
use App\Http\Requests\UpdateSurveyRequest;
use App\Http\Resources\SurveyResource;
use App\Models\Survey;
use App\Models\SurveyAnswer;
use App\Models\SurveyQuestion;
use App\Models\SurveyQuestionAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Requests\StoreSurveyAnswerRequest;


class SurveyController extends Controller
{
    /**
     * Display a listing of the user's surveys.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get the authenticated user
        $user = $request->user();

        // Retrieve the surveys for the authenticated user, ordered by creation date (most recent first), and paginated
        return SurveyResource::collection(Survey::where('user_id', $user->id)->orderBy('created_at', 'DESC')->paginate(10));
    }

    /**
     * Store a newly created survey in storage.
     *
     * @param StoreSurveyRequest $request - instance of StoreSurveyRequest with request data
     * @return \Illuminate\Http\Response - response object
     */
    public function store(StoreSurveyRequest $request)
    {
        // Get validated data from the request
        $data = $request->validated();

        // Check and save image if provided
        if (isset($data['image'])) {
            $relativePath = $this->saveImage($data['image']);
            $data['image'] = $relativePath;
        }

        // Create new survey record in database
        $survey = Survey::create($data);

        // Create new questions for the survey
        foreach ($data['questions'] as $question) {
            $question['survey_id'] = $survey->id;
            $this->createQuestion($question);
        }

        // Return newly created survey
        return new SurveyResource($survey);
    }

    /**
     * Show specified survey.
     *
     * @param Survey $survey, Request $request
     * @return \Illuminate\Http\Response
     */
    public function show(Survey $survey, Request $request)
    {
        // Fetch user from request.
        $user = $request->user();

        // Check if current user is owner of the survey.
        if ($user->id != $survey->user_id) {
            // Return 403 if unauthorized.
            return abort(403, 'Unauthorized action.');
        }

        // Return survey resource.
        return new SurveyResource($survey);
    }

    /**
     * Display specified survey for guest.
     *
     * @param \App\Models\Survey $survey
     * @return \Illuminate\Http\Response
     */
    public function showForGuest(Survey $survey)
    {
        // Returns survey resource for guest.
        return new SurveyResource($survey);
    }


    // Store survey answer by creating a new record in the SurveyAnswer table

    /**
     * Store survey answer.
     * @param \App\Http\Requests\StoreSurveyAnswerRequest $request
     * @param \App\Models\Survey $survey
     * @return \Illuminate\Http\Response
     */

    public function storeAnswer(StoreSurveyAnswerRequest $request, Survey $survey)
    {
        // Validate request data
        $validated = $request->validated();

        // Create new survey answer record
        $surveyAnswer = SurveyAnswer::create([
            'survey_id' => $survey->id,
            'start_date' => date('Y-m-d H:i:s'),
            'end_date' => date('Y-m-d H:i:s')
        ]);

        // Loop through each answer and create new survey question answer records
        foreach ($validated['answers'] as $questionId => $answer) {

            // Verify that the answer is part of the survey
            $question = SurveyQuestion::where([
                'id' => $questionId,
                'survey_id' => $survey->id
            ])->get();

            // If question doesn't exist, return error response
            if (!$question) {
                return response("Invalid question ID: \"$questionId\"", 400);
            }

            // Save answer data if question exists
            $data = [
                'survey_question_id' => $questionId,
                'survey_answer_id' => $surveyAnswer->id,
                'answer' => is_array($answer) ? json_encode($answer) : $answer
            ];
            SurveyQuestionAnswer::create($data);
        }

        // Return 201 status code for successful creation
        return response("", 201);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateSurveyRequest $request
     * @param \App\Models\Survey $survey
     * @return \Illuminate\Http\Response
     */
    // Update survey information with input from UpdateSurveyRequest
    public function update(UpdateSurveyRequest $request, Survey $survey)
    {
        // Validate input data from the request
        $data = $request->validated();

        // Check if image was given and save on local file system
        if (isset($data['image'])) {
            $relativePath = $this->saveImage($data['image']);
            $data['image'] = $relativePath;

            // If there is an old image, delete it
            if ($survey->image) {
                $absolutePath = public_path($survey->image);
                File::delete($absolutePath);
            }
        }
        // Update survey in the database
        $survey->update($data);

        // Get ids as plain array of existing questions
        $existingIds = $survey->questions()->pluck('id')->toArray();
        // Get ids as plain array of new questions
        $newIds = Arr::pluck($data['questions'], 'id');
        // Find questions to delete
        $toDelete = array_diff($existingIds, $newIds);
        // Find questions to add
        $toAdd = array_diff($newIds, $existingIds);

        // Delete questions by $toDelete array
        SurveyQuestion::destroy($toDelete);

        // Create new questions
        //We need to check if the question id is in the To Add array. We want to update not delete.
        foreach ($data['questions'] as $question) {
            if (in_array($question['id'], $toAdd)) {
                $question['survey_id'] = $survey->id;
                $this->createQuestion($question);
            }
        }
        // Update existing questions
        // Creating a map with id in key.
        $questionMap = collect($data['questions'])->keyBy('id');
        // Loop through the questions in the database
        foreach ($survey->questions as $question) {
            // If the database question id exists inside the question map, this means that it is the question to update.
            if (isset($questionMap[$question->id])) {
                $this->updateQuestion($question, $questionMap[$question->id]);
            }
        }
        // Return the updated survey
        return new SurveyResource($survey);
    }

    /**
     * Delete a survey and its associated image.
     *
     * @param \App\Models\Survey $survey
     * @return \Illuminate\Http\Response
     * @param \Illuminate\Http\Request $request
     */

    public function destroy(Survey $survey, Request $request)
    {
        // Verify that the current user has the right to delete the form.
        $user = $request->user();

        if ($user->id !== $survey->user_id) {
            return abort(403, 'Unauthorized action.');
        }

        // Delete survey from database
        $survey->delete();

        // If there is an image associated with survey, delete it
        if ($survey->image) {
            $absolutePath = public_path($survey->image);
            File::delete($absolutePath);
        }
        // Return HTTP No Content status code
        return response('', 204);
    }

    /**
     * Save image to disk and return the relative path.
     *
     * @param string $image
     * @return string
     * @throws \Exception
     */
    private function saveImage($image)
    {
        // Check if the image is a valid base64 string
        if (preg_match('/^data:image\/(\w+);base64,/', $image, $type)) {
            // Remove the mime type from the image string
            $image = substr($image, strpos($image, ',') + 1);
            // Get the image file extension
            $type = strtolower($type[1]); // jpg, png, gif

            // Check if the file is an image
            if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
                throw new \Exception('Invalid image type');
            }
            $image = str_replace(' ', '+', $image);
            $image = base64_decode($image);

            if ($image === false) {
                throw new \Exception('base64_decode failed');
            }
        } else {
            throw new \Exception('Data URI does not contain image data');
        }

        $dir = 'images/';
        $file = Str::random() . '.' . $type;
        $absolutePath = public_path($dir);
        $relativePath = $dir . $file;
        // Create the image directory if it does not exist
        if (!File::exists($absolutePath)) {
            File::makeDirectory($absolutePath, 0755, true);
        }
        file_put_contents($relativePath, $image);

        return $relativePath;
    }



    /**
     * Create a survey question from data.
     *
     * @param array $data
     * @return \App\Models\SurveyQuestion
     * @throws \Exception
     */
    private function createQuestion(array $data)
    {
        // Check if data contains data
        if (is_array($data['data'])) {
            $data['data'] = json_encode($data['data']);
        }
        // Validate the data
        $validator = Validator::make($data, [
            'question' => 'required|string',
            'type' => ['required', Rule::in([
                Survey::TYPE_TEXT,
                Survey::TYPE_TEXTAREA,
                Survey::TYPE_SELECT,
                Survey::TYPE_RADIO,
                Survey::TYPE_CHECKBOX,
            ])],
            'description' => 'nullable|string',
            'data' => 'present',
            'survey_id' => 'exists:App\Models\Survey,id'
        ]);

        // Check if data is valid
        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        return SurveyQuestion::create($validator->validated());
    }

    /**
     * Update a survey question with new data
     * 
     * @param SurveyQuestion $question - instance of survey question to update
     * @param array $data - updated data for the survey question
     * @return boolean - result of the update operation
     */
    private function updateQuestion(SurveyQuestion $question, $data)
    {
        // Convert data array to json encoded string
        if (is_array($data['data'])) {
            $data['data'] = json_encode($data['data']);
        }

        // Validate updated data
        $validator = Validator::make($data, [
            // Check if id exists in the SurveyQuestion id column
            'id' => 'exists:App\Models\SurveyQuestion,id',
            // Ensure question is a required string
            'question' => 'required|string',
            // Ensure type is a required value in the list of options
            'type' => ['required', Rule::in([
                Survey::TYPE_TEXT,
                Survey::TYPE_TEXTAREA,
                Survey::TYPE_SELECT,
                Survey::TYPE_RADIO,
                Survey::TYPE_CHECKBOX,
            ])],
            // Make description an optional string
            'description' => 'nullable|string',
            // Ensure data is present
            'data' => 'present',
        ]);

        // Update the survey question with validated data
        return $question->update($validator->validated());
    }
}
