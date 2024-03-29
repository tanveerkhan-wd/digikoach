<?php

namespace App\Http\Resources\Question;

use Illuminate\Http\Resources\Json\ResourceCollection;

use App\Http\Resources\Question\QuestionResource;

class QuestionCollection extends ResourceCollection
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
            'data' => $this->collection->transform(function ($question) {
                return new QuestionResource($question);
            })
        ];
    }
}
