<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSavedItem extends Model
{
    protected $primaryKey = 'item_id';

    /* public function item()
    {
        switch($this->item_type){
            case 'ARTICLE':
                return $this->hasOne(ArticlesNews::class, 'item_type_id', 'articles_news_id')->where('item_type', 'ARTICLE');
            break;
            case 'BLOG':
                return $this->hasOne(BlogPost::class, 'item_type_id', 'blog_post_id')->where('item_type', 'BLOG');
            break;
            case 'QUESTION':
                return $this->hasOne(Question::class, 'item_type_id', 'questions_id')->where('item_type', 'QUESTION');
            break;
        }
    } */
}
