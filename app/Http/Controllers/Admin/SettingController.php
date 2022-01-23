<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Storage;
use File;
use Settings;
use Config;

class SettingController extends Controller
{
    /**
	 * Used for Admin Setting
	 * @return redirect to Admin->Setting
	 */

	public function index(Request $request)
    {
        /*=== About us Data ===*/
        $setting['about']['image'] = Settings::get('about_us_image');
        $setting['about']['title'] = Settings::get('about_us_title');
        $setting['about']['tag_line'] = Settings::get('about_us_tag_line');
        
        /*=== SEO Data ===*/
        $setting['seo']['title'] = Settings::get('setting_seo_title');
        $setting['seo']['desc'] = Settings::get('setting_seo_description');

        /*=== Header Data ===*/
        $setting['head']['logo'] = Settings::get('website_header_logo');        
        $setting['head']['banner'] = Settings::get('website_header_banner_img');        
        $setting['head']['line1'] = Settings::get('website_header_first_tag_line');     
        $setting['head']['line2'] = Settings::get('website_header_second_tag_line');

        /*=== Download Links ===*/
        $setting['down_link']['image'] = Settings::get('download_link_image');      
        $setting['down_link']['head'] = Settings::get('download_link_heading');     
        $setting['down_link']['text'] = Settings::get('download_link_text');        
        $setting['down_link']['ps_icon'] = Settings::get('download_link_play_store_icon');      
        $setting['down_link']['ps_link'] = Settings::get('download_link_play_store_link');      
        $setting['down_link']['as_icon'] = Settings::get('download_link_app_store_icon');       
        $setting['down_link']['as_link'] = Settings::get('download_link_app_store_link');

        /*=== Feature ===*/
        $setting['feature']['head'] = Settings::get('feature_heading');
        $setting['feature']['vi_link'] = Settings::get('feature_video_link');

        $setting['feature']['img1'] = Settings::get('feature_1_image');
        $setting['feature']['title1'] = Settings::get('feature_1_title');
        $setting['feature']['desc1'] = Settings::get('feature_1_description');

        $setting['feature']['img2'] = Settings::get('feature_2_image');
        $setting['feature']['title2'] = Settings::get('feature_2_title');
        $setting['feature']['desc2'] = Settings::get('feature_2_description');

        $setting['feature']['img3'] = Settings::get('feature_3_image');
        $setting['feature']['title3'] = Settings::get('feature_3_title');
        $setting['feature']['desc3'] = Settings::get('feature_3_description');

        $setting['feature']['img4'] = Settings::get('feature_4_image');
        $setting['feature']['title4'] = Settings::get('feature_4_title');
        $setting['feature']['desc4'] = Settings::get('feature_4_description');

        $setting['feature']['img5'] = Settings::get('feature_5_image');
        $setting['feature']['title5'] = Settings::get('feature_5_title');
        $setting['feature']['desc5'] = Settings::get('feature_5_description');

        $setting['feature']['img6'] = Settings::get('feature_6_image');
        $setting['feature']['title6'] = Settings::get('feature_6_title');
        $setting['feature']['desc6'] = Settings::get('feature_6_description');

        /*=== Contact Data ===*/
        $setting['contact']['address'] = Settings::get('contact_address');
        $setting['contact']['phone'] = Settings::get('contact_phone');
        $setting['contact']['email'] = Settings::get('contact_email');

        /*$ab_Data = [];
        $get_wh_Data = [];
        $get_dl_Data = [];
        $seo_Data  = [];*/
        
        //WEBSITE ABOUT DATA 
        /*$about = Setting::where('text_key','about_us_image')
                ->orWhere('text_key','about_us_title')
                ->orWhere('text_key','about_us_tag_line')->get();
        
        foreach ($about as $ab_value) {
            if($ab_value->text_key=='about_us_image'){
                $ab_Data['ab_image'] = $ab_value->text_value ? $ab_value->text_value : '';
            }
            if($ab_value->text_key=='about_us_title'){
                $ab_Data['ab_title'] = $ab_value->text_value ? $ab_value->text_value : '';
            }
            if($ab_value->text_key=='about_us_tag_line'){
                $ab_Data['ab_tag_line'] =  $ab_value->text_value ? $ab_value->text_value : '';
            }
        }*/
        //WEBSITE HEADER DATA 
        /*$wh_Data = Setting::where('text_key','website_header_logo')
                ->orWhere('text_key','website_header_banner_img')
                ->orWhere('text_key','website_header_first_tag_line')
                ->orWhere('text_key','website_header_second_tag_line')->get();

        foreach ($wh_Data as $value) {
            if($value->text_key=='website_header_logo'){
                $get_wh_Data['wh_logo'] = $value->text_value ? $value->text_value : '';
            }
            if($value->text_key=='website_header_banner_img'){
                $get_wh_Data['wh_banner_img'] = $value->text_value ? $value->text_value : '';
            }
            if($value->text_key=='website_header_first_tag_line'){
                $get_wh_Data['wh_first_tag_line'] =  $value->text_value ? $value->text_value : '';
            }
            if($value->text_key=='website_header_second_tag_line'){
                $get_wh_Data['wh_second_tag_line'] =  $value->text_value ? $value->text_value : '';
            }
        }*/

        //DOWNLOAD LINKS DATA
        /*$dl_data = Setting::where('text_key','download_link_image')
                ->orWhere('text_key','download_link_heading')
                ->orWhere('text_key','download_link_text')
                ->orWhere('text_key','download_link_play_store_icon')
                ->orWhere('text_key','download_link_play_store_link')
                ->orWhere('text_key','download_link_app_store_icon')
                ->orWhere('text_key','download_link_app_store_link')->get();

        foreach ($dl_data as $dl_value) {
            if($dl_value->text_key=='download_link_image'){
                $get_dl_Data['dl_image'] = $dl_value->text_value ? $dl_value->text_value : '';
            }
            if($dl_value->text_key=='download_link_heading'){
                $get_dl_Data['dl_heading'] = $dl_value->text_value ? $dl_value->text_value : '';
            }
            if($dl_value->text_key=='download_link_text'){
                $get_dl_Data['dl_text'] =  $dl_value->text_value ? $dl_value->text_value : '';
            }
            if($dl_value->text_key=='download_link_play_store_icon'){
                $get_dl_Data['dl_gpsi'] =  $dl_value->text_value ? $dl_value->text_value : '';
            }
            if($dl_value->text_key=='download_link_play_store_link'){
                $get_dl_Data['dl_gpsl'] =  $dl_value->text_value ? $dl_value->text_value : '';
            }
            if($dl_value->text_key=='download_link_app_store_icon'){
                $get_dl_Data['dl_asi'] =  $dl_value->text_value ? $dl_value->text_value : '';
            }
            if($dl_value->text_key=='download_link_app_store_link'){
                $get_dl_Data['dl_asl'] =  $dl_value->text_value ? $dl_value->text_value : '';
            }
        }*/

        //SEO DATA
        /*$seo_data =Setting::where('text_key','setting_seo_title')
                ->orWhere('text_key','setting_seo_description')->get();
        
        foreach($seo_data as $seo_value) {
            if($seo_value->text_key=='setting_seo_title'){
                $seo_Data['seo_title'] = $seo_value->text_value ? $seo_value->text_value : '';
            }
            if($seo_value->text_key=='setting_seo_description'){
                $seo_Data['seo_description'] = $seo_value->text_value ? $seo_value->text_value : '';
            }
        }*/

        //Test Rules Text
        /*$test_rules =Setting::where('text_key','test_rule_live_test_hi')
                ->orWhere('text_key','test_rule_live_test_en')->orWhere('text_key','test_rule_quizzes_test_hi')->orWhere('text_key','test_rule_quizzes_test_en')->get();
        foreach ($test_rules as $test_rule_value) {
            if($test_rule_value->text_key=='test_rule_live_test_hi'){
                $test_rule_Data['live_test_hi'] = $test_rule_value->text_value ? $test_rule_value->text_value : '';
            }
            if($test_rule_value->text_key=='test_rule_live_test_en'){
                $test_rule_Data['live_test_en'] = $test_rule_value->text_value ? $test_rule_value->text_value : '';
            }
            if($test_rule_value->text_key=='test_rule_quizzes_test_hi'){
                $test_rule_Data['quizzes_test_hi'] = $test_rule_value->text_value ? $test_rule_value->text_value : '';
            }
            if($test_rule_value->text_key=='test_rule_quizzes_test_en'){
                $test_rule_Data['quizzes_test_en'] = $test_rule_value->text_value ? $test_rule_value->text_value : '';
            }
        }*/
        

        /*$contact_data = Setting::where('text_key','contact_address')->orWhere('text_key','contact_phone')->orWhere('text_key','contact_email')->get();
        
    	if (request()->ajax()) {
            return \View::make('admin.setting.index')->with(['test_rule_Data'=>$test_rule_Data,'ab_Data'=>$ab_Data,'get_wh_Data'=>$get_wh_Data,'get_dl_Data'=>$get_dl_Data,'seo_Data'=>$seo_Data,'contact_data'=>$contact_data])->renderSections();
        }*/
    	/*return view('admin.setting.index')->with(['test_rule_Data'=>$test_rule_Data,'ab_Data'=>$ab_Data,'get_wh_Data'=>$get_wh_Data,'get_dl_Data'=>$get_dl_Data,'seo_Data'=>$seo_Data,'contact_data'=>$contact_data]);*/
        return view('admin.setting.index')->with(['setting'=>$setting]);
    }


