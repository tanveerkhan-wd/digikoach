<?php

namespace App\Http\Resources\Challenges;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Challenges\ChallengeUsersCollection;

class ChallengeResource extends JsonResource
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
            'exam_challenge_id' => $this->exam_challenge_id,
            'exam_id' => $this->exam_id,
            'users' => new ChallengeUsersCollection($this->users)
        ];
    }
}
