<?php

namespace App\Http\Resources\Banner;

use Illuminate\Http\Resources\Json\JsonResource;
use Utilities;
use Config;
use Storage;

class BannerResource extends JsonResource
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
            'banner_id' => $this->banner_id,
            'banner_thumb_image' =>  $this->getBannerImage($this->desc->banner_file),
            'lang_code' => $this->desc->lang_code,
        ];
    }

    private function getBannerImage($banner_image){
        $images_dirs = Config::get('siteglobal.images_dirs');
        $banner_dir = $images_dirs['BANNER'];

        return url('public' . Storage::url($banner_dir . '/' . $banner_image));
    }

    private function generateBannerThumb($banner_image){
        $images_dirs = Config::get('siteglobal.images_dirs');
        $banner_dir = $images_dirs['BANNER'];

        return Utilities::getThumbImage($banner_dir . '/' . $banner_image, 400, 200);
    }
}