    /**
     * Used for Admin edit Header
     * @return redirect to Admin->edit Header
     */
    public function editHeader(Request $request)
    {
        $input = $request->all();
        $aTableData = '';
    
        if($request->hasFile('logo')){
            $get_pre_img = Setting::where('text_key','website_header_logo')->first();
            $gen_rand = rand(100,99999).time();
            $image_path = $request->file('logo');
            $extension = $image_path->getClientOriginalExtension();
            Storage::disk('public')->put(Config::get('siteglobal.images_dirs.SETTING').'/'.$gen_rand.'.'.$extension,  File::get($image_path));
            if(!empty($get_pre_img->text_value)){
                Storage::disk('public')->delete(Config::get('siteglobal.images_dirs.SETTING').'/'.$get_pre_img->text_value);
            }
            $logo_image = $gen_rand.'.'.$extension;
            $aTableData = Setting::where('text_key','website_header_logo')->update(['text_value'=>$logo_image]);
        }
    
        if($request->hasFile('banner')){
            $get_pre_img1 = Setting::where('text_key','website_header_banner_img')->first();
            $gen_rand = rand(100,99999).time();
            $image_path = $request->file('banner');
            $extension = $image_path->getClientOriginalExtension();
            Storage::disk('public')->put(Config::get('siteglobal.images_dirs.SETTING').'/'.$gen_rand.'.'.$extension,  File::get($image_path));
            if(!empty($get_pre_img1->text_value)){
                Storage::disk('public')->delete(Config::get('siteglobal.images_dirs.SETTING').'/'.$get_pre_img1->text_value);
            }
            $banner_image = $gen_rand.'.'.$extension;
            $aTableData = Setting::where('text_key','website_header_banner_img')->update(['text_value'=>$banner_image]);
        }

        if ($input['first_tag_line']) {
            $first_tag_line = $input['first_tag_line'];
            $aTableData = Setting::where('text_key','website_header_first_tag_line')->update(['text_value'=>$first_tag_line]);   
        }

        if ($input['second_tag_line']) {
            $second_tag_line = $input['second_tag_line'];
            $aTableData = Setting::where('text_key','website_header_second_tag_line')->update(['text_value'=>$second_tag_line]);  
        }

        $response['status'] = true;
        $response['message'] = "Website Header Successfully Updated";

        return response()->json($response);
    }


