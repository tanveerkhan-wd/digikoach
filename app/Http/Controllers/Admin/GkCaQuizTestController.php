<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QuestionOptionDesc;
use App\Helpers\FrontHelper;
use App\Models\QuestionOption;
use App\Models\QuestionDesc;
use App\Models\UserAttempt;
use App\Models\ExamDesc;
use App\Models\Category;
use App\Models\Question;
use App\Models\ExamQuestion;
use App\Models\Exam;
use App\Models\EmailMaster;
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

class GkCaQuizTestController extends Controller
{
    /**
     * Used for Admin Gk & Ca Quiz Test
     * @return redirect to Admin->QuizTest
     */
    public function index(Request $request)
    {   
        if (request()->ajax()) {
            return \View::make('admin.gkCa.quizTest')->renderSections();
        }
        return view('admin.gkCa.quizTest');
    }

    /**
     * Used for Admin addQuizTest
     * @return redirect to Admin->addQuizTest
     */
    public function addQuizTest(Request $request)
    {	
    	$no_of = [];

        $no_of['cate_name'] = 'Gk & CA';
        
        $no_of['total_quetion'] = Question::where('question_type','GK_CA')->where('status',1)->count();        

        $no_of['unused_que'] = Question::where('question_type','GK_CA')->where('isAssignable',true)->where('status',1)->count();

        if (request()->ajax()) {
            return \View::make('admin.gkCa.addQuizTest')->with(['no_of'=>$no_of])->renderSections();
        }
        return view('admin.gkCa.addQuizTest')->with(['no_of'=>$no_of]);
    }

