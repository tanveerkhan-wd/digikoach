<?php

namespace App\Http\Controllers\API\v1;

use App;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\Articles\ArticleCollection;
use App\Http\Resources\Articles\ArticlePageCollection;
use App\Http\Resources\Articles\ArticleResource;

use App\Models\ArticlesNews;


class ArticleNewsController extends Controller
{
    /**
     * Banner listing
     *
     * @param  mixed $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listing(Request $request)
    {
        $articles = ArticlesNews::with('desc')->where('status', 1)->orderBy('created_at', 'DESC')->paginate();

        return response()->json(new ArticlePageCollection($articles), 200, [], JSON_INVALID_UTF8_IGNORE);
    }

    public function latest()
    {
        $latest_articles = ArticlesNews::with('desc')->where('status', 1)->orderBy('created_at', 'DESC')->limit(3)->get();
        return response()->json(new ArticleCollection($latest_articles), 200, [], JSON_INVALID_UTF8_IGNORE);
    }

    public function detail($articles_news_id)
    {
        $article = ArticlesNews::with('desc')->where('articles_news_id', $articles_news_id)->first();
        if ($article) {
            return response()->json(new ArticleResource($article), 200, [], JSON_INVALID_UTF8_IGNORE);
        }

        return response()->json(['message' => trans('message.error.invalid_request')], 400, [], JSON_INVALID_UTF8_IGNORE);
    }
}
