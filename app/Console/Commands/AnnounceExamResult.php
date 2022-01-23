<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\AnnounceLiveTestResult;
use App\Models\UserAttempt;
use App\Models\EmailMaster;
use App\Models\Exam;
use App\Models\Notification;
use App\Models\NotificationDesc;
use App\Models\Translation;
use App\Models\User;
use App\Models\UserExamResponse;
use Carbon\Carbon;
use UserNotifications;
use DB;
use App;

class AnnounceExamResult extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'result:Announce';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Announce Exam Result';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $updateRank = [];
        $notifi = [];
        $addData = [];
        $checkUserId = [];
        $updateRankBase ='';
        $current_date = date('Y-m-d H:i',strtotime(now()));
        $current_date_only = date('Y-m-d',strtotime(now()));
        $current_date_live_test_start = date('Y-m-d H:i A');

        /*========  If Live test is strat then notification will be send =========*/
        $getLiveTestExam = Exam::with('desc')->where('exams_type','LIVE_TEST')->whereDate('exam_starts_on',$current_date_only)->get();
        
        $aNotifyStartLiveMsg = Translation::where('group','notification_message')->where('key','live_test_started_msg')->first()->toArray();
        $aNotifyStartLiveTitle = Translation::where('group','notification_title')->where('key','live_test_started_tile')->first()->toArray();
        foreach ($getLiveTestExam as $glte_value) {
            $examStartingDate = date('Y-m-d H:i A',strtotime($glte_value->exam_starts_on));
            if ($examStartingDate==$current_date_live_test_start ){
                $getLiveTestExamUser =  UserAttempt::where('exam_id',$glte_value->exam_id)->get();
                foreach ($getLiveTestExamUser as $lteu_key => $lteu_value) {
                    
                    $getSingleUser = User::select('user_lang_code','user_id','name','email','device_token')->where('user_id',$lteu_value->user_id)->first();
                    
                    if (!empty($getSingleUser->device_token) || $getSingleUser->device_token != null) {
                        $userLangCode = !empty($getSingleUser->user_lang_code) ? $getSingleUser->user_lang_code : 'en';
                        $bodyTexts = $aNotifyStartLiveMsg['text'][$userLangCode];
                        $titleTexts = $aNotifyStartLiveTitle['text'][$userLangCode];
                        $bodyTexts =  str_replace('TEST_NAME',$glte_value->desc->exam_name, $bodyTexts);

                        $device_token = $getSingleUser->device_token;
                        $notification = ['title' => $titleTexts, 'body' => $bodyTexts];
                        $notification_data = [
                            'action' => 'LIVE_TEST_STARTING',
                            'exam_id' =>  $glte_value->exam_id
                        ];
                        
                        UserNotifications::sendPush($device_token, $notification, $notification_data);
                        
                    }
                }
            }
        }
        /*========= END ===========*/

        /*=========== SEND NOTIFICATIONS TO USERS BEFORE EXAM START =============*/
        $getBeforeExam = Exam::with('desc')->where('exams_type','LIVE_TEST')->whereDate('exam_starts_on',$current_date_only)->get();
        $aNotificaTransMsgs = Translation::where('group','notification_message')->where('key','live_test_before_1h_msg')->first()->toArray();
        $aNotificaTransTtle = Translation::where('group','notification_title')->where('key','live_test_before_1h_title')->first()->toArray();

        foreach ($getBeforeExam as $gbe_key => $gbe_value) {
            $getExamStartDate = date('Y-m-d H:i',strtotime(Carbon::parse($gbe_value->exam_starts_on)->subHour()));
            //$getExamStartDate1 = date('Y-m-d H:i',strtotime(Carbon::parse($gbe_value->exam_starts_on)->subMinutes(59)));
            if ($current_date==$getExamStartDate /*|| $current_date==$getExamStartDate1*/) {
                $getBeforeExamsUser = UserAttempt::where('attempt_status','REGISTERED')->where('exam_id',$gbe_value->exam_id)->get();
                foreach ($getBeforeExamsUser as $beu_key => $beu_value) {
                    $getSingleUser = User::select('user_lang_code','user_id','name','email','device_token')->where('user_id',$beu_value->user_id)->first();
                    
                    /*======= send notifications to each user ========*/
                    if (!empty($getSingleUser->device_token) || $getSingleUser->device_token != null) {
                        $userLangCode = !empty($getSingleUser->user_lang_code) ? $getSingleUser->user_lang_code : 'en';
                        $bodyTexts = $aNotificaTransMsgs['text'][$userLangCode];
                        $titleTexts = $aNotificaTransTtle['text'][$userLangCode];
                        $bodyTexts =  str_replace('TEST_NAME',$gbe_value->desc->exam_name, $bodyTexts);

                        $device_token = $getSingleUser->device_token;
                        $notification = ['title' => $titleTexts, 'body' => $bodyTexts];
                        
                        $notification_data = [
                            'action' => 'LIVE_TEST_STARTING',
                            'exam_id' =>  $gbe_value->exam_id
                        ];
                        
                        UserNotifications::sendPush($device_token, $notification, $notification_data);
                        
                    }
                }
            }//END Date if
        }
        //--END
        //END -------SEND NOTIFICATIONS TO USERS BEFORE EXAM START

        $getExamData = Exam::with('desc')->where('exam_ends_on','<=',$current_date)->where('exams_type','LIVE_TEST')->where('result_announce_status',false)->first();
        //UPDATE USERATTEMPT AND USER EXAMRESPONSE TABLE
        if (empty($getExamData) || $getExamData==null) {
            return 0;
        }
        else
        {

            //DELETE USERS DATA
            UserAttempt::where('exam_id',$getExamData->exam_id)->where('attempt_status','!=','COMPLETED')->delete();
            
            //UPDATE RANK AND BASE
            $total_participants =  UserAttempt::where('attempt_status','COMPLETED')->where('exam_id',$getExamData->exam_id)->count();
            
            if ($total_participants>0) {
                $updateRankBase = UserAttempt::where('exam_id',$getExamData->exam_id)->where('attempt_status','COMPLETED')->update(['user_rank_base'=>$total_participants]);
            }
            //UPDATE RANK 
            if ($updateRankBase) {
                $rank = 0;
                $allData = UserAttempt::where('exam_id',$getExamData->exam_id)->where('attempt_status','COMPLETED')->orderBy('total_obtain_marks','DESC')->get()->each(function($value,$key) use($rank){
                    $value->update(['user_rank' => $key+1]);
                });
            }
            
            $getExamData = Exam::with('desc')->where('result_date','<=',$current_date)->where('exams_type','LIVE_TEST')->where('result_announce_status',false)->first();
            if (!empty($getExamData)) {
                Exam::where('exam_id',$getExamData->exam_id)->update(['result_announce_status'=>true]);
                $getExamsUser = UserAttempt::where('attempt_status','COMPLETED')->where('exam_id',$getExamData->exam_id)->orderBy('total_obtain_marks','DESC')->get()->toArray();
                
                //UPDATE THROUGH EMAIL
                $date = date('d-M-Y, h:i:s');
                $data = EmailMaster::where('email_key', 'admin_announce_live_test_result')->first();
                $checkStu = count($getExamsUser);

                if ($checkStu > 0) {
                    //GET NOTIFICATION TRANSLATE KEY
                    $aNotificaTransMsg = Translation::where('group','notification_message')->where('key','live_test_result_announce_msg')->first()->toArray();
                    $aNotificaTransTitle = Translation::where('group','notification_title')->where('key','live_test_result_announce_title')->first()->toArray();
                    foreach ($getExamsUser as $key => $value) {
                        $user = User::select('user_lang_code','user_id','name','email','device_token')->where('user_id',$value['user_id'])->first();
                        if (isset($data) && !empty($data)) {
                            //UPDATE RESULT ANNOUNCE STATUS
                            Exam::where('exam_id',$getExamData->exam_id)->update(['result_announce_status'=>true]);
                            //SEND NOTIFICATION
                            //GET NOTIFICATION TRANSLATE KEY
                            $userLangCode = !empty($user->user_lang_code) ? $user->user_lang_code : 'en';
                            //SET LANGUAGE
                            $local = $userLangCode;
                            App::setLocale($local);
                            //--END
                            $bodyTxt = $aNotificaTransMsg['text'][$userLangCode];
                            $titleText = $aNotificaTransTitle['text'][$userLangCode];
                            $bodyText =  str_replace('TEST_NAME',$getExamData->desc->exam_name, $bodyTxt);
                            if (!empty($user->device_token) || $user->device_token != null) {
                                $device_token = $user->device_token;
                                $notification = ['title' => $titleText, 'body' => $bodyText];
                                
                                $notification_data = [
                                    'action' => 'RESULT_ANNOUCED',
                                    'exam_id' =>  $getExamData->exam_id
                                ];
                                
                                try
                                {
                                    UserNotifications::sendPush($device_token, $notification, $notification_data);
                                } catch (\Exception $e) {
                                    continue;   
                                }
                                
                                $notifi = new Notification;
                                $notifi->user_id  = $user->user_id;
                                $notifi->notification_type  = 'RESULT';
                                $notifi->ntoification_type_id = $getExamData->exam_id;
                                $notifi->notification_data = json_encode($notification_data);
                                $notifi->status = false;
                                $notifi->save();
                                if ($notifi) {
                                    for ($i=1; $i <=2 ; $i++) { 
                                        $addData[$i]['notification_id'] = $notifi->notification_id;
                                        $addData[$i]['lang_code'] = $i==1 ? 'en' : 'hi';
                                        $addData[$i]['message'] = $i==1 ? str_replace('TEST_NAME',$getExamData->desc->exam_name, $aNotificaTransMsg['text']['en']) : str_replace('TEST_NAME',$getExamData->desc->exam_name, $aNotificaTransMsg['text']['hi']);
                                    }
                                    NotificationDesc::insert($addData);
                                }
                            }
                            //$data = $data->toArray();      
                            
                            $message = $data->content;

                            $subject = $data->subject;
                            $em_name = $user->name ?? 'NA';
                            $em_email = $user->email ?? 'NA';
                            $message1 = str_replace("{{USERNAME}}", $em_name, $message);
                            $msg = str_replace("{{DATE}}", $date, $message1);
                            if ($em_email!='NA') {
                                $mail = Mail::to($em_email)->send(new AnnounceLiveTestResult($msg,$subject));
                            }

                        }

                    }
                }
            
            }
        }


        /*============= UPDATE CALCULATE %  FOR PRACTICE or GK CA =============*/
        $examType = ['PRACTICE_TEST','GK_CA'];
        $getAllAttemptedExam = UserAttempt::whereIn('exams_type',$examType)->where('attempt_status','COMPLETED')->where('user_percentage',0)->orderBy('user_attempt_id','DESC')->get();
        foreach ($getAllAttemptedExam as $aekey => $aevalue) {
            $get_marks_per_que = $aevalue->total_marks/$aevalue->total_questions;
            $get_obtain_marks = $get_marks_per_que*$aevalue->total_correct;
            $calculate_percent = $get_obtain_marks/$aevalue->total_marks*100;
            UserAttempt::where('user_attempt_id',$aevalue->user_attempt_id)->update(['total_obtain_marks'=>$get_obtain_marks,'user_percentage'=>$calculate_percent]);
        }
        /*======= END ======*/
    }
}
