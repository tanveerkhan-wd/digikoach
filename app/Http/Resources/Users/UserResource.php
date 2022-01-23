<?php

namespace App\Http\Resources\Users;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\Exam\ExamResource;
use Config;
use Storage;
use Utilities;

class UserResource extends JsonResource
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
            'user_id' => $this->user_id,
            'name' => $this->name,
            'email' => $this->email,
            'mobile_number' => $this->mobile_number,
            'user_photo' => $this->user_photo,
            'user_thumb_photo' => $this->user_thumb_photo(),
            'user_fav_category' => $this->user_fav_category,
            'user_lang_code' => $this->user_lang_code,
        ];
    }

    private function user_thumb_photo(){
        $user_photo_thumb = '';
        if (!empty($this->user_photo)) {
            $images_dirs = Config::get('siteglobal.images_dirs');
            $user_dir = $images_dirs['USERS'];

            //$user->user_photo_thumb = Utilities::getThumbImage($user_dir . $user->user_photo, 150, 150);
            //$user_photo_thumb = url('public' . Storage::url($user_dir . $this->user_photo));
            return Utilities::checkImageExists($user_dir, $this->user_photo);
            
        }

        return $user_photo_thumb;
    }
}
