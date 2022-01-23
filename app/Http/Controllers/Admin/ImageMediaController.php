<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ImageMedia;
use Storage;
use File;
use Config;
class ImageMediaController extends Controller
{
	 /**
	 * Used for Admin image media
	 * @return redirect to Admin->image media
	 */
    public function index(Request $request)
    {
        if (request()->ajax()) {
            return \View::make('admin.imageMedia.index')->renderSections();
        }
    	return view('admin.imageMedia.index');
    }

    /**
	 * Used for Admin get Image Media
 	 * @return redirect to Admin->get Image Media listing
	*/
    public function getImageMedia(Request $request)
    {
    	
      /**
       * Used for Admin get Image Media Listing
       */
    	$data =$request->all();
	    
	    $perpage = !empty( $data[ 'length' ] ) ? (int)$data[ 'length' ] : 10;
	      
	    $filter = isset( $data['search'] ) && is_string( $data['search'] ) ? $data['search'] : '';
	    
	    $search_category = isset( $data['search_category'] ) ? $data['search_category'] :'';
	    
	    $status_type = isset( $data['status_type'] ) ? $data['status_type'] :'';

	    $sort_type = isset( $data['order'][0]['dir'] ) && is_string( $data['order'][0]['dir'] ) ? $data['order'][0]['dir'] : '';	

	    $sort_col =  isset($data['order'][0]['column']) ? $data['order'][0]['column'] :'';

	    $sort_field = isset($data['columns'][$sort_col]['data']) ? $data['columns'][$sort_col]['data'] :'';
	    
    	$aTable = new ImageMedia;

    	if($filter){
    		$aTable = $aTable->where('file', 'LIKE', '%' . $filter . '%' )->orWhere('image_media_id','LIKE', '%' . $filter . '%' );
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
            $value['index'] = $counter+1;
            $value['image'] = Config::get('siteglobal.images_dirs.QUESTIONS').'/'.$value['file'];
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
   	* Used for add Admin Image media
   	*/
    public function addImageMedia(Request $request)
    {
    	if (request()->ajax()) {
            return \View::make('admin.imageMedia.add')->renderSections();
        }
    	return view('admin.imageMedia.add');
    }

    public function addImageMediaPost(Request $request)
    {
        $response = [];
    	$input = $request->all();
        $aData = [];
        $addQuesMedia = [];
        try
        {
    		$que_en_images = !empty($request->file('imagemedia'))?$request->file('imagemedia'):'';
            if (!empty($que_en_images)) {
                foreach ($que_en_images as $key=> $file) {
                    $gen_rand = rand(100,99999).time();
                    $image_path = $file;
                    $extension = $image_path->getClientOriginalExtension();
                    Storage::disk('public')->put(Config::get('siteglobal.images_dirs.QUESTIONS').'/'.$gen_rand.'.'.$extension,  File::get($image_path));
                    $addQuesMedia[$key]['file'] = $gen_rand.'.'.$extension;
                    $addQuesMedia[$key]['created_at'] = now();
                }
                $addImage =  ImageMedia::insert($addQuesMedia);
    	        if ($addImage) {
    	        	$response['status'] = true;
    		        $response['message'] = "Image Media Successfully Added";

    	        }else{
    	            $response['status'] = false;
    	            $response['message'] = "Something Wrong Please try again Later";
    	        }
            }else{
            	$response['status'] = false;
    	        $response['message'] = "Please Select Image";
            }
        }catch (\Exception $e) {
            $response['status'] = false;
            $response['message'] = "Error:" . $e->getMessage();
        }
        return response()->json($response);
        
    }

  	/**
   	* Used for Delete Admin App User
   	*/
    public function deleteImageMedia(Request $request)
    {
    	$input = $request->all();
    	$cid = $input['cid'];
    	$response = [];

    	if(empty($cid)){
    		$response['status'] = false;
    	}else{
        	$media = ImageMedia::where('image_media_id', $cid)->first();
            if (!empty($media->file)) {
                Storage::disk('public')->delete(Config::get('siteglobal.images_dirs.QUESTIONS').'/'.$media->file);
            }
        	if($media->delete()){
	            $response['status'] = true;
	            $response['message'] = "Image Media Successfully deleted";
        	}else{
        		$response['status'] = false;
	            $response['message'] = "Something Went Wrong";
        	}
        }
    	return response()->json($response);
    }
   
}
