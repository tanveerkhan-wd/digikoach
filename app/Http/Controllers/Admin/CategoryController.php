<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\FrontHelper;
use App\Models\User;
use App\Models\Category;
use App\Models\CategoriesDesc;
use Validator;
use Carbon\Carbon;
use Auth;
use Hash;
use View;
use Redirect;
use Storage;
use File;
use Config;


class CategoryController extends Controller
{
    /**
	 * Used for Admin Category
	 * @return redirect to Admin->Category
	 */
    public function index(Request $request)
    {
        $streams = Category::with('category_desc')->get()->toArray();
        $category = FrontHelper::buildtree($streams);
        
    	if (request()->ajax()) {
            return \View::make('admin.category.index')->with('category',$category)->renderSections();
        }
    	return view('admin.category.index',compact('category'));
    }


    /**
	 * Used for Admin get Category
 	 * @return redirect to Admin->get Category listing
	 */
    public function getCategory(Request $request)
    {
    	
      /**
       * Used for Admin get Category Listing
       */
    	$data =$request->all();
	    
	    $perpage = !empty( $data[ 'length' ] ) ? (int)$data[ 'length' ] : 10;
	      
	    $filter = isset( $data['search'] ) && is_string( $data['search'] ) ? $data['search'] : '';

	    $sort_type = isset( $data['order'][0]['dir'] ) && is_string( $data['order'][0]['dir'] ) ? $data['order'][0]['dir'] : '';	

	    $sort_col =  isset($data['order'][0]['column']) ? $data['order'][0]['column'] :'';

	    $sort_field = isset($data['columns'][$sort_col]['data']) ? $data['columns'][$sort_col]['data'] :'';
	    
    	$aTable = Category::with(["category_desc","parent_category"]);

    	if($filter){
    		$aTable = $aTable->whereHas('category_desc', function($q) use($filter){
                $q->where('name', 'LIKE', '%' . $filter . '%' );
            })->orWhereHas('parent_category', function($q) use($filter){
                $q->where('name', 'LIKE', '%' . $filter . '%' );
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
            $aTableQuery = $aTableQuery->orderBy('category_id', 'DESC');
        }

    	$total_table_data= $aTableQuery->count();

		$offset = isset($data['start']) ? $data['start'] :'';
	     
	    $counter = $offset;
	    $aTabledata = [];
	    $aTables = $aTableQuery->offset($offset)->limit($perpage)->get()->toArray();
        
        foreach ($aTables as $key => $value) {
            $name_en = '';
       		$name_hi = '';
            $prt_cat_hi = '';
            $prt_cat_en = '';
       		if (!empty($value['category_desc'])) {
            	foreach ($value['category_desc'] as $cd_key => $cd_value) {
                    if ($cd_value['lang_code']=='en') {
                        $name_en = !empty($cd_value['name']) ? $cd_value['name']:'';
                    }elseif ($cd_value['lang_code']=='hi') {
                        $name_hi = !empty($cd_value['name']) ? $cd_value['name']:'';
                    }
            	}
            }
            if (!empty($value['parent_category'])) {
                foreach ($value['parent_category'] as $pckey => $pcvalue) {
                    if ($pcvalue['lang_code']=='en') {
                        $prt_cat_en = !empty($pcvalue['name']) ? $pcvalue['name'] :'';
                    }elseif ($cd_value['lang_code']=='hi') {
                        $prt_cat_hi = !empty($pcvalue['name']) ? $pcvalue['name'] :'';    
                    }
                } 
            }
            $value['name_hi'] = !empty($name_hi) ? $name_hi :'';
            $value['name_en'] = !empty($name_en) ? $name_en :'';
            $value['prt_cat_hi'] = !empty($prt_cat_hi) ? $prt_cat_hi :'NA';
            $value['prt_cat_en'] = !empty($prt_cat_en) ? $prt_cat_en :'NA';
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
    public function addCategory(Request $request)
    {
    	$response = [];
    	$input = $request->all();
        $aData = [];
        $cdData = [];
        try
        {
            if(!empty($input['pkCat']) && $input['pkCat'] != null){
                if(!empty($input['parent_category']) && $input['pkCat']==$input['parent_category']){
                  $response['status'] = false;
                  $response['message'] ="Category and Parent Category can not be same";
                }else{
            		$id = Category::where('category_id', $input['pkCat']);
                    
                    if($request->hasFile('icon')){
                        $get_pre_img = Category::where('category_id', $input['pkCat'])->first();
                        $gen_rand = rand(100,99999).time();
                        $image_path = $request->file('icon');
                        $extension = $image_path->getClientOriginalExtension();
                        Storage::disk('public')->put(Config::get('siteglobal.images_dirs.CATEGORY_ICON').'/'.$gen_rand.'.'.$extension,  File::get($image_path));
                        if(!empty($get_pre_img->icon_img)){
                            Storage::disk('public')->delete(Config::get('siteglobal.images_dirs.CATEGORY_ICON').'/'.$get_pre_img->icon_img);
                        }
                        $aData['icon_img'] = $gen_rand.'.'.$extension;
                    }

                    $aData['parent_category'] = isset($input['parent_category']) ? $input['parent_category'] : false;
                    $aData['gk_ca'] = isset($input['gk_ca']) && $input['gk_ca']=='on' ? 1:0;
                    $id->update($aData);
                    if ($id) {
                        $cdId = CategoriesDesc::where('category_id', $input['pkCat'])->get();
                        
                        foreach ($cdId as $key => $value) {
                            if ($value->lang_code=='en') {
                                $cdData['lang_code'] = $value->lang_code;
                                $cdData['category_id'] = $input['pkCat'];
                                $cdData['name'] = $input['name_en'];    
                            }else{
                                $cdData['lang_code'] = $value->lang_code;
                                $cdData['category_id'] = $input['pkCat'];
                                $cdData['name'] = $input['name_hi'];    
                            }

                            $category_desc = CategoriesDesc::where('category_id',$input['pkCat'])->where('lang_code',$cdData['lang_code'])->update($cdData);
                        }

                        $response['status'] = true;
                        $response['message'] = "Category Successfully Updated";
                    }
                }
        	}else{
                $checkPrev = CategoriesDesc::where('name',$input['name_hi'])->orWhere('name',$input['name_en'])->first();
                if(!empty($checkPrev)){
                  $response['status'] = false;
                  $response['message'] ="Category already exists with this name";
                }
                //Store Category
                $category = new Category;

                if($request->hasFile('icon')){
                    $gen_rand = rand(100,99999).time();
                    $image_path = $request->file('icon');
                    $extension = $image_path->getClientOriginalExtension();
                    Storage::disk('public')->put(Config::get('siteglobal.images_dirs.CATEGORY_ICON').'/'.$gen_rand.'.'.$extension,  File::get($image_path));
                    $category->icon_img = $gen_rand.'.'.$extension;
                }
                
                $category->parent_category = isset($input['parent_category']) ? $input['parent_category'] : false; 
                $category->status = true;
                $category->gk_ca = isset($input['gk_ca']) && $input['gk_ca']=='on' ? 1:0;
                if ($category->save()) {
                	for ($i=1; $i <= 2 ; $i++) { 
        	        	$aData[$i]['lang_code'] = $i==1 ? 'en' :'hi';
        	        	$aData[$i]['category_id'] = $category->category_id;
                        $aData[$i]['name'] = $i==1 ? $input['name_en'] : $input['name_hi'];
        	        	$aData[$i]['created_at'] = now();
                	}
                	$category_desc = CategoriesDesc::insert($aData);
                }

                if ($category_desc) {
                	$response['status'] = true;
        	        $response['message'] = "Category Successfully Added";

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
    public function editCategory(Request $request)
    {
    	$input = $request->all();
    	$cid = $input['cid'];
    	$response = [];
        $lang = 'en';
    	$aTable = Category::with(["category_desc","parent_category"=> function($qu) use($lang){
                $qu->where('lang_code',$lang);
            }])->where('category_id','=',$input['cid'])->first()->toArray();
    	
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
    public function deleteCategory(Request $request)
    {
    	$input = $request->all();
    	$cid = $input['cid'];
    	$response = [];

    	if(empty($cid)){
    		$response['status'] = false;
    	}else{
            $category = Category::where('category_id', $cid)->first();
        	$categoriesDesc = CategoriesDesc::where('category_id', $cid);
            
            if(!empty($category->icon_img)){
                Storage::disk('public')->delete(Config::get('siteglobal.images_dirs.CATEGORY_ICON').'/'.$category->icon_img);
            }
            $category->delete();
            $categoriesDesc->delete();
    		$response['status'] = true;
            $response['message'] = "Category Successfully deleted";
        }
    	return response()->json($response);
    }

    /**
    * Used for Delete Admin Category
    */
    public function statusCategory(Request $request)
    {
        $input = $request->all();
        $cid = $input['cid'];
        $response = [];

        if(empty($cid)){
            $response['status'] = false;
        }else{
            $category = Category::where('category_id', $cid)->first();
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

}
