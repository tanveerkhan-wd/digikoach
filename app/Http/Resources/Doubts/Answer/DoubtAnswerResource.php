<?php

namespace App\Http\Resources\Doubts\Answer;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\Doubts\Doubt\DoubtResource;
use App\Http\Resources\Users\UserResource;

use Storage;
use Config;
use Utilities;

class DoubtAnswerResource extends JsonResource
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
        $doubt_answer = [
            'answer_id' => $this->answer_id,
            'parent_id' => $this->parent_id,
            'user_id' => $this->user_id,
            'doubt_id' => $this->doubt_id,
            'doubt_answer' => $this->doubt_answer,
            'total_replies' => $this->total_replies,
            'answer_upvote' => $this->answer_upvote,
            'answer_image' => $this->getDoubtAnswerImageUrl(),
            //'created_at' => $this->created_at->format('d/m/Y'),
            'created_at' => $this->created_at,
            'frm_created_at' => Utilities::getTimeAgo($this->created_at),
            'user' => $this->user ? new UserResource($this->user) : null,
        ];

        if($this->doubt){
            $doubt_answer['doubt'] = new DoubtResource($this->doubt);
        }

        return $doubt_answer;
    }

    private function getDoubtAnswerImageUrl()
    {
        if ($this->answer_image) {
            $images_dirs = Config::get('siteglobal.images_dirs');
            $doubt_dir = $images_dirs['DOUBT'];

            $file_answer_image = $doubt_dir . '/' . $this->answer_image;

            //return url('public' . Storage::url($file_answer_image));
            return Utilities::getThumbImage($file_answer_image, 800, 400);
        }

        return null;
    }
}
