<?php
/**
* UserController 
*/

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\MailHelper;
use App\Helpers\FrontHelper;
use App\Mail\ForgotPassword;
use App\Models\UserVerification;
use App\Models\AdminModule;
use App\Models\EmailMaster;
use App\Models\User;
use Carbon\Carbon;
use Validator;
use Hash;
use Auth;
use Session;
use URL;
use Config;

class UserController extends Controller
{
    use AuthenticatesUsers;
    
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function index(Request $request)
    {
        /**
         * Used for Admin Login
         * @return redirect to Login
         */
      $data = '';
    	$input = $request->all();       
    	return view('auth.login',compact('data'));
    }

    public function loginPost(Request $request)
    {
        /**
         * Used for Login Checking
         */

        $loginType = ['admin'=>0, 'Sub Admin'=>1];

        $input = $request->all();            
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        if ($validator->fails()) {
            return redirect()->route('adminLoginForm')
                        ->withErrors($validator)
                        ->withInput();
        }
        $emailCount = User::where('email','=',$input['email'])->count();

        $noEmailFound = true;

        if($emailCount != 0){
          $userData = User::where('email','=',$input['email'])->first();
          $Name = $userData->adm_Name;
          $noEmailFound = false;
        }

        if($noEmailFound){
          $message = $this->translations['msg_no_email_found'] ?? 'No such email found';
          return redirect()->route('adminLoginForm')->withErrors([$message]);
        }

        if($userData->user_status == 0){
            $message = 'Your account is inactive, please try again after sometime';
            return redirect()->route('adminLoginForm')
                        ->withErrors([$message]);
        }

        if($userData->deleted == true){
            $message = 'Your account is deleted, please contact admin';
            return redirect()->route('adminLoginForm')
                        ->withErrors([$message]);
        }

        if($userData->user_type == 0){
            $userType = 'admin';
        }else if($userData->user_type == 1){
            $userType = 'sub-admin';
        }else{
            $message = 'Access Prohibited';
            return redirect()->route('adminLoginForm')
                        ->withErrors([$message]); 
        }

        if(!empty($userType)){
            if(Auth::attempt(['email' => $input['email'], 'password' => $input['password'] ])){
                
                //SET SESSION FOR SUB ADMIN
                if ($userType=='sub-admin') {
                  $accessPriData = [];
                  $accessPrivilege = AdminModule::where('subadmin_id',Auth::id())->get()->toArray();
                  foreach ($accessPrivilege as $key => $value) {
                      $accessPriData['accessPriData'][$value['module']] = json_decode($value['sections']);
                  }
                  session($accessPriData);
                }
                // END !SET SESSION FOR SUB ADMIN

                return redirect()->route('adminDashboard');
            }else{
                $message = $this->translations['msg_invalid_password'] ?? 'Please enter valid password';
                return redirect()->route('adminLoginForm')
                        ->withErrors([$message]);
            }
        }


    }   


    public function forgotPassword(Request $request)
    {
         /**
         * Used for Forgot Password Page
         * @return redirect to Admin->Forgot Password page
         */
        return view('auth.forgot');        
    }

    public function forgotPasswordPost(Request $request)
    {
        /**
         * Used for Forgot Password Check
         * @return redirect to Admin->Forgot Password Check
        */
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        
        if ($validator->fails()) {
            return redirect()->route('forgotPassword')
                        ->withErrors($validator)
                        ->withInput();
        }

        $user = User::where('email','=',$input['email'])->first();
        
        if (empty($user)) 
        {
            $message = 'Sorry, we can not find this email id in the system. Please try again later';
            return redirect()->route('forgotPassword')
                        ->withErrors([$message])
                        ->withInput();
        }
        else{ 
          
          $data = EmailMaster::where('email_key', 'forget_password')->first();

          if (isset($data) && !empty($data)) {
            $data = $data->toArray();      
            
            $num = mt_rand(100000,999999);
            
            $userVerify = new UserVerification;

            $userVerify->user_id = $user->user_id;
            $userVerify->verification_type = 'FORGOT_PASSWORD';
            $userVerify->verification_value = $user->email;
            $userVerify->verification_otp = $num;
            $userVerify->save();
            
            $message = $data['content'];

            $subject = $data['subject'];
            
            $message1 = str_replace("{{USERNAME}}", $user->name, $message);
            
            $msg = str_replace("{{RESET_PASS_OTP}}", $num, $message1);
            $mail = Mail::to($user->email)->send(new ForgotPassword($msg,$subject));

            $user_id = $user->user_id;

            $message ='A password reset Otp has been sent on your registered Email address';
            return redirect()->route('getVerifyOtp',['id'=>$user_id])->with('success', $message);
            
          }else{
            $message ='Email Template Not Found';
            return redirect()->route('forgotPassword')->withErrors([$message])
                        ->withInput();
          }

        }
    }


    /**
    * Used for Admin OTP verify check right or not
    * @return redirect to Admin->OTP Check
    */
    public function getVerifyOtp(Request $request, $user_id)
    {
      $user_id = User::where('user_id',$user_id)->first();
      if ($user_id) {
        return view('auth.verifyOtp',compact('user_id'));
      }else{
        $message ='Something Went Wrong';
            return redirect()->route('forgotPassword')->withErrors([$message])
                        ->withInput();
      }
    }


