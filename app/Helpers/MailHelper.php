<?php

namespace App\Helpers;

use App\User;
use App\GeneralSetting;
use Mail;
use Illuminate\Contracts\View\View;
use App\Helpers\CustomLogHelper;

class MailHelper {

   public static $dataArray = [];

   public static function sendOTPEmail($user,$otp) {
      // $email = Email::find(1);

      // $content = 'Hi {NAME},<br>
      // Here is your OTP for reset password.<br>OTP - {OTP}';

      $content = view('email.password_reset_otp')->render();
      $content = str_replace('{NAME}', $user->name, $content);
      $content = str_replace('{OTP}', $otp, $content);

      //var_dump($content);die();

      MailHelper::sendEmail($user->email, 'Password reset OTP', $content);
   }

   public static function sendWelcomeEmailWithTempPassword($user,$dataArray=[]) {
      $email = Email::find(78);

      $content = $email->content;
      $content = str_replace('{FIRSTNAME}', ucwords($user->first_name . ' ' . $user->last_name), $content);
      $content = str_replace('{PASSWORD}', $dataArray['password'], $content);

      MailHelper::sendEmail($user->email, $email->title, $content);
   }

   public static function sendNewCredentials($user){

      $content = view('email.new_credentials')->render();
      $content = str_replace('{NAME}', $user['name'], $content);
      $content = str_replace('{VERIFY_KEY}', $user['verify_key'], $content);
      $content = str_replace('{RESET_PASS_LINK}', $user['reset_pass_link'], $content);
      $content = str_replace('{EMAIL}', $user['email'], $content);

      MailHelper::sendEmail($user['email'], $user['subject'], $content);
   }

   public static function sendNewPrincipalCredentials($user){

      $content = view('email.new_principal_credentials')->render();
      $content = str_replace('{NAME}', $user['name'], $content);
      $content = str_replace('{SCHOOLNAME}', $user['school'], $content);
      $content = str_replace('{VERIFY_KEY}', $user['verify_key'], $content);
      $content = str_replace('{RESET_PASS_LINK}', $user['reset_pass_link'], $content);
      $content = str_replace('{EMAIL}', $user['email'], $content);

      MailHelper::sendEmail($user['email'], $user['subject'], $content);
   }
   public static function sendEmailVerification($user){

    $content = view('email.verification_mail')->render();
    $content = str_replace('{NAME}', $user['name'], $content);
    $content = str_replace('{VERIFY_KEY}', $user['verify_key'], $content);
    $content = str_replace('{EMAIL}', $user['email'], $content);

    MailHelper::sendEmail($user['email'], $user['subject'], $content);

   }

   public static function sendForgotPassAdmin($dataArray=[]){
    
      $content = view('email.forgot_password_mail')->render();
      $content = str_replace('{FIRSTNAME}', $dataArray['name'], $content);
      $content = str_replace('{RESET_PASS_LINK}', $dataArray['reset_pass_link'], $content);
      var_dump($content);
      MailHelper::sendEmail($dataArray['email'], 'Forgot Password Notification', $content);
   }

   public static function sendBANotifactionAdmin($dataArray=[]){
    
      $content = view('email.admin_ba_notification_mail')->render();
      $content = str_replace('{FIRSTNAME}', $dataArray['firstname'], $content);
      $content = str_replace('{DATE}', $dataArray['date'], $content);
      $content = str_replace('{TIME}', $dataArray['time'], $content);
      $content = str_replace('{LOCATION_NAME}', $dataArray['location_name'], $content);
      $content = str_replace('{CREATED_BY}', $dataArray['created_by'], $content);


      // var_dump($dataArray['email']);die();

      MailHelper::sendEmail($dataArray['email'], 'New BA Notification', $content);
   }

   public static function sendWelcomeEmail($user) {
      $email = Email::find(7);
      $content = $email->content;
      $content = str_replace('{FIRSTNAME}', $user->first_name, $content);
      $content = str_replace('{USERNAME}', $user->email, $content);

      MailHelper::sendEmail($user->email, $email->title, $content);
   }

