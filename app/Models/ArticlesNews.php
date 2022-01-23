<?php

namespace App\Models;

use App;
use Illuminate\Database\Eloquent\Model;
use Auth;

class ArticlesNews extends Model
{
    protected $primaryKey = "articles_news_id";

    protected $appends = ['is_saved'];

    public function getIsSavedAttribute()
    {
        $is_saved_article = false;
        $user = Auth::guard('api')->user();

        if ($user) {
            $ojbSavedArticle = $this->hasOne(UserSavedItem::class, 'item_type_id', 'articles_news_id')->where('item_type', 'ARTICLE');
            $ojbSavedArticle->where('user_id', $user->user_id);
            $saved_article = $ojbSavedArticle->first();

            if ($saved_article) {
                if ((int)$saved_article->item_id > 0) {
                    $is_saved_article = true;
                }
            }
        }

        return $is_saved_article;
    }

    public function desc()
    {
        $language = App::getLocale();
        return $this->hasOne('App\Models\ArticlesNewsDesc', 'articles_news_id', 'articles_news_id')->where('lang_code', $language);
    }

    public function article_desc()
    {
        return $this->hasMany('App\Models\ArticlesNewsDesc', 'articles_news_id', 'articles_news_id');
    }
}