    /**
    * Used for Admin OTP verify check right or not
    * @return redirect to Admin->OTP Check
    */
    public function verifyOtpPost(Request $request)
    {
        $input = $request->all();
        
        $otpData = UserVerification::whereDate('created_at', Carbon::today())->where('user_id','=',$input['user_id'])->where('verification_type','FORGOT_PASSWORD')->orderBy('created_at','DESC')->first();
        $user = User::where('user_id','=',$input['user_id'])->first();
        if(!empty($otpData) && $otpData != null  && $otpData->verification_otp==$input['otp']){
              $uType = Config::get('constant.role.'.$user->user_type);
              $current_time = date("Y-m-d H:i:s");
              $reset_pass_token = base64_encode($user->email.'&&'.$uType."&&".$current_time);
              $resetLink = URL::to('resetPassword').'/'.$reset_pass_token;
             return redirect($resetLink);            
        
        }else{
          $message ='Please enter valid OTP';
            return redirect()->route('getVerifyOtp',['id'=>$input['user_id']])->withErrors([$message])->withInput();
        }
    }

    //SET PASSWORD FOR SUB ADMIN
    public function setPassword($token)
    {
        $response = [];

        $decoded = base64_decode($token);
        $tmp_dec = explode('&&', $decoded);
        
        if(empty($tmp_dec[0]) || empty($tmp_dec[1]) || empty($tmp_dec[2])){
            $response['status'] = false;
            $response['message'] = 'Invalid reset password token';
            return response()->json($response);
            exit();
        }


        $current_time = date("Y-m-d H:i:s");

        $minuteDiff = round((strtotime($current_time) - strtotime($tmp_dec[2]))/60, 1);


        if($minuteDiff > 30){ //check if link is generated more than 30 mins ago
            $message = $this->translations['msg_reset_pass_link_expire'] ?? 'Sorry, the reset password link is expired please try again';
            return redirect()->route('forgotPassword')
                        ->withErrors([$message])
                        ->withInput();
        }
      
        return view('auth.reset_password',['token'=>$token]);
    }

    public function resetPassword($token)
    {
        $response = [];

        $decoded = base64_decode($token);
        $tmp_dec = explode('&&', $decoded);
        
        if(empty($tmp_dec[0]) || empty($tmp_dec[1]) || empty($tmp_dec[2])){
            $response['status'] = false;
            $response['message'] = 'Invalid reset password token';
            return response()->json($response);
            exit();
        }


        $current_time = date("Y-m-d H:i:s");

        $minuteDiff = round((strtotime($current_time) - strtotime($tmp_dec[2]))/60, 1);


        if($minuteDiff > 30){ //check if link is generated more than 30 mins ago
            $message = $this->translations['msg_reset_pass_link_expire'] ?? 'Sorry, the reset password link is expired please try again';
            return redirect()->route('forgotPassword')
                        ->withErrors([$message])
                        ->withInput();
        }
      
        return view('auth.reset_password',['token'=>$token]);
    }

    public function logout(Request $request)
    {
      /**
       * Used for Admin Logout
       * @return redirect to Admin->Logout
       */
        Auth::logout();
        $request->session()->flush();
        return redirect()->route('adminLoginForm');     
      
    }

    public function changePasswordPost(Request $request)
    {
         /**
         * Used for Profile Change Password when forgot save
         * @return redirect to Admin->Profile
         */

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

    public function resetPasswordPost(Request $request)
    {
        $input = $request->all();
        $response = [];
        $decoded = base64_decode($input['token']);
        $tmp_dec = explode('&&', $decoded);
        
        if(empty($tmp_dec[0]) || empty($tmp_dec[1])){
            $response['status'] = false;
            $response['message'] = $this->translations['msg_invalid_pass_token'] ?? 'Invalid reset password token';
            return response()->json($response);
            exit();
        }

        $new_pass = Hash::make($input['new_password']);

        $user = User::where('email', $tmp_dec[0])->first();

        if(!empty($user)) {
          $user->password = $new_pass;
          $user->save();
          $response['status'] = true;
          $response['message'] = $this->translations['msg_reset_pass_success'] ?? 'Reset Password Successful';
        }else{
          $response['status'] = false;
          $response['message'] = $this->translations['msg_account_not_exist'] ?? 'Sorry, your account does not exist in the system';
        }
        $response['redirect'] = route('adminLoginForm');
        return response()->json($response);
    }

    public function verifyEmail() {
      $key = Input::get('verification-key');
      $user = Admin::where('email_verification_key', $key)->get()->first();
      if(empty($user)) {
        $user = Employee::where('email_verification_key', $key)->get()->first();
      }
      if(empty($user)) {
        $user = Student::where('email_verification_key', $key)->get()->first();
      }
      $message = '';
      $status = '';
      if (!empty($user)) {
         if ($user->email_verified_at) {
            $message = $this->translations['msg_email_already_verify'] ?? 'Your email is already verified. You can login at';
            // $message = $message ." <a href='".url('/')."'>Hertronic</a>";
            $status = true;
         } else {
            $user->email_verification_key = null;
            $user->email_verified_at = now();
            $user->save();
            $message = $this->translations['msg_email_verify_success'] ?? 'Thank you, your email has been verified';
            $status = true;
         }
      } else {
         $message = $this->translations['msg_invalid_verify_key'] ?? "Invalid Verification Key.";
         $status = false;
      }
    
      return view('email_verify', array('message' => $message,'status'=>$status));
    }

    public function generateRandomText($n) { 
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
        $randomString = ''; 
      
        for ($i = 0; $i < $n; $i++) { 
            $index = rand(0, strlen($characters) - 1); 
            $randomString .= $characters[$index]; 
        } 
      
        return $randomString; 
    } 

    public function switchLanguage(Request $request){
          $response = [];

          $data = $request->all();

          Session::put('current_language', $data['lang']);
          
          $response['message']="Language changed successfully!";
          $response['status']=true;

        return response()->json($response);
    }
}