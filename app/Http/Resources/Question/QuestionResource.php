<?php

namespace App\Http\Resources\Question;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\Question\OptionResource;
use App\Http\Resources\Question\QuestionMediaResource;
use App\Http\Resources\Category\Category;

class QuestionResource extends JsonResource
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
        //dd($this->toArray());
        return [
            'questions_id' => $this->questions_id,
            'exam_id' => $this->exam_id,
            'category_id' => $this->category_id,
            'marks' => $this->marks,
            'question_type' => $this->question_type,
            'lang_code' => $this->question_desc->lang_code,
            'question_text' => $this->question_desc->question_text,
            'solution_text' => $this->question_desc->solution_text,
            'options' => $this->options->transform(function ($option) {
                return new OptionResource($option);
            }),
            'question_media' => $this->question_media->transform(function ($media) {
                return new QuestionMediaResource($media);
            }),
            'solution_media' => $this->solution_media->transform(function ($media) {
                return new QuestionMediaResource($media);
            }),
            'is_saved' => $this->is_saved,
            'category' => new Category($this->category),
            
        ];
    }
}
