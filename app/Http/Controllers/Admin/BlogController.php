<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\BlogCategory;
use App\Models\BlogCategoriesDesc;
use App\Models\BlogPost;
use App\Models\BlogPostDesc;
use App\Models\Notification;
use App\Models\Translation;
use App\Models\UserSavedItem;
use App\Models\NotificationDesc;
use Carbon\Carbon;
use UserNotifications;
use Validator;
use Auth;
use Hash;
use View;
use Redirect;
use Storage;
use File;
use Config;

class BlogController extends Controller
{
	
    /**
	 * Used for Admin Blog Category
	 * @return redirect to Admin->BlogCategory
	 */
    public function index(Request $request)
    {
    	$category =  BlogCategoriesDesc::where('lang_code','en')->get();
    	if (request()->ajax()) {
            return \View::make('admin.blog.blog_post')->with('category',$category)->renderSections();
        }
    	return view('admin.blog.blog_post',compact('category'));
    }


    /**
	 * Used for Admin get Category
 	 * @return redirect to Admin->get Category listing
	 */
    public function getBlog(Request $request)
    {
    	
      /**
       * Used for Admin get Category Listing
       */
    	$data =$request->all();

	    $perpage = !empty( $data[ 'length' ] ) ? (int)$data[ 'length' ] : 10;
	    
        $accessPriData = !empty(session()->get('accessPriData')) ? session()->get('accessPriData') :'' ;
  
	    $filter = isset( $data['search'] ) && is_string( $data['search'] ) ? $data['search'] : '';

        $category_type = isset($data['category_type']) ? $data['category_type'] : '';

	    $sort_type = isset( $data['order'][0]['dir'] ) && is_string( $data['order'][0]['dir'] ) ? $data['order'][0]['dir'] : '';	

	    $sort_col =  isset($data['order'][0]['column']) ? $data['order'][0]['column'] :'';

	    $sort_field = isset($data['columns'][$sort_col]['data']) ? $data['columns'][$sort_col]['data'] :'';
	    
    	$aTable = BlogPost::with('blog_post_desc','blog_category_id');

        //ACCESS PRIVILEGE
        if($accessPriData){
            $getAcc = [];
            if(!empty($accessPriData['Blog_Post']) && $accessPriData['Blog_Post']->view==true)
            {
                $getAcc['STATUS'] = $accessPriData['Blog_Post']->status==true ? true:false;
                $getAcc['DELETED'] = $accessPriData['Blog_Post']->delete==true ? true:false;
                $getAcc['EDIT'] = $accessPriData['Blog_Post']->edit==true ? true:false; 
            }
        }

    	if($filter){
    		$aTable = $aTable->whereHas('blog_post_desc', function($q) use($filter){
                $q->where('blog_post_title', 'LIKE', '%' . $filter . '%' );
            });
    	}

        if ($category_type) {
            $aTable = $aTable->where('blog_category_id', 'LIKE', '%' . $category_type . '%' );
        }

        if (!empty($data['status_type']) && $data['status_type'] !=null ) {
            $statusData = $data['status_type']=='Active' ? 1 : 0;
            $aTable = $aTable->where('status','=',$statusData);
        }

    	$aTableQuery = $aTable;

    	if($sort_col != 0){
    		$aTableQuery = $aTableQuery->orderBy($sort_field, $sort_type);
    	}else{
            $aTableQuery = $aTableQuery->orderBy('created_at', 'DESC');
        }

         
         
    	$total_table_data= $aTableQuery->count();
        
		$offset = isset($data['start']) ? $data['start'] :'';
	     
	    $counter = $offset;
	    $aTabledata = [];
      	
	    $aTables = $aTableQuery->offset($offset)->limit($perpage)->get()->toArray();

        foreach ($aTables as $key => $value) {
            $name_en = '';
       		$name_hi = '';
            if (!empty($value['blog_post_desc'])) {
            	foreach ($value['blog_post_desc'] as $cd_key => $cd_value) {
                    if ($cd_value['lang_code']=='en') {
                        $name_en = !empty($cd_value['blog_post_title']) ? $cd_value['blog_post_title']:'';
                        $desc_en = !empty($cd_value['description']) ? $cd_value['description']:'';
                    }elseif ($cd_value['lang_code']=='hi') {
                        $name_hi = !empty($cd_value['blog_post_title']) ? $cd_value['blog_post_title']:'';
                        $desc_hi = !empty($cd_value['description']) ? $cd_value['description']:'';
                    }
            	}
            }
            $value['name_hi'] = !empty($name_hi) ? $name_hi :'';
            $value['name_en'] = !empty($name_en) ? $name_en :'';
            $value['desc_hi'] = !empty($desc_hi) ? $desc_hi :'';
            $value['desc_en'] = !empty($desc_en) ? $desc_en :'';
            $value['date'] = date('d-M-Y',strtotime($value['created_at']));
            
            $value['status_access'] = Auth::user()->user_type==0 ? true : $getAcc['STATUS'];
            $value['deleted_access'] = Auth::user()->user_type==0 ? true : $getAcc['DELETED'];
            $value['edit_access'] = Auth::user()->user_type==0 ? true : $getAcc['EDIT'];
         
            $value['index'] = $counter+1;
            $aTabledata[$counter] = $value;
            $counter++;
      	}
	    $price = array_column($aTabledata, 'index');

	    if($sort_col == 0){
	     	if($sort_type == 'desc'){
	     		array_multisort($price, SORT_DESC, $aTabledata);
	     	}else{
	     		array_multisort($price, SORT_ASC, $aTabledata);
	     	}
		}
	      $result = array(
	      	"draw" => $data['draw'],
			"recordsTotal" =>$total_table_data,
			"recordsFiltered" => $total_table_data,
	        'data' => $aTabledata,
	      );

	       return response()->json($result);
    
    }

