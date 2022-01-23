<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\FrontHelper;
use App\Models\User;
use App\Models\Notification;
use App\Models\Question;
use App\Models\Exam;
use App\Models\Category;
use App\Models\UserAttempt;
use Validator;
use Carbon\Carbon;
use Auth;
use Hash;
use View;
use Redirect;
use Storage;
use File;
use Config;
use DB;

class DashboardController extends Controller
{
    
	/**
	 * Used for Admin Dashboard
	 * @return redirect to Admin->Dashboard
	 */
    public function dashboard()
    {
        $accessPriData = session()->get('accessPriData');

        $cntAttemLiveTest = [];
        $cntAttemQuizTest = [];
        $cntAttemPracTest = [];
        $cntAttemGkQuizTest =[];
        $categoryId = [];

        //ACCESS PRIVILEGE FOR TOTAL QUEION AND FRESH QUE.
        if(!empty($accessPriData) && Auth::user()->user_type==1){
            $streams = Category::with('category_desc')->get()->toArray();
            $category = FrontHelper::buildtree($streams);
            
            $cateId['LIVE_TEST'] = $accessPriData['Live_Test']->categories==true ? $accessPriData['Live_Test']->categories : [];
            $cateId['QUIZZES'] = $accessPriData['Quizz_Test']->categories==true ? $accessPriData['Quizz_Test']->categories : [];
            $cateId['PRACTICE_TEST'] = $accessPriData['Practice_Test']->categories==true ? $accessPriData['Practice_Test']->categories : '';
            $cateId['GK_CA'] = $accessPriData['GK_CA_Quizz']->categories==true ? $accessPriData['GK_CA_Quizz']->categories : [];

            foreach ($cateId as $key => $value) {
                $categoryId[$key] = FrontHelper::getAllChilCategory($category,$cateId[$key]);
            }
        }

        if (!empty($categoryId)) {
            $getLiveTestId = Exam::select('exam_id')->whereIn('category_id',$categoryId['LIVE_TEST'])->where('exams_type','LIVE_TEST')->get();
            $getQuizTestId = Exam::select('exam_id')->whereIn('category_id',$categoryId['QUIZZES'])->where('exams_type','QUIZZES')->get();
            $getpracticeTestId = Exam::select('exam_id')->whereIn('category_id',$categoryId['PRACTICE_TEST'])->where('exams_type','PRACTICE_TEST')->get();
            $getgkQuizTestId = Exam::select('exam_id')->whereIn('category_id',$categoryId['GK_CA'])->where('exams_type','GK_CA')->get();            
        }else{
            $getLiveTestId = Exam::select('exam_id')->where('exams_type','LIVE_TEST')->get();
            $getQuizTestId = Exam::select('exam_id')->where('exams_type','QUIZZES')->get();
            $getpracticeTestId = Exam::select('exam_id')->where('exams_type','PRACTICE_TEST')->get();
            $getgkQuizTestId = Exam::select('exam_id')->where('exams_type','GK_CA')->get();
        }

        
        //COUNT TEST and App User
        $totalAppUser = User::where('user_type',2)->where('deleted',0)->count();
        $totalLiveTest = $getLiveTestId->count();
        $totalQuizTest = $getQuizTestId->count();
        $totalpracticeTest = $getpracticeTestId->count();
        $totalgkQuizTest = $getgkQuizTestId->count();
        
        //COUNT ATTEMPTED TEST
        foreach ($getLiveTestId as $key => $value) {
            $cntAttemLiveTest[] =  $value->exam_id;
        }
        $countUserAttLiveTest = UserAttempt::whereIn('exam_id',$cntAttemLiveTest)->count();

        foreach ($getQuizTestId as $key1 => $value1) {
            $cntAttemQuizTest[] =  $value1->exam_id;
        }
        $countUserAttQuizTest = UserAttempt::whereIn('exam_id',$cntAttemQuizTest)->count();

        foreach ($getpracticeTestId as $key2 => $value2) {
            $cntAttemPracTest[] =  $value2->exam_id;
        }
        $countUserAttPrcTest = UserAttempt::whereIn('exam_id',$cntAttemPracTest)->count();

        foreach ($getgkQuizTestId as $key3 => $value3) {
            $cntAttemGkQuizTest[] =  $value3->exam_id;
        }
        $countUserAttGkQuizTest = UserAttempt::whereIn('exam_id',$cntAttemGkQuizTest)->count();

        //ACCESS PRIVILEGE FOR TOTAL QUEION AND FRESH QUE.
        $getQueType = [];
        if(!empty($accessPriData['Question_Bank_Live_Test']) && $accessPriData['Question_Bank_Live_Test']->view==true){ $getQueType[] = 'LIVE_TEST'; }

        if(!empty($accessPriData['Question_Bank_Quizz_Test']) && $accessPriData['Question_Bank_Quizz_Test']->view==true){ $getQueType[] = 'QUIZZES'; }

        if(!empty($accessPriData['Question_Bank_Practice_Test']) && $accessPriData['Question_Bank_Practice_Test']->view==true){ $getQueType[] = 'PRACTICE_TEST'; }

        if(!empty($accessPriData['Question_Bank_GK_CA_Test']) && $accessPriData['Question_Bank_GK_CA_Test']->view==true){ $getQueType[] = 'GK_CA'; }
        
        if(!empty($getQueType) && Auth::user()->user_type==1){
            $cateIdForQue = [];
            //SET COUNTER DATA ACCORDING TO ACCESS PRIVILEGE CATEGORY
            $cateIdForQue[] = $accessPriData['Question_Bank_Live_Test']->categories==true ? $accessPriData['Question_Bank_Live_Test']->categories : []; 
            $cateIdForQue[] = $accessPriData['Question_Bank_Quizz_Test']->categories==true ? $accessPriData['Question_Bank_Quizz_Test']->categories : [];
            $cateIdForQue[] = $accessPriData['Question_Bank_Practice_Test']->categories==true ? $accessPriData['Question_Bank_Practice_Test']->categories : [];
            $cateIdForQue[] = $accessPriData['Question_Bank_GK_CA_Test']->categories==true ? $accessPriData['Question_Bank_GK_CA_Test']->categories : [];
            $cateIdForQue =  array_filter($cateIdForQue);
            
            foreach ($cateIdForQue as $key1 => $value1) {
                $categoryIdForQue[$key1] = FrontHelper::getAllChilCategory($category,$cateIdForQue[$key1]);
            }

            $countCategoryIdForQue = count($categoryIdForQue);
            if ($countCategoryIdForQue==1) {
                $arrMergeCatIDForQue = $categoryIdForQue[0];
            }elseif($countCategoryIdForQue==2){
                $arrMergeCatIDForQue = array_merge($categoryIdForQue[0],$categoryIdForQue[1]);
                $arrMergeCatIDForQue = array_unique($arrMergeCatIDForQue);
            }elseif($countCategoryIdForQue==3){
                $arrMergeCatIDForQue = array_merge($categoryIdForQue[0],$categoryIdForQue[1],$categoryIdForQue[2]);
                $arrMergeCatIDForQue = array_unique($arrMergeCatIDForQue);
            }elseif($countCategoryIdForQue==4){
                $arrMergeCatIDForQue = array_merge($categoryIdForQue[0],$categoryIdForQue[1],$categoryIdForQue[2],$categoryIdForQue[3]);
                $arrMergeCatIDForQue = array_unique($arrMergeCatIDForQue);
            }

            $totalQue = Question::whereIn('question_type',$getQueType)->whereIn('category_id',$arrMergeCatIDForQue)->count();
            $totalFreshQue = Question::whereIn('question_type',$getQueType)->whereIn('category_id',$arrMergeCatIDForQue)->whereNull('exam_id')->where('status',1)->count();
        }else{
            $totalQue = Question::count();
            $totalFreshQue = Question::whereNull('exam_id')->where('status',1)->count();
        }

        
        //GRAPH DATA
        // ------ FOR STUDENT CREATION CHART DATA START   ----------//
        $stuAccCredata = User::select(DB::raw("COUNT(user_id) as total"), DB::raw("MONTH(created_at) as month_num"))
                        ->whereYear('created_at', date('Y'))
                        ->where('user_type',2)
                        ->groupBy('month_num')
                        ->get()->toArray();
        $graph_data1 = [];
        if (!empty($stuAccCredata)) {
            $month_data1 = array_column($stuAccCredata, 'total', 'month_num');
            for ($i = 1; $i <= 12; $i++) {
                if (array_key_exists($i, $month_data1)) {
                    array_push($graph_data1, $month_data1[$i]);
                } else {
                    array_push($graph_data1, 0);
                }
            }
        }
        $studentAccCreation_total = json_encode($graph_data1, JSON_NUMERIC_CHECK);

        $year_stuAccCre = User::select(DB::raw("YEAR(created_at) as year"))
                ->groupBy('year')->where('user_type',2)
                ->get();
        
        //GRAPH DATA
        // ------ FOR LIVE TEST CHART DATA START   ----------//
        $data = UserAttempt::select(DB::raw("COUNT(exam_id) as total"), DB::raw("MONTH(created_at) as month_num"))
                        ->whereYear('created_at', date('Y'))
                        ->whereIn('exam_id',$cntAttemLiveTest)
                        ->groupBy('month_num')
                        ->get()->toArray();
        $graph_data = [];
        if (!empty($data)) {
            $month_data = array_column($data, 'total', 'month_num');
            for ($i = 1; $i <= 12; $i++) {
                if (array_key_exists($i, $month_data)) {
                    array_push($graph_data, $month_data[$i]);
                } else {
                    array_push($graph_data, 0);
                }
            }
        }
        $live_test_total = json_encode($graph_data, JSON_NUMERIC_CHECK);

        $year_live_test = UserAttempt::select(DB::raw("YEAR(created_at) as year"))
                ->groupBy('year')->whereIn('exam_id',$cntAttemLiveTest)
                ->get();

        // ------ FOR QUIZ TEST CHART DATA START   ----------//
        $dataQuiz = UserAttempt::select(DB::raw("COUNT(exam_id) as total"), DB::raw("MONTH(created_at) as month_num"))
                        ->whereYear('created_at', date('Y'))
                        ->whereIn('exam_id',$cntAttemQuizTest)
                        ->groupBy('month_num')
                        ->get()->toArray();
        $graph_data2 = [];
        if (!empty($dataQuiz)) {
            $month_data2 = array_column($dataQuiz, 'total', 'month_num');
            for ($i = 1; $i <= 12; $i++) {
                if (array_key_exists($i, $month_data2)) {
                    array_push($graph_data2, $month_data2[$i]);
                } else {
                    array_push($graph_data2, 0);
                }
            }
        }
        $quiz_test_total = json_encode($graph_data2, JSON_NUMERIC_CHECK);

        $year_quiz_test = UserAttempt::select(DB::raw("YEAR(created_at) as year"))
                ->groupBy('year')->whereIn('exam_id',$cntAttemQuizTest)
                ->get();

         // ------ FOR PRACTICE TEST CHART DATA START   ----------//
        $dataPrac = UserAttempt::select(DB::raw("COUNT(exam_id) as total"), DB::raw("MONTH(created_at) as month_num"))
                        ->whereYear('created_at', date('Y'))
                        ->whereIn('exam_id',$cntAttemPracTest)
                        ->groupBy('month_num')
                        ->get()->toArray();
        $graph_data3 = [];
        if (!empty($dataPrac)) {
            $month_data3 = array_column($dataPrac, 'total', 'month_num');
            for ($i = 1; $i <= 12; $i++) {
                if (array_key_exists($i, $month_data3)) {
                    array_push($graph_data3, $month_data3[$i]);
                } else {
                    array_push($graph_data3, 0);
                }
            }
        }
        $practice_test_total = json_encode($graph_data3, JSON_NUMERIC_CHECK);

        $year_practice_test = UserAttempt::select(DB::raw("YEAR(created_at) as year"))
                ->groupBy('year')->whereIn('exam_id',$cntAttemPracTest)
                ->get();


         // ------ FOR GK QUIZ TEST CHART DATA START   ----------//
        $dataGkQuiz = UserAttempt::select(DB::raw("COUNT(exam_id) as total"), DB::raw("MONTH(created_at) as month_num"))
                        ->whereYear('created_at', date('Y'))
                        ->whereIn('exam_id',$cntAttemGkQuizTest)
                        ->groupBy('month_num')
                        ->get()->toArray();
        $graph_data4 = [];
        if (!empty($dataGkQuiz)) {
            $month_data4 = array_column($dataGkQuiz, 'total', 'month_num');
            for ($i = 1; $i <= 12; $i++) {
                if (array_key_exists($i, $month_data4)) {
                    array_push($graph_data4, $month_data4[$i]);
                } else {
                    array_push($graph_data4, 0);
                }
            }
        }
        $gk_quiz_test_total = json_encode($graph_data4, JSON_NUMERIC_CHECK);

        $year_gk_quiz_test = UserAttempt::select(DB::raw("YEAR(created_at) as year"))
                ->groupBy('year')->whereIn('exam_id',$cntAttemGkQuizTest)
                ->get();
        
    	return view('admin.dashboard.dashboard')->with(['gk_quiz_test_total'=>$gk_quiz_test_total,'year_gk_quiz_test'=>$year_gk_quiz_test,'practice_test_total'=>$practice_test_total,'year_practice_test'=>$year_practice_test,'quiz_test_total'=>$quiz_test_total,'year_quiz_test'=>$year_quiz_test,'studentAccCreation_total'=>$studentAccCreation_total,'year_stuAccCre'=>$year_stuAccCre,'live_test_total'=>$live_test_total,'year_live_test'=>$year_live_test,'totalFreshQue'=>$totalFreshQue,'totalQue'=>$totalQue,'countUserAttGkQuizTest'=>$countUserAttGkQuizTest,'countUserAttPrcTest'=>$countUserAttPrcTest,'countUserAttQuizTest'=>$countUserAttQuizTest,'countUserAttLiveTest'=>$countUserAttLiveTest,'totalAppUser'=>$totalAppUser,'totalLiveTest'=>$totalLiveTest,'totalQuizTest'=>$totalQuizTest,'totalpracticeTest'=>$totalpracticeTest,'totalgkQuizTest'=>$totalgkQuizTest]);
    }


