<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Testimonial;
use Settings;

class HomeController extends Controller
{
    public function index()
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

		/*===  Testimonial  ===*/
		$testimonial = Testimonial::with('test_desc')->orderBy('sequence','ASC')->get();
		return view('welcome',compact('testimonial','setting'));

    }
}
