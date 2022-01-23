<?php

namespace App\Http\Controllers\API\v1;

use App;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Question;
use App\Models\ExamQuestion;

use App\Http\Resources\Question\QuestionResource;

class QuestionController extends Controller
{
    /**
     * Get exam questions
     *
     * @param  number $exam_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getExamQuestions($exam_id)
    {
        $exam_questions = ExamQuestion::where('exam_id', $exam_id)->orderBy('questions_id')->get()->pluck('questions_id');
        if ($exam_questions) {
            return response()->json($exam_questions, 200, [], JSON_INVALID_UTF8_IGNORE);
        } else {
            return $this->invalidRequest();
        }
    }

    /**
     * Get question detail with options
     *
     * @param  number $question_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getQuestion($question_id, Request $request)
    {
        $question = Question::where('questions_id', $question_id)->with(['options', 'question_media', 'solution_media'])->first();

        if ($question) {
            return response()->json(new QuestionResource($question), 200, [], JSON_INVALID_UTF8_IGNORE);
        } else {
            return $this->invalidRequest();
        }
    }

    private function invalidRequest()
    {
        return response()->json(['message' => trans('message.error.invalid_request')], 400, [], JSON_INVALID_UTF8_IGNORE);
    }
}