	/**
	 * Used for Admin Profile
	 * @return redirect to Admin->Profile
	 */
    public function profile(Request $request)
    {
        if (request()->ajax()) {
            return \View::make('admin.dashboard.profile')->renderSections();
        }
        return view('admin.dashboard.profile');
    }


    /**
	 * Used for editProfile
	 * @return redirect to Admin->editProfile
	 */
    public function editProfile(Request $request){

        $response = [];
        $input = $request->all();      

        $emailExist = User::where('email','=', $input['email'])->where('user_id','!=',Auth::user()->user_id)->get();
        $emailExistCount = $emailExist->count();
        
        if($emailExistCount != 0){
            $response['status'] = false;
            $response['message'] = $this->translations['msg_email_exist'] ?? 'Email already exist, Please try with a different email';
            return response()->json($response);
            die();
        }

        $user = User::findorfail(Auth::user()->user_id);
        $user->email = $input['email'];
        $user->name = $input['name'];
        $user->mobile_number = $input['mobile_number'];
        $user->info_email = isset($input['info_email']) && !empty($input['info_email']) ? $input['info_email'] :NULL;
        
        if($request->hasFile('user_img')){
            $gen_rand = rand(100,99999).time();
            $image_path = $request->file('user_img');
            $extension = $image_path->getClientOriginalExtension();
            Storage::disk('public')->put(Config::get('siteglobal.images_dirs.USERS').'/'.$gen_rand.'.'.$extension,  File::get($image_path));
            if(!empty($user->user_photo)){
                Storage::disk('public')->delete(Config::get('siteglobal.images_dirs.USERS').'/'.$user->user_photo);
            }
            $user->user_photo = $gen_rand.'.'.$extension;
        }

        if($user->save()){
        	$response['status'] = true;
            $response['message'] = $this->translations['msg_profile_update_success'] ?? "Profile Successfully updated";

        }else{
            $response['status'] = false;
            $response['message'] = $this->translations['msg_something_wrong'] ?? "Something Wrong Please try again Later";
        }

        return response()->json($response);
 
    }


