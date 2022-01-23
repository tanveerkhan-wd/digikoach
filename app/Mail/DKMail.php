<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use DB;
use App\Models\EmailMaster;
use Settings;

class DKMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private $email_body = '';
    private $email_subject = '';
    private $email_cc = '';

    public function __construct($email_template_key = '', $email_values = [], $subject = '', $email_body = '')
    {
        $this->init_config($email_template_key, $email_values, $subject, $email_body);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        if(Settings::get('email_from') && Settings::get('email_from_name')){
            $this->from(Settings::get('email_from'), Settings::get('email_from_name'));
        }else{
            $setting_values = DB::table('settings')->whereIn('text_key', ['email_from', 'email_from_name'])->pluck('text_value', 'text_key')->toArray();
            if(is_array($setting_values) && count($setting_values)){
                $this->from($setting_values['email_from'], $setting_values['email_from_name']);
            }
        }

        if ($this->email_cc != '') {
            $cc = explode(",", $this->email_cc);
            $cc = array_map('trim', $cc);
            if (is_array($cc) && count($cc)) {
                $this->bcc($cc);
            }
        }

        return $this->subject($this->email_subject)
            ->view('email.dkemail')
            ->with(['html' => $this->email_body])
            ->priority(3);
    }

    private function init_config($email_template_key, $values, $subject, $email_body)
    {

        $replace_values = [];
        $find_values = [];

        $defaults = [
            /* 'CONTACT_EMAIL' => Settings::get('CONTACT_EMAIL'),
            'COMPANY_URL' => route('home'),
            'COMPANY_NAME' => Settings::get('SITE_NAME'), */
        ];

        if ($email_template_key != '' && count($values)) {
            $replace_values = array_merge(array_values($values), array_values($defaults));
            foreach (array_keys($values) as $key) {
                $find_values[] = '{{' . $key . '}}';
            }
            $find_values = array_merge($find_values, array_keys($defaults));
        }

        $email_info = EmailMaster::where('email_key', $email_template_key)->first();

        if ($subject != '') {
            $this->email_subject = $subject;
        } elseif ($email_template_key != '' && isset($email_info->subject) && $email_info->subject != '') {
            $this->email_subject = str_replace($find_values, $replace_values, $email_info->subject);
        }

        if ($email_body !== '') {
            $this->email_body = $email_body;
        } elseif ($email_template_key != '' && isset($email_info->content) && $email_info->content != '') {
            $this->email_body = str_replace($find_values, $replace_values, $email_info->content);
        }
    }
}
