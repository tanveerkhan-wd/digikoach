<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\CMSResource;
use App\Models\Cms;

class CMSController extends Controller
{

    /**
     * Get CMS Info
     *
     * @param  string $slug
     * @param  mixed $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCMSInfo($slug, Request $request)
    {
        $cms_info = Cms::with('desc')->where('slug', $slug)->first();
        if ($cms_info) {
            return response()->json(new CMSResource($cms_info), 200, [], JSON_INVALID_UTF8_IGNORE);
        } else {
            return $this->invalidRequest();
        }
    }

    private function invalidRequest()
    {
        return response()->json(['message' => trans('message.error.invalid_request')], 400, [], JSON_INVALID_UTF8_IGNORE);
    }
}