    /**
     * Used for notification
     * @return redirect to Admin->notification
     */
    public function notification(Request $request)
    {   
        $desc = Notification::with('desc')->whereIn('notification_type',['PROF_COMP','PROF_DEACT','DOUBT_ANSWER','NEW_DOUBT'])->orWhere('user_id',Auth::user()->user_id)->where('status',false)->orderBy('notification_id','DESC')->paginate(10);
        return view('admin.dashboard.notification',compact('desc'));
    }


    /**
     * Used for changeNotificationStatus
     * @return redirect to Admin->changeNotificationStatus
     */
    public function changeNotificationStatus(Request $request)
    {

        $input = $request->all();
        $cid = $input['cid'];
        $response = [];

        if(empty($cid)){
            $response['status'] = false;
        }else{
            $desc = '';
            if ($cid=='ALL') {
                $desc = Notification::where('status',false)->update(['status'=>true]);
            }else{
                $desc = Notification::where('notification_id',$cid)->update(['status'=>true]);
            }
            if ($desc) {
                $response['status'] = true;
                $response['message'] = "Notification Successfully Read";
            }else{
                $response['status'] = false;
            }
        }
        return response()->json($response);
        
    }
    

    /**
     * Used for liveTestChartData
     * @return redirect to Admin->liveTestChartData
     */
    public function liveTestChartData(Request $request)
    {
        $input = $request->all();
        $cntAttemLiveTest = [];
        
        $getLiveTestId = Exam::select('exam_id')->where('exams_type','LIVE_TEST')->get();
        foreach ($getLiveTestId as $key => $value) {
            $cntAttemLiveTest[] =  $value->exam_id;
        }

        $data = UserAttempt::select(DB::raw("COUNT(exam_id) as total"), DB::raw("MONTH(created_at) as month_num"))
                        ->whereIn('exam_id',$cntAttemLiveTest)
                        ->whereYear('created_at', $input['year'])
                        ->groupBy('month_num')
                        ->get()->toArray();
        $graph_data = [];
        if (!empty($data)) {
            $month_data = array_column($data, 'total', 'month_num');
            for ($i = 1; $i <= 12; $i++) {
                if (array_key_exists($i, $month_data)) {
                    //$graph_data[$i]=$month_data[$i];
                    array_push($graph_data, $month_data[$i]);
                } else {
                    array_push($graph_data, 0);
                }
            }
        }
        return $live_test_total = json_encode($graph_data, JSON_NUMERIC_CHECK);
    }



