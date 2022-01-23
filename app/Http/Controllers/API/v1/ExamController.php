<?php

namespace App\Http\Controllers\API\v1;

use App;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\Exam\ExamPageCollection;
use App\Http\Resources\Exam\ExamCollection;
use App\Http\Resources\Exam\ExamResource;
use App\Http\Resources\UserAttempt\UserAttemptCollection;
use App\Http\Resources\Challenges\ChallengeCollection;
use App\Http\Resources\Challenges\ChallengeResource;
use App\Http\Resources\Users\UserResource;

use App\Models\Exam;
use App\Models\UserAttempt;
use App\Models\ExamQuestion;
use App\Models\UserExamResponse;
use App\Models\QuestionOption;
use App\Models\UserCategory;
use App\Models\ExamChallenge;
use App\Models\ExamChallengeUser;

use Auth;
use Settings;
use UserNotifications;
use DB;
use Storage;
use Config;

class ExamController extends Controller
{
    /**
     * Exam listing
     *
     * @param  mixed $request
     * @param  string $exam_type
     * @return \Illuminate\Http\JsonResponse
     */
    public function getExams(Request $request, $exam_type)
    {
        $user = Auth::user();

        $request_type = $request->get('type');
        if (!$request_type) $request_type = 'ONGOING';

        $exam_type = strtoupper($exam_type);

        $user_exam_attempted = UserAttempt::select('exam_id')->where('user_id', $user->user_id)->whereIn('attempt_status', ['COMPLETED'])->where('exams_type', $exam_type)->get()->toArray();

        if ($request_type == 'ONGOING') {
            switch ($exam_type) {
                case 'LIVE_TEST':
                    if ($request_type == 'ONGOING') {
                        //User Level and Sub Levles
                        $user_levels = UserCategory::where('user_id', $user->user_id)->get()->pluck('category_id');
                        $user_levels[] = $user->user_fav_category;

                        $exams_query = Exam::query()->with(['desc', 'category'])->where('status', 1);
                        $exams_query->where('exams_type', $exam_type);

                        if (is_array($user_exam_attempted) && count($user_exam_attempted)) {
                            $exams_query->whereNotIn('exam_id', $user_exam_attempted);
                        }

                        $exams_query->where('exam_ends_on', '>', date("Y-m-d H:i:s"));
                        $exams_query->whereIn('category_id', $user_levels);

                        $exams_query->orderBy('exam_starts_on', 'ASC');

                        $exams = $exams_query->paginate(10);
                        return response()->json(new ExamPageCollection($exams), 200, [], JSON_INVALID_UTF8_IGNORE);
                    }
                    break;
                case 'QUIZZES':
                    $category_id = $request->get('cat_id');

                    $exams_query = Exam::query()->with(['desc', 'category'])->where('status', 1);
                    $exams_query->where('exams_type', strtoupper($exam_type));

                    $exams_query->where('category_id', $category_id);

                    $exams_query->orderBy('created_at', 'DESC');

                    $exams = $exams_query->paginate(10);
                    return response()->json(new ExamPageCollection($exams), 200, [], JSON_INVALID_UTF8_IGNORE);
                    break;
                case 'GK_CA':
                    $exams_query = Exam::query()->with(['desc', 'category'])->where('status', 1);
                    $exams_query->where('exams_type', strtoupper($exam_type));
                    $exams_query->orderBy('created_at', 'DESC');

                    $exams = $exams_query->paginate(10);
                    return response()->json(new ExamPageCollection($exams), 200, [], JSON_INVALID_UTF8_IGNORE);
                    break;
                case 'PRACTICE_TEST':
                    $category_id = $request->get('cat_id');
                    $exams_query = Exam::query()->with(['desc', 'category'])->where('status', 1);
                    $exams_query->where('exams_type', strtoupper($exam_type));
                    $exams_query->where('category_id', $category_id);
                    $exams_query->orderBy('created_at', 'DESC');

                    $exams = $exams_query->paginate(10);
                    return response()->json(new ExamPageCollection($exams), 200, [], JSON_INVALID_UTF8_IGNORE);
                    break;
            }
        } else if ($request_type == 'ATTEMPTED') {
            $exams_query = UserAttempt::with('exam')->whereIn('exam_id', $user_exam_attempted)->orderBy('attempted_on', 'DESC')->where('exams_type', $exam_type)->where('user_id', $user->user_id);

            $exams = $exams_query->paginate(10);
            return response()->json(new UserAttemptCollection($exams), 200, [], JSON_INVALID_UTF8_IGNORE);
        }
    }

