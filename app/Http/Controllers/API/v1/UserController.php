<?php

namespace App\Http\Controllers\API\v1;

use App;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

use App\Mail\DKMail;

use Hash;
use Validator;
use Config;
use Storage;
use UserNotifications;

use App\Http\Resources\Category\Category as CategoryResource;
use App\Http\Resources\Category\CategoryCollection;
use App\Http\Resources\UserSavedItems\UserSavedItemPageCollection;
use App\Http\Resources\Users\UserPageCollection;

use App\Models\User;
use App\Models\UserVerification;
use App\Models\UserCategory;
use App\Models\Category;
use App\Models\UserSavedItem;
use App\Models\ArticlesNews;
use App\Models\BlogPost;
use App\Models\Doubt;
use App\Models\ExamChallenge;
use App\Models\ExamChallengeUser;
use App\Models\Notification;
use App\Models\NotificationDesc;
use App\Models\Question;

class UserController extends Controller
{
    /**
     * Deactivate User Account
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deActivateAccount()
    {
        $user = Auth::user();
        if ($user) {
            if ($user->device_token) {
                UserNotifications::sendPush($user->device_token, [], ['action' => 'LOGOUT']);
            }

            $user->deactivated = 1;
            $user->save();

            //Sending email to student
            $email_replacements = [
                'STUDENT_NAME' => $user->name
            ];

            try {
                Mail::to($request->verification_value)->send(new DKMail('student_deactivated_account', $email_replacements));
            } catch (\Exception $e) {
                //dd(Mail::failure());
            }

            // Send Web Push to Admin
            UserNotifications::sendAdminNotification([
                'title' => 'Profile deactivated.',
                'body' => 'Account has been deactivated by ' . $user->name . '.',
                'type' => 'PROF_DEACT',
                'type_id' => $user->user_id
            ], [
                'action' => 'PROF_DEACT',
                'user_id' => $user->user_id
            ]);

            return response()->json(['message' => trans('message.success.student_deactivate')], 200, [], JSON_INVALID_UTF8_IGNORE);
        } else {
            $error_messages = trans('message.error.invalid_user');
        }

        return response()->json(['message' => $error_messages], 401, [], JSON_INVALID_UTF8_IGNORE);
    }

    public function changeLanguage($lang_code)
    {
        $error_messages = trans('message.error.invalid_user');
        $user = Auth::user();
        if ($user) {
            $user->user_lang_code = $lang_code;
            $user->save();

            return response()->json(['message' => ''], 200, [], JSON_INVALID_UTF8_IGNORE);
        }

        return response()->json(['message' => $error_messages], 401, [], JSON_INVALID_UTF8_IGNORE);
    }

    /**
     * Change User Password
     *
     * @param  mixed $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function changePassword(Request $request)
    {
        $changePassValidationRules = [
            'old_password' => 'required|min:6',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|min:6|same:new_password',
        ];

        $changePassValidationMessages = [
            'old_password.required' => trans('validation.old_password.required'),
            'old_password.min' => trans('validation.old_password.min_length'),
            'new_password.required' => trans('validation.new_password.required'),
            'new_password.min' => trans('validation.new_password.min_length'),
            'confirm_password.required' => trans('validation.confirm_password.required'),
            'confirm_password.min' => trans('validation.confirm_password.min_length'),
            'confirm_password.same' => trans('validation.confirm_password.same'),
        ];

        $validation = Validator::make($request->all(), $changePassValidationRules, $changePassValidationMessages);

        $has_error = false;
        if ($validation->fails()) {
            $has_error = true;
            $error_messages = implode("\n", $validation->messages()->all());
        }

        if (!$has_error) {
            $user = Auth::user();
            if ($user) {
                if (Hash::check($request->old_password, $user->password)) {
                    $user->password = Hash::make($request->confirm_password);
                    $user->save();
                    return response()->json(['message' => trans('message.success.password_reset')], 200, [], JSON_INVALID_UTF8_IGNORE);
                }

                $error_messages = trans('message.error.invalid_old_password');
            } else {
                $error_messages = trans('message.error.invalid_user');
            }
        }

        return response()->json(['message' => $error_messages], 401, [], JSON_INVALID_UTF8_IGNORE);
    }

    /**
     * Update user mobile number
     *
     * @param  mixed $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateMobile(Request $request)
    {
        $updateMobileValidationRules = [
            'mobile' => [
                'required',
                'min:6',
                'max:10',
                Rule::unique('users', 'mobile_number')->where(function ($query) use ($request) {
                    return $query->where('deleted', '0');
                })
            ],
            'verification_code' => 'required'
        ];

        $updateMobileValidationMessages = [
            'mobile.required' => trans('validation.mobile.required'),
            'mobile.min' => trans('validation.mobile.min_length'),
            'mobile.max' => trans('validation.mobile.max_length'),
            'mobile.unique' => trans('validation.mobile.duplicate'),
            'verification_code.required' => trans('validation.verification_code.required'),
        ];

        $validation = Validator::make($request->all(), $updateMobileValidationRules, $updateMobileValidationMessages);

        $has_error = false;
        if ($validation->fails()) {
            $has_error = true;
            $error_messages = implode("\n", $validation->messages()->all());
        }

        if (!$has_error) {
            $user = Auth::user();

            if ($user) {
                $verification = UserVerification::whereIn('verification_type', ['UPDATE_MOBILE', 'NEW_MOBILE'])->where('user_id', $user->user_id)->where('verification_value', $request->mobile)->orderBy('verification_id', 'DESC')->first();

                if ($verification && $verification->verification_otp == $request->verification_code) {
                    $user->mobile_number = $request->mobile;
                    $user->save();

                    UserVerification::where('verification_id', $verification->verification_id)->delete();

                    return response()->json(['message' => trans('message.success.mobile_update')], 200, [], JSON_INVALID_UTF8_IGNORE);
                } else {
                    $error_messages = trans('message.error.invalid_otp');
                }
            } else {
                $error_messages = trans('message.error.invalid_user');
            }
        }

        return response()->json(['message' => $error_messages], 401, [], JSON_INVALID_UTF8_IGNORE);
    }

    /**
     * Update user email address
     *
     * @param  mixed $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateEmail(Request $request)
    {
        $updateEmailValidationRules = [
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->where(function ($query) use ($request) {
                    return $query->where('deleted', '0');
                })
            ],
            'verification_code' => 'required'
        ];

        $updateEmailValidationMessages = [
            'email.required' => trans('validation.email.required'),
            'email.unique' => trans('validation.email.duplicate'),
            'verification_code.required' => trans('validation.verification_code.required'),

        ];

        $validation = Validator::make($request->all(), $updateEmailValidationRules, $updateEmailValidationMessages);

        $has_error = false;
        if ($validation->fails()) {
            $has_error = true;
            $error_messages = implode("\n", $validation->messages()->all());
        }

        if (!$has_error) {
            $user = Auth::user();

            if ($user) {
                $verification = UserVerification::whereIn('verification_type', ['UPDATE_EMAIL', 'NEW_EMAIL'])->where('user_id', $user->user_id)->where('verification_value', $request->email)->orderBy('verification_id', 'DESC')->first();

                if ($verification && $verification->verification_otp == $request->verification_code) {

                    $user->email = $request->email;
                    $user->save();

                    UserVerification::where('verification_id', $verification->verification_id)->delete();

                    return response()->json(['message' => trans('message.success.email_update')], 200, [], JSON_INVALID_UTF8_IGNORE);
                } else {
                    $has_error = true;
                    $error_messages = trans('message.error.invalid_otp');
                }
            } else {
                $has_error = true;
                $error_messages = trans('message.error.invalid_user');
            }
        }

        return response()->json(['message' => $error_messages], 401, [], JSON_INVALID_UTF8_IGNORE);
    }

    /**
     * Update User Photo
     *
     * @param  mixed $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePhoto(Request $request)
    {
        //$user_photo = $request->user_photo;
        $user_photo = $request->file('user_photo');

        /* if (!$user_photo) {
            return response()->json(['message' => 'Test'], 200, [], JSON_INVALID_UTF8_IGNORE);
        } */