    /**
     * Used for stuAccCreationChartData
     * @return redirect to Admin->stuAccCreationChartData
     */
    public function stuAccCreationChartData(Request $request)
    {
        $input = $request->all();
        
        $data = User::select(DB::raw("COUNT(user_id) as total"), DB::raw("MONTH(created_at) as month_num"))
                        ->where('user_type',2)
                        ->whereYear('created_at', $input['year'])
                        ->groupBy('month_num')
                        ->get()->toArray();
        $graph_data = [];
        if (!empty($data)) {
            $month_data = array_column($data, 'total', 'month_num');
            for ($i = 1; $i <= 12; $i++) {
                if (array_key_exists($i, $month_data)) {
                    //$graph_data[$i]=$month_data[$i];
                    array_push($graph_data, $month_data[$i]);
                } else {
                    array_push($graph_data, 0);
                }
            }
        }
        return  $studentAccCreation_total = json_encode($graph_data, JSON_NUMERIC_CHECK);
    }


    /**
     * Used for quizTestChartData
     * @return redirect to Admin->quizTestChartData
     */
    public function quizTestChartData(Request $request)
    {
        $input = $request->all();
        $cntAttemQuizTest = [];
        
        $getQuizTestId = Exam::select('exam_id')->where('exams_type','QUIZZES')->get();
        foreach ($getQuizTestId as $key1 => $value1) {
            $cntAttemQuizTest[] =  $value1->exam_id;
        }
        
        $data = UserAttempt::select(DB::raw("COUNT(exam_id) as total"), DB::raw("MONTH(created_at) as month_num"))
                        ->whereIn('exam_id',$cntAttemQuizTest)
                        ->whereYear('created_at', $input['year'])
                        ->groupBy('month_num')
                        ->get()->toArray();
        $graph_data = [];
        if (!empty($data)) {
            $month_data = array_column($data, 'total', 'month_num');
            for ($i = 1; $i <= 12; $i++) {
                if (array_key_exists($i, $month_data)) {
                    //$graph_data[$i]=$month_data[$i];
                    array_push($graph_data, $month_data[$i]);
                } else {
                    array_push($graph_data, 0);
                }
            }
        }
        return $quiz_test_total = json_encode($graph_data, JSON_NUMERIC_CHECK);
    }



