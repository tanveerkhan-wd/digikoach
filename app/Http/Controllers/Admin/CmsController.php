<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Cms;
use App\Models\CmsDesc;
use Validator;
use Carbon\Carbon;
use Auth;
use Hash;
use Redirect;

class CmsController extends Controller
{
	/**
	 * Used for Admin Cms
	 * @return redirect to Admin->cms
	*/
    public function index(Request $request)
    {
    	if (request()->ajax()) {
            return \View::make('admin.cms.index')->renderSections();
        }
    	return view('admin.cms.index');
    }   

	
	/**
	 * Used for Admin get CMS
 	 * @return redirect to Admin->get CMS listing
	*/
    public function getCms(Request $request)
    {
    	
      /**
       * Used for Admin get CMS Listing
       */
    	$data =$request->all();
	    
	    $perpage = !empty( $data[ 'length' ] ) ? (int)$data[ 'length' ] : 10;
	      
	    $filter = isset( $data['search'] ) && is_string( $data['search'] ) ? $data['search'] : '';

	    $sort_type = isset( $data['order'][0]['dir'] ) && is_string( $data['order'][0]['dir'] ) ? $data['order'][0]['dir'] : '';	

	    $sort_col =  isset($data['order'][0]['column']) ? $data['order'][0]['column'] :'';

	    $sort_field = isset($data['columns'][$sort_col]['data']) ? $data['columns'][$sort_col]['data'] :'';
	    
    	$aTable = Cms::with('cms_desc');

    	if($filter){
    		$aTable = $aTable->whereHas('cms_desc', function($q) use($filter){
                $q->where('cms_title', 'LIKE', '%' . $filter . '%' );
            });
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
       		$desc_en = '';
       		$desc_hi = '';
            if (!empty($value['cms_desc'])) {
            	foreach ($value['cms_desc'] as $cd_key => $cd_value) {
                    if ($cd_value['lang_code']=='en') {
                        $name_en = !empty($cd_value['cms_title']) ? $cd_value['cms_title']:'';
                        $desc_en = !empty($cd_value['cms_description']) ? $cd_value['cms_description']:'';
                    }elseif ($cd_value['lang_code']=='hi') {
                        $name_hi = !empty($cd_value['cms_title']) ? $cd_value['cms_title']:'';
                        $desc_hi = !empty($cd_value['cms_description']) ? $cd_value['cms_description']:'';
                    }
            	}
            }
            $value['name_hi'] = !empty($name_hi) ? $name_hi :'';
            $value['name_en'] = !empty($name_en) ? $name_en :'';
            $value['desc_hi'] = !empty($desc_hi) ? $desc_hi :'';
            $value['desc_en'] = !empty($desc_en) ? $desc_en :'';
            $value['date'] = date('d-M-Y',strtotime($value['created_at']));
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
	 * Used for Admin add CMS
 	 * @return redirect to Admin->add CMS
	 */
    public function addCms(Request $request)
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
                  $response['message'] ="CMS Setting already exists with this name";
                }else{
            		$id = Cms::where('cms_id', $input['pkCat']);
            		
                    $aData['slug'] = $this->str_slug($input['name_en']);
    	            $aData['seo_meta_title'] = $input['seo_meta_title'];
    	            $aData['seo_meta_description'] = $input['seo_meta_description'];
    	            $aData['updated_by'] = Auth::check() ? Auth::user()->user_id : '';
    	            if ($id->update($aData)) {
                        $cdId = CmsDesc::where('cms_id', $input['pkCat'])->get();
                        foreach ($cdId as $key => $value) {
                            if ($value->lang_code=='en') {
                                $cdData['lang_code'] = $value->lang_code;
                                $cdData['cms_id'] = $input['pkCat'];
                                $cdData['cms_title'] = $input['name_en'];    
                                $cdData['cms_description'] = $input['desc_en'];    
                            }else{
                                $cdData['lang_code'] = $value->lang_code;
                                $cdData['cms_id'] = $input['pkCat'];
                                $cdData['cms_title'] = $input['name_hi'];    
                                $cdData['cms_description'] = $input['desc_hi'];    
                            }

                            $category_desc = CmsDesc::where('cms_id',$input['pkCat'])->where('lang_code',$cdData['lang_code'])->update($cdData);
                        }

                        $response['status'] = true;
                        $response['message'] = "CMS Setting Successfully Updated";
                    }
                }
        	}
        }catch (\Exception $e) {
            $response['status'] = false;
            $response['message'] = "Error:" . $e->getMessage();
        }
        return response()->json($response);
    }


    /**
    * Used for Edit Admin CMS
    */
    public function editCms(Request $request,$id)
    {
    	$aTable = Cms::with('cms_desc')->where('cms_id','=',$id)->first();
    	return view('admin.cms.editCms',compact('aTable'));

    }

    /**
    * Used for Delete Admin Cms
    */
    public function statusCms(Request $request)
    {
        $input = $request->all();
        $cid = $input['cid'];
        $response = [];

        if(empty($cid)){
            $response['status'] = false;
        }else{
            $cms = Cms::where('cms_id', $cid)->first();
            $cms->status = $cms->status == 1 ? 0 : 1;
            if ($cms->update()) {
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