        $has_error = false;
        $error_messages = '';
        if ($user_photo && !$has_error) {
            $user = Auth::user();
            if ($user) {
                /* $user_photo = str_replace('data:image/png;base64,', '', $user_photo);
                $user_photo = str_replace(' ', '+', $user_photo); */
                $new_user_file_name = 'user_' . md5($user->user_id) . time() . '.' . $user_photo->getClientOriginalExtension();

                $images_dirs = Config::get('siteglobal.images_dirs');
                $user_dir = $images_dirs['USERS'] . '/';

                //return response()->json(['message' => $user_dir . $new_user_file_name], 200, [], JSON_INVALID_UTF8_IGNORE);
                //Storage::disk('public')->put($user_dir . $new_user_file_name, base64_decode($user_photo));
                //Storage::disk('public')->put($user_dir . $new_user_file_name, File::get($user_photo));

                $user_old_photo = $user->user_photo;

                $destinationPath = Storage::disk('public')->path($user_dir);
                if ($user_photo->move($destinationPath, $new_user_file_name)) {

                    if (file_exists($destinationPath . $user_old_photo)) {
                        @unlink($destinationPath . $user_old_photo);
                    }

                    $user->user_photo = $new_user_file_name;
                    $user->save();
                }

                return response()->json(['message' => trans('message.success.photo_update')], 200, [], JSON_INVALID_UTF8_IGNORE);
            } else {
                $has_error = true;
                $error_messages = trans('message.error.invalid_user');
            }
        }

