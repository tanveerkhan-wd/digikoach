<?php

namespace App\Http\Resources\Blog;

use Illuminate\Http\Resources\Json\JsonResource;

class BlogCategoryResource extends JsonResource
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
            'blog_category_id' => $this->blog_category_id,
            'slug' => $this->slug,
            'blog_category_title' => $this->desc->blog_category_title,
            'description' => $this->desc->description,
            'lang_code' => $this->desc->lang_code,
        ];
    }
}