    public function getAddBlog(Request $request)
    {
        $category =  BlogCategoriesDesc::where('lang_code','en')->get();
        if (request()->ajax()) {
            return \View::make('admin.blog.blog_post_add')->with('category',$category)->renderSections();
        }
        return view('admin.blog.blog_post_add',compact('category'));
    }

    /**
	 * Used for Admin add Category
 	 * @return redirect to Admin->add Category
	 */
    public function addBlog(Request $request)
    {
    	$response = [];
    	$input = $request->all();   
        $aData = [];
        $cdData = [];
        try
        {
            if(!empty($input['pkCat']) && $input['pkCat'] != null){
                $checkPrev = '';
                if(!empty($checkPrev)){
                  $response['status'] = false;
                  $response['message'] ="Blog already exists with this name";
                }else{
            		$id = BlogPost::where('blog_post_id', $input['pkCat']);
            		
                    //If Image Updated 
                    if($request->hasFile('blog_img')){
                        $get_pre_img = BlogPost::where('blog_post_id', $input['pkCat'])->first();
                        $gen_rand = rand(100,99999).time();
                        $image_path = $request->file('blog_img');
                        $extension = $image_path->getClientOriginalExtension();
                        Storage::disk('public')->put(Config::get('siteglobal.images_dirs.BLOG').'/'.$gen_rand.'.'.$extension,  File::get($image_path));
                        if(!empty($get_pre_img->blog_image)){
                            Storage::disk('public')->delete(Config::get('siteglobal.images_dirs.BLOG').'/'.$get_pre_img->blog_image);
                        }
                        $aData['blog_image'] = $gen_rand.'.'.$extension;
                    }
                    $aData['slug'] = $this->str_slug($input['name_en']);
    	            $aData['seo_meta_title'] = $input['seo_meta_title'];
    	            $aData['blog_category_id'] = $input['category'];
    	            $aData['seo_meta_description'] = $input['seo_meta_description'];
    	            $aData['updated_by'] = Auth::check() ? Auth::user()->user_id : '';
                    
    	            if ($id->update($aData)) {
                        $cdId = BlogPostDesc::where('blog_post_id', $input['pkCat'])->get();
                        foreach ($cdId as $key => $value) {
                            if ($value->lang_code=='en') {
                                $cdData['lang_code'] = $value->lang_code;
                                $cdData['blog_post_id'] = $input['pkCat'];
                                $cdData['blog_post_title'] = $input['name_en'];    
                                $cdData['description'] = $input['desc_en'];    
                            }else{
                                $cdData['lang_code'] = $value->lang_code;
                                $cdData['blog_post_id'] = $input['pkCat'];
                                $cdData['blog_post_title'] = $input['name_hi'];    
                                $cdData['description'] = $input['desc_hi'];    
                            }
                            
                            $category_desc = BlogPostDesc::where('blog_post_id',$input['pkCat'])->where('lang_code',$cdData['lang_code'])->update($cdData);
                        }

                        $response['status'] = true;
                        $response['message'] = "Blog Successfully Updated";
                    }
                }
        	}else{
            
                $checkPrev = BlogPostDesc::where('blog_post_title',$input['name_hi'])->orWhere('blog_post_title',$input['name_en'])->first();
                if(!empty($checkPrev)){
                  $response['status'] = false;
                  $response['message'] ="Blog title already exists with this name";
                }
                //Store Category
                $aTableData = new BlogPost;
                
                if($request->hasFile('blog_img')){
                    $gen_rand = rand(100,99999).time();
                    $image_path = $request->file('blog_img');
                    $extension = $image_path->getClientOriginalExtension();
                    Storage::disk('public')->put(Config::get('siteglobal.images_dirs.BLOG').'/'.$gen_rand.'.'.$extension,  File::get($image_path));
                    $aTableData->blog_image = $gen_rand.'.'.$extension;
                }

                $aTableData->slug = $this->str_slug($input['name_en']);
                $aTableData->seo_meta_title = $input['seo_meta_title'];
                $aTableData->seo_meta_description = $input['seo_meta_description'];
    	        $aTableData->blog_category_id = $input['category'];
                $aTableData->status = 1;
                $aTableData->created_by = Auth::check() ? Auth::user()->user_id : '';
                $aTableData->updated_by = Auth::check() ? Auth::user()->user_id : '';
                
                if ($aTableData->save()) {
                	for ($i=1; $i <= 2 ; $i++) { 
        	        	$aData[$i]['lang_code'] = $i==1 ? 'en' :'hi';
        	        	$aData[$i]['blog_post_id'] = $aTableData->blog_post_id;
        	        	$aData[$i]['blog_post_title'] = $i==1 ? $input['name_en'] : $input['name_hi'];
        	        	$aData[$i]['description'] = $i==1 ? $input['desc_en'] :$input['desc_hi'];
                	}
                	$blog_desc = BlogPostDesc::insert($aData);
                }

                if ($blog_desc) {

                    //SEND EMAIL AND NOTIFICATIONS TO USERS
                    $notifi = [];
                    $addDataNot = [];
                    $getUserCate = User::where('user_type',2)->whereNotNull('device_token')->where('deleted',false)->where('user_status',true)->get();
                    if (!empty($getUserCate) || !$getUserCate->isEmpty()) {
                        
                        //GET NOTIFICATION TRANSLATE KEY
                        $aNotificaTransMsg = Translation::where('group','notification_message')->where('key','new_blog_add_msg')->first()->toArray();
                        $aNotificaTransTitle = Translation::where('group','notification_title')->where('key','new_blog_add_title')->first()->toArray();
                        foreach ($getUserCate as $key => $value) {
                            //SEND NOTIFICATION
                            if (!empty($value->device_token) || $value->device_token==!null) {
                                //GET NOTIFICATION TRANSLATE KEY
                                $userLangCode = !empty($value->user_lang_code) ? $value->user_lang_code : 'en';
                                
                                $bodyText = $aNotificaTransMsg['text'][$userLangCode];
                                $titleText = $aNotificaTransTitle['text'][$userLangCode];

                                $device_token = $value->device_token;
                                $notification = ['title' => $titleText, 'body' => $bodyText];

                                $notification_data = [
                                    'action' => 'BLOG_ADDED',
                                    'blog_post_id' =>  $aTableData->blog_post_id
                                ];
                                
                                try
                                {
                                    UserNotifications::sendPush($device_token, $notification, $notification_data);
                                } catch (\Exception $e) {
                                    continue;   
                                }

                                $notifi = new Notification;
                                $notifi->user_id  = $value->user_id;
                                $notifi->notification_type  = 'BLOG';
                                $notifi->ntoification_type_id = $aTableData->blog_post_id;
                                $notifi->notification_data = json_encode($notification_data);
                                $notifi->status = false;
                                $notifi->save();
                                if ($notifi) {
                                    for ($i=1; $i <=2 ; $i++) { 
                                        $addDataNot[$i]['notification_id'] = $notifi->notification_id;
                                        $addDataNot[$i]['lang_code'] = $i==1 ? 'en' : 'hi';
                                        $addDataNot[$i]['message'] = $i==1 ? $aNotificaTransMsg['text']['en'] : $aNotificaTransMsg['text']['hi'];
                                    }
                                    NotificationDesc::insert($addDataNot);
                                }
                            }
                        }
                    }

                	$response['status'] = true;
        	        $response['message'] = "Blog Successfully Added";

                }else{
                    $response['status'] = false;
                    $response['message'] = "Something Wrong Please try again Later";
                }
            }
        }catch (\Exception $e) {
            $response['status'] = false;
            $response['message'] = "Error:" . $e->getMessage();
        }
        return response()->json($response);
    }