    /**
     * Used for practiceTestChartData
     * @return redirect to Admin->practiceTestChartData
     */
    public function practiceTestChartData(Request $request)
    {
        $input = $request->all();
        $cntAttemQuizTest = [];
        
        $getQuizTestId = Exam::select('exam_id')->where('exams_type','PRACTICE_TEST')->get();
        foreach ($getQuizTestId as $key1 => $value1) {
            $cntAttemQuizTest[] =  $value1->exam_id;
        }
        
        $data = UserAttempt::select(DB::raw("COUNT(exam_id) as total"), DB::raw("MONTH(created_at) as month_num"))
                        ->whereIn('exam_id',$cntAttemQuizTest)
                        ->whereYear('created_at', $input['year'])
                        ->groupBy('month_num')
                        ->get()->toArray();
        $graph_data = [];
        if (!empty($data)) {
            $month_data = array_column($data, 'total', 'month_num');
            for ($i = 1; $i <= 12; $i++) {
                if (array_key_exists($i, $month_data)) {
                    //$graph_data[$i]=$month_data[$i];
                    array_push($graph_data, $month_data[$i]);
                } else {
                    array_push($graph_data, 0);
                }
            }
        }
        return $practice_test_total = json_encode($graph_data, JSON_NUMERIC_CHECK);
    }


