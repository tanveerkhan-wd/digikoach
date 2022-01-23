<?php

namespace App\Http\Resources\Doubts\Answer;

use Illuminate\Http\Resources\Json\ResourceCollection;

use App\Http\Resources\Doubts\Answer\DoubtSimpleAnswerResource;

class DoubtAnsCollectionResouce extends ResourceCollection
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
            'data' => $this->collection->transform(function ($answer) {
                return new DoubtSimpleAnswerResource($answer);
            })
        ];
    }
}
