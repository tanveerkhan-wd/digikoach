<?php

namespace App\Http\Resources\Blog;

use Illuminate\Http\Resources\Json\JsonResource;

use Utilities;
use Config;
use Storage;

class BlogResource extends JsonResource
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
            'blog_post_id' => $this->blog_post_id,
            'blog_category_id' => $this->blog_category_id,
            'blog_image' =>  $this->getBlogImage($this->blog_image),
            'blog_thumb_image' =>  $this->getBlogImage($this->blog_image),
            'slug' => $this->slug,
            'blog_post_title' => $this->desc->blog_post_title,
            'short_description' => $this->generate_short_desc($this->desc->description),
            //'description' => $this->desc->description,
            'lang_code' => $this->desc->lang_code,
            'blog_date' => ($this->created_at ? $this->created_at->format('d-m-Y') : NULL),
            'is_saved' => $this->is_saved,
        ];

        //return mb_convert_encoding($return_blog_values, 'UTF-8', 'UTF-8');
    }

    private function generate_short_desc($description, $length = 100){
        return substr(strip_tags(html_entity_decode($description)), 0, $length);
    }

    private function getBlogImage($blog_image){
        $images_dirs = Config::get('siteglobal.images_dirs');
        $blog_dir = $images_dirs['BLOG'];

        return Utilities::checkImageExists($blog_dir, $blog_image);
        //return url('public' . Storage::url($blog_dir . '/' . $blog_image));
    }

    private function generateBlogThumb($blog_image, $width=300, $height=150){
        $images_dirs = Config::get('siteglobal.images_dirs');
        $blog_dir = $images_dirs['BLOG'];

        return Utilities::getThumbImage($blog_dir . '/' . $blog_image, $width, $height);
    }
}
