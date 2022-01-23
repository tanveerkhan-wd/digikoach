<?php

namespace App\Common;

use Exception;
use Kreait\Firebase\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\ApnsConfig;

use App\Models\User;
use App\Models\Notification as NotificationModal;
use App\Models\NotificationDesc;


class UserNotifications
{
    private $sms_endpoint = 'https://api.textlocal.in/send/';
    private $sms_api_key = '';
    private $sms_username = '';
    private $sms_hash = '';
    private $sms_sender_id = 'DIGKCH';
    private $sms_api_mode = 'LIVE';

    function __construct(Messaging $messaging)
    {
        $this->sms_api_key = env('TEXTLOCAL_KEY', '');
        $this->sms_username = env('TEXTLOCAL_USERNAME', '');
        $this->sms_hash = env('TEXTLOCAL_HASH', '');
        
        $this->messaging = $messaging;
    }

    /**
     * Send SMS to provided number
     *
     * @param  integar $mobile_no
     * @param  mixed $sms_content
     * @return mixed response
     */
    public function send_sms($mobile_no, $sms_content)
    {
        try {
            if ($mobile_no && $sms_content !== "") {
                $mobile_number = isset($mobile_no) ? substr($mobile_no, -10) : '';

                if ($mobile_number) {
                    $mobile_numbers = implode(",", [$mobile_number]);
                    $message = rawurlencode($sms_content);

                    // Prepare data for POST request
                    $post_data = [
                        'apikey' => $this->sms_api_key,
                        'username' => $this->sms_username,
                        'hash' => $this->sms_hash,
                        'numbers' => $mobile_numbers,
                        'sender' => $this->sms_sender_id,
                        'message' => $message,
                        'test' => ($this->sms_api_mode === 'TEST')
                    ];

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $this->sms_endpoint);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
                    $response = curl_exec($ch);
                    /* if($_SERVER['REMOTE_ADDR'] == '43.241.194.74'){
                        echo '<pre>';
                        print_r($response);
                        echo '</pre>';
                        exit;
                        if(!$response){
                            $error = curl_error($ch);
                            dd($error);
                        }
                    } */

                    curl_close($ch);
                    return $response;
                }
            }
        } catch (\Exception $e) {
        }
    }

    public function sendPush($device_token, $notification = [], $notification_data = [])
    {

        try {
            if ($device_token) {
                $message = CloudMessage::withTarget('token', $device_token)->withDefaultSounds();

                /*  if (!count($notification) && count($notification_data)) {

               $config = [
                    'time_to_live' => 86400,
                    'collapse_key' => 'new_message',
                    'delay_while_idle' => false
                ];

                $message = $message->withAndroidConfig($config);

                $config = ApnsConfig::fromArray([
                    'headers' => [
                        'apns-push-type' => 'background',
                        'apns-priority' => '5',
                        'apns-topic' => 'com.digikoach.application',
                    ],
                    'payload' => [
                        'aps' => [
                            'content-available' => 1,
                            'priority' => 5,
                            'sound' => ""
                        ],
                    ],
                ]);

                $message = $message->withApnsConfig($config);
            } */

                if (count($notification)) {
                    $notification = Notification::fromArray($notification);
                    $message = $message->withNotification($notification);
                }

                if (count($notification_data)) {
                    $message = $message->withData($notification_data);
                }

                $this->messaging->send($message);
            }
        } catch (Exception $e) {
            //dd($e);
            return false;
        }
    }

    public function sendAdminNotification($notification_info, $notification_data)
    {
        // Send Web Push to Admin
        $admin_users = User::where('user_type', '0')->get();
        foreach ($admin_users as $user) {
            if ($user->device_token) {
                try {
                    $this->sendPush($user->device_token, [
                        'title' => $notification_info['title'],
                        'body' => $notification_info['body']
                    ]);

                    $notification = new NotificationModal;
                    $notification->user_id = $user->user_id;
                    $notification->notification_type = $notification_info['type'];
                    $notification->ntoification_type_id = $notification_info['type_id'];
                    $notification->notification_data = json_encode($notification_data);
                    $notification->status = 0;
                    $notification->save();

                    $notification_desc = new NotificationDesc;
                    $notification_desc->notification_id = $notification->notification_id;
                    $notification_desc->lang_code = 'en';
                    $notification_desc->message = $notification['body'];
                    $notification_desc->save();
                } catch (\Exception $e) {
                    //return false;
                }
            }
        }
    }
}
