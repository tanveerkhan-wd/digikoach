<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feature;
use App\Models\Setting;
use Storage;
use File;
use Config;

class FeatureController extends Controller
{
	/**
	 * Used for Admin feature
	 * @return redirect to Admin->feature
	 */
    public function index(Request $request)
    {
      $aData = [];
      // DATA 
      $feature = Setting::where('text_key','feature_heading')
              ->orWhere('text_key','feature_video_link')
              ->orWhere('text_key','feature_video_link')
              ->orWhere('text_key','feature_1_image')
              ->orWhere('text_key','feature_1_title')
              ->orWhere('text_key','feature_1_description')
              ->orWhere('text_key','feature_2_image')
              ->orWhere('text_key','feature_2_title')
              ->orWhere('text_key','feature_2_description')
              ->orWhere('text_key','feature_3_image')
              ->orWhere('text_key','feature_3_title')
              ->orWhere('text_key','feature_3_description')
              ->orWhere('text_key','feature_4_image')
              ->orWhere('text_key','feature_4_title')
              ->orWhere('text_key','feature_4_description')
              ->orWhere('text_key','feature_5_image')
              ->orWhere('text_key','feature_5_title')
              ->orWhere('text_key','feature_5_description')
              ->orWhere('text_key','feature_6_image')
              ->orWhere('text_key','feature_6_title')
              ->orWhere('text_key','feature_6_description')
              ->get();
      
      foreach ($feature as $value) {
          if($value->text_key=='feature_heading'){
              $aData['feature_heading'] = $value->text_value ? $value->text_value : '';
          }
          if($value->text_key=='feature_video_link'){
              $aData['feature_video_link'] = $value->text_value ? $value->text_value : '';
          }
          if($value->text_key=='feature_1_image'){
              $aData['feature_1_image'] =  $value->text_value ? $value->text_value : '';
          }
          if($value->text_key=='feature_1_title'){
              $aData['feature_1_title'] =  $value->text_value ? $value->text_value : '';
          }
          if($value->text_key=='feature_1_description'){
              $aData['feature_1_description'] =  $value->text_value ? $value->text_value : '';
          }

          if($value->text_key=='feature_2_image'){
              $aData['feature_2_image'] =  $value->text_value ? $value->text_value : '';
          }
          if($value->text_key=='feature_2_title'){
              $aData['feature_2_title'] =  $value->text_value ? $value->text_value : '';
          }
          if($value->text_key=='feature_2_description'){
              $aData['feature_2_description'] =  $value->text_value ? $value->text_value : '';
          }

          if($value->text_key=='feature_3_image'){
              $aData['feature_3_image'] =  $value->text_value ? $value->text_value : '';
          }
          if($value->text_key=='feature_3_title'){
              $aData['feature_3_title'] =  $value->text_value ? $value->text_value : '';
          }
          if($value->text_key=='feature_3_description'){
              $aData['feature_3_description'] =  $value->text_value ? $value->text_value : '';
          }

          if($value->text_key=='feature_4_image'){
              $aData['feature_4_image'] =  $value->text_value ? $value->text_value : '';
          }
          if($value->text_key=='feature_4_title'){
              $aData['feature_4_title'] =  $value->text_value ? $value->text_value : '';
          }
          if($value->text_key=='feature_4_description'){
              $aData['feature_4_description'] =  $value->text_value ? $value->text_value : '';
          }

          if($value->text_key=='feature_5_image'){
              $aData['feature_5_image'] =  $value->text_value ? $value->text_value : '';
          }
          if($value->text_key=='feature_5_title'){
              $aData['feature_5_title'] =  $value->text_value ? $value->text_value : '';
          }
          if($value->text_key=='feature_5_description'){
              $aData['feature_5_description'] =  $value->text_value ? $value->text_value : '';
          }

          if($value->text_key=='feature_6_image'){
              $aData['feature_6_image'] =  $value->text_value ? $value->text_value : '';
          }
          if($value->text_key=='feature_6_title'){
              $aData['feature_6_title'] =  $value->text_value ? $value->text_value : '';
          }
          if($value->text_key=='feature_6_description'){
              $aData['feature_6_description'] =  $value->text_value ? $value->text_value : '';
          }

      }
    	if (request()->ajax()) {
            return \View::make('admin.feature.index')->with(['aData' => $aData])->renderSections();
        }
    	 return view('admin.feature.index')->with(['aData' => $aData]);
    }

