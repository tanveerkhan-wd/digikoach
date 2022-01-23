<?php

namespace App\Http\Resources\Challenges;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Users\UserResource;

class ChallengeUserResource extends JsonResource
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
            'challenge_user_id' => $this->challenge_user_id,
            'is_organiser' => $this->is_organiser,
            'challenge_status' => $this->challenge_status,
            'user_id' => $this->user_id,
            'user' => new UserResource($this->user)
        ];
    }
}
