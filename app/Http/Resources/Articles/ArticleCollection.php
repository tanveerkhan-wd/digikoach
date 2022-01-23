<?php

namespace App\Http\Resources\Articles;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Articles\ArticleResource;

class ArticleCollection extends ResourceCollection
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
            'data' => $this->collection->transform(function ($article) {
                return new ArticleResource($article);
            })
        ];
    }
}
