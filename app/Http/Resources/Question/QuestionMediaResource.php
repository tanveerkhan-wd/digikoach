<?php

namespace App\Http\Resources\Question;

use Illuminate\Http\Resources\Json\JsonResource;

use Config;
use Storage;
use Utilities;

class QuestionMediaResource extends JsonResource
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
            'media_id' => $this->media_id,
            'media_int_id' => $this->media_int_id,
            'media_int_type' => $this->media_int_type,
            'media_file' => $this->getQuestMedia($this->media_file)
        ];
    }

    private function getQuestMedia($media_file){
        $images_dirs = Config::get('siteglobal.images_dirs');
        $question_dir = $images_dirs['QUESTIONS'];

        return Utilities::checkImageExists($question_dir, $media_file);
        //return url('public' . Storage::url($question_dir . '/' . $media_file));
    }
}