    /**
     * Get upcoming exams
     *
     * @param  string $exam_type
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUpcomingExams($exam_type)
    {
        $user = Auth::user();

        $user_exam_attempted = UserAttempt::select('exam_id')->where('user_id', $user->user_id)->whereIn('attempt_status', ['COMPLETED'])->get()->toArray();

        //User Level and Sub Levles
        $user_levels = UserCategory::where('user_id', $user->user_id)->get()->pluck('category_id');
        $user_levels[] = $user->user_fav_category;

        $exams_query = Exam::query()->with(['desc', 'category'])->whereIn('category_id', $user_levels)->where('status', 1);
        $exams_query->where('exams_type', strtoupper($exam_type))->where('exam_ends_on', '>=', date("Y-m-d H:i:s"));

        $exams_query->whereNotIn('exam_id', $user_exam_attempted);

        //$exams_query->inRandomOrder()->limit(3);
        $exams_query->orderBy('exam_starts_on', 'ASC')->limit(3);

        $exams = $exams_query->get();

        return response()->json(new ExamCollection($exams), 200, [], JSON_INVALID_UTF8_IGNORE);
    }

    /**
     * Get Exam Information / Detail
     *
     * @param  number $exam_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getExamInfo($exam_id)
    {
        $exam_query = Exam::query()->with(['desc', 'category'])->where('exam_id', $exam_id);
        $exam = $exam_query->first();

        if ($exam) {
            return response()->json(new ExamResource($exam), 200, [], JSON_INVALID_UTF8_IGNORE);
        } else {
            return response()->json(['message' => trans('message.error.invalid_request')], 400, [], JSON_INVALID_UTF8_IGNORE);
        }
    }

    /**
     * Get exam rules
     *
     * @param  string $exam_type
     * @return \Illuminate\Http\JsonResponse
     */
    public function getExamRules($exam_type)
    {
        $language = App::getLocale();

        $rules_text = '';
        $rules_setting_key = '';

        $exam_type = strtoupper($exam_type);
        if ($exam_type == 'LIVE_TEST') {
            $rules_setting_key = 'test_rule_live_test_' . $language;
        } else if ($exam_type == 'QUIZZES') {
            $rules_setting_key = 'test_rule_quizzes_test_' . $language;
        } else if ($exam_type == 'GK_CA') {
            $rules_setting_key = 'test_rule_quizzes_test_' . $language;
        }

        if ($rules_setting_key) {
            $rules_text = Settings::get($rules_setting_key);
        }


        return response()->json($rules_text, 200, [], JSON_INVALID_UTF8_IGNORE);
    }