    /**
     * Used for Admin edit About
     * @return redirect to Admin->edit About
     */
    public function editAbout(Request $request)
    {
        $input = $request->all();
        $aTableData = '';
        
        if($request->hasFile('ab_image')){
            $get_pre_img = Setting::where('text_key','about_us_image')->first();
            $gen_rand = rand(100,99999).time();
            $image_path = $request->file('ab_image');
            $extension = $image_path->getClientOriginalExtension();
            Storage::disk('public')->put(Config::get('siteglobal.images_dirs.SETTING').'/'.$gen_rand.'.'.$extension,  File::get($image_path));
            if(!empty($get_pre_img->text_value)){
                Storage::disk('public')->delete(Config::get('siteglobal.images_dirs.SETTING').'/'.$get_pre_img->text_value);
            }
            $ab_image = $gen_rand.'.'.$extension;
            $aTableData = Setting::where('text_key','about_us_image')->update(['text_value'=>$ab_image]);
        }

        if ($input['ab_title']) {
            $ab_title = $input['ab_title'];
            $aTableData = Setting::where('text_key','about_us_title')->update(['text_value'=>$ab_title]);   
        }

        if ($input['ab_second_tag_line']) {
            $ab_tag_line = $input['ab_second_tag_line'];
            $aTableData = Setting::where('text_key','about_us_tag_line')->update(['text_value'=>$ab_tag_line]);   
        }

          
        $response['status'] = true;
        $response['message'] = "About Page Successfully Updated";
          
        return response()->json($response);
    }


