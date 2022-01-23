<?php

namespace App\Http\Resources\Banner;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Banner\BannerResource;

class BannerCollection extends ResourceCollection
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
            'data' => $this->collection->transform(function($banner){
                return new BannerResource($banner);
            })
        ];
    }
}
