<?php

namespace App\Http\Resources\Articles;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Articles\ArticleResource;

class ArticlePageCollection extends ResourceCollection
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
            'data' => $this->collection->transform(function($article){
                return new ArticleResource($article);
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
