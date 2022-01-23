<?php

namespace App\Http\Resources\UserSavedItems;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\Articles\ArticleResource;
use App\Http\Resources\Blog\BlogResource;
use App\Http\Resources\Question\QuestionResource;
use App\Http\Resources\Doubts\Doubt\DoubtResource;

use App\Models\ArticlesNews;
use App\Models\BlogPost;
use App\Models\Question;
use App\Models\Doubt;

class UserSavedItemResource extends JsonResource
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
            'item_id' => $this->item_id,
            'user_id' => $this->user_id,
            'item_type' => $this->item_type,
            'item_type_id' => $this->item_type_id,
            'item' => $this->item_detail(),
        ];
    }

    private function item_detail()
    {
        switch($this->item_type){
            case 'ARTICLE':
                $item = ArticlesNews::where('articles_news_id', $this->item_type_id)->first();
                return new ArticleResource($item);
            break;
            case 'BLOG':
                $item = BlogPost::where('blog_post_id', $this->item_type_id)->first();
                return new BlogResource($item);
            break;
            case 'QUESTION':
                $item = Question::where('questions_id', $this->item_type_id)->first();
                return new QuestionResource($item);
            break;
            case 'DOUBT':
                $item = Doubt::where('doubt_id', $this->item_type_id)->first();
                return new DoubtResource($item);
            break;
        }
    }
}
