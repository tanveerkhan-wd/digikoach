<?php

namespace App\Http\Resources\Doubts\Answer;

use Illuminate\Http\Resources\Json\ResourceCollection;

use App\Http\Resources\Doubts\Answer\DoubtAnswerResource;

class DoubtAnsPageCollectionResouce extends ResourceCollection
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
                return new DoubtAnswerResource($answer);
            }),
            'pagination' => [
                'count' => $this->count(),
                'total' => $this->total(),
                'current' => $this->currentPage(),
                'hasMorePages' => $this->hasMorePages(),
                'lastPage' => $this->lastPage(),
            ]
        ];
    }
}
