<?php

namespace App\Http\Resources\Doubts\Reply;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Users\UserResource;

use Storage;
use Config;
use Utilities;

class DoubtReplyResource extends JsonResource
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
            'reply_id' => $this->reply_id,
            'parent_id' => $this->parent_id,
            'answer_id' => $this->answer_id,
            'user_id' => $this->user_id,
            'doubt_id' => $this->doubt_id,
            'doubt_reply' => $this->doubt_reply,
            'reply_image' => $this->getDoubtReplyImageUrl(),
            //'created_at' => $this->created_at->format('d/m/Y'),
            'created_at' => $this->created_at,
            'frm_created_at' => Utilities::getTimeAgo($this->created_at),
            'user' => $this->user ? new UserResource($this->user) : null,
        ];
    }

    private function getDoubtReplyImageUrl()
    {
        if ($this->reply_image) {
            $images_dirs = Config::get('siteglobal.images_dirs');
            $doubt_dir = $images_dirs['DOUBT'];

            $file_reply_image = $doubt_dir . '/' . $this->reply_image;

            //return url('public' . Storage::url($file_reply_image));
            return Utilities::getThumbImage($file_reply_image, 800, 400);
        }

        return null;
    }
}
