<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmailLog;
use App\Models\User;
use App\Models\EmailMaster;
use Auth;

class ContactController extends Controller
{
    /**
    * Send contact Query to admin 
    *
    */
    public function contactQueryPost(Request $request)
    {
    	$input = $request->all();
    	$response =[];
    	$user = User::where('user_type',0)->where('deleted',0)->first();
    	$data = EmailMaster::where('email_key', 'website_contact_form')->first();

        if (isset($data) && !empty($data)) {
            $data = $data->toArray();      

            $message = $data['content'];

            $subject = $data['subject'];
            
            $message1 = str_replace("{{ADMINNAME}}", $user->name, $message);
            
            $message2 = str_replace("{{NAME}}", $input['name'], $message1);
            
            $message3 = str_replace("{{EMAIL}}", $input['email'], $message2);

            $message4 = str_replace("{{MESSAGECONTENT}}", $input['message'], $message3);
           	
           	$email_log = new EmailLog;
           	$email_log->user_id = Auth::check() ? Auth::user()->user_id : $user->user_id;
           	$email_log->subject = isset($subject) ? $subject :'';
           	$email_log->email_content = isset($message4) ? $message4 :'';
           	$email_log->email_status = 2;
           	$email_log->created_at = now();
           	if($email_log->save()){
            	$message ='Contact Query Submitted Successfully';
           		return redirect()->route('websiteHome')->with('success',$message);
           	}
         	

        }else{
            $message ='Email Template Not Found';
            return redirect()->route('websiteHome')->withErrors([$message])
                        ->withInput();
        }
    }
}
