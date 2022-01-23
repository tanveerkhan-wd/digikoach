<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Mail\AnnounceLiveTestResult;
use App\Helpers\FrontHelper;
use App\Models\QuestionOptionDesc;
use App\Models\QuestionOption;
use App\Models\QuestionDesc;
use App\Models\EmailMaster;
use App\Models\UserAttempt;
use App\Models\ExamDesc;
use App\Models\Category;
use App\Models\Question;
use App\Models\ExamQuestion;
use App\Models\Exam;
use App\Models\User;
use App\Models\EmailLog;
use App\Models\Notification;
use App\Models\Translation;
use App\Models\QuestionMedia;
use App\Models\UserExamResponse;
use App\Models\NotificationDesc;
use Carbon\Carbon;
use UserNotifications;
use Redirect;
use Config;
use DB;
use Auth;

class LiveTestController extends Controller
{
    /**
	 * Used for Admin live test
	 * @return redirect to Admin->liveTest
	 */
    public function index(Request $request)
    {	
        $cateIdForQue = [];
    	$streams = Category::with('category_desc')->get()->toArray();
        $category = FrontHelper::buildtree($streams);

        //GIVE ACCESSS TO CATEGORY
        $accessPriData = session()->get('accessPriData');
        if (!empty($accessPriData) && Auth::user()->user_type==1) {
            $cateIdForQue = $accessPriData['Live_Test']->categories==true ? $accessPriData['Live_Test']->categories : [];
        }
        $getCategoryWithSubCat = FrontHelper::getCategoryWithSubCat($category,$cateIdForQue);

        if (request()->ajax()) {
            return \View::make('admin.liveTest.index')->with(['getCategoryWithSubCat'=>$getCategoryWithSubCat])->renderSections();
        }
    	return view('admin.liveTest.index')->with(['getCategoryWithSubCat'=>$getCategoryWithSubCat]);
    }   

    /**
	 * Used for Admin addLivetest
	 * @return redirect to Admin->addLivetest
	 */
    public function addLiveTest(Request $request)
    {
        $accessPriData = session()->get('accessPriData');

        $getCatgory = [];
        if(!empty($accessPriData['Live_Test']) && $accessPriData['Live_Test']->view==true)
        {
            $getCatgory = $accessPriData['Live_Test']->categories==true ? $getCatgory = $accessPriData['Live_Test']->categories: [] ;
        }
        if (Auth::user()->user_type==0) {
           $parent_category = Category::with('category_desc')->where('status',1)->where('parent_category',0)->get(); 
        }else{
           $parent_category = Category::with('category_desc')->whereIn('category_id',$getCatgory)->where('status',1)->where('parent_category',0)->get();
        }

    	if (request()->ajax()) {
            return \View::make('admin.liveTest.addLiveTest')->with(['parent_category'=>$parent_category])->renderSections();
        }
    	return view('admin.liveTest.addLiveTest')->with(['parent_category'=>$parent_category]);
    }

