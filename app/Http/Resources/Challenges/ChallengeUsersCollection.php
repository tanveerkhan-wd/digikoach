<?php

namespace App\Http\Resources\Challenges;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Challenges\ChallengeUserResource;

class ChallengeUsersCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'data' => $this->collection->transform(function ($challenge_user) {
                return new ChallengeUserResource($challenge_user);
            })
        ];
    }
}