    /**
     * Register with an exam
     *
     * @param  number $exam_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerExam($exam_id)
    {
        $error_messages = trans('message.error.exam_register_failed');
        $user = Auth::user();
        if ($user) {
            $exam = Exam::find($exam_id);
            if ($exam) {
                if (strtotime($exam->exam_starts_on) > time()) {
                    $prevAttempted = UserAttempt::where('exam_id', $exam_id)->where('user_id', $user->user_id)->first();

                    if (!$prevAttempted) {
                        if ($this->createExamAttempt($exam, 'REGISTERED')) {
                            $success_message = trans('message.success.exam_registered');
                            return response()->json(['message' => $success_message], 200, [], JSON_INVALID_UTF8_IGNORE);
                        }
                    } else {
                        $success_message = trans('message.error.exam_register_already');
                        return response()->json(['message' => $success_message], 200, [], JSON_INVALID_UTF8_IGNORE);
                    }
                }
            }
        } else {
            $error_messages = trans('message.error.invalid_user');
        }


        return response()->json(['message' => $error_messages], 401, [], JSON_INVALID_UTF8_IGNORE);
    }

    /**
     * Start an exam
     *
     * @param  mixed $exam_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function startExam($exam_id, Request $request)
    {
        $error_messages = trans('message.error.exam_start_failed');
        $user = Auth::user();

        $challenge_id = $request->challenge_id;

        if ($user) {
            $is_started = false;
            $exam = Exam::find($exam_id);

            if ($exam->exams_type == 'PRACTICE_TEST') {
                $prevAttempted = $this->createExamAttempt($exam, 'COMPLETED', $challenge_id);
                $is_started = true;
            } else {
                $prevAttemptedQuery = UserAttempt::query()->where('exam_id', $exam_id)->where('user_id', $user->user_id);
                if ($challenge_id > 0) {
                    $prevAttemptedQuery->where('exam_challenge_id', $challenge_id);
                }

                $prevAttemptedQuery->whereIn('attempt_status', ['STARTED', 'REGISTERED']);

                $prevAttempted = $prevAttemptedQuery->first();

                if (!$prevAttempted) {
                    if ($exam) {
                        if ($exam->exams_type == 'LIVE_TEST') {
                            if (strtotime($exam->exam_starts_on) <= time()) {
                                $is_started = true;
                            }
                        } else {
                            $is_started = true;
                        }

                        if ($is_started) {
                            $prevAttempted = $this->createExamAttempt($exam, 'STARTED', $challenge_id);
                        }
                    }
                } else {
                    $prevAttempted->attempt_status = 'STARTED';
                    $prevAttempted->attempted_on = date("Y-m-d H:i:s");
                    $prevAttempted->save();

                    $is_started = true;
                }
            }

            if ($is_started) {
                $examInfo = new ExamResource($exam);
                $exam_questions = $this->getExamQuestions($exam_id, $user->user_id, $prevAttempted->user_attempt_id);

                $total_attempted = UserExamResponse::where('user_attempt_id', $prevAttempted->user_attempt_id)->where('attempt_status', '1')->count();
                $total_skipped = UserExamResponse::where('user_attempt_id', $prevAttempted->user_attempt_id)->where('attempt_status', '0')->count();
                $total_seen = UserExamResponse::where('user_attempt_id', $prevAttempted->user_attempt_id)->where('attempt_status', '2')->count();

                $success_message = trans('message.success.exam_started');
                return response()->json([
                    'user_attempt_id' => $prevAttempted->user_attempt_id,
                    'total_attempted' => $total_attempted,
                    'total_skipped' => $total_skipped,
                    'total_seen' => $total_seen,
                    'total_unseen' => ($examInfo->total_questions - ($total_attempted + $total_skipped)),
                    'total_time_spent' => ($prevAttempted->total_time_spent ? $prevAttempted->total_time_spent : 0),
                    'exam' => $examInfo,
                    'exam_questions' => $exam_questions,
                    'message' => $success_message
                ], 200, [], JSON_INVALID_UTF8_IGNORE);
            }
        } else {
            $error_messages = trans('message.error.invalid_user');
        }


        return response()->json(['message' => $error_messages], 400, [], JSON_INVALID_UTF8_IGNORE);
    }

    /**
     * Save user question response
     *
     * @param  mixed $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveExamResponse(Request $request)
    {
        $error_messages = trans('message.error.invalid_request');

        $user = Auth::user();

        $user_attempt = UserAttempt::find($request->user_attempt_id);
        if ($user_attempt) {
            $exam = Exam::find($user_attempt->exam_id);

            if(strtotime($exam->exam_ends_on) < time() && $user_attempt->exams_type == 'LIVE_TEST'){
                return response()->json(['exam_expired' => true], 200);
            }

            if ($user) {
                $option_info = QuestionOption::with('question')->where('question_options_id', $request->option_id)->first();

                $user_exam_response = UserExamResponse::where('user_attempt_id', $request->user_attempt_id)->where('questions_id', $request->questions_id)->first();

                if (!$user_exam_response) {
                    $user_exam_response = new UserExamResponse;
                    $user_exam_response->user_id = $user->user_id;
                    $user_exam_response->exam_id = $request->exam_id;
                    $user_exam_response->user_attempt_id = $request->user_attempt_id;
                    $user_exam_response->exam_questions_id = $request->exam_questions_id;
                    $user_exam_response->questions_id = $request->questions_id;
                    $user_exam_response->total_time_spent = $request->total_time_spent;
                }

                $user_exam_response->option_id = $request->option_id;

                if ($option_info) {
                    $user_exam_response->is_valid = $option_info->is_valid;
                    $user_exam_response->obtain_mark = ($option_info->is_valid == 1 ? $option_info->question->marks : 0);
                    $user_exam_response->attempt_status = 1;
                } else {
                    $user_exam_response->is_valid = 0;
                    $user_exam_response->obtain_mark = 0;
                    $user_exam_response->attempt_status = 0;
                }

                if ($user_exam_response->save()) {
                    if ($user_attempt->exams_type == 'PRACTICE_TEST') {
                        $total_time_spent = UserExamResponse::where('user_attempt_id', $user_attempt->user_attempt_id)->sum('total_time_spent');

                        $total_attempted = UserExamResponse::where('user_attempt_id', $user_attempt->user_attempt_id)->where('attempt_status', '1')->count();
                        $total_skipped = UserExamResponse::where('user_attempt_id', $user_attempt->user_attempt_id)->where('attempt_status', '0')->count();
                        $total_correct = UserExamResponse::where('user_attempt_id', $user_attempt->user_attempt_id)->where('is_valid', '1')->count();
                        $total_incorrect = UserExamResponse::where('user_attempt_id', $user_attempt->user_attempt_id)->where('is_valid', '0')->count();

                        $user_attempt->total_attempted = $total_attempted;
                        $user_attempt->total_skipped = $total_skipped;
                        $user_attempt->total_correct = $total_correct;
                        $user_attempt->total_incorrect = $total_incorrect;
                    } else {
                        $total_time_spent = (($exam->exam_duration * 60) - $request->remaining_time);
                    }

                    $user_attempt->total_time_spent = $total_time_spent;
                    $user_attempt->save();

                    $success_message = '';
                    return response()->json(['message' => $success_message], 200, [], JSON_INVALID_UTF8_IGNORE);
                }
            } else {
                $error_messages = trans('message.error.invalid_user');
            }
        }

        return response()->json(['message' => $error_messages], 400, [], JSON_INVALID_UTF8_IGNORE);
    }

    /**
     * Complete an exam using attempt id
     *
     * @param  mixed $user_attempt_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function finishExam($user_attempt_id, Request $request)
    {
        $error_messages = trans('message.error.invalid_request');

        $user = Auth::user();
        if ($user) {
            $user_attempt = UserAttempt::find($user_attempt_id);
            if ($user_attempt) {
                //$exam = Exam::find($user_attempt->exam_id);

                $total_attempted = UserExamResponse::where('user_attempt_id', $user_attempt_id)->where('attempt_status', '1')->count();
                $total_skipped = UserExamResponse::where('user_attempt_id', $user_attempt_id)->where('attempt_status', '0')->count();
                $total_correct = UserExamResponse::where('user_attempt_id', $user_attempt_id)->where('is_valid', '1')->count();
                $total_incorrect = UserExamResponse::where('user_attempt_id', $user_attempt_id)->where('is_valid', '0')->count();
                $total_obtain_marks = UserExamResponse::where('user_attempt_id', $user_attempt_id)->sum('obtain_mark');

                $user_percentage = 0;
                if ((int)$user_attempt->total_marks > 0) {
                    $user_percentage = round((($total_obtain_marks * 100) / $user_attempt->total_marks), 2);
                }

                $user_attempt->attempt_status = 'COMPLETED';
                $user_attempt->attempted_on = date("Y-m-d H:i:s");
                $user_attempt->total_attempted = $total_attempted;
                $user_attempt->total_skipped = $total_skipped;
                $user_attempt->total_correct = $total_correct;
                $user_attempt->total_incorrect = $total_incorrect;
                $user_attempt->total_obtain_marks = $total_obtain_marks;
                $user_attempt->user_percentage = $user_percentage;

                if ($user_attempt->save()) {
                    $success_message = '';

                    $total_completed_exams = UserAttempt::where('exam_id', $user_attempt->exam_id)->where('attempt_status', 'COMPLETED')->count();
                    $other_user_ranks = UserAttempt::where('exam_id', $user_attempt->exam_id)->where('attempt_status', 'COMPLETED')->where('total_obtain_marks', '>', $total_obtain_marks)->count('user_id');

                    $user_rank = $other_user_ranks + 1;

                    $user_attempt->user_rank = $user_rank;
                    $user_attempt->user_rank_base = $total_completed_exams;
                    $user_attempt->save();

                    return response()->json(['message' => $success_message], 200, [], JSON_INVALID_UTF8_IGNORE);
                }
            }
        } else {
            $error_messages = trans('message.error.invalid_user');
        }

        return response()->json(['message' => $error_messages], 400, [], JSON_INVALID_UTF8_IGNORE);
    }

    public function getExamAttempt($user_attempt_id)
    {
        $error_messages = trans('message.error.invalid_request');

        $user = Auth::user();
        if ($user) {
            $user_attempt = UserAttempt::where('user_attempt_id', $user_attempt_id)->where('user_id', $user->user_id)->first();

            $user_attempt_info = $this->getAttemptInfo($user_attempt);
            return response()->json($user_attempt_info, 200, [], JSON_INVALID_UTF8_IGNORE);
        } else {
            $error_messages = trans('message.error.invalid_user');
        }

        return response()->json(['message' => $error_messages], 400, [], JSON_INVALID_UTF8_IGNORE);
    }

    public function saveSeenResponse(Request $request)
    {
        $error_messages = trans('message.error.invalid_request');

        $user = Auth::user();

        $user_attempt = UserAttempt::find($request->user_attempt_id);

        if ($user_attempt) {
            $user_exam_response = UserExamResponse::where('user_attempt_id', $request->user_attempt_id)->where('questions_id', $request->questions_id)->first();

            if ($user) {
                if (!$user_exam_response) {
                    $user_exam_response = new UserExamResponse;
                    $user_exam_response->user_id = $user->user_id;
                    $user_exam_response->exam_id = $request->exam_id;
                    $user_exam_response->user_attempt_id = $request->user_attempt_id;
                    $user_exam_response->exam_questions_id = $request->exam_questions_id;
                    $user_exam_response->questions_id = $request->questions_id;
                    $user_exam_response->option_id = 0;
                    $user_exam_response->is_valid = 0;
                    $user_exam_response->obtain_mark = 0;
                    $user_exam_response->attempt_status = 2;
                    $user_exam_response->save();
                }

                $success_message = '';
                return response()->json(['message' => $success_message], 200, [], JSON_INVALID_UTF8_IGNORE);
            } else {
                $error_messages = trans('message.error.invalid_user');
            }
        }

        return response()->json(['message' => $error_messages], 400, [], JSON_INVALID_UTF8_IGNORE);
    }

    public function getAttemptChallenges($user_attempt_id)
    {
        $error_messages = trans('message.error.invalid_request');

        $user = Auth::user();

        if ($user) {
            $user_attempt = UserAttempt::find($user_attempt_id);
            if ($user_attempt) {
                $exam_challenge_id = $user_attempt->exam_challenge_id;

                $exam_challenge = ExamChallenge::with('users')->where('exam_challenge_id', $exam_challenge_id)->first();

                return response()->json(new ChallengeResource($exam_challenge), 200, [], JSON_INVALID_UTF8_IGNORE);
            }
        } else {
            $error_messages = trans('message.error.invalid_user');
        }

        return response()->json(['message' => $error_messages], 400, [], JSON_INVALID_UTF8_IGNORE);
    }

    public function getChallengeAttempts($exam_challenge_id)
    {
        $error_messages = trans('message.error.invalid_request');

        $user = Auth::user();

        $challenge_attempts = [];
        if ($user) {
            $user_attempts = UserAttempt::with(['user', 'responses'])->where('exam_challenge_id', $exam_challenge_id)->orderByRaw("user_attempts.user_id = '" . $user->user_id . "' DESC, user_attempts.updated_at DESC")->get();

            $logged_in_user_attempt = null;
            if ($user_attempts) {
                foreach ($user_attempts as $user_attempt) {
                    $user_attempt_info = $this->getAttemptInfo($user_attempt);

                    if($user->user_id != $user_attempt_info->user_id){
                        $compared_performace = "EXCELLENT";
                        if ($logged_in_user_attempt && $logged_in_user_attempt->user_percentage <= $user_attempt_info->user_percentage) {
                            $compared_performace = "LOW";
                        }

                        $user_attempt_info->compared_performace = $compared_performace;
                    }else{
                        $logged_in_user_attempt = $user_attempt_info;
                    }

                    $challenge_attempts[] = $user_attempt_info;
                }
            }

            return response()->json($challenge_attempts, 200, [], JSON_INVALID_UTF8_IGNORE);
        } else {
            $error_messages = trans('message.error.invalid_user');
        }

        return response()->json(['message' => $error_messages], 400, [], JSON_INVALID_UTF8_IGNORE);
    }

    private function getExamQuestions($exam_id, $user_id, $user_attempt_id = 0)
    {
        //DB::enableQueryLog();
        return ExamQuestion::select('exam_questions.exam_questions_id', 'exam_questions.questions_id', 'user_exam_responses.attempt_status', 'user_exam_responses.option_id', 'user_exam_responses.is_valid', 'user_exam_responses.total_time_spent')->leftJoin('user_exam_responses', function ($join) use ($exam_id, $user_id, $user_attempt_id) {
            if ($user_attempt_id) {
                $join->on('user_exam_responses.exam_id', '=', 'exam_questions.exam_id')->on('user_exam_responses.questions_id', '=', 'exam_questions.questions_id')->where('user_exam_responses.exam_id', $exam_id)->where('user_exam_responses.user_id', $user_id)->where('user_exam_responses.user_attempt_id', $user_attempt_id);
            }
        })->where('exam_questions.exam_id', $exam_id)->orderBy('exam_questions.questions_id')->get();
        //dd(DB::getQueryLog());
    }

    private function getAttemptInfo($user_attempt)
    {
        $user = Auth::user();
        if ($user_attempt) {
            $user_performance = 'LOW';
            if ($user_attempt->user_percentage >= 90) {
                $user_performance = 'EXCELLENT';
            } else if ($user_attempt->user_percentage >= 40) {
                $user_performance = 'AVERAGE';
            }

            $user_attempt->user_performance = $user_performance;

            $user_responses = [];
            $quests_attempted = [];
            if ($user_attempt->responses) {
                foreach ($user_attempt->responses as $response_info) {
                    $option_group = 'UNATTEMPTED';
                    if ($response_info->attempt_status == 1) {
                        $option_group = ($response_info->is_valid ? 'CORRECT' : 'INCORRECT');
                    }

                    $user_responses[$option_group][] = [
                        'response_id' => $response_info->exam_responses_id,
                        'questions_id' => $response_info->question->questions_id,
                        'option_id' => $response_info->option_id,
                        'question_text' => ($response_info->question ? $response_info->question->question_desc->question_text : null),
                    ];

                    $quests_attempted[] = $response_info->question->questions_id;
                }
            }

            $total_attempted = count($quests_attempted);

            $total_skipped = 0;
            if ($total_attempted) {
                $exam_skipped_questions = ExamQuestion::with('question')->where('exam_id', $user_attempt->exam_id)->whereNotIn('questions_id', $quests_attempted)->get();

                foreach ($exam_skipped_questions as $question_info) {
                    $total_skipped++;
                    $user_responses['UNATTEMPTED'][] = [
                        'questions_id' => ($question_info->question ? $question_info->question->questions_id : 0),
                        'question_text' => ($question_info->question && $question_info->question->question_desc ? $question_info->question->question_desc->question_text : null),
                        'option_id' => 0,
                    ];
                }
            }

            $user_attempt->total_time_spent = round($user_attempt->total_time_spent / 60, 0);

            /* $user_attempt->total_attempted = $total_attempted;
                $user_attempt->total_skipped = $total_skipped; */