    /**
     * Used for gkQuizTestChartData
     * @return redirect to Admin->gkQuizTestChartData
     */
    public function gkQuizTestChartData(Request $request)
    {
        $input = $request->all();
        $cntAttemQuizTest = [];
        
        $getQuizTestId = Exam::select('exam_id')->where('exams_type','GK_CA')->get();
        foreach ($getQuizTestId as $key1 => $value1) {
            $cntAttemQuizTest[] =  $value1->exam_id;
        }
        
        $data = UserAttempt::select(DB::raw("COUNT(exam_id) as total"), DB::raw("MONTH(created_at) as month_num"))
                        ->whereIn('exam_id',$cntAttemQuizTest)
                        ->whereYear('created_at', $input['year'])
                        ->groupBy('month_num')
                        ->get()->toArray();
        $graph_data = [];
        if (!empty($data)) {
            $month_data = array_column($data, 'total', 'month_num');
            for ($i = 1; $i <= 12; $i++) {
                if (array_key_exists($i, $month_data)) {
                    //$graph_data[$i]=$month_data[$i];
                    array_push($graph_data, $month_data[$i]);
                } else {
                    array_push($graph_data, 0);
                }
            }
        }
        return $gk_quiz_test_total = json_encode($graph_data, JSON_NUMERIC_CHECK);
    }

    /**
     * Used for Profile Change Password when forgot save
     * @return redirect to Admin->Profile
    */
    public function changePasswordPost(Request $request)
    {
        $response = [];
        $input = $request->all();
        if(isset($input['old_password']) && $input['old_password'] != null && !empty($input['old_password']))
        {    

            if (Hash::check($input['old_password'], Auth::user()->password)) {
                // The passwords match...
                $user = User::findorfail(Auth::user()->user_id);
                
                if(isset($input['new_password']) && $input['new_password'] != null && !empty($input['new_password']))
                {
                    $user->password = Hash::make($input['new_password']);
                }   
                if($user->save()){
                    //return redirect('logout')->with('success', 'Password Updated Successful');
                    $response['status'] = true;
                    $response['message'] = $this->translations['msg_pass_update_success'] ?? "Password updated successfully";
                    $response['redirect'] = url('/logout');

                }else{
                    $response['status'] = false;
                    $response['message'] = $this->translations['msg_something_wrong'] ?? 'Something Wrong Please try again Later';
                }
               
            }else{
                $response['status'] = false;
                $response['message'] = $this->translations['msg_pass_match_fail'] ?? "Entered password is incorrect, Your password doesn't match";
            }
        }
        return response()->json($response);
    }
    
    //SAVE FIREBASE USER TOKKEN FOR PUSH NOTIFICATIONS
    public function saveToken(Request $request)
    {
        $input = $request->all();
        $tokenCoutn = User::where('user_id','=',Auth::user()->user_id)->count();
        if($tokenCoutn > 0)
        {
          $token = User::where('user_id','=',Auth::user()->user_id)->first();
            if ($token->device_token!=$input['token']) {
                $token->updated_at = Carbon::now();
                $token->device_token = $input['token'];
                if($token->save())
                {
                    return response()->json(['message' => 'Token saved successfully', 'code' => 200]);
                }
            }elseif($token->device_token==$input['token']){
                return response()->json(['message' => 'Token is matched', 'code' => 201]);
            }
        }
        else
        {
            return response()->json(['message' => 'Something went wrong successfully', 'code' => 201]);
        }
    } 
}
