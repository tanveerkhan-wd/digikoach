<?php

namespace App\Http\Resources\Doubts\Doubt;

use Illuminate\Http\Resources\Json\ResourceCollection;

use App\Http\Resources\Doubts\Doubt\DoubtResource;

class DoubtPageCollectionResouce extends ResourceCollection
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
            'data' => $this->collection->transform(function ($doubt) {
                return new DoubtResource($doubt);
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
