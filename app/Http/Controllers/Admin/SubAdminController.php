<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\FrontHelper;
use App\Models\CategoriesDesc;
use App\Models\Category;
use App\Models\AdminModule;
use App\Models\EmailMaster;
use App\Helpers\MailHelper;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\View\View;
use App\Mail\ForgotPassword;
use App\Models\User;
use Carbon\Carbon;
use Storage;
use File;
use Config;
use DB;
use URL;

class SubAdminController extends Controller
{
	/**
	 * Used for Admin subAdmin
	 * @return redirect to Admin->subAdmin
	 */
    public function index(Request $request)
    {
    	if (request()->ajax()) {
            return \View::make('admin.subAdmin.index')->renderSections();
        }
    	return view('admin.subAdmin.index');
    }

    /**
	 * Used for Admin get App Users
 	 * @return redirect to Admin->get App Users listing
	*/
    public function getSubAdmins(Request $request)
    {
    	
      /**
       * Used for Admin get App Users Listing
       */
    	$data =$request->all();
	    
	    $perpage = !empty( $data[ 'length' ] ) ? (int)$data[ 'length' ] : 10;
	      
	    $filter = isset( $data['search'] ) && is_string( $data['search'] ) ? $data['search'] : '';
	    
	    $status_type = isset( $data['status_type'] ) ? $data['status_type'] :'';

	    $sort_type = isset( $data['order'][0]['dir'] ) && is_string( $data['order'][0]['dir'] ) ? $data['order'][0]['dir'] : '';	

	    $sort_col =  isset($data['order'][0]['column']) ? $data['order'][0]['column'] :'';

	    $sort_field = isset($data['columns'][$sort_col]['data']) ? $data['columns'][$sort_col]['data'] :'';
	    
    	$aTable = User::where('user_type',1)->where('deleted',0);

    	if($filter){
            $aTable = $aTable->where('name', 'LIKE', '%' . $filter . '%' )->orWhere(function ($query) use($filter)
            {
                  $query->where('user_type','=',1)->where('mobile_number', 'LIKE', '%' . $filter . '%');
            })->orWhere(function ($quer) use($filter)
            {
                  $quer->where('user_type','=',1)->where('email', 'LIKE', '%' . $filter . '%');
            });
        }

    	if ($status_type) {
            $status_type = $status_type=='Active'?1:0;
    		$aTable = $aTable->where('user_status',$status_type);
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
            $value['created_at'] = date('d-M-Y',strtotime($value['created_at']));
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
	 * Used for Admin addSubAdmin
	 * @return redirect to Admin->addSubAdmin
	 */
    public function addSubAdmin(Request $request)
    {
        if (request()->ajax()) {
            return \View::make('admin.subAdmin.addSubAdmin')->renderSections();
        }
    	return view('admin.subAdmin.addSubAdmin');
    }


    /**
	 * Used for Admin add addSubAdminPost
 	 * @return redirect to Admin->add addSubAdminPost
	 */
    public function addSubAdminPost(Request $request)
    {
    	$response = [];
    	$input = $request->all();
        $aData = [];
        $cdData = [];
        try
        {
            if(!empty($input['pkCat']) && $input['pkCat'] != null){
            	$checkPrev = User::where('email',$input['email'])->where('user_id','!=',$input['pkCat'])->where('deleted',0)->whereNull('deleted_at')->count();
            	if ($checkPrev > 0) {
            		$response['status'] = false;
                  	$response['message'] ="Email already exists";
            		return response()->json($response);
            	}else{
    	        	$userData = User::where('user_id',$input['pkCat']);
    	            $aData['name'] = $input['name'];
    	            $aData['mobile_number'] = $input['mobile_number'];
    	            $aData['email'] = $input['email'];
    	            if ($userData->update($aData)) {
    	            	$response['status'] = true;
    	              	$response['message'] ="Sub Admin Successfully Updated";	
    	            }else{
    	            	$response['status'] = false;
    	              	$response['message'] ="Something Went Wrong please try again";	
    	            }
            	}

        	}else{

                $checkPrev = DB::table('users')->where('email','=',$input['email'])->where('deleted',0)->whereNull('deleted_at')->first();
                
                if(!empty($checkPrev)){
                  $response['status'] = false;
                  $response['message'] ="Email already exists";
                }else{
                    $adminModule = [];
                    
                	//Store Sub Admin User
    	            $aTableData = new User;
    	            $aTableData->name = $input['name'];
    	            $aTableData->email = $input['email'];
    	            $aTableData->mobile_number = $input['mobile_number'];
    	            $aTableData->user_type = 1;
    	            $aTableData->user_status = true;
    	            $aTableData->save();
    	            if ($aTableData) {
                        $data = EmailMaster::where('email_key', 'sub_admin_set_password')->first();
    			        if (isset($data) && !empty($data)) {
    			            $data = $data->toArray();

    			           	$uType = 'Sub-Admin';
    			           	$pass = FrontHelper::generatePassword(10);
    			           	$current_time = date("Y-m-d H:i:s");
    			           	$reset_pass_token = base64_encode($input['email'].'&&'.$uType."&&".$current_time);
    			            $message = $data['content'];

    			            $subject = $data['subject'];
    			            
    			            $message1 = str_replace("{{USERNAME}}", $input['name'], $message);
    			            
    			            $link = URL::to('sub-admin/setPassword',$reset_pass_token);
    			            
    			            $fullUrl = "<a style='padding:5px;color:white;background:#2682df;border:1px solid #2682df;border-radius: 4px;text-decoration: none;' href='".$link."'>Set Password</a>";

    			            $msg = str_replace("{{PASSWORD}}", $fullUrl, $message1);
                            try{
                                $mail = Mail::to($aTableData->email)->send(new ForgotPassword($msg,$subject));
                            }catch(\Exception $e){

                            }
    		        	}

    	            	$response['status'] = true;
    	              	$response['message'] ="Sub-Admin Successfully Added";
    	            }else{
    	            	$response['status'] = false;
    	              	$response['message'] ="Something went wrong please try again";
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
     * Used for Admin accessPrivileges
     * @return redirect to Admin->accessPrivileges
     */
    public function accessPrivileges(Request $request, $id)
    {
        $accessPriData = [];
        $accessPrivilege = AdminModule::where('subadmin_id',$id)->get()->toArray();
        foreach ($accessPrivilege as $key => $value) {
            $accessPriData[$value['module']] = json_decode($value['sections']);
        }

        $user = User::where('user_id',$id)->first()->toArray();
        $categories = Category::with('desc')->where('parent_category',false)->get()->toArray();
        if (request()->ajax()) {
            return \View::make('admin.subAdmin.access_privileges')->with(['accessPriData'=>$accessPriData,'user'=>$user,'categories'=>$categories])->renderSections();
        }
        return view('admin.subAdmin.access_privileges')->with(['accessPriData'=>$accessPriData,'user'=>$user,'categories'=>$categories]);
        
    }




    /**
     * Used for Admin add accessPrivilegePost
     * @return redirect to Admin->add accessPrivilegePost
     */
    public function accessPrivilegePost(Request $request)
    {
        $response = [];
        $input = $request->all();
        $adminModule = [];
        //VALIDATE CATEGORY
        for ($i=0; $i < count($input['module']); $i++) {
            if ($i<=5 && !isset($input['categories'][$i]) && $input['view'][$i]==true) {
                $response['status'] = false;
                $response['message'] ="Please select category";
                return response()->json($response);
            }
            if ($i<=5 && !empty($input['categories'][$i]) && $input['view'][$i]==false) {
                $response['status'] = false;
                $response['message'] ="You have to check view to store category";
                return response()->json($response);
            }
        }
        if(!empty($input['pkCat']) && $input['pkCat'] != null){

            if($request->hasFile('user_img')){
                $user = User::where('user_id',$input['pkCat'])->first();
                $gen_rand = rand(100,99999).time();
                $image_path = $request->file('user_img');
                $extension = $image_path->getClientOriginalExtension();
                Storage::disk('public')->put(Config::get('siteglobal.images_dirs.USERS').'/'.$gen_rand.'.'.$extension,  File::get($image_path));
                if(!empty($user->user_photo)){
                    Storage::disk('public')->delete(Config::get('siteglobal.images_dirs.USERS').'/'.$user->user_photo);
                }
                $userImage = $gen_rand.'.'.$extension;
                User::where('user_id',$input['pkCat'])->update(['user_photo'=>$userImage]);
            }

            $deleteExtData = AdminModule::where('subadmin_id',$input['pkCat']);
            if ($deleteExtData->count() >0) {
                $deleteExtData->delete();
            }
            for ($i=0; $i < count($input['module']); $i++) {
                $categories = !empty($input['categories'][$i]) ? $input['categories'][$i] : false;
                $cjStudents = !empty($input['students'][$i]) ? $input['students'][$i] : false;
                $cjAnswer = !empty($input['answer'][$i]) ? $input['answer'][$i] : false;
                $sections =  ['view'=>$input['view'][$i],'add'=>$input['add'][$i],'edit'=>$input['edit'][$i],'delete'=>$input['delete'][$i], 'status'=>$input['status'][$i], 'students'=>$cjStudents,'categories'=>$categories,'answer'=>$cjAnswer];

                $adminModule[$i]['subadmin_id'] = $input['pkCat'];
                $adminModule[$i]['module'] = $input['module'][$i];
                $adminModule[$i]['sections'] = json_encode($sections);
                $adminModule[$i]['created_at'] = now();
            }
            $data =  AdminModule::insert($adminModule);

            if ($data) {
                $response['status'] = true;
                $response['message'] ="Access Privileges Successfully Added"; 
            }else{
                $response['status'] = false;
                $response['message'] ="Something Went Wrong please try again";  
            }
        }
        return response()->json($response);
    }



    /**
	 * Used for Admin viewSubAdmin
	 * @return redirect to Admin->viewSubAdmin
	 */
    public function viewSubAdmin(Request $request,$id)
    {
    	$data = User::where('user_id',$id)->first();
    	if (request()->ajax()) {
            return \View::make('admin.subAdmin.viewSubAdmin')->with(['data'=>$data])->renderSections();
        }
    	return view('admin.subAdmin.viewSubAdmin')->with(['data'=>$data]);
    }


    /**
	 * Used for Admin editSubAdmin
	 * @return redirect to Admin->editSubAdmin
	 */
    public function editSubAdmin(Request $request,$id)
    {
    	$data = User::where('user_id',$id)->first();
    	if (request()->ajax()) {
            return \View::make('admin.subAdmin.editSubAdmin')->with(['data'=>$data])->renderSections();
        }
    	return view('admin.subAdmin.editSubAdmin')->with(['data'=>$data]);
    }
    

    /**
     * Used for Admin deleteSubAdmin
     * @return redirect to Admin->delete deleteSubAdmin
     */
    public function deleteSubAdmin(Request $request)
    {
        $input = $request->all();
        $cid = $input['cid'];
        $response = [];

        if(empty($cid)){
            $response['status'] = false;
        }else{
            $userData = User::where('user_id', $cid)->first();
            $userData->deleted = true;
            $userData->deleted_at = date('Y-m-d H:i:s',strtotime(now()));
            if($userData->update()){
                $response['status'] = true;
                $response['message'] = "Sub-Admin Successfully deleted";
            }else{
                $response['status'] = false;
                $response['message'] = "Something Went Wrong";
            }
        }
        return response()->json($response);
    }


    /**
    * Used for Delete Admin statusSubAdmin
    */
    public function statusSubAdmin(Request $request)
    {
        $input = $request->all();
        $cid = $input['cid'];
        $response = [];

        if(empty($cid)){
            $response['status'] = false;
        }else{
            $status = User::where('user_id',$cid)->first();
            $status->user_status = $status->user_status == 1 ? 0 : 1;
            if ($status->update()) {
                $response['status'] = true;
                $response['message'] = "Status Successfully changed";
            }else{
                $response['status'] = false;
            }
        }
        return response()->json($response);
    } 




}
