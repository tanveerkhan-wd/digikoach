<?php

namespace App\Http\Resources\Notifications;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Notifications\NotificationResource;

class NotificationPageCollection extends ResourceCollection
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
            'data' => $this->collection->transform(function ($notification) {
                return new NotificationResource($notification);
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