   public static function sendEmail($to, $subject, $content, $cc = [], $sent = 0, $attach = false, $pdf_link = '') {

      //$general_data = GeneralSetting::first();


      $header = view('layout.email.email_header')->render();
      $header = str_replace('{DOMAIN}', env('DOMAIN'), $header);
      $header = str_replace('{LOGO}', env('DOMAIN') . '/v1/public/images/logo.png', $header);
      $footer = view('layout.email.email_footer')->render();
      $footer = str_replace('{YEAR}', date('Y'), $footer);

      if ($pdf_link != '') {
         $pdf_link = url('') . '/' . $pdf_link;
      }

      $data = array(
          'header' => $header,
          'content' => $content,
          'footer' => $footer,
      );

      try {
         Mail::send('layout.email.email_master', $data, function($message) use($to, $subject, $cc, $attach, $pdf_link) {

            $message->from(env('MAIL_FROM'), env('MAIL_NAME'));
            $message->to($to)->subject($subject);
            $message->replyTo(env('MAIL_REPLY'), env('MAIL_NAME'));
            $message->cc($cc);

            if(isset(self::$dataArray['pdf_path']) && self::$dataArray['pdf_path']){
               $message->attach(self::$dataArray['pdf_path']);
            }

            foreach ($cc as $bcc) {
               $message->bcc($bcc);
            }
         });
      } catch (\Exception $e) {
        // $logHelper = new CustomLogHelper();
        // $logHelper->addToLog("Error for email is ".$e->getMessage()."\n ".$e->getTraceAsString());
        var_dump($e->getMessage());die();
      }

      // $message = view('layout.email_master', $data)->render();
   }

 

   public static function contactusEmail($first_name, $last_name, $phone, $useremail, $city, $state_name, $county_data, $message, $contactus_email) {

      //$contactus_email1 = "jaydhakan@technource.com";
      $email_admin = Email::find(25);

      $content = $email_admin->content;
      $content = str_replace('{FIRSTNAME}', ucfirst($first_name), $content);
      $content = str_replace('{LASTNAME}', ucfirst($last_name), $content);
      $content = str_replace('{EMAIL}', $useremail, $content);
      $content = str_replace('{PHONE}', $phone, $content);
      $content = str_replace('{CITY}', $city, $content);
      $content = str_replace('{STATE}', $state_name, $content);
      $content = str_replace('{COUNTY}', $county_data, $content);
      $content = str_replace('{MESSAGE}', $message, $content);

      $email_user = Email::find(26);

      $content_user = $email_user->content;
      $content_user = str_replace('{FIRSTNAME}', ucfirst($first_name), $content_user);
      $content_user = str_replace('{LASTNAME}', ucfirst($last_name), $content_user);

      MailHelper::sendEmail($contactus_email, $email_admin->title, $content);
      MailHelper::sendEmail($useremail, $email_user->title, $content_user);
   }

 

   public static function sendCustomerEmail($cpdf_path, $cemail){
     $email = Email::find(69);

     $content = $email->content;
     $content = str_replace('{NAME}', 'Customer',$content);

     $content = str_replace('{OVERVIEW}', '', $content);
     $weekenddate = date('F j, Y');

     self::$dataArray['pdf_path'] = $cpdf_path;

     MailHelper::sendEmail($cemail, $email->title, $content);
   }



   public static function sendSMSLeadMail($data) {

     if ($data['usertype'] == 1) {
        $email = Email::find(81);
     } elseif ($data['usertype'] == 2) {
        $email = Email::find(82);
     }

     $content = $email->content;
     $content = str_replace('{FROMNUMBER}', ucfirst($data['fromusernumber']), $content);
     $content = str_replace('{AGENTNAME}', ucfirst($data['agent_name']), $content);
     $content = str_replace('{ADDRESS}', $data['address'], $content);

     MailHelper::sendEmail($data['email'], $email->title, $content);
   }

   
}

?>