    /**
     * Used for Admin edit downlod_link
     * @return redirect to Admin->edit downlod_link
     */
    public function editDownloadLink(Request $request)
    {
        $input = $request->all();
        $aTableData = '';
        
        if($request->hasFile('image')){
            $get_pre_img = Setting::where('text_key','download_link_image')->first();
            $gen_rand = rand(100,99999).time();
            $image_path = $request->file('image');
            $extension = $image_path->getClientOriginalExtension();
            Storage::disk('public')->put(Config::get('siteglobal.images_dirs.SETTING').'/'.$gen_rand.'.'.$extension,  File::get($image_path));
            if(!empty($get_pre_img->text_value)){
                Storage::disk('public')->delete(Config::get('siteglobal.images_dirs.SETTING').'/'.$get_pre_img->text_value);
            }
            $dl_image = $gen_rand.'.'.$extension;
            $aTableData = Setting::where('text_key','download_link_image')->update(['text_value'=>$dl_image]);
        }
        if($request->hasFile('play_store_icon')){
            $get_pre_img = Setting::where('text_key','download_link_play_store_icon')->first();
            $gen_rand = rand(100,99999).time();
            $image_path = $request->file('play_store_icon');
            $extension = $image_path->getClientOriginalExtension();
            Storage::disk('public')->put(Config::get('siteglobal.images_dirs.SETTING').'/'.$gen_rand.'.'.$extension,  File::get($image_path));
            if(!empty($get_pre_img->text_value)){
                Storage::disk('public')->delete(Config::get('siteglobal.images_dirs.SETTING').'/'.$get_pre_img->text_value);
            }
            $dl_image1 = $gen_rand.'.'.$extension;
            $aTableData = Setting::where('text_key','download_link_play_store_icon')->update(['text_value'=>$dl_image1]);
        }

        if($request->hasFile('app_store_icon')){
            $get_pre_img = Setting::where('text_key','download_link_app_store_icon')->first();
            $gen_rand = rand(100,99999).time();
            $image_path = $request->file('app_store_icon');
            $extension = $image_path->getClientOriginalExtension();
            Storage::disk('public')->put(Config::get('siteglobal.images_dirs.SETTING').'/'.$gen_rand.'.'.$extension,  File::get($image_path));
            if(!empty($get_pre_img->text_value)){
                Storage::disk('public')->delete(Config::get('siteglobal.images_dirs.SETTING').'/'.$get_pre_img->text_value);
            }
            $dl_image2 = $gen_rand.'.'.$extension;
            $aTableData = Setting::where('text_key','download_link_app_store_icon')->update(['text_value'=>$dl_image2]);
        }
        
        if ($input['dl_heading']) {
            $dl_heading = $input['dl_heading'];
            $aTableData = Setting::where('text_key','download_link_heading')->update(['text_value'=>$dl_heading]);   
        }
        if ($input['dl_text']) {
            $dl_text = $input['dl_text'];
            $aTableData = Setting::where('text_key','download_link_text')->update(['text_value'=>$dl_text]);   
        }
        if ($input['gps_link']) {
            $gps_link = $input['gps_link'];
            $aTableData = Setting::where('text_key','download_link_play_store_link')->update(['text_value'=>$gps_link]);   
        }
        if ($input['app_store_link']) {
            $app_store_link = $input['app_store_link'];
            $aTableData = Setting::where('text_key','download_link_app_store_link')->update(['text_value'=>$app_store_link]);
        }

        $response['status'] = true;
        $response['message'] = "Downlod Links Successfully Updated";        

        return response()->json($response);
    }


