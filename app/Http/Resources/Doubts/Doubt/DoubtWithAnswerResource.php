<?php

namespace App\Http\Resources\Doubts\Doubt;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\Category\Category;
use App\Http\Resources\Users\UserResource;
use App\Http\Resources\Doubts\Answer\DoubtAnsCollectionResouce;

use Storage;
use Config;
use Utilities;

class DoubtWithAnswerResource extends JsonResource
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
            'doubt_id' => $this->doubt_id,
            'doubt_text' => $this->doubt_text,
            'doubt_upvote' => $this->doubt_upvote,
            'doubt_image' => $this->getDoubtImageUrl(),
            'doubt_attachment' => $this->getDoubtAttachmentUrl(),
            'total_answers' => $this->total_answers,
            'status' => $this->status,
            'is_saved' => $this->is_saved,
            'is_up_voted' => $this->is_up_voted,
            //'created_at' => $this->created_at->format('d/m/Y'),
            'created_at' => $this->created_at,
            'frm_created_at' => Utilities::getTimeAgo($this->created_at),
            'category' => $this->category ? new Category($this->category) : null,
            'user' => $this->user ? new UserResource($this->user) : null,
            'answers' => $this->my_answers ? new DoubtAnsCollectionResouce($this->my_answers) : null,
        ];
    }

    private function getDoubtImageUrl()
    {
        if ($this->doubt_image) {
            $images_dirs = Config::get('siteglobal.images_dirs');
            $doubt_dir = $images_dirs['DOUBT'];

            $file_doubt_image = $doubt_dir . '/' . $this->doubt_image;

            //return url('public' . Storage::url($file_doubt_image));
            return Utilities::getThumbImage($file_doubt_image, 400, 200);
        }

        return null;
    }

    private function getDoubtAttachmentUrl()
    {
        if ($this->doubt_attachment) {
            $images_dirs = Config::get('siteglobal.images_dirs');
            $doubt_dir = $images_dirs['DOUBT'];

            $file_doubt_attachment = $doubt_dir . '/' . $this->doubt_attachment;

            return url('public' . Storage::url($file_doubt_attachment));
        }

        return null;
    }
}
