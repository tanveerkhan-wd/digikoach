<?php

namespace App\Http\Resources\Notifications;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'notification_id' => $this->notification_id,
            'notification_type' => $this->notification_type,
            'ntoification_type_id' => $this->ntoification_type_id,
            'notification_data' => $this->notification_data,
            'notification_date' => ($this->created_at ? $this->created_at->format('d/m/Y') : NULL),
            'notification_time' => ($this->created_at ? $this->created_at->format('h:i A') : NULL),
            'lang_code' => ($this->desc && $this->desc->lang_code ? $this->desc->lang_code : NULL),
            'message' => ($this->desc && $this->desc->message ? $this->desc->message : NULL),
        ];
    }
}
