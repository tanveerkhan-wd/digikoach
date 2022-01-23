<?php

namespace App\Http\Resources\Category;

use Config;
use Illuminate\Http\Resources\Json\JsonResource;

use Utilities;
use Storage;
use App\Models\Category as CategoryModel;

class Category extends JsonResource
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
        $categories_values = [
            'id' => $this->category_id,
            'parent_category' => $this->parent_category,
            'icon_img' => $this->generateCatThumb(),
            'lang_code' => $this->desc->lang_code,
            'name' => $this->desc->name,
            'nested_name' => $this->nested_cat_name($this->parent_category, $this->desc->name),
            'total_child' => $this->totalChild()
        ];

        return $categories_values;
    }

    private function totalChild(){
        return CategoryModel::where('parent_category', $this->category_id)->count();
    }

    private function nested_cat_name($parent_id, $cat_name){
        $nested_cat_name = "";
        if($parent_id != 0){
            $parent_cat = CategoryModel::where('category_id', $parent_id)->first();
            if($parent_id != '0'){
                $nested_cat_name .= $this->nested_cat_name($parent_cat->parent_category, $parent_cat->desc->name);
            }
        }

        if($nested_cat_name != "")$nested_cat_name .= " > ";
        $nested_cat_name .= $cat_name;

        return $nested_cat_name;
    }

    private function generateCatThumb(){
        $images_dirs = Config::get('siteglobal.images_dirs');
        $cat_icon_dir = $images_dirs['CATEGORY_ICON'];

        $cat_icon_img = "";
        if($this->icon_img){
            $cat_icon_img = $cat_icon_dir . '/' . $this->icon_img;
        }

        //return Utilities::getThumbImage($cat_icon_img, 96, 96);
        return url('public' . Storage::url($cat_icon_img));
    }
}
