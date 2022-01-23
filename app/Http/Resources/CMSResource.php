<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CMSResource extends JsonResource
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
            'cms_id' => $this->cms_id,
            'slug' => $this->slug,
            'lang_code' => $this->desc->lang_code,
            'cms_title' => $this->desc->cms_title,
            'cms_description' => $this->desc->cms_description,
        ];
    }
}