        return response()->json(['message' => $error_messages], 401, [], JSON_INVALID_UTF8_IGNORE);
    }

    /**
     * Update user profile data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(Request $request)
    {
        $updateProfileValidationRules = [
            'name' => 'required'
        ];

        $updateProfileValidationMessages = [
            'name.required' => trans('validation.name.required'),
        ];

        $validation = Validator::make($request->all(), $updateProfileValidationRules, $updateProfileValidationMessages);

        $has_error = false;
        if ($validation->fails()) {
            $has_error = true;
            $error_messages = implode("\n", $validation->messages()->all());
        }

        if (!$has_error) {
            $user = Auth::user();

            if ($user) {
                $user->name = $request->name;
                $user->save();

                return response()->json(['message' => trans('message.success.profile_update')], 200, [], JSON_INVALID_UTF8_IGNORE);
            } else {
                $has_error = true;
                $error_messages = trans('message.error.invalid_user');
            }
        }

        return response()->json(['message' => $error_messages], 401, [], JSON_INVALID_UTF8_IGNORE);
    }

    public function userVerification(Request $request)
    {
        $userVerificationValidationRules = [
            'verification_otp' => 'required'
        ];

        $userVerificationValidationMessages = [
            'verification_otp.required' => trans('validation.verification_otp.required')
        ];

        $validation = Validator::make($request->all(), $userVerificationValidationRules, $userVerificationValidationMessages);

        $has_error = false;
        if ($validation->fails()) {
            $has_error = true;
            $error_messages = implode("\n", $validation->messages()->all());
        }

        if (!$has_error) {
            $user = Auth::user();

            if ($user) {
                $user_verification = UserVerification::where('verification_type', $request->verification_type)->where('verification_otp', $request->verification_otp)->first();
                if ($user_verification) {
                    UserVerification::where('verification_id', $user_verification->verification_id)->delete();

                    $user->is_mobile_verify = '1';
                    $user->save();

                    return response()->json([
                        'is_setup_completed' => $user->is_setup_completed,
                        'message' => trans('message.success.user_verification')
                    ]);
                } else {
                    $has_error = true;
                    $error_messages = trans('message.error.invalid_otp');
                }
            } else {
                $has_error = true;
                $error_messages = trans('message.error.invalid_user');
            }
        }

        return response()->json(['message' => $error_messages], 401, [], JSON_INVALID_UTF8_IGNORE);
    }

    /**
     * Send verification code to user
     *
     * @param  mixed $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendUserVerification(Request $request)
    {
        $sendVeriValidationRules = [
            'verification_value' => 'required'
        ];

        $sendVeriValidationMessages = [
            'verification_value.required' => trans('validation.verification_value.required')
        ];

        if (in_array($request->verification_type, ['NEW_MOBILE', 'UPDATE_MOBILE'])) {
            $sendVeriValidationRules['verification_value'] = [
                'required',
                'min:6',
                'max:10',
                Rule::unique('users', 'mobile_number')->where(function ($query) use ($request) {
                    return $query->where('deleted', '0');
                })
            ];

            $sendVeriValidationMessages = array_merge(
                $sendVeriValidationMessages,
                [
                    'verification_value.required' => trans('validation.mobile.required'),
                    'verification_value.min' => trans('validation.mobile.min_length'),
                    'verification_value.max' => trans('validation.mobile.max_length'),
                    'verification_value.unique' => trans('validation.mobile.duplicate'),
                ]
            );
        } else if (in_array($request->verification_type, ['NEW_EMAIL', 'UPDATE_EMAIL'])) {
            $sendVeriValidationRules['verification_value'] = [
                'required',
                'email',
                Rule::unique('users', 'email')->where(function ($query) use ($request) {
                    return $query->where('deleted', '0');
                })
            ];

            $sendVeriValidationMessages = array_merge(
                $sendVeriValidationMessages,
                [
                    'verification_value.required' => trans('validation.email.required'),
                    'verification_value.email' => trans('validation.email.required'),
                    'verification_value.unique' => trans('validation.email.duplicate'),
                ]
            );
        }

        $validation = Validator::make($request->all(), $sendVeriValidationRules, $sendVeriValidationMessages);

        $error_messages = '';
        $has_error = false;
        if ($validation->fails()) {
            $has_error = true;
            $error_messages = implode("\n", $validation->messages()->all());
        }

        if (!$has_error) {
            $user = Auth::user();

            if ($user) {
                $user_verification = new UserVerification;
                $user_verification->user_id = $user->user_id;
                $user_verification->verification_type = $request->verification_type;
                $user_verification->verification_value = $request->verification_value;
                $user_verification->verification_otp = $this->generateOtp();
                $user_verification->save();

                //Sending Notifications
                if (in_array($request->verification_type, ['NEW_MOBILE', 'UPDATE_MOBILE'])) {
                    //$sms_content = trans('content.forgot_pass_otp_sms', ['otp' => $user_verification->verification_otp]);

                    $sms_content = 'Your one time password is ' . $user_verification->verification_otp . ' .
                    Please use this One Time Password (OTP) within the next ten minutes to proceed.
                    Thank You,
                    Team DigiKoach';

                    UserNotifications::send_sms($user->mobile_number, $sms_content);
                    $success_message = trans('message.success.phone_verification_sent');
                } else if (in_array($request->verification_type, ['NEW_EMAIL', 'UPDATE_EMAIL'])) {
                    $email_replacements = [
                        'STUDENT_NAME' => $user->name,
                        'VERIFICATION_CODE' => $user_verification->verification_otp
                    ];

                    try {
                        Mail::to($request->verification_value)->send(new DKMail('student_change_email_otp', $email_replacements));
                    } catch (\Exception $e) {
                        //dd(Mail::failure());
                    }

                    $success_message = trans('message.success.email_verification_sent');
                }

                return response()->json([
                    'message' => $success_message
                ], 200, [], JSON_INVALID_UTF8_IGNORE);
            } else {
                $has_error = true;
                $error_messages = trans('message.error.invalid_user');
            }
        }

        return response()->json(['message' => $error_messages], 401, [], JSON_INVALID_UTF8_IGNORE);
    }

    /**
     * Update user level
     *
     * @return void
     */
    public function updateLevel(Request $request)
    {
        $levelValidationRules = [
            'category_id' => 'required'
        ];

        $levelValidationMessages = [
            'category_id.required' => trans('validation.category.required')
        ];

        $validation = Validator::make($request->all(), $levelValidationRules, $levelValidationMessages);

        $error_messages = '';
        $has_error = false;
        if ($validation->fails()) {
            $has_error = true;
            $error_messages = implode("\n", $validation->messages()->all());
        }

        if (!$has_error) {
            $user = Auth::user();

            if ($user) {
                $user->user_fav_category = $request->category_id;
                $user->save();

                UserCategory::where('user_id', $user->user_id)->delete();

                return response()->json([
                    'message' => trans('message.success.level_updated')
                ]);
            } else {
                $has_error = true;
                $error_messages = trans('message.error.invalid_user');
            }
        }

        return response()->json(['message' => $error_messages], 401, [], JSON_INVALID_UTF8_IGNORE);
    }

    /**
     * Update user sub levels
     *
     * @return void
     */
    public function updateSubLevel(Request $request)
    {
        $error_messages = '';
        $has_error = false;

        if (!$has_error) {
            $user = Auth::user();

            if ($user) {
                $sub_categories = $request->sub_categories;

                UserCategory::where('user_id', $user->user_id)->delete();
                if (is_array($sub_categories) && count($sub_categories)) {
                    foreach ($sub_categories as $category_id) {
                        $user_category = new UserCategory;
                        $user_category->category_id = $category_id;
                        $user_category->user_id = $user->user_id;
                        $user_category->save();
                    }
                }

                if ($request->is_profile_setup) {
                    $user->is_setup_completed = '1';
                    $user->save();

                    // Send Web Push to Admin
                    UserNotifications::sendAdminNotification([
                        'title' => 'Profile completed.',
                        'body' => 'Profile has been completed by ' . $user->name . '.',
                        'type' => 'PROF_COMP',
                        'type_id' => $user->user_id
                    ], [
                        'action' => 'PROF_COMP',
                        'user_id' => $user->user_id
                    ]);
                }
                //UserCategory::where('user_id', $user->user_id)->where('user_id', $user->user_id)->delete();

                return response()->json([
                    'message' => trans('message.success.sub_level_updated')
                ]);
            } else {
                $has_error = true;
                $error_messages = trans('message.error.invalid_user');
            }
        }

        return response()->json(['message' => $error_messages], 401, [], JSON_INVALID_UTF8_IGNORE);
    }

    public function getUserLevel()
    {
        $error_messages = '';
        $has_error = false;

        if (!$has_error) {
            $user = Auth::user();

            if ($user) {
                $user_level = [];
                if ((int)$user->user_fav_category > 0) {
                    $language = App::getLocale();

                    $objCategory = Category::query()->join('categories_descs', 'categories_descs.category_id', '=', 'categories.category_id');
                    $objCategory->where('categories.category_id', $user->user_fav_category)->where('categories.status', 1)->where('categories_descs.lang_code', $language);
                    $user_level = $objCategory->first();

                    $user_level = new CategoryResource($user_level);
                }

                return response()->json($user_level, 200);
            }
        }

        return response()->json(['message' => $error_messages], 401, [], JSON_INVALID_UTF8_IGNORE);
    }

    public function getUserLevels()
    {
        $error_messages = '';
        $has_error = false;

        if (!$has_error) {
            $user = Auth::user();

            if ($user) {
                $user_sub_levels = [];
                if ((int)$user->user_fav_category > 0) {
                    $language = App::getLocale();

                    //UserCategory::where('user_id', $user->user_id)
                    $objCategory = Category::query()->join('categories_descs', 'categories_descs.category_id', '=', 'categories.category_id')->join('user_categories', 'user_categories.category_id', '=', 'categories.category_id');
                    $objCategory->where('user_id', $user->user_id);
                    $objCategory->where('categories.status', 1)->where('categories_descs.lang_code', $language);
                    $user_sub_levels = $objCategory->get();
                    $user_sub_levels = new CategoryCollection($user_sub_levels);
                }

                return response()->json($user_sub_levels, 200);
            }
        }

        return response()->json(['message' => $error_messages], 401, [], JSON_INVALID_UTF8_IGNORE);
    }

    public function createSavedItem($item_type, Request $request)
    {
        $error_messages = trans('message.error.invalid_request');

        $item_type = strtoupper($item_type);

        $user = Auth::user();
        if ($user) {
            $dup_item_info = UserSavedItem::where('user_id', $user->user_id)->where('item_type', $item_type)->where('item_type_id', $request->item_type_id)->first();

            if (!$dup_item_info) {
                if ($item_type == 'ARTICLE') {
                    $success_message = trans('message.success.article_saved');
                    $item_info = ArticlesNews::find($request->item_type_id);
                } else if ($item_type == 'DOUBT') {
                    $success_message = trans('message.success.my_doubt_saved');
                    $item_info = Doubt::find($request->item_type_id);
                } else if ($item_type == 'BLOG') {
                    $success_message = trans('message.success.user_blog_saved');
                    $item_info = BlogPost::find($request->item_type_id);
                } else if ($item_type == 'QUESTION') {
                    $success_message = trans('message.success.user_question_saved');
                    $item_info = Question::find($request->item_type_id);
                }

                if ($item_info) {
                    $user_saved_item = new UserSavedItem;
                    $user_saved_item->user_id = $user->user_id;
                    $user_saved_item->item_type = $item_type;
                    $user_saved_item->item_type_id = $request->item_type_id;

                    if ($user_saved_item->save()) {
                        return response()->json(['message' => $success_message], 200, [], JSON_INVALID_UTF8_IGNORE);
                    }
                }
            } else {
                if ($item_type == 'ARTICLE') {
                    $success_message = trans('message.success.article_unsaved');
                } else if ($item_type == 'BLOG') {
                    $success_message = trans('message.success.user_blog_deleted');
                } else if ($item_type == 'QUESTION') {
                    $success_message = trans('message.success.user_question_deleted');
                }

                $is_deleted = UserSavedItem::where('user_id', $user->user_id)->where('item_id', $dup_item_info->item_id)->delete();

                if ($is_deleted) {
                    return response()->json(['message' => $success_message], 200, [], JSON_INVALID_UTF8_IGNORE);
                }
            }
        } else {
            $error_messages = trans('message.error.invalid_user');
        }

        return response()->json(['message' => $error_messages], 400, [], JSON_INVALID_UTF8_IGNORE);
    }

    public function removeSavedItem($item_id)
    {
        $error_messages = trans('message.error.invalid_request');

        $user = Auth::user();
        if ($user) {
            $item_info = UserSavedItem::find($item_id);

            if ($item_info) {
                if ($item_info->item_type == 'ARTICLE') {
                    $success_message = trans('message.success.article_unsaved');
                }

                UserSavedItem::where('user_id', $user->user_id)->where('item_id', $item_id)->delete();
                return response()->json(['message' => $success_message], 200, [], JSON_INVALID_UTF8_IGNORE);
            }
        } else {
            $error_messages = trans('message.error.invalid_user');
        }

        return response()->json(['message' => $error_messages], 401, [], JSON_INVALID_UTF8_IGNORE);
    }

    public function getSavedItems($item_type)
    {
        $error_messages = trans('message.error.invalid_request');

        $item_type = strtoupper($item_type);

        $user = Auth::user();
        if ($user) {
            $user_items = UserSavedItem::where('user_id', $user->user_id)->where('item_type', $item_type)->orderBy('item_id', 'DESC')->paginate();
            return response()->json(new UserSavedItemPageCollection($user_items), 200, [], JSON_INVALID_UTF8_IGNORE);
        } else {
            $error_messages = trans('message.error.invalid_user');
        }

        return response()->json(['message' => $error_messages], 401, [], JSON_INVALID_UTF8_IGNORE);
    }

    public function sendChallange($exam_id, Request $request)
    {
        $languages = Config::get('siteglobal.languages');
        $error_messages = trans('message.error.invalid_request');

        $user = Auth::user();
        if ($user) {
            $challenged_users = $request->users;
            if (is_array($challenged_users) && count($challenged_users)) {
                $exam_challenge = new ExamChallenge;
                $exam_challenge->exam_id = $exam_id;
                $exam_challenge->save();

                foreach ($challenged_users as $challenged_user_id) {
                    $exam_challenge_user = new ExamChallengeUser;
                    $exam_challenge_user->exam_challenge_id = $exam_challenge->exam_challenge_id;
                    $exam_challenge_user->user_id = $challenged_user_id;
                    $exam_challenge_user->challenge_status = 'P';
                    $exam_challenge_user->is_organiser = '0';
                    $exam_challenge_user->save();

                    $recepient = User::find($challenged_user_id);

                    $notification_data = [
                        'action' => 'CHALLENGE',
                        'exam_challenge_id' => $exam_challenge->exam_challenge_id
                    ];

                    UserNotifications::sendPush($recepient->device_token, [
                        'title' => trans('notification_title.exam_challenge', [], $recepient->user_lang_code),
                        'body' => trans('notification_message.exam_challenge', ['name' => $user->name], $recepient->user_lang_code),
                    ], $notification_data);

                    $notification = new Notification;
                    $notification->user_id = $challenged_user_id;
                    $notification->notification_type = 'CHALLENGE';
                    $notification->ntoification_type_id = $exam_challenge->exam_challenge_id;
                    $notification->notification_data = json_encode($notification_data);
                    $notification->status = 0;
                    $notification->save();

                    foreach ($languages as $lang_code => $lang) {
                        $message = trans('notification_message.exam_challenge', ['name' => $user->name], $lang_code);

                        $notification_desc = new NotificationDesc;
                        $notification_desc->notification_id = $notification->notification_id;
                        $notification_desc->lang_code = $lang_code;
                        $notification_desc->message = $message;
                        $notification_desc->save();
                    }
                }

                //Self Entry
                $exam_challenge_user = new ExamChallengeUser;
                $exam_challenge_user->exam_challenge_id = $exam_challenge->exam_challenge_id;
                $exam_challenge_user->user_id = $user->user_id;
                $exam_challenge_user->challenge_status = 'A';
                $exam_challenge_user->is_organiser = '1';
                $exam_challenge_user->save();

                $success_message = trans('message.success.challenge_sent');
                return response()->json(['exam_challenge_id' => $exam_challenge->exam_challenge_id, 'message' => $success_message], 200, [], JSON_INVALID_UTF8_IGNORE);
            }
        } else {
            $error_messages = trans('message.error.invalid_user');
        }

        return response()->json(['message' => $error_messages], 401, [], JSON_INVALID_UTF8_IGNORE);
    }

    public function searchChallangeUsers(Request $request)
    {
        $error_messages = trans('message.error.invalid_request');

        $user = Auth::user();
        if ($user) {
            $exam_type = $request->exam_type;

            if ($exam_type == 'GKCA') {
                $category_id = $user->user_fav_category;
            } else {
                $category_id = $request->category_id;
            }

            if ((int)$category_id <= 0) return false;

            $parent_categories = $this->getParentCategories($category_id);

            if (is_array($parent_categories) && count($parent_categories)) {
                $valid_challange_users_query = User::query();

                if ($exam_type == 'GKCA') {
                    $valid_challange_users_query->where('user_fav_category', $category_id);
                } else {
                    $valid_challange_users_query->join('user_categories', 'user_categories.user_id', 'users.user_id')->whereIn('user_categories.category_id', $parent_categories);
                }

                $valid_challange_users_query->where('users.user_id', '!=', $user->user_id)->where('user_type', 2)->where('users.deleted', 0)->where('users.deactivated', 0)->whereNotNull('users.device_token')->where('users.user_status', 1)->where('users.is_setup_completed', '1')->where('users.is_mobile_verify', 1);

                if ($request->keywords) {
                    $search_keywords = $request->keywords;
                    $valid_challange_users_query->where(function ($query) use ($search_keywords) {
                        $query->orWhere('users.name', 'like', '%' . $search_keywords . '%')
                            ->orWhere('users.email', 'like', '%' . $search_keywords . '%')
                            ->orWhere('users.mobile_number', 'like', '%' . $search_keywords . '%');
                    });
                }

                $valid_challange_users = $valid_challange_users_query->paginate();

                //->whereIn('users.user_fav_category', $parent_categories)

                return response()->json(new UserPageCollection($valid_challange_users), 200, [], JSON_INVALID_UTF8_IGNORE);
            }

            return $parent_categories;
        } else {
            $error_messages = trans('message.error.invalid_user');
        }

        return response()->json(['message' => $error_messages], 401, [], JSON_INVALID_UTF8_IGNORE);
    }

    public function getChallangeInfo($challenge_id)
    {
        $error_messages = trans('message.error.invalid_request');

        if ((int)$challenge_id) {
            $user = Auth::user();
            if ($user) {
                $challange = ExamChallenge::with('users')->where('exam_challenge_id', $challenge_id)->first();
                return response()->json($challange, 200, [], JSON_INVALID_UTF8_IGNORE);
            }
        }

        return response()->json(['message' => $error_messages], 401, [], JSON_INVALID_UTF8_IGNORE);
    }

    public function updateChallangeRequest($challenge_id, Request $request)
    {
        $error_messages = trans('message.error.invalid_request');

        if ((int)$challenge_id) {
            $user = Auth::user();
            if ($user) {
                $challanged_user = ExamChallengeUser::where('exam_challenge_id', $challenge_id)->where('user_id', $user->user_id)->first();
                if ($challanged_user) {
                    $challanged_user->challenge_status = $request->challenge_status;
                    $challanged_user->save();
                    return response()->json(['message' => ''], 200, [], JSON_INVALID_UTF8_IGNORE);
                }
            }
        }

        return response()->json(['message' => $error_messages], 401, [], JSON_INVALID_UTF8_IGNORE);
    }

    private function getParentCategories($category_id)
    {
        $categories[] = (int)$category_id;
        $category = Category::where('category_id', $category_id)->first();
        if ($category->parent_category != 0) {
            $parent_cat_ids = $this->getParentCategories($category->parent_category);
            $categories = array_merge($categories, $parent_cat_ids);
        }

        return $categories;
    }

    private function generateOtp()
    {
        $digits = 4;
        return  str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
    }
}
