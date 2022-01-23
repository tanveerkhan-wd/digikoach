<?php

namespace App\Http\Resources\UserAttempt;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\Exam\ExamResource;
use App\Http\Resources\Users\UserResource;

class UserAttemptResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'user_attempt_id' => $this->user_attempt_id,
            'exam_challenge_id' => $this->exam_challenge_id,
            'total_time_spent' => $this->total_time_spent,
            'total_questions' => $this->total_questions,
            'total_marks' => $this->total_marks,
            'total_attempted' => $this->total_attempted,
            'total_skipped' => $this->total_skipped,
            'total_incorrect' => $this->total_incorrect,
            'total_obtain_marks' => $this->total_obtain_marks,
            'user_percentage' => $this->user_percentage,
            'user_rank' => $this->user_rank,
            'user_rank_base' => $this->user_rank_base,
            'attempt_status' => $this->attempt_status,
            'attempted_on' => $this->attempted_on,
            'exam' => new ExamResource($this->exam)
        ];
    }
}
