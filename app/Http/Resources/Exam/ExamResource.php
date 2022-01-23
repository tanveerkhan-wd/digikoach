<?php

namespace App\Http\Resources\Exam;

use Illuminate\Http\Resources\Json\JsonResource;

use Storage;
use Config;

class ExamResource extends JsonResource
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
            'exam_id' => $this->exam_id,
            'category_id' => $this->category_id,
            'exams_type' => $this->exams_type,
            'exam_duration' => $this->exam_duration,
            'exam_duration_sec' => ($this->exam_duration * 60),
            'exam_starts_on' => $this->exam_starts_on,
            'fm_exam_starts_on' => ($this->exam_starts_on ? $this->exam_starts_on->format('d M - h:i A') : NULL),
            'fm_exam_start_date' => ($this->exam_starts_on ? $this->exam_starts_on->format('d/m/Y') : NULL),
            'fm_exam_start_time' => ($this->exam_starts_on ? $this->exam_starts_on->format('h:i A') : NULL),
            'exam_ends_on' => $this->exam_ends_on,
            'fm_exam_ends_on' => ($this->exam_ends_on ? $this->exam_ends_on->format('d M - h:i A') : NULL),
            'result_date_time' => $this->result_date,
            'result_date' => ($this->result_date ? $this->result_date->format('d/m/Y') : NULL),
            'result_time' => ($this->result_date ? $this->result_date->format('h:i A') : NULL),
            'total_questions' => $this->total_questions,
            'total_marks' => $this->total_marks,
            'lang_code' => $this->desc->lang_code,
            'exam_name' => $this->desc->exam_name,
            'category_image' => $this->getCategoryImage(),
            'prev_attempted' => $this->prev_attempted,
            'prev_completed_attempt' => $this->prev_completed_attempt,
            'exam_validity' => $this->getExamValidity(),
        ];
    }

    private function getExamValidity(){
        $exam_validity = 'PENDING';
        if($this->exams_type == 'LIVE_TEST'){
            $exam_validity = 'EXPIRED';
            if(strtotime($this->exam_starts_on) > time()){
                $exam_validity = 'FUTURE';
            }else {
                if(strtotime($this->exam_ends_on) > time()){
                    $exam_validity = 'RUNNING';
                }
            }

            if($this->prev_attempted){
                if($this->prev_attempted->attempt_status == 'COMPLETED' && strtotime($this->result_date) > time()){
                    $exam_validity = 'WAITING_FOR_RESULT';
                }
            }
        }else{
            if($this->prev_attempted){
                $exam_validity = $this->prev_attempted->attempt_status;
            }
        }

        return $exam_validity;
    }

    private function getCategoryImage(){
        $images_dirs = Config::get('siteglobal.images_dirs');
        $cat_icon_dir = $images_dirs['CATEGORY_ICON'];

        $cat_icon_img = "";
        if($this->category && $this->category->icon_img){
            $cat_icon_img = $cat_icon_dir . '/' . $this->category->icon_img;
        }

        //return Utilities::getThumbImage($cat_icon_img, 96, 96);
        return url('public' . Storage::url($cat_icon_img));
    }
}
