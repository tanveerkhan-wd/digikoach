<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\BlogCategory;
use App\Models\BlogPost;

use App\Http\Resources\Blog\BlogResource;
use App\Http\Resources\Blog\BlogCollection;
use App\Http\Resources\Blog\BlogPageCollection;
use App\Http\Resources\Blog\BlogCategoryCollection;

class BlogController extends Controller
{
    /**
     * Blog Listing
     *
     * @param  mixed $request
     * @return void
     */
    public function listing(Request $request)
    {
        $blog_categories = $request->categories;

        $objBlogs = BlogPost::query()->with('desc')->where('status', 1);
        if (is_array($blog_categories) && count($blog_categories)) {
            $objBlogs->whereIn('blog_category_id', $blog_categories);
        }

        $objBlogs->orderBy('created_at', 'DESC');

        $blogs = $objBlogs->paginate();

        return response()->json(new BlogPageCollection($blogs), 200, [], JSON_INVALID_UTF8_IGNORE);
    }

    /**
     * Latest Blogs
     *
     * @param  mixed $request
     * @return void
     */
    public function latest()
    {
        $objBlogs = BlogPost::query()->with('desc')->where('status', 1);
        $objBlogs->orderBy('created_at', 'DESC')->limit(3);

        $blogs = $objBlogs->get();

        return response()->json(new BlogCollection($blogs), 200, [], JSON_INVALID_UTF8_IGNORE);
    }

    /**
     * Blog Categories
     *
     * @param  mixed $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function categories(Request $request)
    {
        $blog_categories = BlogCategory::with('desc')->get();

        return response()->json(new BlogCategoryCollection($blog_categories), 200, [], JSON_INVALID_UTF8_IGNORE);
    }

    /**
     * Blog Detail
     *
     * @param  integar $blog_id
     * @param  mixed $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail($blog_id, Request $request)
    {
        $blog = BlogPost::with('desc')->where('blog_post_id', $blog_id)->first();

        if ($blog) {
            return response()->json(new BlogResource($blog), 200, [], JSON_INVALID_UTF8_IGNORE);
        } else {
            return $this->invalidRequest();
        }
    }

    private function invalidRequest()
    {
        return response()->json(['message' => trans('message.error.invalid_request')], 400, [], JSON_INVALID_UTF8_IGNORE);
    }
}
