<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\BlogCategory;
use App\Models\BlogCategoriesDesc;
use Validator;
use Carbon\Carbon;
use Auth;
use Hash;
use View;
use Redirect;

class BlogCategoryController extends Controller
{
	
    /**
	 * Used for Admin Blog Category
	 * @return redirect to Admin->BlogCategory
	 */
    public function index(Request $request)
    {
    	if (request()->ajax()) {
            return \View::make('admin.blog.blog_post_category')->renderSections();
        }
    	return view('admin.blog.blog_post_category');
    }


    /**
	 * Used for Admin get Category
 	 * @return redirect to Admin->get Category listing
	 */
    public function getBlogCategory(Request $request)
    {
    	
      /**
       * Used for Admin get Category Listing
       */
    	$data =$request->all();
	            
        $accessPriData = !empty(session()->get('accessPriData')) ? session()->get('accessPriData') :'' ;

	    $perpage = !empty( $data[ 'length' ] ) ? (int)$data[ 'length' ] : 10;
	      
	    $filter = isset( $data['search'] ) && is_string( $data['search'] ) ? $data['search'] : '';

	    $sort_type = isset( $data['order'][0]['dir'] ) && is_string( $data['order'][0]['dir'] ) ? $data['order'][0]['dir'] : '';	

	    $sort_col =  isset($data['order'][0]['column']) ? $data['order'][0]['column'] :'';

	    $sort_field = isset($data['columns'][$sort_col]['data']) ? $data['columns'][$sort_col]['data'] :'';
	    
    	$aTable = BlogCategory::with('blog_cat_desc');

        //ACCESS PRIVILEGE
        if($accessPriData){
            $getAcc = [];
            if(!empty($accessPriData['Blog_Categories']) && $accessPriData['Blog_Categories']->view==true)
            {
                $getAcc['STATUS'] = $accessPriData['Blog_Categories']->status==true ? true:false;
                $getAcc['DELETED'] = $accessPriData['Blog_Categories']->delete==true ? true:false;
                $getAcc['EDIT'] = $accessPriData['Blog_Categories']->edit==true ? true:false; 
            }
        }

    	if($filter){
    		$aTable = $aTable->whereHas('blog_cat_desc', function($q) use($filter){
                $q->where('blog_category_title', 'LIKE', '%' . $filter . '%' );
            });
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
            if (!empty($value['blog_cat_desc'])) {
            	foreach ($value['blog_cat_desc'] as $cd_key => $cd_value) {
                    if ($cd_value['lang_code']=='en') {
                        $name_en = !empty($cd_value['blog_category_title']) ? $cd_value['blog_category_title']:'';
                        $desc_en = !empty($cd_value['description']) ? $cd_value['description']:'';
                    }elseif ($cd_value['lang_code']=='hi') {
                        $name_hi = !empty($cd_value['blog_category_title']) ? $cd_value['blog_category_title']:'';
                        $desc_hi = !empty($cd_value['description']) ? $cd_value['description']:'';
                    }
            	}
            }
            $value['name_hi'] = !empty($name_hi) ? $name_hi :'';
            $value['name_en'] = !empty($name_en) ? $name_en :'';
            $value['desc_hi'] = !empty($desc_hi) ? $desc_hi :'';
            $value['desc_en'] = !empty($desc_en) ? $desc_en :'';

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

    /**
	 * Used for Admin add Category
 	 * @return redirect to Admin->add Category
	 */
    public function addBlogCategory(Request $request)
    {
    	$response = [];
    	$input = $request->all();
        $image = $request->file('icon');      
        $aData = [];
        $cdData = [];
        try{
            if(!empty($input['pkCat']) && $input['pkCat'] != null){
                $checkPrev = '';
                if(!empty($checkPrev)){
                  $response['status'] = false;
                  $response['message'] ="Blog category already exists with this name";
                }else{
            		$id = BlogCategory::where('blog_category_id', $input['pkCat']);
                    $aData['slug'] = $this->str_slug($input['name_en']);
    				$aData['updated_by'] = Auth::check() ? Auth::user()->user_id : '';
    	            $aData['status']  = !empty($input['blog_status'])?$input['blog_status']:0;

                    if ($id->update($aData)) {
                        $cdId = BlogCategoriesDesc::where('blog_category_id', $input['pkCat'])->get();
                        foreach ($cdId as $key => $value) {
                            if ($value->lang_code=='en') {
                                $cdData['lang_code'] = $value->lang_code;
                                $cdData['blog_category_id'] = $input['pkCat'];
                                $cdData['blog_category_title'] = $input['name_en'];    
                                //$cdData['description'] = $input['desc_en'];    
                            }else{
                                $cdData['lang_code'] = $value->lang_code;
                                $cdData['blog_category_id'] = $input['pkCat'];
                                $cdData['blog_category_title'] = $input['name_hi'];    
                                //$cdData['description'] = $input['desc_hi'];    
                            }

                            $category_desc = BlogCategoriesDesc::where('blog_category_id',$input['pkCat'])->where('lang_code',$cdData['lang_code'])->update($cdData);
                        }

                        $response['status'] = true;
                        $response['message'] = "Blog Category Successfully Updated";
                    }
                }
        	}else{
            
                $checkPrev = BlogCategoriesDesc::where('blog_category_title',$input['name_hi'])->orWhere('blog_category_title',$input['name_en'])->first();
                if(!empty($checkPrev)){
                  $response['status'] = false;
                  $response['message'] ="Blog category title already exists with this name";
                }
                //Store Category
                $category = new BlogCategory;
                $category->slug = $this->str_slug($input['name_en']);

                $category->status  = !empty($input['blog_status'])?$input['blog_status']:0;
                $category->created_by = Auth::check() ? Auth::user()->user_id : '';
                $category->updated_by = Auth::check() ? Auth::user()->user_id : '';
                
                if ($category->save()) {
                	for ($i=1; $i <= 2 ; $i++) { 
        	        	$aData[$i]['lang_code'] = $i==1 ? 'en' :'hi';
        	        	$aData[$i]['blog_category_id'] = $category->blog_category_id;
        	        	$aData[$i]['blog_category_title'] = $i==1 ? $input['name_en'] : $input['name_hi'];
        	        	//$aData[$i]['description'] = $i==1 ? $input['desc_en'] :$input['desc_hi'];
                	}
                	$category_desc = BlogCategoriesDesc::insert($aData);
                }

                if ($category_desc) {
                	$response['status'] = true;
        	        $response['message'] = "Blog Category Successfully Added";

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
    public function editBlogCategory(Request $request)
    {
    	$input = $request->all();
    	$cid = $input['cid'];
    	$response = [];
        $lang = 'en';
    	$aTable = BlogCategory::with('blog_cat_desc')->where('blog_category_id','=',$input['cid'])->first()->toArray();

    	if(empty($cid) || empty($aTable)){
    		$response['status'] = false;
    	}else{
    		$response['status'] = true;
    		$response['data'] = $aTable;
    	}

    	return response()->json($response);

    }

  	/**
   	* Used for Delete Admin Category
   	*/
    public function deleteBlogCategory(Request $request)
    {
    	$input = $request->all();
    	$cid = $input['cid'];
    	$response = [];

    	if(empty($cid)){
    		$response['status'] = false;
    	}else{
        	$categoriesDesc = BlogCategoriesDesc::where('blog_category_id', $cid)->delete();
            $category = BlogCategory::where('blog_category_id', $cid)->delete();
            
    		$response['status'] = true;
            $response['message'] = "Category Successfully deleted";
        }
    	return response()->json($response);
    }

    /**
    * Used for Delete Admin Category
    */
    public function statusBlogCategory(Request $request)
    {
        $input = $request->all();
        $cid = $input['cid'];
        $response = [];

        if(empty($cid)){
            $response['status'] = false;
        }else{
            $category = BlogCategory::where('blog_category_id', $cid)->first();
            $category->status = $category->status == 1 ? 0 : 1;
            if ($category->update()) {
                $response['status'] = true;
                $response['message'] = "Status Successfully changed";
            }else{
                $response['status'] = false;
            }
        }
        return response()->json($response);
    }
    /*
	* Create Slug
    */
    function str_slug($string){
	   $slug=preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
	   return $slug;
	}

}
