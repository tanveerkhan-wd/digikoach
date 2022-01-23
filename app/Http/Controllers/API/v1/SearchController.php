<?php

namespace App\Http\Controllers\API\v1;

use App;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\Exam\ExamPageCollection;
use App\Http\Resources\Articles\ArticlePageCollection;
use App\Http\Resources\Blog\BlogPageCollection;
use App\Http\Resources\Doubts\Doubt\DoubtPageCollectionResouce;

use App\Models\Exam;
use App\Models\ArticlesNews;
use App\Models\BlogPost;
use App\Models\Doubt;
use App\Models\UserCategory;
use App\Models\UserAttempt;
use App\Models\Category;

use Auth;
use DB;

class SearchController extends Controller
{
    /**
     * Banner listing
     *
     * @param  mixed $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search($search_type, Request $request)
    {
        $user = Auth::user();

        $keywords = $request->get('keywords');
        $search_type = strtoupper($search_type);

        //User Level and Sub Levles
        $user_levels = UserCategory::where('user_id', $user->user_id)->get()->pluck('category_id');
        $categories = [];
        if ($user_levels && count($user_levels)) {
            foreach ($user_levels as $category_id) {
                $parent_categories = $this->get_parent_categories($category_id);
                $child_categories = $this->get_child_categories($category_id);
                
                $categories = array_merge($categories, $parent_categories, $child_categories);
            }
        }

        $user_categories = array_unique($categories);

        //$user_level = $user->user_fav_category;

        switch ($search_type) {
            case 'LIVE_TEST':
            case 'QUIZZES':
            case 'GK_CA':
                //DB::enableQueryLog();
                $exams_query = Exam::query()->with(['category'])->where('status', 1);
                $exams_query->where('exams_type', $search_type);

                $exams_query->whereHas('desc', function ($query) use ($keywords) {
                    $query->where('exam_name', 'like', '%' . $keywords . '%');
                });

                if ($search_type == 'LIVE_TEST') {
                    $user_exam_attempted = UserAttempt::select('exam_id')->where('user_id', $user->user_id)->whereIn('attempt_status', ['COMPLETED'])->where('exams_type', $search_type)->get()->toArray();

                    if (is_array($user_exam_attempted) && count($user_exam_attempted)) {
                        $exams_query->whereNotIn('exam_id', $user_exam_attempted);
                    }
                    $exams_query->where('exam_ends_on', '>', date("Y-m-d H:i:s"));
                }

                if ($search_type == 'LIVE_TEST' || $search_type == 'QUIZZES') {
                    $exams_query->whereIn('category_id', $user_categories);
                }

                if ($search_type == 'LIVE_TEST') {
                    $exams_query->orderBy('exam_starts_on', 'ASC');
                } else {
                    $exams_query->orderBy('created_at', 'DESC');
                }

                $exams = $exams_query->paginate(10);
                //dd(DB::getQueryLog());
                return response()->json(new ExamPageCollection($exams), 200, [], JSON_INVALID_UTF8_IGNORE);
                break;
            case 'ARTICLES':
                $articles = ArticlesNews::whereHas('desc', function ($query) use ($keywords) {
                    $query->where(function ($q) use ($keywords) {
                        $q->where('article_title', 'like', '%' . $keywords . '%')->orWhere('article_body', 'like', '%' . $keywords . '%');
                    });
                })->where('status', 1)->orderBy('created_at', 'DESC')->paginate();
                return response()->json(new ArticlePageCollection($articles), 200, [], JSON_INVALID_UTF8_IGNORE);
                break;
            case 'BLOGS':
                $objBlogs = BlogPost::query()->whereHas('desc', function ($query) use ($keywords) {
                    $query->where(function ($q) use ($keywords) {
                        $q->where('blog_post_title', 'like', '%' . $keywords . '%')->orWhere('description', 'like', '%' . $keywords . '%');
                    });
                })->where('status', 1);
                $objBlogs->orderBy('created_at', 'DESC');

                $blogs = $objBlogs->paginate();

                return response()->json(new BlogPageCollection($blogs), 200, [], JSON_INVALID_UTF8_IGNORE);
                break;
            case 'DOUBTS':
                $doubts_query = Doubt::with(['category', 'user'])->where('doubt_text', 'like', '%' . $request->keywords . '%')->where('status', 1);

                $doubts = $doubts_query->orderBy('created_at', 'DESC')->paginate();

                return response()->json(new DoubtPageCollectionResouce($doubts), 200, [], JSON_INVALID_UTF8_IGNORE);
                break;
        }

        //return response()->json(new BannerCollection([]), 200, [], JSON_INVALID_UTF8_IGNORE);
    }

    private function get_parent_categories($category_id)
    {
        $categories = [];
        $category = Category::where('category_id', $category_id)->first();

        if ($category && $category->parent_category != 0) {
            $categories = $this->get_parent_categories($category->parent_category);
        }

        $categories[] = $category_id;

        return $categories;
    }

    private function get_child_categories($category_id)
    {
        $categories = [];
        $child_category = Category::where('parent_category', $category_id)->first();

        if ($child_category) {
            $categories = $this->get_child_categories($child_category->category_id);
            $categories[] = $child_category->category_id;
        }

        return $categories;
    }
}
