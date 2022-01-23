<?php

namespace App\Http\Resources\Question;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\Question\QuestionMediaResource;

class OptionResource extends JsonResource
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
            'question_options_id' => $this->question_options_id,
            'questions_id' => $this->questions_id,
            'category_id' => $this->category_id,
            'option_order' => $this->option_order,
            'is_valid' => $this->is_valid,
            'lang_code' => $this->desc->lang_code,
            'option_text' => $this->desc->option_text,
            'media' => $this->media->transform(function ($media) {
                return new QuestionMediaResource($media);
            }),
        ];
    }
}
