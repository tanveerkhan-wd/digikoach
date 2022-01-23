<?php

namespace App\Http\Resources\Articles;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
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
            'articles_news_id' => $this->articles_news_id,
            'lang_code' => $this->desc->lang_code,
            'article_title' => $this->desc->article_title,
            'short_article_body' => $this->generate_short_desc($this->desc->article_body),
            'article_body' => $this->desc->article_body,
            'created_at' => ($this->created_at ? $this->created_at->format('d/m/Y') : NULL),
            'is_saved' => $this->is_saved,
        ];
    }

    private function generate_short_desc($description, $length = 100)
    {
        return substr(strip_tags(html_entity_decode($description)), 0, $length);
    }
}