    /**
     * Used for Admin select nth level category
     * @return redirect to Admin->addCategory
     */
    public function getCategoryData(Request $request)
    {
        $data = $request->all();
        $subCate = Category::with('category_desc')->where('parent_category',$data['cid'])->get();
        if ($subCate->isEmpty()) {
            $subCate = Category::with('category_desc')->where('category_id',$data['cid'])->get();
        }
        foreach ($subCate as $key => $value) {
            $cnt_is_parent_cate = Category::where('parent_category',$data['cid'])->count();
            $cnt_categoryWiseQue = Question::where('category_id',$value->category_id)->where('question_type','LIVE_TEST')->count();
            $cnt_categoryWiseUnusedQue = Question::where('category_id',$value->category_id)->where('question_type','LIVE_TEST')->where('isAssignable',true)->where('status',1)->count();

            $value->is_parent_cate =  $cnt_is_parent_cate;
            $value->no_of_que = $cnt_categoryWiseQue;
            $value->no_of_unused_que = $cnt_categoryWiseUnusedQue;
        }
        if ($subCate->isNotEmpty()) {
            $response['status'] = true;
            $response['data'] = $subCate;
        }
        else if($subCate->isEmpty()){
            $response['data'] = 'not_found';
        }
        else{
            $response['status'] = false;
            $response['message'] = "Something Went Wrong";
        }

        return response()->json($response);    
    }

    
    /**
     * Used for Admin add addLiveTestPost
     * @return redirect to Admin->add addLiveTestPost
     */
    public function addLiveTestPost(Request $request)
    {
        $response = [];
        $input = $request->all();
        $addData = [];
        $update_que = [];
        $email_log = [];
        
        $checkQueNum = isset($input['question_number']) ? true: false;
        if ($checkQueNum==false) {
            $response['status'] = false;
            $response['message'] = "Please add question";
            return response()->json($response);
        }
        $checkQuestion = array_filter($input['question_number']);
        if (empty($checkQuestion)) {
            $response['status'] = false;
            $response['message'] = "Please add minimum 1 question";
            return response()->json($response);    
        }
        
        try
        {
            //EDIT LIVE TEST
            if(!empty($input['pkCat']) && $input['pkCat'] != null)
            {
                //ADD LIVE TEST
                $start_date = $input['start_date'];
                $end_date = $input['end_date'];
                $start_time = $input['start_time'];
                $end_time = $input['end_time'];
                $result_date = $input['result_date'];
                $result_time = $input['result_time'];


                $addExam['category_id'] = $input['main_category'];
                $addExam['exams_type'] = 'LIVE_TEST';
                $addExam['exam_duration'] = $input['duration'];
                $addExam['exam_starts_on'] = date('Y-m-d H:i:s',strtotime("$start_date $start_time"));
                $addExam['exam_ends_on'] = date('Y-m-d H:i:s',strtotime("$end_date $end_time"));
                $addExam['result_date'] = date('Y-m-d H:i:s',strtotime("$result_date $result_time"));
                $addExam['total_questions'] = array_sum($input['question_number']);
                $updateExm = Exam::where('exam_id',$input['pkCat'])->update($addExam);

                if ($updateExm) {
                    //UPDATE SELECTED QUESTION
                    //Question::where('exam_id',$input['pkCat'])->update(['isAssignable'=>true]);
                    $getAllQuetion = [];
                    foreach ($input['sub_category_id'] as $key=> $value) {
                        $limit = 0;
                        $total_questions = $input['question_number'][$key] != null ? $input['question_number'][$key] : 0;
                        $getQueCount = Question::where('exam_id',$input['pkCat'])->where('question_type','LIVE_TEST')->where('category_id',$value)->where('isAssignable',false)->count();
                        if ($getQueCount < $total_questions) {
                            $limit = $total_questions-$getQueCount;
                            $getAllQuetion = Question::inRandomOrder()->where('question_type','LIVE_TEST')->where('category_id',$value)->where('isAssignable',true)->where('status',1)->limit($limit)->get();
                            $countAllQue = $getAllQuetion->count();
                            if ($countAllQue < $limit) {
                                $response['status'] = false;
                                $response['message'] = "Question Limit Exceeds";
                                return response()->json($response);
                            }
                            foreach ($getAllQuetion as $uq_value) {
                                $update_que['exam_id'] = $input['pkCat'];
                                $update_que['isAssignable'] = false;
                                Question::where('questions_id',$uq_value->questions_id)->update($update_que);
                            }
                        }elseif($getQueCount > $total_questions){
                            $limit = $getQueCount-$total_questions;
                            Question::where('exam_id',$input['pkCat'])->where('question_type','LIVE_TEST')->where('category_id',$value)->where('isAssignable',false)->limit($limit)->update(['isAssignable'=>true]);
                        }
                        
                    }
                    
                    //UPDATE ALL QUESTION WITH EXAM ID TO EXAM QUESTION TABLE.
                    $exam_questions = [];
                    $getAllQueOfExam = Question::select('questions_id','exam_id')->where('question_type','LIVE_TEST')->where('exam_id',$input['pkCat'])->where('isAssignable',false)->get()->toArray();
                    if (!empty($getAllQueOfExam)) {
                        ExamQuestion::where('exam_id',$input['pkCat'])->delete();
                        foreach ($getAllQueOfExam as $key => $value) {
                            $exam_questions[$key]['questions_id'] = $value['questions_id'];
                            $exam_questions[$key]['exam_id'] = $value['exam_id'];
                            $exam_questions[$key]['created_at'] = now();
                            $exam_questions[$key]['updated_at'] = now();
                        }
                        ExamQuestion::insert($exam_questions);
                        $exam_questions = [];
                    }
                    
                    $addExam = [];
                    $total_marks = DB::table('questions')->where('exam_id',$input['pkCat'])->where('isAssignable',false)->sum('marks');
                    $addExam['total_marks'] = $total_marks;
                    Exam::where('exam_id',$input['pkCat'])->update($addExam);

                    $cdId = ExamDesc::where('exam_id', $input['pkCat'])->get();
                    foreach ($cdId as $key => $value) {
                        if ($value->lang_code=='en') {
                            $cdData['lang_code'] = $value->lang_code;
                            $cdData['exam_id'] = $input['pkCat'];
                            $cdData['exam_name'] = $input['name_en'];
                        }else{
                            $cdData['lang_code'] = $value->lang_code;
                            $cdData['exam_id'] = $input['pkCat'];
                            $cdData['exam_name'] = $input['name_hi'];
                        }
                        
                        $desc = ExamDesc::where('exam_id',$input['pkCat'])->where('lang_code',$value->lang_code)->update($cdData);
                        if ($desc) {
                            $response['status'] = true;
                            $response['message'] = "Exam Successfully Updated";
                        }
                        else{
                            $response['status'] = false;
                            $response['message'] = "Something Wrong Please try again Later";
                        }
                    }
                }

            }
            else
            {
                //ADD LIVE TEST
                $start_date = $input['start_date'];
                $end_date = $input['end_date'];
                $start_time = $input['start_time'];
                $end_time = $input['end_time'];
                $result_date = $input['result_date'];
                $result_time = $input['result_time'];

                $addExam = new Exam;
                $addExam->category_id = $input['main_category'];
                $addExam->exams_type = 'LIVE_TEST';
                $addExam->exam_duration = $input['duration'];
                $addExam->exam_starts_on = date('Y-m-d H:i:s',strtotime("$start_date $start_time"));
                $addExam->exam_ends_on = date('Y-m-d H:i:s',strtotime("$end_date $end_time"));
                $addExam->result_date = date('Y-m-d H:i:s',strtotime("$result_date $result_time"));
                $addExam->total_questions = array_sum($input['question_number']);
                $addExam->total_marks = false;

                if ($addExam->save()) {
                    //UPDATE SELECTED QUESTION
                    $actice_cat = [];
                    foreach ($input['sub_category_id'] as $key=> $value) {
                        /*==GET ACTIVE CATEGORY ==*/
                        if ($input['question_number'][$key] != null) {
                            $actice_cat[] = $value;
                        }
                        /*== /END ==*/
                        $limit = $input['question_number'][$key] != null ? $input['question_number'][$key] :0;
                        $getAllQuetion = Question::inRandomOrder()->where('question_type','LIVE_TEST')->where('category_id',$value)->where('isAssignable',true)->where('status',1)->limit($limit)->get();
                        
                        foreach ($getAllQuetion as $uq_value) {
                            $update_que['exam_id'] = $addExam->exam_id;
                            $update_que['isAssignable'] = false;
                            Question::where('questions_id',$uq_value->questions_id)->update($update_que);
                        }
                        
                    }

                    //ADD ALL QUESTION WITH EXAM ID TO EXAM QUESTION TABLE.
                    $exam_questions = [];
                    $getAllQueOfExam = Question::select('questions_id','exam_id')->where('exam_id',$addExam->exam_id)->where('question_type','LIVE_TEST')->where('isAssignable',false)->get()->toArray();
                    foreach ($getAllQueOfExam as $key => $value) {
                        $exam_questions[$key]['questions_id'] = $value['questions_id'];
                        $exam_questions[$key]['exam_id'] = $value['exam_id'];
                        $exam_questions[$key]['created_at'] = now();
                    }
                    ExamQuestion::insert($exam_questions);
                    
                    $total_marks = DB::table('questions')->where('isAssignable',false)->where('question_type','LIVE_TEST')->where('exam_id',$addExam->exam_id)->sum('marks');
                    Exam::where('exam_id',$addExam->exam_id)->update(['total_marks'=>$total_marks]);

                    for ($i=1; $i <= 2 ; $i++) { 
                        $addData[$i]['lang_code'] = $i==1 ? 'en' :'hi';
                        $addData[$i]['exam_id'] = $addExam->exam_id;
                        $addData[$i]['exam_name'] = $i==1 ? $input['name_en'] : $input['name_hi'];
                        $addData[$i]['created_at']  = now();
                    }
                    $exam_desc = ExamDesc::insert($addData);
                    
                    if ($exam_desc) {
                        
                        //SEND EMAIL AND NOTIFICATIONS TO USERS
                        $notifi = [];
                        $addDataNot = [];
                        $getAllCateId = [];
                        $getAllSubCate = Category::select('category_id')->where('parent_category',$input['parent_cate_id'])->get();
                        $getSelectedCate = !$getAllSubCate->isEmpty() ? $getAllSubCate->toArray() : false;
                        if ($getSelectedCate) {
                            foreach ($getSelectedCate as $sckey => $scvalue) {
                                $getAllCateId[$sckey] = $scvalue['category_id'];
                            }    
                        }
                        /*$getUserCate = User::with('user_category')->whereNotNull('user_fav_category')->where('user_type',2)->where('deleted',false)->where('user_status',true);
                        $getUserCate = $getUserCate->whereHas('user_category', function($queCat) use($getAllCateId){
                            $queCat->whereIn('category_id',$getAllCateId);
                        })->get();*/
                        /*== GET USER TO SEND NOTIFICATION ==*/
                        $cat_id = $actice_cat;
                        $getUserCate = User::with(array('user_category'=>function($queCat) use($getAllCateId,$cat_id){
                            $queCat->whereIn('category_id',$cat_id);
                        }))->where('user_fav_category',$input['parent_cate_id'])->where('user_type',2)->where('deleted',false)->where('user_status',true)->get();
                        $getUserCates = [];
                        foreach ($getUserCate as $key => $value) {
                            if (!$value->user_category->isEmpty()) {
                                $getUserCates[$key] = $value;
                            }
                        }
                        $getUserCate = $getUserCates;
                        /*== /END ==*/
                        

                        $data = EmailMaster::where('email_key', 'add_new_live_test_exam')->first();
                        if (isset($data) && !empty($data) && !empty($getUserCate) && count($getUserCate)>0) {
                            
                            $message = $data->content;

                            $subject = $data->subject;

                            //GET NOTIFICATION TRANSLATE KEY
                            $aNotificaTransMsg = Translation::where('group','notification_message')->where('key','live_test_add_msg')->first()->toArray();
                            $aNotificaTransTitle = Translation::where('group','notification_title')->where('key','live_test_add_title')->first()->toArray();
                            foreach ($getUserCate as $key => $value) {
                                $uName = $value->name ?? 'NA';
                                $uCat = $value->fav_category->name ?? 'NA';
                                $examDate = date('d-M-Y H:i:s',strtotime("$start_date $start_time")) ?? 'NA';

                                $message1 = str_replace("{{USERNAME}}",$uName, $message);
                                
                                $message2 = str_replace("{{DATE}}", $examDate, $message1);
                                
                                $message3 = str_replace("{{CATEGORY}}", $uCat, $message2);
                                
                            
                                $email_log[$key]['user_id'] = $value->user_id;
                                $email_log[$key]['subject'] = isset($subject) ? $subject :'';
                                $email_log[$key]['email_content'] = isset($message3) ? $message3 :'';
                                $email_log[$key]['email_status'] = 2;
                                $email_log[$key]['created_at'] = date('Y-m-d H:i:s',strtotime(now()));

                                //SEND NOTIFICATION
                                if (!empty($value->device_token) || $value->device_token==!null) {
                                    //GET NOTIFICATION TRANSLATE KEY
                                    $userLangCode = !empty($value->user_lang_code) ? $value->user_lang_code : 'en';
                                    
                                    $bodyText = $aNotificaTransMsg['text'][$userLangCode];
                                    $titleText = $aNotificaTransTitle['text'][$userLangCode];
                                    $bodyText =  $userLangCode=='en' ? str_replace('TEST_NAME',$input['name_en'], $bodyText)  : str_replace('TEST_NAME',$input['name_hi'], $bodyText);
                                    $device_token = $value->device_token;
                                    $notification = ['title' => $titleText, 'body' => $bodyText];

                                    $notification_data = [
                                        'action' => 'LIVE_TEST_NEW_EXAM',
                                        'exam_id' =>  $addExam->exam_id
                                    ];
                                    
                                    try
                                    {
                                        UserNotifications::sendPush($device_token, $notification, $notification_data);
                                    } catch (\Exception $e) {
                                        continue;   
                                    }
                                    
                                    $notifi = new Notification;
                                    $notifi->user_id  = $value->user_id;
                                    $notifi->notification_type  = 'EXAM_CREATED';
                                    $notifi->ntoification_type_id = $addExam->exam_id;
                                    $notifi->notification_data = json_encode($notification_data);
                                    $notifi->status = false;
                                    $notifi->save();
                                    if ($notifi) {
                                        for ($i=1; $i <=2 ; $i++) { 
                                            $addDataNot[$i]['notification_id'] = $notifi->notification_id;
                                            $addDataNot[$i]['lang_code'] = $i==1 ? 'en' : 'hi';
                                            $addDataNot[$i]['message'] = $i==1 ? str_replace('TEST_NAME',$input['name_en'], $aNotificaTransMsg['text']['en']) : str_replace('TEST_NAME',$input['name_hi'], $aNotificaTransMsg['text']['hi']);
                                        }
                                        NotificationDesc::insert($addDataNot);
                                    }
                                }
                            }
                            $emailLogData = EmailLog::insert($email_log);
                        }
                        $response['status'] = true;
                        $response['message'] = "Exam Successfully Created";
                    }
                    else{
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
     * Used for Admin get getLiveTest
     * @return redirect to Admin->get getLiveTest listing
    */
    public function getLiveTest(Request $request)
    {
        
      /**
       * Used for Admin get getLiveTest Listing
       */
        $data =$request->all();
        
        $accessPriData = !empty(session()->get('accessPriData')) ? session()->get('accessPriData') :'' ;

        $perpage = !empty( $data[ 'length' ] ) ? (int)$data[ 'length' ] : 10;
          
        $filter = isset( $data['search'] ) && is_string( $data['search'] ) ? $data['search'] : '';
        
        $search_category = isset( $data['search_category'] ) ? $data['search_category'] :'';
        
        $date_from = isset( $data['date_from'] ) ? $data['date_from'] :'';

        $date_to = isset( $data['date_to'] ) ? $data['date_to'] :'';

        $status_type = isset( $data['status_type'] ) ? $data['status_type'] :'';

        $test_type = isset( $data['test_type'] ) ? $data['test_type'] :'';

        $sort_type = isset( $data['order'][0]['dir'] ) && is_string( $data['order'][0]['dir'] ) ? $data['order'][0]['dir'] : '';    

        $sort_col =  isset($data['order'][0]['column']) ? $data['order'][0]['column'] :'';

        $sort_field = isset($data['columns'][$sort_col]['data']) ? $data['columns'][$sort_col]['data'] :'';
        
        $aTable = Exam::with('desc','category_desc')->where('exams_type','LIVE_TEST');

        //ACCESS PRIVILEGE
        if($accessPriData){
            $getAcc = [];

            $streams = Category::with('category_desc')->get()->toArray();
            $category = FrontHelper::buildtree($streams);

            if(!empty($accessPriData['Live_Test']) && $accessPriData['Live_Test']->view==true)
            {
                $getAcc['STATUS'] = $accessPriData['Live_Test']->status==true ? true:false;
                $getAcc['DELETED'] = $accessPriData['Live_Test']->delete==true ? true:false;
                $getAcc['EDIT'] = $accessPriData['Live_Test']->edit==true ? true:false;
                $getAcc['STUDENT'] = $accessPriData['Live_Test']->students==true ? true:false;

                $cateIdForLiveQue = $accessPriData['Live_Test']->categories==true ? $accessPriData['Live_Test']->categories : [];
                
                $categoryIdForQue = FrontHelper::getAllChilCategory($category,$cateIdForLiveQue);
                $aTable = $aTable->whereIn('category_id',$categoryIdForQue);
            }
        }

        if($filter){
            $aTable = $aTable->whereHas('desc', function($query) use($filter){
                $query->where('exam_name', 'LIKE', '%' . $filter . '%' );
            });
        }

        if ($search_category) {
            /*$aTable = $aTable->whereHas('category_desc', function($query) use($search_category){
                $query->where('category_id',$search_category);
            }); */
            $aTable = $aTable->where('category_id',$search_category);
        }

        if ($date_from || $date_to) {
            $date_from = date('Y-m-d',strtotime($date_from));
            $date_to = date('Y-m-d',strtotime($date_to));
            $aTable = $aTable->whereBetween('exam_starts_on',[$date_from , $date_to]);
            
        }

        if ($status_type) {
            $status_type = $status_type=='Active'?1:0;
            $aTable = $aTable->where('status',$status_type);
        }

        if ($test_type) {
            $getAllTestId = Exam::select('exam_id')->where('exams_type','LIVE_TEST')->get()->toArray();
            foreach ($getAllTestId as $value) {
                $getTestId[] = $value['exam_id'];
            }
            $findUserAttem = UserAttempt::select('exam_id')->whereIn('exam_id',$getTestId)->get()->toArray();
            foreach ($findUserAttem as $user_value) {
                $getUserAttId[] = $user_value['exam_id'];       
            }
            if ($test_type=='Attempted') {
                $aTable = $aTable->whereIn('exam_id',$getUserAttId);
            }else{
                $aTable = $aTable->whereNotIn('exam_id',$getUserAttId);
            }
        }

        $aTableQuery = $aTable;

        if($sort_col != 0){
            $aTableQuery = $aTableQuery->orderBy($sort_field, $sort_type);
        }else{
            $aTableQuery = $aTableQuery->orderBy('exam_starts_on', 'DESC');
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
                if ($cvalue['category_id']==$value['category_id']) {
                    $cate[$key] = $cvalue['category_desc'][0]['name'];
                }
                
                if(!empty($cvalue['children'])){
                foreach ($cvalue['children'] as $key1 => $value1){
                    if ($value1['category_id']==$value['category_id']) {
                        $cate[$key] = $cvalue['category_desc'][0]['name'].' > '.$value1['category_desc'][0]['name'];
                    }

                        if(!empty($value1['children'])){
                        foreach ($value1['children'] as $key2 => $value2) {
                            if ($value2['category_id']==$value['category_id']) {
                                $cate[$key] = $cvalue['category_desc'][0]['name'].' > '.$value1['category_desc'][0]['name']. ' > ' .$value2['category_desc'][0]['name'];
                            }
     
                            if(!empty($value2['children'])){
                            foreach ($value2['children'] as $key3 => $value3){
                                if ($value3['category_id'] == $value['category_id']) {
                                    $cate[$key] = $cvalue['category_desc'][0]['name'].' > '.$value1['category_desc'][0]['name']. ' > ' .$value2['category_desc'][0]['name'].' > '.$value3['category_desc'][0]['name'];
                                }
                                
                                if(!empty($value3['children'])){
                                foreach ($value3['children'] as $key4 => $value4){
                                    if ($value4['category_id']==$value['category_id']) {
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

            $value['index'] = $counter+1;
            $value['category'] = $cate[$key];
            $value['date_time'] = date('d-M | h:i A',strtotime($value['exam_starts_on'])) ." To <br>". date('d-M | h:i A',strtotime($value['exam_ends_on']));
            $value['result_date'] = date('d-M-Y h:i A',strtotime($value['result_date']));
            $value['current_date'] = date('d-M-Y h:i A',strtotime(now()));
            $value['checkStu'] = UserAttempt::where('exam_id',$value['exam_id'])->count();
            $value['check_action'] = date('d-M-Y, H:i:s',strtotime($value['exam_starts_on'])) < date('d-M-Y, H:i:s',strtotime(now())) || $value['checkStu']>0 ? true:false;
            $value['result_announce'] = $value['result_date'] <= $value['current_date'] ? true:false;
            
            $value['status_access'] = Auth::user()->user_type==0 ? true : $getAcc['STATUS'];
            $value['deleted_access'] = Auth::user()->user_type==0 ? true : $getAcc['DELETED'];
            $value['edit_access'] = Auth::user()->user_type==0 ? true : $getAcc['EDIT'];
            $value['student_access'] = Auth::user()->user_type==0 ? true : $getAcc['STUDENT'];
            
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
     * Used for Admin viewLiveTest
     * @return redirect to Admin->edit viewLiveTest
     */
    public function viewLiveTest(Request $request,$id)
    {
        $testData = [];
        $no_of = [];
        $getQueCate = [];
        
        $getExam = Exam::with('desc','category_desc')->where('exam_id',$id)->first()->toArray();
        //GEt CATEGORY WITH SUB CATEGORY
        $streams = Category::with('category_desc')->get()->toArray();
        $category = FrontHelper::buildtree($streams);
        $categoryData = FrontHelper::getSingleHeararcyofCat($category,$getExam['category_id']);
        //END

        $getAllQue = ExamQuestion::with('question')->where('exam_id',$id)->get()->toArray();
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
            }
        }
        $getQueCate = array_map("unserialize", array_unique(array_map("serialize", $getQueCate)));
        $no_of['quetion'] = ExamQuestion::where('exam_id',$getExam['exam_id'])->count();
        
        return view('admin.liveTest.viewLiveTest')->with(['cate'=>$categoryData,'getQueCate'=>$getQueCate,'getExam'=>$getExam,'testData'=>$testData,'no_of'=>$no_of]);
    }

    /**
     * Used for Admin editLiveTest
     * @return redirect to Admin->edit editLiveTest
     */
    public function editLiveTest(Request $request, $id)
    {
        $no_of = [];
        $getExam = Exam::with('desc_both_lang','category_desc')->where('exam_id',$id)->first()->toArray();

        $getSubPare = Category::with('desc')->where('category_id',$getExam['category_id'])->first()->toArray();
        
        $parent_category = Category::with('category_desc')->where('status',1)->where('parent_category',0)->get();

        $getCateWiseQueData = Category::select('category_id')->where('parent_category',$getExam['category_id'])->get()->toArray();
        foreach ($getCateWiseQueData as $key => $value) {
            $no_of[$key]['cate_name'] = Category::with('desc')->where('category_id',$value['category_id'])->first()->toArray();        
            
            $no_of[$key]['total_quetion'] = Question::where('category_id',$value['category_id'])->where('question_type','LIVE_TEST')->count();        

            $no_of[$key]['unused_que'] = Question::where('category_id',$value['category_id'])->where('question_type','LIVE_TEST')->where('status',1)->where(function ($query) use($id){
                        $query->where('isAssignable',true)->orwhere('exam_id',$id)
                        ->orWhereNull('exam_id');
                    })->count();    

            $no_of[$key]['used_que'] = Question::where('category_id',$value['category_id'])->where('question_type','LIVE_TEST')->where('exam_id',$id)->where('isAssignable',false)->count();
        }


        return view('admin.liveTest.editLiveTest')->with(['getSubPare'=>$getSubPare,'parent_category'=>$parent_category,'getExam'=>$getExam,'no_of'=>$no_of]);
    }

    /**
     * Used for Admin deleteLiveTest
     * @return redirect to Admin->delete deleteLiveTest
     */
    public function deleteLiveTest(Request $request)
    {
        $input = $request->all();
        $cid = $input['cid'];
        $response = [];

        if(empty($cid)){
            $response['status'] = false;
        }else{
            $userAttempt = UserAttempt::where('exam_id',$cid)->count();
            
            if (empty($userAttempt) && $userAttempt <= 0) {
                Exam::where('exam_id', $cid)->delete();
                ExamDesc::where('exam_id', $cid)->delete();
                ExamQuestion::where('exam_id', $cid)->delete(); 
                $updated = Question::where('exam_id',$cid)->where('question_type','LIVE_TEST')->update(['isAssignable'=>true]);
                if($updated){
                    $response['status'] = true;
                    $response['message'] = "Live Test Successfully deleted";
                }else{
                    $response['status'] = false;
                    $response['message'] = "Something Went Wrong";
                }
            }else{
                $response['status'] = false;
                $response['message'] = "Student Already Registered";
            }
        }
        return response()->json($response);
    }


    /**
    * Used for Delete Admin App User
    */
    public function statusLiveTest(Request $request)
    {
        $input = $request->all();
        $cid = $input['cid'];
        $response = [];

        if(empty($cid)){
            $response['status'] = false;
        }else{
            $status = Exam::where('exam_id',$cid)->first();
            $status->status = $status->status == 1 ? 0 : 1;
            if ($status->update()) {
                $response['status'] = true;
                $response['message'] = "Status Successfully changed";
            }else{
                $response['status'] = false;
            }
        }
        return response()->json($response);
    } 


    /**
    * Used for Delete Admin App User
    */
    public function diclareLiveTestResult(Request $request)
    {
        $input = $request->all();
        $cid = $input['cid'];
        $response = [];

        if(empty($cid)){
            $response['status'] = false;
        }else{
            $date = date('d-M-Y, h:i:s');
            $data = EmailMaster::where('email_key', 'admin_announce_live_test_result')->first();

            $userAttempt = UserAttempt::where('exam_id',$cid)->where('attempt_status','COMPLETED')->get();
            $checkStu = $userAttempt->count();
            if ($checkStu > 0) {
                foreach ($userAttempt as $key => $value) {
                    $user = User::where('user_id',$value->user_id)->first();
                    
                    if (isset($data) && !empty($data) && !empty($user->email) && !empty($user->name)) {

                        //SEND NOTIFICATION
                        if (!empty($user->device_token) || $user->device_token==!null) {
                            // $device_token = $user->device_token;
                            // $notification = ['title' => 'Test Messsage', 'body' => 'Test Message Content'];
                            // $notification_data = ['exam_id' => $cid];
                            // UserNotifications::sendPush($device_token, $notification, $notification_data);
                        }

                        $data = $data->toArray();      
                        
                        $message = $data['content'];

                        $subject = $data['subject'];
                        
                        $message1 = str_replace("{{USERNAME}}", $user->name, $message);
                        
                        $msg = str_replace("{{DATE}}", $date, $message1);

                        $mail = Mail::to($user->email)->send(new AnnounceLiveTestResult($msg,$subject));

                    }

                }
                $response['status'] = true;
                $response['message'] = "Live Test Result Announced Successfully";
            }else{
                $response['status'] = false;
                $response['message'] = "Student Not Registered";
            }
        }
        return response()->json($response);
    } 
    
    
    /**
    * Used for Admin appearedStudents list
    */
    public function appearedStudents(Request $request, $id)
    {
        $appearedStu = UserAttempt::with('user')->where('exam_id',$id)->where('attempt_status','COMPLETED')->first();
        return view('admin.liveTest.appearedStuList')->with(['appearedStu'=>$appearedStu]);

    }

    /**
     * Used for Admin get appearedStudentsPost
     * @return redirect to Admin->get appearedStudentsPost listing
    */
    public function appearedStudentsPost(Request $request)
    {
        
      /**
       * Used for Admin get appearedStudentsPost Listing
       */

        $data =$request->all();
        
        $perpage = !empty( $data[ 'length' ] ) ? (int)$data[ 'length' ] : 10;
          
        $filter = isset( $data['search'] ) && is_string( $data['search'] ) ? $data['search'] : '';
       
        $sort_type = isset( $data['order'][0]['dir'] ) && is_string( $data['order'][0]['dir'] ) ? $data['order'][0]['dir'] : '';    

        $sort_col =  isset($data['order'][0]['column']) ? $data['order'][0]['column'] :'';

        $sort_field = isset($data['columns'][$sort_col]['data']) ? $data['columns'][$sort_col]['data'] :'';
        
        $stu_exam_id = isset($data['stu_exam_id']) ? $data['stu_exam_id'] :'';

        $aTable = UserAttempt::with('user')->where('attempt_status','COMPLETED')->where('exam_id',$stu_exam_id);

        if($filter){
            $aTable = $aTable->whereHas('user', function($query) use($filter){
                $query->where('name', 'LIKE', '%' . $filter . '%' );
            })->orWhere('total_correct', 'LIKE', '%' . $filter . '%' )->orWhere('total_incorrect', 'LIKE', '%' . $filter . '%' )->orWhere('user_rank', 'LIKE', '%' . $filter . '%' );
        }

        $aTableQuery = $aTable;

        if($sort_col != 0){
            $aTableQuery = $aTableQuery->orderBy($sort_field, $sort_type);
        }else{
            $aTableQuery = $aTableQuery->orderBy('user_rank', 'ASC');
        }

        $total_table_data= $aTableQuery->count();

        $offset = isset($data['start']) ? $data['start'] :'';
         
        $counter = $offset;
        $aTabledata = [];
        $aTables = $aTableQuery->offset($offset)->limit($perpage)->get()->toArray();
        foreach($aTables as $key => $value) {
            $value['index'] = $counter+1;
            $value['attempt_date'] = date('d-M-Y',strtotime($value['attempted_on']));
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
     * Used for Admin AppUser appearedStudentsViewUserTest 
     * @return redirect to Admin->AppUser/appearedStudentsViewUserTest 
     */
    public function appearedStudentsViewUserTest(Request $request,$id)
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
        $categoryData = FrontHelper::getSingleHeararcyofCat($category,$getExam['category_id']);
        //END
        
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
            return \View::make('admin.liveTest.viewUserTest')->with(['getAttemptedOption'=>$getAttemptedOption,'data'=>$data,'cate'=>$categoryData,'getQueCate'=>$getQueCate,'getExam'=>$getExam,'testData'=>$testData,'no_of'=>$no_of])->renderSections();
        }
        return view('admin.liveTest.viewUserTest')->with(['getAttemptedOption'=>$getAttemptedOption,'data'=>$data,'cate'=>$categoryData,'getQueCate'=>$getQueCate,'getExam'=>$getExam,'testData'=>$testData,'no_of'=>$no_of]);
    }

}