    /**
    * Used for update Admin Feature
    */
    public function updateFeature(Request $request)
    {
        $response = [];
        $input = $request->all();
        
        if($request->hasFile('image')){
            $get_pre_img = Setting::where('text_key','feature_1_image')->first();
            $gen_rand = rand(100,99999).time();
            $image_path = $request->file('image');
            $extension = $image_path->getClientOriginalExtension();
            Storage::disk('public')->put(Config::get('siteglobal.images_dirs.FEATURE').'/'.$gen_rand.'.'.$extension,  File::get($image_path));
            if(!empty($get_pre_img->text_value)){
                Storage::disk('public')->delete(Config::get('siteglobal.images_dirs.FEATURE').'/'.$get_pre_img->text_value);
            }
            $image1 = $gen_rand.'.'.$extension;
            $aTableData = Setting::where('text_key','feature_1_image')->update(['text_value'=>$image1]);
        }

        if($request->hasFile('image1')){
            $get_pre_img1 = Setting::where('text_key','feature_2_image')->first();
            $gen_rand = rand(100,99999).time();
            $image_path = $request->file('image1');
            $extension = $image_path->getClientOriginalExtension();
            Storage::disk('public')->put(Config::get('siteglobal.images_dirs.FEATURE').'/'.$gen_rand.'.'.$extension,  File::get($image_path));
            if(!empty($get_pre_img1->text_value)){
                Storage::disk('public')->delete(Config::get('siteglobal.images_dirs.FEATURE').'/'.$get_pre_img1->text_value);
            }
            $image2 = $gen_rand.'.'.$extension;
            $aTableData = Setting::where('text_key','feature_2_image')->update(['text_value'=>$image2]);
        }

        if($request->hasFile('image2')){
            $get_pre_img3 = Setting::where('text_key','feature_3_image')->first();
            $gen_rand = rand(100,99999).time();
            $image_path = $request->file('image2');
            $extension = $image_path->getClientOriginalExtension();
            Storage::disk('public')->put(Config::get('siteglobal.images_dirs.FEATURE').'/'.$gen_rand.'.'.$extension,  File::get($image_path));
            if(!empty($get_pre_img3->text_value)){
                Storage::disk('public')->delete(Config::get('siteglobal.images_dirs.FEATURE').'/'.$get_pre_img3->text_value);
            }
            $image3 = $gen_rand.'.'.$extension;
            $aTableData = Setting::where('text_key','feature_3_image')->update(['text_value'=>$image3]);
        }


        if($request->hasFile('image3')){
            $get_pre_img4 = Setting::where('text_key','feature_4_image')->first();
            $gen_rand = rand(100,99999).time();
            $image_path = $request->file('image3');
            $extension = $image_path->getClientOriginalExtension();
            Storage::disk('public')->put(Config::get('siteglobal.images_dirs.FEATURE').'/'.$gen_rand.'.'.$extension,  File::get($image_path));
            if(!empty($get_pre_img4->text_value)){
                Storage::disk('public')->delete(Config::get('siteglobal.images_dirs.FEATURE').'/'.$get_pre_img4->text_value);
            }
            $image4 = $gen_rand.'.'.$extension;
            $aTableData = Setting::where('text_key','feature_4_image')->update(['text_value'=>$image4]);
        }


        if($request->hasFile('image4')){
            $get_pre_img5 = Setting::where('text_key','feature_5_image')->first();
            $gen_rand = rand(100,99999).time();
            $image_path = $request->file('image4');
            $extension = $image_path->getClientOriginalExtension();
            Storage::disk('public')->put(Config::get('siteglobal.images_dirs.FEATURE').'/'.$gen_rand.'.'.$extension,  File::get($image_path));
            if(!empty($get_pre_img5->text_value)){
                Storage::disk('public')->delete(Config::get('siteglobal.images_dirs.FEATURE').'/'.$get_pre_img5->text_value);
            }
            $image5 = $gen_rand.'.'.$extension;
            $aTableData = Setting::where('text_key','feature_5_image')->update(['text_value'=>$image5]);
        }


        if($request->hasFile('image5')){
            $get_pre_img6 = Setting::where('text_key','feature_6_image')->first();
            $gen_rand = rand(100,99999).time();
            $image_path = $request->file('image5');
            $extension = $image_path->getClientOriginalExtension();
            Storage::disk('public')->put(Config::get('siteglobal.images_dirs.FEATURE').'/'.$gen_rand.'.'.$extension,  File::get($image_path));
            if(!empty($get_pre_img6->text_value)){
                Storage::disk('public')->delete(Config::get('siteglobal.images_dirs.FEATURE').'/'.$get_pre_img6->text_value);
            }
            $image6 = $gen_rand.'.'.$extension;
            $aTableData = Setting::where('text_key','feature_6_image')->update(['text_value'=>$image6]);
        }
        
        if ($input['feature_heading']) {
            $feature_heading = $input['feature_heading'];
            $aTableData = Setting::where('text_key','feature_heading')->update(['text_value'=>$feature_heading]);
        }
        if ($input['video_link']) {
            $video_link = $input['video_link'];
            $aTableData = Setting::where('text_key','feature_video_link')->update(['text_value'=>$video_link]);
        }


        if ($input['title']) {
            $title = $input['title'];
            $aTableData = Setting::where('text_key','feature_1_title')->update(['text_value'=>$title]);
        }
        if ($input['title1']) {
            $title1 = $input['title1'];
            $aTableData = Setting::where('text_key','feature_2_title')->update(['text_value'=>$title1]);
        }
        if ($input['title2']) {
            $title2 = $input['title2'];
            $aTableData = Setting::where('text_key','feature_3_title')->update(['text_value'=>$title2]);
        }
        if ($input['title3']) {
            $title3 = $input['title3'];
            $aTableData = Setting::where('text_key','feature_4_title')->update(['text_value'=>$title3]);
        }
        if ($input['title4']) {
            $title4 = $input['title4'];
            $aTableData = Setting::where('text_key','feature_5_title')->update(['text_value'=>$title4]);
        }
        if ($input['title5']) {
            $title5 = $input['title5'];
            $aTableData = Setting::where('text_key','feature_6_title')->update(['text_value'=>$title5]);
        }

        if ($input['description']) {
            $description = $input['description'];
            $aTableData = Setting::where('text_key','feature_1_description')->update(['text_value'=>$description]);
        }
        if ($input['description1']) {
            $description1 = $input['description1'];
            $aTableData = Setting::where('text_key','feature_2_description')->update(['text_value'=>$description1]);
        }
        if ($input['description2']) {
            $description2 = $input['description2'];
            $aTableData = Setting::where('text_key','feature_3_description')->update(['text_value'=>$description2]);
        }
        if ($input['description3']) {
            $description3 = $input['description3'];
            $aTableData = Setting::where('text_key','feature_4_description')->update(['text_value'=>$description3]);
        }
        if ($input['description4']) {
            $description4 = $input['description4'];
            $aTableData = Setting::where('text_key','feature_5_description')->update(['text_value'=>$description4]);
        }
        if ($input['description5']) {
            $description5 = $input['description5'];
            $aTableData = Setting::where('text_key','feature_6_description')->update(['text_value'=>$description5]);
        }

        $response['status'] = true;
        $response['message'] = "Feature Successfully updated";

        return response()->json($response);
 

    }
}
