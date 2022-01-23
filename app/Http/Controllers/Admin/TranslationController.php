<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Translation;
use Validator;
use Carbon\Carbon;
use Redirect;

class TranslationController extends Controller
{
	/**
	 * Used for Admin translation
	 * @return redirect to Admin->translation
	*/
    public function index(Request $request)
    {
    	if (request()->ajax()) {
            return \View::make('admin.translation.index')->renderSections();
        }
    	return view('admin.translation.index');
    }   

	
	/**
	 * Used for Admin get translation
 	 * @return redirect to Admin->get translation listing
	*/
    public function getTranslation(Request $request)
    {
    	
      /**
       * Used for Admin get translation Listing
       */
    	$data =$request->all();
	    
	    $perpage = !empty( $data[ 'length' ] ) ? (int)$data[ 'length' ] : 10;
	      
	    $filter = isset( $data['search'] ) && is_string( $data['search'] ) ? $data['search'] : '';

	    $sort_type = isset( $data['order'][0]['dir'] ) && is_string( $data['order'][0]['dir'] ) ? $data['order'][0]['dir'] : '';	

	    $sort_col =  isset($data['order'][0]['column']) ? $data['order'][0]['column'] :'';

	    $sort_field = isset($data['columns'][$sort_col]['data']) ? $data['columns'][$sort_col]['data'] :'';
	    
    	$aTable = new Translation;

    	if($filter){
    		$aTable = $aTable->where('group', 'LIKE', '%' . $filter . '%' )->orWhere('key', 'LIKE', '%' . $filter . '%' )->orWhere('text', 'LIKE', '%' . $filter . '%' );
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
        	$value['name_en'] = !empty($value['text']['en']) ? $value['text']['en'] :'NA';
        	$value['name_hi'] = !empty($value['text']['hi']) ? $value['text']['hi'] :'NA';
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
    public function addTranslation(Request $request)
    {
    	$response = [];
    	$input = $request->all();
        $aData = [];
        $cdData = [];
        try{
            if(!empty($input['pkCat']) && $input['pkCat'] != null){
                $checkPrev = Translation::where('translation_id','!=',$input['pkCat'])->where('key',$input['key'])->count();
                if(!empty($checkPrev) && $checkPrev > 0){
                  $response['status'] = false;
                  $response['message'] ="Translation Key already exists with this name";
                }else{
            		$id = Translation::where('translation_id', $input['pkCat']);
            		$nameText = [];
                    $aData['key'] = $input['key'];
    	            $aData['group'] = $input['group'];
    	            $nameText['en'] = $input['name_en'];
    	            $nameText['hi'] = $input['name_hi'];
    				$aData['text'] = $nameText;
    	            
                    if ($id->update($aData)) {
                        $response['status'] = true;
                        $response['message'] = "Translation Successfully Updated";
                    }
                }
        	}else{
            
                $checkPrev = Translation::where('key',$input['key'])->count();
                if(!empty($checkPrev) && $checkPrev >0 ){
                  $response['status'] = false;
                  $response['message'] ="Translation Key already exists with this name";
                }else{

    	            $aTableData = new Translation;
    	            $aTableData->group = $input['group'];
    	            $aTableData->key = $input['key'];
    	          	$nameText['en'] = $input['name_en'];
    		        $nameText['hi'] = $input['name_hi'];
    	            $aTableData->text = $nameText;
    		        
    	            if ($aTableData->save()) {
    	            	$response['status'] = true;
    	    	        $response['message'] = "Translation Successfully Added";

    	            }else{
    	                $response['status'] = false;
    	                $response['message'] = "Something Wrong Please try again Later";
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
    public function editTranslation(Request $request)
    {
    	$input = $request->all();
    	$cid = $input['cid'];
    	$response = [];
        $lang = 'en';
    	$aTable = Translation::where('translation_id','=',$input['cid'])->first()->toArray();
    	$aTable['name_hi'] = !empty($aTable['text']['hi']) ? $aTable['text']['hi'] :'';
    	$aTable['name_en'] =  !empty($aTable['text']['en']) ? $aTable['text']['en'] :'';

    	if(empty($cid) || empty($aTable)){
    		$response['status'] = false;
    	}else{
    		$response['status'] = true;
    		$response['data'] = $aTable;
    	}

    	return response()->json($response);

    }

  	/**
   	* Used for Delete Admin CMS
   	*/
    public function deleteTranslation(Request $request)
    {
    	$input = $request->all();
    	$cid = $input['cid'];
    	$response = [];

    	if(empty($cid)){
    		$response['status'] = false;
    	}else{
        	Translation::where('translation_id', $cid)->delete();
            
    		$response['status'] = true;
            $response['message'] = "Translation Successfully deleted";
        }
    	return response()->json($response);
    }

}
