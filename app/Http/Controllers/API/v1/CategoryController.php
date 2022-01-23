<?php

namespace App\Http\Controllers\API\v1;

use App;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\Category\CategoryCollection;
use App\Http\Resources\Category\Category as CategoryResource;

use App\Models\Category;

class CategoryController extends Controller
{

    public function index(Request $request)
    {
        $language = App::getLocale();
        $parent_id = 0;

        $objCategory = Category::query()->join('categories_descs', 'categories_descs.category_id', '=', 'categories.category_id');
        $objCategory->where('categories.parent_category', $parent_id)->where('categories.status', 1)->where('categories_descs.lang_code', $language);

        if ($request->keywords) {
            $objCategory->where('categories_descs.name', 'like', '%' . $request->keywords . '%');
        }

        $categories = $objCategory->get();

        return response()->json(new CategoryCollection($categories), 200, [], JSON_INVALID_UTF8_IGNORE);
    }

    public function subCategories($parent_id, Request $request)
    {
        $language = App::getLocale();

        $objCategory = Category::query()->join('categories_descs', 'categories_descs.category_id', '=', 'categories.category_id');
        $objCategory->where('categories.parent_category', $parent_id)->where('categories.status', 1)->where('categories_descs.lang_code', $language);

        if ($request->keywords) {
            $objCategory->where('categories_descs.name', 'like', '%' . $request->keywords . '%');
        }

        $categories = $objCategory->get();

        return response()->json(new CategoryCollection($categories), 200, [], JSON_INVALID_UTF8_IGNORE);
    }

    public function getSearchCategories(Request $request)
    {
        $language = App::getLocale();

        if ($request->keywords) {
            $objCategory = Category::query()->join('categories_descs', 'categories_descs.category_id', '=', 'categories.category_id');
            $objCategory->where('categories.status', 1)->where('categories_descs.lang_code', $language);

            $objCategory->where('categories_descs.name', 'like', '%' . $request->keywords . '%');

            $categories = $objCategory->get();

            return response()->json(new CategoryCollection($categories), 200, [], JSON_INVALID_UTF8_IGNORE);
        }

        return response()->json(['message' => trans('message.error.no_keywords')], 400, [], JSON_INVALID_UTF8_IGNORE);
    }

    public function getCategory($category_id)
    {
        $category = Category::with('desc')->where('category_id', $category_id)->where('categories.status', 1)->first();

        return response()->json(new CategoryResource($category), 200, [], JSON_INVALID_UTF8_IGNORE);
    }
}
