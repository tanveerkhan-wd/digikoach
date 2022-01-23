<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\BannerDesc;
use Storage;
use File;
use Config;

class BannerImageController extends Controller
{
	
    /**
	 * Used for Admin Banner
	 * @return redirect to Admin->Banner
	 */
    public function index(Request $request)
    {
    	if (request()->ajax()) {
            return \View::make('admin.bannerImage.index')->renderSections();
        }
    	return view('admin.bannerImage.index');
    }


    /**
	 * Used for Admin get Banner
 	 * @return redirect to Admin->get Banner listing
	 */
    public function getBannerImage(Request $request)
    {
    	
      /**
       * Used for Admin get Banner Listing
       */
    	$data =$request->all();
	    
	    $perpage = !empty( $data[ 'length' ] ) ? (int)$data[ 'length' ] : 10;
	      
	    $filter = isset( $data['search'] ) && is_string( $data['search'] ) ? $data['search'] : '';

	    $sort_type = isset( $data['order'][0]['dir'] ) && is_string( $data['order'][0]['dir'] ) ? $data['order'][0]['dir'] : '';	

	    $sort_col =  isset($data['order'][0]['column']) ? $data['order'][0]['column'] :'';

	    $sort_field = isset($data['columns'][$sort_col]['data']) ? $data['columns'][$sort_col]['data'] :'';
	    
    	$aTable = Banner::with('banner_desc');

    	if($filter){
    		$aTable = $aTable->where('sequence', 'LIKE', '%' . $filter . '%' );
    	}

    	$aTableQuery = $aTable;

    	if($sort_col != 0){
    		$aTableQuery = $aTableQuery->orderBy($sort_field, $sort_type);
    	}else{
            $aTableQuery = $aTableQuery->orderBy('sequence', 'ASC');
        }

    	$total_table_data= $aTableQuery->count();

		$offset = isset($data['start']) ? $data['start'] :'';
	     
	    $counter = $offset;
	    $aTabledata = [];
	    $aTables = $aTableQuery->offset($offset)->limit($perpage)->get()->toArray();
        
        foreach ($aTables as $key => $value) {
            $name_en = '';
       		$name_hi = '';
            if (!empty($value['banner_desc'])) {
            	foreach ($value['banner_desc'] as $cd_key => $cd_value) {
                    if ($cd_value['lang_code']=='en') {
                        $name_en = !empty($cd_value['banner_file']) ? $cd_value['banner_file']:'';
                    }elseif ($cd_value['lang_code']=='hi') {
                        $name_hi = !empty($cd_value['banner_file']) ? $cd_value['banner_file']:'';
                    }
            	}
            }
            $value['banner_file_hi'] = !empty($name_hi) ? $name_hi :'';
            $value['banner_file_en'] = !empty($name_en) ? $name_en :'';
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
	 * Used for Admin add Banner
 	 * @return redirect to Admin->add Banner
	 */
    public function addBannerImage(Request $request)
    {
    	$response = [];
    	$input = $request->all();
        $banner_file_en = '';
        $banner_file_hi = '';
        $aData = [];
        $cdData = [];
        try{
            $getAllBanne = new Banner;
            if(!empty($input['pkCat']) && $input['pkCat'] != null){
                
        		$id = Banner::where('banner_id', $input['pkCat']);
        		$aData['sequence'] = $input['sequence'];
                $aData['updated_at'] = now();

                if ($id->update($aData)) {
                    
                    $cdId = BannerDesc::where('banner_id', $input['pkCat'])->get();

                    foreach ($cdId as $key => $value) {

                        $updateImage = BannerDesc::where('banner_desc_id', $value->banner_desc_id)->where('lang_code',$value->lang_code)->first();
                        if ($value->lang_code=='en') {
                            //If Image Updated 
                            if($request->hasFile('banner_img_eng')){
                                $gen_rand = rand(100,99999).time();
                                $image_path = $request->file('banner_img_eng');
                                $extension = $image_path->getClientOriginalExtension();
                                Storage::disk('public')->put(Config::get('siteglobal.images_dirs.BANNER').'/'.$gen_rand.'.'.$extension,  File::get($image_path));
                                if(!empty($updateImage->banner_file)){
                                    Storage::disk('public')->delete(Config::get('siteglobal.images_dirs.BANNER').'/'.$updateImage->banner_file);
                                }
                                $cdData['banner_file'] = $gen_rand.'.'.$extension;
                            }

                            $cdData['lang_code'] = $value->lang_code;
                        }else{
                            //If Image Updated 
                            if($request->hasFile('banner_img_hi')){
                                $gen_rand = rand(100,99999).time();
                                $image_path = $request->file('banner_img_hi');
                                $extension = $image_path->getClientOriginalExtension();
                                Storage::disk('public')->put(Config::get('siteglobal.images_dirs.BANNER').'/'.$gen_rand.'.'.$extension,  File::get($image_path));
                                if(!empty($updateImage->banner_file)){
                                    Storage::disk('public')->delete(Config::get('siteglobal.images_dirs.BANNER').'/'.$updateImage->banner_file);
                                }
                                $cdData['banner_file'] = $gen_rand.'.'.$extension;
                            }
                            $cdData['lang_code'] = $value->lang_code;
                        }
                        $cdData['banner_id'] = $input['pkCat'];

                        BannerDesc::where('banner_desc_id', $value->banner_desc_id)->where('lang_code',$cdData['lang_code'])->update($cdData);
                        $cdData = null;
                    }

                    $response['status'] = true;
                    $response['message'] = "Banner Successfully Updated";
                }
                
        	}else{
            
                //Store Banner
                $aTableData = new Banner;
                
                $aTableData->sequence = $input['sequence'];
                
                if (Banner::where('sequence','=',$input['sequence'])->count() > 0 && Banner::where('sequence','>=',$input['sequence'])->count() > 0) {
                    
                    $getAllBanne = $getAllBanne->where('sequence','>=',$input['sequence'])->get();
                    foreach ($getAllBanne as $key => $value) {
                        $incSequ = $value->sequence+1;
                        Banner::where('banner_id',$value->banner_id)->update(['sequence'=>$incSequ]);
                    }
                }

                if ($aTableData->save()) {
                    
                    if($request->hasFile('banner_img_eng')){
                        $gen_rand = rand(100,99999).time();
                        $image_path = $request->file('banner_img_eng');
                        $extension = $image_path->getClientOriginalExtension();
                        Storage::disk('public')->put(Config::get('siteglobal.images_dirs.BANNER').'/'.$gen_rand.'.'.$extension,  File::get($image_path));
                        $banner_file_en = $gen_rand.'.'.$extension;
                    }
                    if($request->hasFile('banner_img_hi')){
                        $gen_rand = rand(100,99999).time();
                        $image_path = $request->file('banner_img_hi');
                        $extension = $image_path->getClientOriginalExtension();
                        Storage::disk('public')->put(Config::get('siteglobal.images_dirs.BANNER').'/'.$gen_rand.'.'.$extension,  File::get($image_path));
                        $banner_file_hi = $gen_rand.'.'.$extension;
                    }

                	for ($i=1; $i <= 2 ; $i++) {
        	        	$aData[$i]['lang_code'] = $i==1 ? 'en' :'hi';
        	        	$aData[$i]['banner_id'] = $aTableData->banner_id;
        	        	$aData[$i]['banner_file'] = $i==1 ? $banner_file_en : $banner_file_hi;
                	}
                	$desc = BannerDesc::insert($aData);
                }

                if ($desc) {
                	$response['status'] = true;
        	        $response['message'] = "Banner Successfully Added";

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
    * Used for Edit Admin Banner
    */
    public function editBannerImage(Request $request)
    {
    	$input = $request->all();
    	$cid = $input['cid'];
    	$response = [];
        $lang = 'en';
    	$aTable = Banner::with('banner_desc')->where('banner_id','=',$input['cid'])->first()->toArray();
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
    public function deleteBannerImage(Request $request)
    {
    	$input = $request->all();
    	$cid = $input['cid'];
    	$response = [];
        $getAllBanne = new Banner;
    	if(empty($cid)){
    		$response['status'] = false;
    	}else{

            $getSequence = Banner::select('sequence')->where('banner_id', $cid)->first();
            $getAllBanne = $getAllBanne->where('sequence','>',$getSequence->sequence)->get();
            foreach ($getAllBanne as $key => $value) {
                $incSequ = $value->sequence-1;
                Banner::where('banner_id',$value->banner_id)->update(['sequence'=>$incSequ]);
            }

        	$bannerDesc = BannerDesc::where('banner_id', $cid)->get();
            foreach ($bannerDesc as $key => $value) {
                if (!empty($value->banner_file)) {
                    Storage::disk('public')->delete(Config::get('siteglobal.images_dirs.BANNER').'/'.$value->banner_file);
                }
            }
            
            Banner::where('banner_id', $cid)->delete();
    		$response['status'] = true;
            $response['message'] = "Banner Successfully deleted";
        }
    	return response()->json($response);
    }
    
}