            $user_attempt->user_responses = $user_responses;
            unset($user_attempt->responses);

            $exam_questions = $this->getExamQuestions($user_attempt->exam_id, $user_attempt->user_id, $user_attempt->user_attempt_id);
            $user_attempt->exam_questions = $exam_questions;

            if ($user_attempt->user) {
                $user_attempt_user = [
                    'name' => $user_attempt->user->name,
                    'email' => $user_attempt->user->email,
                    'mobile_number' => $user_attempt->user->mobile_number,
                    'user_photo' => $this->user_thumb_photo($user_attempt->user->user_photo),
                ];

                unset($user_attempt->user);
                $user_attempt->user = $user_attempt_user;
            }

            return $user_attempt;
        }
    }

    /**
     * Create exam attempt record
     *
     * @param  object $exam
     * @param  string $attempt_status
     * @return boolean
     */
    private function createExamAttempt($exam, $attempt_status, $exam_challenge_id = 0)
    {
        $user = Auth::user();

        if ($user) {
            $user_attempt = new UserAttempt;

            $user_attempt->exam_id = $exam->exam_id;
            $user_attempt->exams_type = $exam->exams_type;
            $user_attempt->user_id = $user->user_id;
            $user_attempt->exam_challenge_id = $exam_challenge_id;
            $user_attempt->attempt_status = $attempt_status;
            $user_attempt->attempted_on = date("Y-m-d H:i:s");
            $user_attempt->total_questions = $exam->total_questions;
            $user_attempt->total_marks = $exam->total_marks;
            $user_attempt->total_attempted = 0;
            $user_attempt->total_skipped = 0;
            $user_attempt->total_correct = 0;
            $user_attempt->total_incorrect = 0;
            $user_attempt->total_obtain_marks = 0;
            $user_attempt->user_percentage = 0;
            $user_attempt->user_rank = 0;
            $user_attempt->user_rank_base = 0;

            $user_attempt->save();

            return $user_attempt;
        }

        return false;
    }

    private function user_thumb_photo($user_photo)
    {
        $user_photo_thumb = '';
        if (!empty($user_photo)) {
            $images_dirs = Config::get('siteglobal.images_dirs');
            $user_dir = $images_dirs['USERS'] . '/';


            $user_photo_thumb = url('public' . Storage::url($user_dir . $user_photo));
        }

        return $user_photo_thumb;
    }
}