    /**
    * Used for Edit Admin Category
    */
    public function editBlog(Request $request, $id)
    {	
        $aTable = BlogPost::with('blog_post_desc')->where('blog_post_id','=',$id)->first();

    	$category =  BlogCategoriesDesc::where('lang_code','en')->get();
        return view('admin.blog.blog_post_edit')->with(['category'=>$category,'data'=>$aTable]);

    }

  	/**
   	* Used for Delete Admin Category
   	*/
    public function deleteBlog(Request $request)
    {
    	$input = $request->all();
    	$cid = $input['cid'];
    	$response = [];

    	if(empty($cid)){
    		$response['status'] = false;
    	}else{
            Notification::where('notification_type','BLOG')->where('ntoification_type_id',$cid)->delete();
            $category = BlogPost::where('blog_post_id', $cid)->first();
            if(!empty($category->blog_image)){
                Storage::disk('public')->delete(Config::get('siteglobal.images_dirs.BLOG').'/'.$category->blog_image);
            }
        	$categoriesDesc = BlogPostDesc::where('blog_post_id', $cid)->delete();
            BlogPost::where('blog_post_id', $cid)->delete();
            UserSavedItem::where('item_type','POST')->where('item_type_id',$cid)->delete();
            
    		$response['status'] = true;
            $response['message'] = "Blog Successfully deleted";
        }
    	return response()->json($response);
    }

    /**
    * Used for Delete Admin Category
    */
    public function statusBlog(Request $request)
    {
        $input = $request->all();
        $cid = $input['cid'];
        $response = [];

        if(empty($cid)){
            $response['status'] = false;
        }else{
            $blog_post = BlogPost::where('blog_post_id', $cid)->first();
            $blog_post->status = $blog_post->status == 1 ? 0 : 1;
            if ($blog_post->update()) {
                $response['status'] = true;
                $response['message'] = "Status Successfully changed";
            }else{
                $response['status'] = false;
            }
        }
        return response()->json($response);
    }


    /**
    * Used for Delete Admin Viwe Blog
    */
    public function viewBlog(Request $request,$id)
    {
        $aTable = BlogPost::with('blog_post_desc')->where('blog_post_id','=',$id)->first();

        $category =  BlogCategoriesDesc::where('lang_code','en')->get();
        return view('admin.blog.blog_post_view')->with(['category'=>$category,'data'=>$aTable]);
    }


    /*
	* Create Slug
    */
    function str_slug($string){
	   $slug=preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
	   return $slug;
	}

}
