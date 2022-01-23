<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\FrontHelper;
use App\Models\CategoriesDesc;
use App\Models\Category;
use App\Models\User;
use App\Models\Exam;
use App\Models\UserCategory;
use App\Models\Question;
use App\Models\QuestionDesc;
use App\Models\ExamQuestion;
use App\Models\QuestionOption;
use App\Models\UserAttempt;
use App\Models\UserExamResponse;
use App\Models\QuestionMedia;
use App\Models\QuestionOptionDesc;
use Carbon\Carbon;
use Validator;
use Redirect;
use Storage;
use File;
use Config;
use DB;
use Auth;

class AppUserController extends Controller
{
	
    /**
	 * Used for Admin AppUser
	 * @return redirect to Admin->AppUser
	 */
    public function index(Request $request)
    {
        $streams = Category::with('category_desc')->get()->toArray();
        $category = FrontHelper::buildtree($streams);

    	if (request()->ajax()) {
            return \View::make('admin.appUser.index')->with('category',$category)->renderSections();
        }
    	return view('admin.appUser.index')->with('category',$category);
    }

    /**
	 * Used for Admin get App Users
 	 * @return redirect to Admin->get App Users listing
	*/
    public function getAppUsers(Request $request)
    {
    	
      /**
       * Used for Admin get App Users Listing
       */
    	$data =$request->all();
	    
	    $perpage = !empty( $data[ 'length' ] ) ? (int)$data[ 'length' ] : 10;
	      
	    $filter = isset( $data['search'] ) && is_string( $data['search'] ) ? $data['search'] : '';
	    $search_category = isset( $data['search_category'] ) ? $data['search_category'] :'';
	    
	    $status_type = isset( $data['status_type'] ) ? $data['status_type'] :'';

	    $sort_type = isset( $data['order'][0]['dir'] ) && is_string( $data['order'][0]['dir'] ) ? $data['order'][0]['dir'] : '';	

	    $sort_col =  isset($data['order'][0]['column']) ? $data['order'][0]['column'] :'';

	    $sort_field = isset($data['columns'][$sort_col]['data']) ? $data['columns'][$sort_col]['data'] :'';
	    
    	$aTable = User::with('fav_category')->where('user_type','=',2);

    	if($filter){
    		$aTable = $aTable->where('name', 'LIKE', '%' . $filter . '%' )->orWhere(function ($query) use($filter)
            {
                  $query->where('user_type','=',2)->where('mobile_number', 'LIKE', '%' . $filter . '%');
            })->orWhere(function ($quer) use($filter)
            {
                  $quer->where('user_type','=',2)->where('email', 'LIKE', '%' . $filter . '%');
            });
    	}

    	if ($search_category) {
    		$aTable = $aTable->whereHas('fav_category', function($query) use($search_category){
                $query->where('category_id',$search_category);
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
	    $aTables = $aTableQuery->where('user_type','=',2)->where('deleted',0)->offset($offset)->limit($perpage)->get()->toArray();
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
	 * Used for Admin AppUser
	 * @return redirect to Admin->edit AppUser
	 */
    public function editAppUser(Request $request,$id)
    {
    	$streams = Category::with('category_desc')->get()->toArray();
        $category = FrontHelper::buildtree($streams);

    	$data = User::with('fav_category')->where('user_id',$id)->where('user_type',2)->where('deleted',0)->first();

        //Get Selecte Category
        $userCateId = UserCategory::select('category_id')->where('user_id',$data->user_id)->get();
        
        //Get All sub category of parent 
        $subCate = Category::with('category_desc')->where('parent_category',$data->user_fav_category)->where('parent_category','!=',0)->get();
        
        if ($subCate->isEmpty()) {
          $subCate = Category::with('category_desc')->where('parent_category',$data->user_fav_category)->get();
        }
        if ($data) {
            
    	   if (request()->ajax()) {
             return \View::make('admin.appUser.editAppUser')->with(['userCateId'=>$userCateId,'subCate'=>$subCate,'data'=>$data, 'category'=>$category])->renderSections();
            }
    	   return view('admin.appUser.editAppUser')->with(['userCateId'=>$userCateId,'subCate'=>$subCate,'data'=>$data, 'category'=>$category]);
        }else{
            return redirect()->back()->with('errors','Something Went Wrong');
        }
    }
    

    /**
     * Used for Admin AppUser
     * @return redirect to Admin->edit post AppUser
     */
    public function getAppUserCategory(Request $request)
    {
        
        $data = $request->all();
        $subCate = Category::with('category_desc')->where('parent_category',$data['cid'])->get();

        if ($subCate) {
            $response['status'] = true;
            $response['data'] = $subCate;
        }
        else{
            $response['status'] = false;
            $response['message'] = "Something Went Wrong";
        }
            return response()->json($response);    
    }

	/**
	 * Used for Admin AppUser
	 * @return redirect to Admin->edit post AppUser
	 */
    public function editAppUserPost(Request $request)
    {
    	$response = [];
    	$input = $request->all();
        $aData = [];
        $userFavCat = '';
    	if(!empty($input['pkCat']) && $input['pkCat'] != null){
            
            $userData = User::where('user_id',$input['pkCat'])->first();
            if($request->hasFile('profile_img')){
                $gen_rand = rand(100,99999).time();
                $image_path = $request->file('profile_img');
                $extension = $image_path->getClientOriginalExtension();
                Storage::disk('public')->put(Config::get('siteglobal.images_dirs.USERS').'/'.$gen_rand.'.'.$extension,  File::get($image_path));

                if(!empty($userData->user_photo)){
                    Storage::disk('public')->delete(Config::get('siteglobal.images_dirs.USERS').'/'.$userData->user_photo);
                }
                $aData['user_photo'] = $gen_rand.'.'.$extension;
            }
            $user = User::where('user_id',$input['pkCat']);
    		$aData['name'] = $input['name'];
    		$aData['email'] = $input['email'];
    		$aData['mobile_number'] = $input['mobile_number'];
    		$aData['user_fav_category'] = !empty($input['parent_category']) ? $input['parent_category'] : '';

            $user->update($aData);
            //Insert Data Into UserCategory Table
            $getUserCateData = UserCategory::where('user_id',$input['pkCat'])->delete();
            foreach ($input['active_category'] as $value) {
                $addNewUserCat = new UserCategory;
                $addNewUserCat->category_id = $value;
                $addNewUserCat->user_id = $input['pkCat'];
                $addNewUserCat->save();
            }
		    $response['status'] = true;
            $response['message'] = "Profile Successfully Updated";
            
        }else{
        	$response['status'] = false;
            $response['message'] = "Something Went Wrong";
        }
        	    
	    return response()->json($response);
    }

  	/**
   	* Used for Delete Admin App User
   	*/
    public function deleteAppUser(Request $request)
    {
    	$input = $request->all();
    	$cid = $input['cid'];
    	$response = [];

    	if(empty($cid)){
    		$response['status'] = false;
    	}else{
        	$user = User::where('user_id', $cid)->first();
        	$user->deleted = 1;
        	$user->deleted_at = now();
        	if($user->update()){
	            $response['status'] = true;
	            $response['message'] = "App User Successfully deleted";
        	}else{
        		$response['status'] = false;
	            $response['message'] = "Something Went Wrong";
        	}
        }
    	return response()->json($response);
    }

    /**
    * Used for Delete Admin App User
    */
    public function statusAppUser(Request $request)
    {
        $input = $request->all();
        $cid = $input['cid'];
        $response = [];

        if(empty($cid)){
            $response['status'] = false;
        }else{
            $user = User::where('user_id', $cid)->first();
            $user->user_status = $user->user_status == 1 ? 0 : 1;
            if ($user->update()) {
                $response['status'] = true;
                $response['message'] = "Status Successfully changed";
            }else{
                $response['status'] = false;
            }
        }
        return response()->json($response);
    } 

	/**
	 * Used for Admin AppUser
	 * @return redirect to Admin->edit AppUser
	 */
    public function viewAppUser(Request $request,$id)
    {
    	$streams = Category::with('category_desc')->get()->toArray();
        $category = FrontHelper::buildtree($streams);

    	$data = User::with('fav_category','parent')->where('user_id',$id)->where('user_type',2)->first();
        if ($data!=null) {
            //Get Selecte Category
            $userCateId = UserCategory::select('category_id')->where('user_id',$data->user_id)->get();
            
            //Get All sub category of parent 
            $subCate = Category::with('category_desc')->where('parent_category',$data->user_fav_category)->where('parent_category','!=',0)->get();
            
            if ($subCate->isEmpty()) {
              $subCate = Category::with('category_desc')->where('parent_category',$data->user_fav_category)->get();
            }

            //Get GRAPH DATA for live test
            $testData = [];
            $getAllUserAttemData = UserAttempt::with('exam_desc','exam')->where('user_id',$id);
            $getAllAttemData = $getAllUserAttemData->whereHas('exam',function($exmTy){
                                    $exmTy->where('exams_type','LIVE_TEST');
                                    })->orderBy('attempted_on', 'desc')->take(5)->get();

            foreach ($getAllAttemData as $key => $value) {
                if (!empty($value->exam)) {
                    $testData[$value->exam->exams_type]['user_percentage'][] = $value['user_percentage'] ?? 0;
                    $testData[$value->exam->exams_type]['exam_name'][] = $value['exam_desc']['exam_name'] .'<br>'. date('d-M-Y', strtotime($value['attempted_on']));
                }
            }
            $getAllUserAttemData = UserAttempt::with('exam_desc','exam')->where('user_id',$id);
            $getAllAttemData = $getAllUserAttemData->whereHas('exam',function($exmTy){
                                    $exmTy->where('exams_type','QUIZZES');
                                    })->orderBy('attempted_on', 'desc')->take(5)->get();        
            foreach ($getAllAttemData as $key => $value) {
                if (!empty($value->exam)) {
                    $testData[$value->exam->exams_type]['user_percentage'][] = $value['user_percentage'] ?? 0;
                    $testData[$value->exam->exams_type]['exam_name'][] = $value['exam_desc']['exam_name'] .'<br>'. date('d-M-Y', strtotime($value['attempted_on']));
                }
            }
            $getAllUserAttemData = UserAttempt::with('exam_desc','exam')->where('user_id',$id);
            $getAllAttemData = $getAllUserAttemData->whereHas('exam',function($exmTy){
                                    $exmTy->where('exams_type','PRACTICE_TEST');
                                    })->orderBy('attempted_on', 'desc')->take(5)->get();        
            foreach ($getAllAttemData as $key => $value) {
                if (!empty($value->exam)) {
                    $testData[$value->exam->exams_type]['user_percentage'][] = $value['user_percentage'] ?? 0;
                    $testData[$value->exam->exams_type]['exam_name'][] = $value['exam_desc']['exam_name'] .'<br>'. date('d-M-Y', strtotime($value['attempted_on']));
                }
            }
            $getAllUserAttemData = UserAttempt::with('exam_desc','exam')->where('user_id',$id);
            $getAllAttemData = $getAllUserAttemData->whereHas('exam',function($exmTy){
                                    $exmTy->where('exams_type','GK_CA');
                                    })->orderBy('attempted_on', 'desc')->take(5)->get();        
            foreach ($getAllAttemData as $key => $value) {
                if (!empty($value->exam)) {
                    $testData[$value->exam->exams_type]['user_percentage'][] = $value['user_percentage'] ?? 0;
                    $testData[$value->exam->exams_type]['exam_name'][] = $value['exam_desc']['exam_name'] .'<br>'. date('d-M-Y', strtotime($value['attempted_on']));
                }
            }

            if (request()->ajax()) {
                return \View::make('admin.appUser.viewAppUser')->with(['testData'=>$testData,'userCateId'=>$userCateId,'subCate'=>$subCate,'data'=>$data, 'category'=>$category])->renderSections();
            }
            return view('admin.appUser.viewAppUser')->with(['testData'=>$testData,'userCateId'=>$userCateId,'subCate'=>$subCate,'data'=>$data, 'category'=>$category]);
            
        }else{
            return redirect()->back()->with('errors','Not a app users');
        }
    }

    /**
     * Used for Admin get liveTestPerformmanceChart
     * @return redirect to Admin->get liveTestPerformmanceChart
    */
    public function liveTestPerformmanceChart(Request $request)
    {
        $data =$request->all();
        $from = date('Y-m-d',strtotime($data['start_date']));
        $to = date('Y-m-d',strtotime($data['end_date']));
        //Get GRAPH DATA for live test
        $testData = [];
        $getAllUserAttemData = UserAttempt::with('exam_desc','exam')->whereBetween('attempted_on', [$from, $to])->where('user_id',$data['user_id'])->get();
        foreach ($getAllUserAttemData as $key => $value) {
            if (!empty($value->exam)) {
                $testData[$value->exam->exams_type]['user_percentage'][] = $value['user_percentage'] ?? 0;
                $testData[$value->exam->exams_type]['exam_name'][] = $value['exam_desc']['exam_name'] .'<br>'. date('d-M-Y', strtotime($value['attempted_on']));
            }
        }
        if (empty($testData)) {
            $gradata['percentage'] = [];
            $gradata['name'] = [];
        }else{
            if ($data['type']=='live') {
                $gradata['percentage'] = $testData['LIVE_TEST']['user_percentage'];
                $gradata['name'] = $testData['LIVE_TEST']['exam_name'];
            }elseif($data['type']=='quiz'){
                $gradata['percentage'] = $testData['QUIZZES']['user_percentage'];
                $gradata['name'] = $testData['QUIZZES']['exam_name'];
            }elseif($data['type']=='practice'){
                $gradata['percentage'] = $testData['PRACTICE_TEST']['user_percentage'];
                $gradata['name'] = $testData['PRACTICE_TEST']['exam_name'];
            }elseif($data['type']=='gk_ca'){
                $gradata['percentage'] = $testData['GK_CA']['user_percentage'];
                $gradata['name'] = $testData['GK_CA']['exam_name'];
            }
        }
        return $gradata;
    }

    /**
     * Used for Admin get getLiveTest
     * @return redirect to Admin->get getLiveTest listing
    */
    public function getLiveTest(Request $request)
    {
      /**
       * Used for Admin get getLiveTest Listing
       */
        $data =$request->all();
        
        $perpage = !empty( $data[ 'length' ] ) ? (int)$data[ 'length' ] : 10;
          
        $filter = isset( $data['search'] ) && is_string( $data['search'] ) ? $data['search'] : '';
        
        $search_category = isset( $data['search_category'] ) ? $data['search_category'] :'';
        
        $date_from = isset( $data['date_from'] ) ? $data['date_from'] :'';

        $date_to = isset( $data['date_to'] ) ? $data['date_to'] :'';

        $sort_type = isset( $data['order'][0]['dir'] ) && is_string( $data['order'][0]['dir'] ) ? $data['order'][0]['dir'] : '';    

        $sort_col =  isset($data['order'][0]['column']) ? $data['order'][0]['column'] :'';

        $sort_field = isset($data['columns'][$sort_col]['data']) ? $data['columns'][$sort_col]['data'] :'';
        
        $dataUserId = $data['get_user_id'] ?? '';

        $aTable = UserAttempt::with('exam','exam_desc')->where('user_id',$dataUserId);
        $aTable = $aTable->whereHas('exam',function($exmque){
            $exmque->where('exams_type','LIVE_TEST');
        });
        if($filter){
            $aTable = $aTable->whereHas('exam_desc', function($query) use($filter){
                $query->where('exam_name', 'LIKE', '%' . $filter . '%' );
            })->orWhereHas('exam',function($exquery) use($filter){
                $exquery->where('exams_type', 'LIKE', '%' . $filter . '%' )->orWhere('exam_duration', 'LIKE', '%' . $filter . '%' )->orWhere('total_questions', 'LIKE', '%' . $filter . '%' );
            });
        }

        if ($search_category) {
            $aTable = $aTable->whereHas('exam', function($findcat) use($search_category){
                $findcat->where('category_id',$search_category);
            }); 
        }

        if ($date_from || $date_to) {
            $date_from = date('Y-m-d',strtotime($date_from));
            $date_to = date('Y-m-d',strtotime($date_to));
            $aTable = $aTable->whereHas('exam', function($finddate) use($date_from , $date_to){
                $finddate->whereBetween('exam_starts_on',[$date_from , $date_to]);
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
        

        $cate = [];


        foreach ($aTables as $key => $value) {
            $streams = Category::with('category_desc')->get()->toArray();
            $category = FrontHelper::buildtree($streams);
            foreach ($category as $ckey => $cvalue){
                if ($cvalue['category_id']==$value['exam']['category_id']) {
                    $cate[$key] = $cvalue['category_desc'][0]['name'];
                }
                
                if(!empty($cvalue['children'])){
                foreach ($cvalue['children'] as $key1 => $value1){
                    if ($value1['category_id']==$value['exam']['category_id']) {
                        $cate[$key] = $cvalue['category_desc'][0]['name'].' > '.$value1['category_desc'][0]['name'];
                    }

                        if(!empty($value1['children'])){
                        foreach ($value1['children'] as $key2 => $value2) {
                            if ($value2['category_id']==$value['exam']['category_id']) {
                                $cate[$key] = $cvalue['category_desc'][0]['name'].' > '.$value1['category_desc'][0]['name']. ' > ' .$value2['category_desc'][0]['name'];
                            }
     
                            if(!empty($value2['children'])){
                            foreach ($value2['children'] as $key3 => $value3){
                                if ($value3['category_id'] == $value['exam']['category_id']) {
                                    $cate[$key] = $cvalue['category_desc'][0]['name'].' > '.$value1['category_desc'][0]['name']. ' > ' .$value2['category_desc'][0]['name'].' > '.$value3['category_desc'][0]['name'];
                                }
                                
                                if(!empty($value3['children'])){
                                foreach ($value3['children'] as $key4 => $value4){
                                    if ($value4['category_id']==$value['exam']['category_id']) {
                                        $cate[$key] = $cvalue['category_desc'][0]['name'].' > '.$value1['category_desc'][0]['name']. ' > ' .$value2['category_desc'][0]['name'].' > '.$value3['category_desc'][0]['name'].' > '.$value4['category_desc'][0]['name'];
                                    }
                                }
                                }
                            }
                            }

                        }
                        }
                    
                    }
                    }

                }


            $getMarks = DB::table('questions')->where('exam_id',$value['exam']['exam_id'])->sum('marks');
            $value['marks'] = $getMarks;
            $value['index'] = $counter+1;
            $value['category'] = $cate[$key];
            $value['date_time'] = date('d-M-Y, h:i',strtotime($value['exam']['exam_starts_on'])) ." To <br>". date('d-M-Y, h:i',strtotime($value['exam']['exam_ends_on']));
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
     * Used for Admin get getPracticeTest
     * @return redirect to Admin->get getPracticeTest listing
    */
    public function getPracticeTest(Request $request)
    {
      /**
       * Used for Admin get getPracticeTest Listing
       */
        $data =$request->all();
        
        $perpage = !empty( $data[ 'length' ] ) ? (int)$data[ 'length' ] : 10;
          
        $filter = isset( $data['search'] ) && is_string( $data['search'] ) ? $data['search'] : '';
        
        $search_category = isset( $data['search_category'] ) ? $data['search_category'] :'';
        
        $sort_type = isset( $data['order'][0]['dir'] ) && is_string( $data['order'][0]['dir'] ) ? $data['order'][0]['dir'] : '';    

        $sort_col =  isset($data['order'][0]['column']) ? $data['order'][0]['column'] :'';

        $sort_field = isset($data['columns'][$sort_col]['data']) ? $data['columns'][$sort_col]['data'] :'';
        
        $dataUserId = $data['get_user_id'] ?? '';

        $aTable = UserAttempt::with('exam','exam_desc')->where('user_id',$dataUserId);
        $aTable = $aTable->whereHas('exam',function($exmque){
            $exmque->where('exams_type','PRACTICE_TEST');
        });
        if($filter){
            $aTable = $aTable->whereHas('exam_desc', function($query) use($filter){
                $query->where('exam_name', 'LIKE', '%' . $filter . '%' );
            })->orWhereHas('exam',function($exquery) use($filter){
                $exquery->where('exams_type', 'LIKE', '%' . $filter . '%' )->orWhere('exam_duration', 'LIKE', '%' . $filter . '%' )->orWhere('total_questions', 'LIKE', '%' . $filter . '%' );
            });
        }

        if ($search_category) {
            $aTable = $aTable->whereHas('exam', function($findcat) use($search_category){
                $findcat->where('category_id',$search_category);
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
        

        $cate = [];


        foreach ($aTables as $key => $value) {
            $streams = Category::with('category_desc')->get()->toArray();
            $category = FrontHelper::buildtree($streams);
            foreach ($category as $ckey => $cvalue){
                if ($cvalue['category_id']==$value['exam']['category_id']) {
                    $cate[$key] = $cvalue['category_desc'][0]['name'];
                }
                
                if(!empty($cvalue['children'])){
                foreach ($cvalue['children'] as $key1 => $value1){
                    if ($value1['category_id']==$value['exam']['category_id']) {
                        $cate[$key] = $cvalue['category_desc'][0]['name'].' > '.$value1['category_desc'][0]['name'];
                    }

                        if(!empty($value1['children'])){
                        foreach ($value1['children'] as $key2 => $value2) {
                            if ($value2['category_id']==$value['exam']['category_id']) {
                                $cate[$key] = $cvalue['category_desc'][0]['name'].' > '.$value1['category_desc'][0]['name']. ' > ' .$value2['category_desc'][0]['name'];
                            }
     
                            if(!empty($value2['children'])){
                            foreach ($value2['children'] as $key3 => $value3){
                                if ($value3['category_id'] == $value['exam']['category_id']) {
                                    $cate[$key] = $cvalue['category_desc'][0]['name'].' > '.$value1['category_desc'][0]['name']. ' > ' .$value2['category_desc'][0]['name'].' > '.$value3['category_desc'][0]['name'];
                                }
                                
                                if(!empty($value3['children'])){
                                foreach ($value3['children'] as $key4 => $value4){
                                    if ($value4['category_id']==$value['exam']['category_id']) {
                                        $cate[$key] = $cvalue['category_desc'][0]['name'].' > '.$value1['category_desc'][0]['name']. ' > ' .$value2['category_desc'][0]['name'].' > '.$value3['category_desc'][0]['name'].' > '.$value4['category_desc'][0]['name'];
                                    }
                                }
                                }
                            }
                            }

                        }
                        }
                    
                    }
                    }

                }


            $getMarks = DB::table('questions')->where('exam_id',$value['exam']['exam_id'])->sum('marks');
            $value['index'] = $counter+1;
            $value['category'] = $cate[$key];
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
     * Used for Admin get getQuizTest
     * @return redirect to Admin->get getQuizTest listing
    */
    public function getQuizTest(Request $request)
    {
      /**
       * Used for Admin get getQuizTest Listing
       */
        $data =$request->all();
        
        $perpage = !empty( $data[ 'length' ] ) ? (int)$data[ 'length' ] : 10;
          
        $filter = isset( $data['search'] ) && is_string( $data['search'] ) ? $data['search'] : '';
        
        $search_category = isset( $data['search_category'] ) ? $data['search_category'] :'';
        
        $sort_type = isset( $data['order'][0]['dir'] ) && is_string( $data['order'][0]['dir'] ) ? $data['order'][0]['dir'] : '';    

        $sort_col =  isset($data['order'][0]['column']) ? $data['order'][0]['column'] :'';

        $sort_field = isset($data['columns'][$sort_col]['data']) ? $data['columns'][$sort_col]['data'] :'';
        
        $dataUserId = $data['get_user_id'] ?? '';

        $aTable = UserAttempt::with('exam','exam_desc')->where('user_id',$dataUserId);
        $aTable = $aTable->whereHas('exam',function($exmque){
            $exmque->where('exams_type','QUIZZES');
        });
        if($filter){
            $aTable = $aTable->whereHas('exam_desc', function($query) use($filter){
                $query->where('exam_name', 'LIKE', '%' . $filter . '%' );
            })->orWhereHas('exam',function($exquery) use($filter){
                $exquery->where('exams_type', 'LIKE', '%' . $filter . '%' )->orWhere('exam_duration', 'LIKE', '%' . $filter . '%' )->orWhere('total_questions', 'LIKE', '%' . $filter . '%' );
            });
        }

        if ($search_category) {
            $aTable = $aTable->whereHas('exam', function($findcat) use($search_category){
                $findcat->where('category_id',$search_category);
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
        

        $cate = [];


        foreach ($aTables as $key => $value) {
            $streams = Category::with('category_desc')->get()->toArray();
            $category = FrontHelper::buildtree($streams);
            foreach ($category as $ckey => $cvalue){
                if ($cvalue['category_id']==$value['exam']['category_id']) {
                    $cate[$key] = $cvalue['category_desc'][0]['name'];
                }
                
                if(!empty($cvalue['children'])){
                foreach ($cvalue['children'] as $key1 => $value1){
                    if ($value1['category_id']==$value['exam']['category_id']) {
                        $cate[$key] = $cvalue['category_desc'][0]['name'].' > '.$value1['category_desc'][0]['name'];
                    }

                        if(!empty($value1['children'])){
                        foreach ($value1['children'] as $key2 => $value2) {
                            if ($value2['category_id']==$value['exam']['category_id']) {
                                $cate[$key] = $cvalue['category_desc'][0]['name'].' > '.$value1['category_desc'][0]['name']. ' > ' .$value2['category_desc'][0]['name'];
                            }
     
                            if(!empty($value2['children'])){
                            foreach ($value2['children'] as $key3 => $value3){
                                if ($value3['category_id'] == $value['exam']['category_id']) {
                                    $cate[$key] = $cvalue['category_desc'][0]['name'].' > '.$value1['category_desc'][0]['name']. ' > ' .$value2['category_desc'][0]['name'].' > '.$value3['category_desc'][0]['name'];
                                }
                                
                                if(!empty($value3['children'])){
                                foreach ($value3['children'] as $key4 => $value4){
                                    if ($value4['category_id']==$value['exam']['category_id']) {
                                        $cate[$key] = $cvalue['category_desc'][0]['name'].' > '.$value1['category_desc'][0]['name']. ' > ' .$value2['category_desc'][0]['name'].' > '.$value3['category_desc'][0]['name'].' > '.$value4['category_desc'][0]['name'];
                                    }
                                }
                                }
                            }
                            }

                        }
                        }
                    
                    }
                    }

                }


            $getMarks = DB::table('questions')->where('exam_id',$value['exam']['exam_id'])->sum('marks');
            $value['marks'] = $getMarks;
            $value['index'] = $counter+1;
            $value['category'] = $cate[$key];
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
     * Used for Admin get getGkQuizTest
     * @return redirect to Admin->get getGkQuizTest listing
    */
    public function getGkQuizTest(Request $request)
    {
      /**
       * Used for Admin get getGkQuizTest Listing
       */
        $data =$request->all();
        
        $perpage = !empty( $data[ 'length' ] ) ? (int)$data[ 'length' ] : 10;
          
        $filter = isset( $data['search'] ) && is_string( $data['search'] ) ? $data['search'] : '';
        
        $search_category = isset( $data['search_category'] ) ? $data['search_category'] :'';
        
        $date_from = isset( $data['date_from'] ) ? $data['date_from'] :'';

        $date_to = isset( $data['date_to'] ) ? $data['date_to'] :'';

        $status_type = isset( $data['status_type'] ) ? $data['status_type'] :'';

        $sort_type = isset( $data['order'][0]['dir'] ) && is_string( $data['order'][0]['dir'] ) ? $data['order'][0]['dir'] : '';    

        $sort_col =  isset($data['order'][0]['column']) ? $data['order'][0]['column'] :'';

        $sort_field = isset($data['columns'][$sort_col]['data']) ? $data['columns'][$sort_col]['data'] :'';
        
        $dataUserId = $data['get_user_id'] ?? '';

        $aTable = UserAttempt::with('exam','exam_desc')->where('user_id',$dataUserId);
        $aTable = $aTable->whereHas('exam',function($exmque){
            $exmque->where('exams_type','GK_CA');
        });
        if($filter){
            $aTable = $aTable->whereHas('exam_desc', function($query) use($filter){
                $query->where('exam_name', 'LIKE', '%' . $filter . '%' );
            })->orWhereHas('exam',function($exquery) use($filter){
                $exquery->where('exams_type', 'LIKE', '%' . $filter . '%' )->orWhere('exam_duration', 'LIKE', '%' . $filter . '%' )->orWhere('total_questions', 'LIKE', '%' . $filter . '%' );
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
        

        $cate = [];


        foreach ($aTables as $key => $value) {
            $getMarks = DB::table('questions')->where('exam_id',$value['exam']['exam_id'])->sum('marks');
            $value['marks'] = $getMarks;
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
     * Used for Admin AppUser viewUserTest 
     * @return redirect to Admin->AppUser/viewUserTest 
     */
    public function viewUserTest(Request $request,$id)
    {
        $cate = '';
        $testData = [];
        $no_of = [];
        $getQueCate = [];
        $marks = [];
        
        $data = UserAttempt::with('exam','exam_desc')->where('user_attempt_id',$id)->first()->toArray();

        $getExam = Exam::with('desc','category_desc')->where('exam_id',$data['exam_id'])->first()->toArray();

        //GEt CATEGORY WITH SUB CATEGORY
        $streams = Category::with('category_desc')->get()->toArray();
        $category = FrontHelper::buildtree($streams);
        $cate = FrontHelper::getSingleHeararcyofCat($category,$getExam['category_id']);
        //END

        if ($getExam['exams_type']=='GK_CA') {
            $cate = 'Gk & Ca';
        }

        //GET ALL ATTEMPTED OPTION
        $getAttemptedOption = UserExamResponse::where('exam_id',$getExam['exam_id'])->where('user_id',$data['user_id'])->get()->toArray();

        $getAllQue = ExamQuestion::with('question')->where('exam_id',$getExam['exam_id'])->get()->toArray();
        foreach ($getAllQue as $key => $value) {
            if (!empty($value['question'])) {
                $testData[$key]['questions_id'] = $value['question']['questions_id'];
                $testData[$key]['question_media'] = QuestionMedia::whereIn('media_int_type',['QUESTION','SOLUTION'])->where('lang_code','en')->where('media_int_id',$value['question']['questions_id'])->get();
                $testData[$key]['category_id'] = $value['question']['category_id'];
                $testData[$key]['question'] = $value['question']['question_desc']['question_text'];
                $testData[$key]['solution'] = $value['question']['question_desc']['solution_text'];
                $testData[$key]['option'] = isset($value['question']['options']) ? $value['question']['options'] : false;
                if ($testData[$key]['option']) {
                    foreach ($value['question']['options'] as $k => $v) {
                        $testData[$key]['option_media'][$k] = QuestionMedia::where('media_int_type','OPTION')->where('lang_code','en')->where('media_int_id',$v['question_options_id'])->get()->toArray();        
                    }
                }
                $getQueCate[$key] =  ['category_id'=>$value['question']['category_id'],'category_desc'=>$value['question']['category_desc']];
                $marks[] = $value['question']['marks'];
            }
        }
        $getQueCate = array_map("unserialize", array_unique(array_map("serialize", $getQueCate)));
        $no_of['marks'] = array_sum($marks);
        $no_of['quetion'] = ExamQuestion::where('exam_id',$getExam['exam_id'])->count();
        
        if (request()->ajax()) {
            return \View::make('admin.appUser.viewUserTest')->with(['getAttemptedOption'=>$getAttemptedOption,'data'=>$data,'cate'=>$cate,'getQueCate'=>$getQueCate,'getExam'=>$getExam,'testData'=>$testData,'no_of'=>$no_of])->renderSections();
        }
        return view('admin.appUser.viewUserTest')->with(['getAttemptedOption'=>$getAttemptedOption,'data'=>$data,'cate'=>$cate,'getQueCate'=>$getQueCate,'getExam'=>$getExam,'testData'=>$testData,'no_of'=>$no_of]);
    }
}