    /**
     * Used for Admin add addQuizTestPost
     * @return redirect to Admin->add addQuizTestPost
     */
    public function addQuizTestPost(Request $request)
    {
        $response = [];
        $input = $request->all();
        //dd($input);
        $addData = [];
        $update_que = [];
        try
        {
            //EDIT Gk & Ca Quiz Test
            if(!empty($input['pkCat']) && $input['pkCat'] != null)
            {
                //ADD Gk & Ca Quiz Test

                $addExam['category_id'] = $input['category'] ?? 1;
                $addExam['exams_type'] = 'GK_CA';
                $addExam['total_questions'] = $input['question_number'];
                $addExam['exam_duration'] = $input['duration'];
                
                //UPDATE SELECTED QUESTION
                //Question::where('exam_id',$input['pkCat'])->update(['isAssignable'=>true]);
                $getQueCount = Question::where('exam_id',$input['pkCat'])->where('question_type','GK_CA')->where('isAssignable',false)->count();
                if ($getQueCount < $input['question_number']) {
                    $limit = $input['question_number']-$getQueCount;
                    $getAllQuetion = Question::inRandomOrder()->where('question_type','GK_CA')->where('isAssignable',true)->where('status',1)->limit($limit)->get();
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
                }elseif($getQueCount > $input['question_number']){
                    $limit = $getQueCount-$input['question_number'];
                    Question::where('exam_id',$input['pkCat'])->where('question_type','GK_CA')->where('isAssignable',false)->limit($limit)->update(['isAssignable'=>true]);
                }

                //UPDATE ALL QUESTION WITH EXAM ID TO EXAM QUESTION TABLE.
                $exam_questions = [];
                $getAllQueOfExam = Question::select('questions_id','exam_id')->where('question_type','GK_CA')->where('exam_id',$input['pkCat'])->where('isAssignable',false)->get()->toArray();
                if (!empty($getAllQueOfExam)) {
                    ExamQuestion::where('exam_id',$input['pkCat'])->delete();
                    foreach ($getAllQueOfExam as $key => $value) {
                        $exam_questions[$key]['questions_id'] = $value['questions_id'];
                        $exam_questions[$key]['exam_id'] = $value['exam_id'];
                        $exam_questions[$key]['created_at'] = now();
                        $exam_questions[$key]['updated_at'] = now();
                    }
                    ExamQuestion::insert($exam_questions);
                }
                

                $total_marks = DB::table('questions')->where('exam_id',$input['pkCat'])->where('question_type','GK_CA')->where('isAssignable',false)->sum('marks');
                $addExam['total_marks'] = $total_marks;

                $updateExm = Exam::where('exam_id',$input['pkCat'])->update($addExam);

                if ($updateExm) {

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
                //ADD Gk & Ca Quiz Test
                $limit = 0;

                $addExam = new Exam;
                $addExam->category_id = $input['category'] ?? 1;
                $addExam->exams_type = 'GK_CA';
                $addExam->total_questions = $input['question_number'];
                $addExam->exam_duration = $input['duration'];
                $addExam->total_marks = false;
                
                if ($addExam->save()) {
                    //UPDATE SELECTED QUESTION
                	$limit = $input['question_number'];
                	$getAllQuetion = Question::inRandomOrder()->where('question_type','GK_CA')->where('isAssignable',true)->where('status',1)->limit($limit)->get();
    	            foreach ($getAllQuetion as $uq_value) {
                        $update_que['exam_id'] = $addExam->exam_id;
    	                $update_que['isAssignable'] = false;
                        Question::where('questions_id',$uq_value->questions_id)->update($update_que);

    	            }        

                    //ADD ALL QUESTION WITH EXAM ID TO EXAM QUESTION TABLE.
                    $exam_questions = [];
                    $getAllQueOfExam = Question::select('questions_id','exam_id')->where('exam_id',$addExam->exam_id)->where('question_type','GK_CA')->where('isAssignable',false)->get()->toArray();
                    foreach ($getAllQueOfExam as $key => $value) {
                        $exam_questions[$key]['questions_id'] = $value['questions_id'];
                        $exam_questions[$key]['exam_id'] = $value['exam_id'];
                        $exam_questions[$key]['created_at'] = now();
                    }
                    ExamQuestion::insert($exam_questions);

                    $total_marks = DB::table('questions')->where('question_type','GK_CA')->where('isAssignable',false)->where('exam_id',$addExam->exam_id)->sum('marks');
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
                        $getUserCate = User::where('user_type',2)->where('deleted',false)->where('user_status',true)->whereNotNull('device_token')->get();
                        $data = EmailMaster::where('email_key', 'add_new_gk_ca_test')->first();
                        if (isset($data) && !empty($data) && !empty($getUserCate) && count($getUserCate)>0) {
                            
                            $message = $data->content;

                            $subject = $data->subject;

                            //GET NOTIFICATION TRANSLATE KEY
                            $aNotificaTransMsg = Translation::where('group','notification_message')->where('key','gk_ca_test_add_msg')->first()->toArray();
                            $aNotificaTransTitle = Translation::where('group','notification_title')->where('key','gk_ca_test_add_title')->first()->toArray();
                            foreach ($getUserCate as $key => $value) {
                                $uName = $value->name ?? 'NA';
                                $uCat = $value->fav_category->name ?? 'NA';
                                
                                $examDate = date('d-M-Y H:i:s A',strtotime(now())) ?? 'NA';

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
                                    
                                    $bodyTxt = $aNotificaTransMsg['text'][$userLangCode];
                                    $titleText = $aNotificaTransTitle['text'][$userLangCode];
                                    $bodyText =  $userLangCode=='en' ? str_replace('TEST_NAME',$input['name_en'], $bodyTxt)  : str_replace('TEST_NAME',$input['name_hi'], $bodyTxt);
                                    $device_token = $value->device_token;
                                    $notification = ['title' => $titleText, 'body' => $bodyText];

                                    $notification_data = [
                                        'action' => 'GKCA_NEW_EXAM',
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

        $accessPriData = !empty(session()->get('accessPriData')) ? session()->get('accessPriData') :'' ;
          
        $filter = isset( $data['search'] ) && is_string( $data['search'] ) ? $data['search'] : '';
        
        $search_category = isset( $data['search_category'] ) ? $data['search_category'] :'';
        
        $status_type = isset( $data['status_type'] ) ? $data['status_type'] :'';

        $test_type = isset( $data['test_type'] ) ? $data['test_type'] :'';
        
        $sort_type = isset( $data['order'][0]['dir'] ) && is_string( $data['order'][0]['dir'] ) ? $data['order'][0]['dir'] : '';    

        $sort_col =  isset($data['order'][0]['column']) ? $data['order'][0]['column'] :'';

        $sort_field = isset($data['columns'][$sort_col]['data']) ? $data['columns'][$sort_col]['data'] :'';
        
        $aTable = Exam::with('desc','category_desc')->where('exams_type','GK_CA');

        //ACCESS PRIVILEGE
        if($accessPriData){
            $getAcc = [];
            if(!empty($accessPriData['GK_CA_Quizz']) && $accessPriData['GK_CA_Quizz']->view==true)
            {
                $getAcc['STATUS'] = $accessPriData['GK_CA_Quizz']->status==true ? true:false;
                $getAcc['DELETED'] = $accessPriData['GK_CA_Quizz']->delete==true ? true:false;
                $getAcc['EDIT'] = $accessPriData['GK_CA_Quizz']->edit==true ? true:false;
                $getAcc['STUDENT'] = $accessPriData['GK_CA_Quizz']->students==true ? true:false; 
            }
        }

        if($filter){
            $aTable = $aTable->whereHas('desc', function($query) use($filter){
                $query->where('exam_name', 'LIKE', '%' . $filter . '%' );
            })->orWhere('exams_type', 'LIKE', '%' . $filter . '%' );
        }

        if ($status_type) {
            $status_type = $status_type=='Active'?1:0;
            $aTable = $aTable->where('status',$status_type);
        }



        if ($test_type) {
            $getAllTestId = Exam::select('exam_id')->where('exams_type','GK_CA')->get()->toArray();
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
            $aTableQuery = $aTableQuery->orderBy('created_at', 'DESC');
        }

        $total_table_data= $aTableQuery->count();

        $offset = isset($data['start']) ? $data['start'] :'';
         
        $counter = $offset;
        $aTabledata = [];
        $aTables = $aTableQuery->offset($offset)->limit($perpage)->get()->toArray();
        foreach ($aTables as $key => $value) {
            $value['checkStu'] = UserAttempt::where('exam_id',$value['exam_id'])->count();
            $value['check_action'] = $value['checkStu']>0 ? true:false;
            
            $value['status_access'] = Auth::user()->user_type==0 ? true : $getAcc['STATUS'];
            $value['deleted_access'] = Auth::user()->user_type==0 ? true : $getAcc['DELETED'];
            $value['edit_access'] = Auth::user()->user_type==0 ? true : $getAcc['EDIT'];
            $value['student_access'] = Auth::user()->user_type==0 ? true : $getAcc['STUDENT'];
         
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
     * Used for Admin viewQuizTest
     * @return redirect to Admin->edit viewQuizTest
     */
    public function viewQuizTest(Request $request,$id)
    {
        $testData = [];
        $no_of = [];
        $getQueCate = [];

        $getExam = Exam::with('desc')->where('exam_id',$id)->first()->toArray();


        $getAllQue = ExamQuestion::with('question')->where('exam_id',$id)->get()->toArray();
        foreach ($getAllQue as $key => $value) {
            if (!empty($value['question'])) {
                $testData[$key]['question_media'] = QuestionMedia::whereIn('media_int_type',['QUESTION','SOLUTION'])->where('lang_code','en')->where('media_int_id',$value['question']['questions_id'])->get();
                $testData[$key]['questions_id'] = $value['question']['questions_id'];
                $testData[$key]['question'] = $value['question']['question_desc']['question_text'];
                $testData[$key]['solution'] = $value['question']['question_desc']['solution_text'];
                $testData[$key]['option'] = isset($value['question']['options']) ? $value['question']['options'] : false;
                if ($testData[$key]['option']) {
                    foreach ($value['question']['options'] as $k => $v) {
                        $testData[$key]['option_media'][$k] = QuestionMedia::where('media_int_type','OPTION')->where('lang_code','en')->where('media_int_id',$v['question_options_id'])->get()->toArray();        
                    }
                }
            }
        }
        $no_of['quetion'] = ExamQuestion::where('exam_id',$getExam['exam_id'])->count();

        return view('admin.gkCa.viewQuizTest')->with(['getExam'=>$getExam,'testData'=>$testData,'no_of'=>$no_of]);
    
    }


    /**
     * Used for Admin editQuizTest
     * @return redirect to Admin->edit editQuizTest
     */
    public function editQuizTest(Request $request, $id)
    {
    	$no_of = [];
        $getExam = Exam::with('desc_both_lang')->where('exam_id',$id)->first()->toArray();
        
        $no_of['cate_name'] = 'Gk & CA';
        
        $no_of['total_quetion'] = Question::where('question_type','GK_CA')->count();        

        $no_of['unused_que'] = Question::where('question_type','GK_CA')->where('status',1)->where(function ($query) use($id){
                        $query->where('isAssignable',true)->orWhere('exam_id',$id)
                        ->orWhereNull('exam_id');
                    })->count();

        $no_of['used_que'] = Question::where('question_type','GK_CA')->where('exam_id',$getExam['exam_id'])->where('isAssignable',false)->count();
        
        return view('admin.gkCa.editQuizTest')->with(['getExam'=>$getExam,'no_of'=>$no_of]);
    }



    /**
     * Used for Admin deleteQuizTest
     * @return redirect to Admin->delete deleteQuizTest
     */
    public function deleteQuizTest(Request $request)
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
                $updated = Question::where('exam_id',$cid)->update(['isAssignable'=>true]);
                if($updated){
                    $response['status'] = true;
                    $response['message'] = "Gk & Ca Quiz Test Successfully deleted";
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
    * Used for Delete Admin Practice
    */
    public function statusQuizTest(Request $request)
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
    * Used for Admin appearedStudents list
    */
    public function appearedStudents(Request $request, $id)
    {
        $appearedStu = UserAttempt::with('user')->where('exam_id',$id)->where('attempt_status','COMPLETED')->first();


        return view('admin.gkCa.appearedStuList')->with(['appearedStu'=>$appearedStu]);

    }

    /**
     * Used for Admin get appearedStudentsPost
     * @return redirect to Admin->get appearedStudentsPost listing
    */
    public function appearedStudentsPost(Request $request)
    {
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
            $aTableQuery = $aTableQuery->orderBy('created_at', 'DESC');
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
    public function viewUserTest(Request $request,$id)
    {
        $cate = '';
        $testData = [];
        $no_of = [];
        $getQueCate = [];
        $marks  = [];
        $data = UserAttempt::with('exam','exam_desc')->where('user_attempt_id',$id)->first()->toArray();
        $getExam = Exam::with('desc','category_desc')->where('exam_id',$data['exam_id'])->first()->toArray();

        if ($getExam['exams_type']=='GK_CA') {
            $cate = 'Gk & Ca';
        }

        //GET ALL ATTEMPTED OPTION
        $getAttemptedOption = UserExamResponse::where('exam_id',$getExam['exam_id'])->where('user_id',$data['user_id'])->where('user_attempt_id',$data['user_attempt_id'])->get()->toArray();

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
            return \View::make('admin.gkCa.viewUserTest')->with(['getAttemptedOption'=>$getAttemptedOption,'data'=>$data,'cate'=>$cate,'getQueCate'=>$getQueCate,'getExam'=>$getExam,'testData'=>$testData,'no_of'=>$no_of])->renderSections();
        }
        return view('admin.gkCa.viewUserTest')->with(['getAttemptedOption'=>$getAttemptedOption,'data'=>$data,'cate'=>$cate,'getQueCate'=>$getQueCate,'getExam'=>$getExam,'testData'=>$testData,'no_of'=>$no_of]);
    }


}
