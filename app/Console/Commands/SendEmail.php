<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\ForgotPassword;
use App\Models\EmailLog;
use App\Models\User;
use App\Models\Exam;
use App\Models\Question;
use Carbon\Carbon;
use Mail;

class SendEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send auto email ';

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
        //SET QUESTION REUSABLE
        $get_current_date = date('Y-m-d',strtotime(now()));
        $get_expire_exam = Exam::whereDate('result_date','<=',$get_current_date)->get();
        foreach ($get_expire_exam as $k => $v) {
            $result_date = date('Y-m-d',strtotime(Carbon::parse($v->result_date)->addDays(10)));
            if ($result_date<=$get_current_date && $v->exams_type=='LIVE_TEST') {
                $get_all_question = Question::where('exam_id',$v->exam_id)->where('isAssignable',0)->get();
                foreach ($get_all_question as $ke => $va) {
                    Question::where('questions_id',$va->questions_id)->update(['isAssignable'=>1]);
                }
            }
        }

        //SET QUE REUSABLE FOR OTHER TEST
        $get_expire_exams = Exam::where('exams_type','!=','LIVE_TEST')->whereDate('created_at','<',$get_current_date)->get();
        foreach ($get_expire_exams as $vs) {
            $result_date = date('Y-m-d',strtotime(Carbon::parse($vs->created_at)->addDays(10)));
            if ($result_date<=$get_current_date && $vs->exams_type!='LIVE_TEST') {
                $get_all_questions = Question::where('exam_id',$vs->exam_id)->where('isAssignable',0)->get();
                foreach ($get_all_questions as $vas) {
                    Question::where('questions_id',$vas->questions_id)->update(['isAssignable'=>1]);
                }
            }
        }
        //--END
        //SEND EMAILS CRON 
        $getAllPendingEmail = EmailLog::where('email_status',2)->get();
        foreach ($getAllPendingEmail as $key => $value) {
            $user = User::where('user_id',$value->user_id)->where('deleted',0)->first();
            
            $msg = $value->email_content;
            
            $subject = $value->subject;
            if (!empty($user->email) || $user->email!=null) {
                Mail::to($user->email)->send(new ForgotPassword($msg,$subject));
            }

            if (Mail::failures()) {

                EmailLog::where('email_log_id',$value->email_log_id)->update(['email_status'=>3]);
            
            }else{

                EmailLog::where('email_log_id',$value->email_log_id)->update(['email_status'=>1]);
            
            }

        }

    }
}
