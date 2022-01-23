<?php

namespace App\Http\Resources\Exam;

use Illuminate\Http\Resources\Json\ResourceCollection;

use App\Http\Resources\Exam\ExamResource;

class ExamCollection extends ResourceCollection
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
            'data' => $this->collection->transform(function ($exam) {
                return new ExamResource($exam);
            })
        ];
    }
}