    /**
     * Used for Admin edit Seo
     * @return redirect to Admin->edit Seo
     */
    public function editSeo(Request $request)
    {
        $input = $request->all();
        if ($input['seo_title']) {
            $seo_title = $input['seo_title'];
            $aTableData = Setting::where('text_key','setting_seo_title')->update(['text_value'=>$seo_title]);   
        }
        if ($input['seo_description']) {
            $seo_description = $input['seo_description'];
            $aTableData = Setting::where('text_key','setting_seo_description')->update(['text_value'=>$seo_description]);
        }

        $response['status'] = true;
        $response['message'] = "Seo Settings Successfully Updated";

        return response()->json($response);
    }


    /**
     * Used for Admin edit editContactQue
     * @return redirect to Admin->edit editContactQue
     */
    public function editContactSet(Request $request)
    {
        $input = $request->all();
        
        $aTableData = '';
        
        if ($input['contact_address']) {
            $address = $input['contact_address'];
            $aTableData = Setting::where('text_key','contact_address')->update(['text_value'=>$address]);
        }
        if ($input['contact_email']) {
            $email = $input['contact_email'];
            $aTableData = Setting::where('text_key','contact_email')->update(['text_value'=>$email]);   
        }
        if ($input['contact_phone']) {
            $contact = $input['contact_phone'];
            $aTableData = Setting::where('text_key','contact_phone')->update(['text_value'=>$contact]);
        }

        $response['status'] = true;
        $response['message'] = "Contact Settings Successfully Updated";
                
        return response()->json($response);
    }


    /**
     * Used for Admin edit editTestRule
     * @return redirect to Admin->edit editTestRule
     */
    public function editTestRule(Request $request)
    {
        $input = $request->all();
        
        $aTableData = '';
        
        if ($input['live_test_en']) {
            $address = $input['live_test_en'];
            $aTableData = Setting::where('text_key','test_rule_live_test_en')->update(['text_value'=>$address]);
        }
        if ($input['live_test_hi']) {
            $address = $input['live_test_hi'];
            $aTableData = Setting::where('text_key','test_rule_live_test_hi')->update(['text_value'=>$address]);
        }
        if ($input['quizzes_test_en']) {
            $address = $input['quizzes_test_en'];
            $aTableData = Setting::where('text_key','test_rule_quizzes_test_en')->update(['text_value'=>$address]);
        }
        if ($input['quizzes_test_hi']) {
            $address = $input['quizzes_test_hi'];
            $aTableData = Setting::where('text_key','test_rule_quizzes_test_hi')->update(['text_value'=>$address]);
        }

        $response['status'] = true;
        $response['message'] = "Test Rule Text Successfully Updated";
                
        return response()->json($response);
    }

    

}
