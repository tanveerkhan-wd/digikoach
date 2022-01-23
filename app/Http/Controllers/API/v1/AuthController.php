<?php

namespace App\Http\Controllers\API\v1;

use App;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Hash;
use Validator;
use Illuminate\Validation\Rule;
use DB;
use UserNotifications;
use Config;
use URL;

use App\Models\User;
use App\Models\UserVerification;
use Illuminate\Support\Facades\Storage;
use Utilities;

class AuthController extends Controller
{
    private $loginValidationRules = [
        'mobile' => 'required|min:10|max:12',
        'password' => 'required|min:6',
    ];

    private $forgotPassValidationRules = [
        'mobile' => 'required|min:10|max:12|exists:users,mobile_number'
    ];

    private $verifyPassOTPValidationRules = [
        'reset_token' => 'required',
        'otp' => 'required',
        'mobile' => 'required|min:10|max:12|exists:users,mobile_number'
    ];

    protected $loginValidationMessages = [];
    protected $forgotPassValidationMessages = [];
    protected $verifyPassOTPValidationMessages = [];

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['register', 'login', 'logout', 'forgotPasswordProcess', 'verifyPasswordProcess', 'resetPasswordProcess']]);
    }

    /**
     * Register user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $language = App::getLocale();
        $mobile_number = $request->mobile;
        $password = $request->password;

        $registerValidationRules = [
            'mobile' => [
                'required',
                'min:10',
                'max:10',
                Rule::unique('users', 'mobile_number')->where(function ($query) {
                    return $query->where('deleted', '0');
                })
            ],
            'password' => 'required|min:6'
        ];
        //|unique:users,mobile_number

        $registerValidationMessages = [
            'mobile.required' => trans('validation.mobile.required'),
            'mobile.min' => trans('validation.mobile.min_length'),
            'mobile.max' => trans('validation.mobile.max_length'),
            'mobile.unique' => trans('validation.mobile.duplicate'),
            'password.required' => trans('validation.password.required'),
            'password.min' => trans('validation.password.min_length'),
        ];

        $has_error = false;

        $validation = Validator::make($request->all(), $registerValidationRules, $registerValidationMessages);
        if ($validation->fails()) {
            $has_error = true;
            $error_messages = implode("\n", $validation->messages()->all());
        }

        if (!$has_error) {
            $user = User::where('mobile_number', $mobile_number)->where('deleted', '0')->first();
            if ($user) {
                $error_messages = trans('message.error.student_duplicate');
                $has_error = true;
            }

            if (!$has_error) {
                $user = new User;
                $user->user_type = 2;
                $user->mobile_number = $mobile_number;
                $user->password = Hash::make($password);
                $user->user_lang_code = $language;
                $user->user_status = '1';
                $user->is_mobile_verify = '0';
                $user->is_email_verify = '0';
                $user->last_logged_in = DB::raw('NOW()');
                $user->device_token = $request->device_token;
                $user->deactivated = '0';
                $user->deleted = '0';
                $user->save();

                //Create JWT token
                $token = auth('api')->attempt(['mobile_number' => $mobile_number, 'password' => $password, 'user_status' => 1, 'user_type' => 2, 'deleted' => '0']);

                //Send OTP to mobile
                $this->sendVerificationCode($user);

                return response()->json([
                    'access_token' => $token,
                    'message' => trans('message.success.student_create')
                ], 200, [], JSON_UNESCAPED_UNICODE);
            }
        }

        return response()->json(['message' => $error_messages], 400, [], JSON_UNESCAPED_UNICODE);
    }


    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {

        $this->loginValidationMessages = [
            'mobile.required' => trans('validation.mobile.required'),
            'mobile.min' => trans('validation.mobile.min_length'),
            'mobile.max' => trans('validation.mobile.max_length'),
            'password.required' => trans('validation.password.required'),
            'password.min' => trans('validation.password.min_length')
        ];

        $has_error = false;
        $validation = Validator::make($request->all(), $this->loginValidationRules, $this->loginValidationMessages);
        if ($validation->fails()) {
            $has_error = true;
            $error_messages = implode("\n", $validation->messages()->all());
        }

        if (!$has_error) {
            if ($token = auth('api')->attempt(['mobile_number' => $request->mobile, 'password' => $request->password, 'user_status' => 1, 'user_type' => 2, 'deleted' => '0'])) {
                //$user = User::where('mobile_number', $request->mobile)->first();
                //return $this->respondWithToken($token);

                $user = auth('api')->user();

                if ($request->device_token && $request->device_token != $user->device_token) {
                    if ($user->device_token) {
                        UserNotifications::sendPush($user->device_token, [], ['action' => 'LOGOUT']);
                    }

                    $user->device_token = $request->device_token;
                }

                if ($user->deactivated  == '1') {
                    $user->deactivated = '0';
                }

                $user->last_logged_in = date('Y-m-d H:i:s');
                $user->save();

                //Send OTP to mobile
                if ($user->is_mobile_verify != 1) {
                    $this->sendVerificationCode($user);
                }

                return response()->json([
                    'access_token' => $token,
                    'is_mobile_verified' => $user->is_mobile_verify,
                    'is_setup_completed' => $user->is_setup_completed,
                ]);
            } else {
                $has_error = true;
                $error_messages = trans('message.error.student_bad_credentials');
            }
        }

        return response()->json(['message' => $error_messages], 401, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Forgot password process - Sending Forgot Password OTP
     *
     * @param  mixed $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgotPasswordProcess(Request $request)
    {
        $this->forgotPassValidationMessages = [
            'mobile.required' => trans('validation.mobile.required'),
            'mobile.min' => trans('validation.mobile.min_length'),
            'mobile.max' => trans('validation.mobile.max_length'),
            'mobile.exists' => trans('validation.mobile.not_exits'),
        ];

        $has_error = false;
        $validation = Validator::make($request->all(), $this->forgotPassValidationRules, $this->forgotPassValidationMessages);
        if ($validation->fails()) {
            $has_error = true;
            $error_messages = implode("\n", $validation->messages()->all());
        }


        if (!$has_error) {
            $user = User::where('mobile_number', $request->mobile)->where('deleted', '0')->first();
            if ($user) {
                $user_verification = new UserVerification;
                $user_verification->user_id = $user->user_id;
                $user_verification->verification_type = 'FORGOT_PASSWORD';
                $user_verification->verification_value = Str::random(40);
                $user_verification->verification_otp = $this->generateOtp();
                $user_verification->save();

                //$sms_content = trans('content.forgot_pass_otp_sms', ['otp' => $user->mobile_otp]);
                //$notification = UserNotifications::send_sms($user->mobile_number, $sms_content);

                $notification = $this->sendVerificationCode($user);

                if ($notification) {
                    $notification_array = json_decode($notification);
                    if ($notification_array->status == 'failure') {
                        return response()->json(['message' => trans('message.error.sms_otp_failed')], 401, [], JSON_UNESCAPED_UNICODE);
                    }
                }

                return response()->json([
                    'verification_token' => $user_verification->verification_value,
                    'message' => trans('message.success.forgot_pass_otp_sent')
                ]);
            }
        }

        return response()->json(['message' => $error_messages], 400, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Verify forgot password OTP
     *
     * @param  mixed $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyPasswordProcess(Request $request)
    {
        $this->verifyPassOTPValidationRules = [
            'reset_token.required' => trans('validation.reset_token.required'),
            'otp.required' => trans('validation.otp.required'),
            'mobile.required' => trans('validation.mobile.required'),
            'mobile.min' => trans('validation.mobile.min_length'),
            'mobile.max' => trans('validation.mobile.max_length'),
            'mobile.exists' => trans('validation.mobile.not_exits'),
        ];

        $has_error = false;
        $validation = Validator::make($request->all(), $this->verifyPassOTPValidationRules, $this->verifyPassOTPValidationMessages);
        if ($validation->fails()) {
            $has_error = true;
            $error_messages = implode("\n", $validation->messages()->all());
        }

        $user_verification = UserVerification::where('verification_type', 'FORGOT_PASSWORD')->where('verification_value', $request->reset_token)->first();

        if ($user_verification) {

            $user = User::where('user_id', $user_verification->user_id)->first();

            if ($user_verification->verification_otp == $request->otp && !$has_error) {
                $user->user_reset_token = Str::random(40);
                $user->save();

                UserVerification::where('verification_id', $user_verification->verification_id)->delete();

                return response()->json([
                    'reset_token' => $user->user_reset_token
                ]);
            } else {
                $has_error = true;
                $error_messages = trans('message.error.invalid_otp');
            }
        } else {
            $has_error = true;
            $error_messages = trans('message.error.invalid_user');
        }

        return response()->json(['message' => $error_messages], 400, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Reset Password
     *
     * @param  mixed $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPasswordProcess(Request $request)
    {
        $resetPassValidationRules = [
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|min:6|same:new_password',
        ];

        $resetPassValidationMessages = [
            'new_password.required' => trans('validation.new_password.required'),
            'new_password.min' => trans('validation.new_password.min_length'),
            'confirm_password.required' => trans('validation.confirm_password.required'),
            'confirm_password.min' => trans('validation.confirm_password.min_length'),
            'confirm_password.same' => trans('validation.confirm_password.same'),
        ];

        $has_error = false;
        $error_messages = "";
        $validation = Validator::make($request->all(), $resetPassValidationRules, $resetPassValidationMessages);
        if ($validation->fails()) {
            $has_error = true;
            $error_messages = implode("\n", $validation->messages()->all());
        }

        $user = User::where('user_reset_token', $request->reset_token)->first();
        if ($user && !$has_error) {
            $user->user_reset_token = Str::random(40);
            $user->password = Hash::make($request->confirm_password);
            $user->save();

            return response()->json([
                'message' => trans('message.success.password_reset')
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } else {
            $has_error = true;
            $error_messages = trans('message.error.invalid_user');
        }

        return response()->json(['message' => $error_messages], 400, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = auth()->user();

        $user->user_photo_thumb = '';
        if (!empty($user->user_photo)) {
            $images_dirs = Config::get('siteglobal.images_dirs');
            $user_dir = $images_dirs['USERS'] . '/';

            //$user->user_photo_thumb = Utilities::getThumbImage($user_dir . $user->user_photo, 150, 150);
            $user->user_photo_thumb = url('public' . Storage::url($user_dir . $user->user_photo));
        }

        $user->user_level;
        //$user->gk_ca = ($user->user_level ? $user->user_level->gk_ca : 0);


        return response()->json($user, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    /**
     * Generate 4 digit OTP String
     *
     * @return number
     */
    private function generateOtp()
    {
        $digits = 4;
        return  str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
    }

    /**
     * guard
     *
     * @return API
     */
    public function guard()
    {
        return Auth::guard('api');
    }

    /**
     * Send mobile verification code to user
     *
     * @param  mixed $user
     * @return void
     */
    private function sendVerificationCode($user)
    {
        $user_verification = new UserVerification;
        $user_verification->user_id = $user->user_id;
        $user_verification->verification_type = 'NEW_MOBILE';
        $user_verification->verification_value = $user->mobile_number;
        $user_verification->verification_otp = $this->generateOtp();
        $user_verification->save();

        $sms_content = "Your one-time password is " . $user_verification->verification_otp . ". Please use this One Time Password (OTP) within the next 10 minutes. 
Thank You, 
Team Digikoach.";
        return UserNotifications::send_sms($user->mobile_number, $sms_content);
    }
}
