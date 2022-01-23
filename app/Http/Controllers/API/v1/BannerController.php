<?php

namespace App\Http\Controllers\API\v1;

use App;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\Banner\BannerCollection;

use App\Models\Banner;


class BannerController extends Controller
{    
    /**
     * Banner listing
     *
     * @param  mixed $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listing(Request $request)
    {
        $banners = Banner::with('desc')->orderBy('sequence', 'ASC')->get();
        
        return response()->json(new BannerCollection($banners), 200, [], JSON_INVALID_UTF8_IGNORE);
    }
}
