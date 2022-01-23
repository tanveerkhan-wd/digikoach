<?php

namespace App\Http\Controllers\API\v1;

use App;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\Doubts\Doubt\DoubtResource;
use App\Http\Resources\Doubts\Doubt\DoubtPageCollectionResouce;
use App\Http\Resources\Doubts\Doubt\DoubtWithAnswerPageCollectionResource;
use App\Http\Resources\Doubts\Answer\DoubtAnsPageCollectionResouce;
use App\Http\Resources\Doubts\Reply\DoubtReplyPageCollectionResource;

use App\Models\Doubt;
use App\Models\DoubtAnswer;
use App\Models\DoubtReply;
use App\Models\UserSavedItem;
use App\Models\Notification;
use App\Models\NotificationDesc;

use Auth;
use Storage;
use Config;
use DB;
use UserNotifications;
use Utilities;

class DoubtController extends Controller
{
    /**
     * Get Doubts
     *
     * @param  mixed $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDoubts(Request $request)
    {
        $doubts_query = Doubt::with(['category', 'user'])->where('status', 1);

        if ($request->parent_id) {
            $doubts_query->where('category_id', $request->category_id);
        }

        $doubts = $doubts_query->orderBy('created_at', 'DESC')->paginate(10);

        return response()->json(new DoubtPageCollectionResouce($doubts), 200, [], JSON_INVALID_UTF8_IGNORE);
    }

    /**
     * Get Doubt Detail
     *
     * @param  number $doubt_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDoubtDetail($doubt_id)
    {
        $error_messages = trans('message.error.invalid_request');

        //$user = Auth::user();
        //where('user_id', $user->user_id)->

        $doubt = Doubt::where('doubt_id', $doubt_id)->first();
        if ($doubt) {
            return response()->json(new DoubtResource($doubt), 200, [], JSON_INVALID_UTF8_IGNORE);
        }

        return response()->json(['message' => $error_messages], 400, [], JSON_INVALID_UTF8_IGNORE);
    }

    /**
     * Get Doubt Answers
     *
     * @param  number $doubt_id
     * @param  mixed $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDoubtAnswers($doubt_id, Request $request)
    {
        $doubt_answers_query = DoubtAnswer::where('doubt_id', $doubt_id);

        if ($request->parent_id) {
            $doubt_answers_query->where('parent_id', $request->parent_id);
        }

        $doubt_answers = $doubt_answers_query->orderBy('created_at', 'DESC')->paginate(10);

        return response()->json(new DoubtAnsPageCollectionResouce($doubt_answers), 200, [], JSON_INVALID_UTF8_IGNORE);
    }

    /**
     * Get Doubt Replies
     *
     * @param  number $doubt_id
     * @param  number $ans_id
     * @param  mixed $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDoubtReplies($doubt_id, $ans_id, Request $request)
    {
        $doubt_replies_query = DoubtReply::where('doubt_id', $doubt_id)->where('answer_id', $ans_id);

        if ($request->parent_id) {
            $doubt_replies_query->where('parent_id', $request->parent_id);
        }

        $doubt_replies = $doubt_replies_query->orderBy('created_at', 'DESC')->paginate(10);

        return response()->json(new DoubtReplyPageCollectionResource($doubt_replies), 200, [], JSON_INVALID_UTF8_IGNORE);
    }

    /**
     * Delete Doubt / My Doubt
     *
     * @param  mixed $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteDoubt($doubt_id)
    {
        $error_messages = trans('message.error.invalid_request');

        $user = Auth::user();
        $doubt = Doubt::where('doubt_id', $doubt_id)->first();
        if ($doubt) {
            //Delete from saved item
            UserSavedItem::where('user_id', $user->user_id)->where('item_type', 'DOUBT')->where('item_type_id', $doubt->doubt_id)->delete();

            //Delete from doubt (if user created)
            if ($doubt->user_id == $user->user_id) {
                Doubt::where('user_id', $user->user_id)->where('doubt_id', $doubt->doubt_id)->delete();
            }

            $success_message = trans('message.success.doubt_deleted');
            return response()->json(['message' => $success_message], 200, [], JSON_INVALID_UTF8_IGNORE);
        }

        return response()->json(['message' => $error_messages], 400, [], JSON_INVALID_UTF8_IGNORE);
    }

    /**
     * Delete Doubt / My Doubt
     *
     * @param  mixed $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteDoubtAnswer($answer_id)
    {
        $error_messages = trans('message.error.invalid_request');

        $user = Auth::user();
        $doubt_answer = DoubtAnswer::where('answer_id', $answer_id)->where('user_id', $user->user_id)->first();
        if ($doubt_answer) {
            DoubtAnswer::where('answer_id', $doubt_answer->answer_id)->delete();

            $this->update_doubt_total_answers($doubt_answer->doubt_id);

            $success_message = trans('message.success.doubt_ans_deleted');
            return response()->json(['message' => $success_message], 200, [], JSON_INVALID_UTF8_IGNORE);
        }

        return response()->json(['message' => $error_messages], 400, [], JSON_INVALID_UTF8_IGNORE);
    }

    /**
     * Get My Doubt Answers - Logged in user answers
     *
     * @param  number $doubt_id
     * @param  number $ans_id
     * @param  mixed $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMyDoubtAnswers(Request $request)
    {
        $user = Auth::user();
        /* $doubt_answers_query = DoubtAnswer::with(['doubt'])->where('user_id', $user->user_id);
        $doubt_answers = $doubt_answers_query->orderBy('created_at', 'DESC')->paginate();

        return response()->json(new DoubtAnsPageCollectionResouce($doubt_answers), 200, [], JSON_INVALID_UTF8_IGNORE); */

        $doubt_answers_query = Doubt::whereHas('answers', function ($query) use ($user) {
            $query->where('user_id', $user->user_id);
        });

        $doubt_answers = $doubt_answers_query->orderBy('created_at', 'DESC')->paginate(10);

        return response()->json(new DoubtWithAnswerPageCollectionResource($doubt_answers), 200, [], JSON_INVALID_UTF8_IGNORE);
    }

    /**
     * Create Doubt
     *
     * @param  mixed $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createDoubt(Request $request)
    {
        $error_messages = trans('message.error.invalid_request');

        $user = Auth::user();

        /* if ($request->doubt_text) {
            
        } */
        $doubt = new Doubt;
        $doubt->user_id = $user->user_id;
        $doubt->category_id = $request->category_id;
        $doubt->doubt_text = $request->doubt_text;
        $doubt->status = 1;
        $doubt->save();

        //Save doubt to user items for My Doubts
        $user_saved_item = new UserSavedItem;
        $user_saved_item->user_id = $user->user_id;
        $user_saved_item->item_type = 'DOUBT';
        $user_saved_item->item_type_id = $doubt->doubt_id;
        $user_saved_item->save();

        // Send Web Push to Admin
        UserNotifications::sendAdminNotification([
            'title' => 'New doubt has been created.',
            'body' => 'Doubt "' . $doubt->doubt_text . '" has been created by ' . $user->name . '.',
            'type' => 'NEW_DOUBT',
            'type_id' => $doubt->doubt_id
        ], [
            'action' => 'NEW_DOUBT',
            'doubt_id' => $doubt->doubt_id
        ]);

        $success_message = trans('message.success.doubt_created');
        return response()->json(['message' => $success_message, 'doubt_id' => $doubt->doubt_id], 200, [], JSON_INVALID_UTF8_IGNORE);

        return response()->json(['message' => $error_messages], 400, [], JSON_INVALID_UTF8_IGNORE);
    }

    /**
     * Create Doubt Answer
     *
     * @param  number $doubt_id
     * @param  mixed $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createDoubtAnswer($doubt_id, Request $request)
    {
        $error_messages = trans('message.error.invalid_request');

        $user = Auth::user();

        $doubt = Doubt::where('doubt_id', $doubt_id)->first();
        if ($doubt) {
            $doubt_answer = new DoubtAnswer;
            $doubt_answer->parent_id = $request->parent_id;
            $doubt_answer->doubt_id = $request->doubt_id;
            $doubt_answer->user_id = $user->user_id;
            $doubt_answer->doubt_answer = $request->doubt_answer;
            $doubt_answer->save();

            $this->update_doubt_total_answers($doubt_answer->doubt_id);

            //Send Notification

            if ($doubt->user->user_id != $user->user_id) { // Don't send self
                $recepient = $doubt->user;

                $push_data = [
                    'title' => trans('notification_title.doubt_answered', [], $recepient->user_lang_code),
                    'body' => trans('notification_message.doubt_answered', ['name' => $user->name], $recepient->user_lang_code),
                ];

                $notification_data = [
                    'action' => 'DOUBT_ANSWER',
                    'doubt_id' => $doubt_id,
                    'answer_id' => $doubt_answer->answer_id
                ];

                $this->send_push_notification($recepient, 'DOUBT_ANSWER', $doubt_id, $push_data, $notification_data);
            }

            // Send Web Push to Admin
            UserNotifications::sendAdminNotification([
                'title' => 'Doubt has been answered.',
                'body' => 'Doubt "' . $doubt->doubt_text . '" has been answered by ' . $user->name . '.',
                'type' => 'DOUBT_ANSWER',
                'type_id' => $doubt_answer->answer_id
            ], [
                'action' => 'DOUBT_ANSWER',
                'doubt_id' => $doubt->doubt_id,
                'answer_id' => $doubt_answer->answer_id
            ]);

            $success_message = trans('message.success.doubt_ans_created');
            return response()->json([
                'answer_id' => $doubt_answer->answer_id,
                'message' => $success_message
            ], 200, [], JSON_INVALID_UTF8_IGNORE);
        }

        return response()->json(['message' => $error_messages], 400, [], JSON_INVALID_UTF8_IGNORE);
    }

    /**
     * Create Doubt Answer Reply
     *
     * @param  number $doubt_id
     * @param  number $ans_id
     * @param  mixed $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createDoubtReply($doubt_id, $answer_id, Request $request)
    {
        $error_messages = trans('message.error.invalid_request');

        $user = Auth::user();

        $doubt = Doubt::where('doubt_id', $doubt_id)->first();
        $doubt_answer = DoubtAnswer::where('answer_id', $answer_id)->first();
        if ($doubt && $doubt_answer) {
            $doubt_reply = new DoubtReply;
            $doubt_reply->parent_id = $request->parent_id;
            $doubt_reply->user_id = $user->user_id;
            $doubt_reply->doubt_id = $doubt_id;
            $doubt_reply->answer_id = $answer_id;
            $doubt_reply->doubt_reply = $request->doubt_reply;
            $doubt_reply->save();

            $this->update_ans_total_replies($answer_id);

            //Send Notification
            $notification_data = [
                'action' => 'DOUBT_REPLY',
                'doubt_id' => $doubt_id,
                'answer_id' => $answer_id
            ];

            if ($doubt->user->user_id != $user->user_id) { // Don't send self
                //Sent to Doubt User
                $doubt_recepient = $doubt->user;

                $doubt_recepient_push_data = [
                    'title' => trans('notification_title.doubt_replied', [], $doubt_recepient->user_lang_code),
                    'body' => trans('notification_message.doubt_replied', ['name' => $user->name], $doubt_recepient->user_lang_code),
                ];

                $this->send_push_notification($doubt_recepient, 'DOUBT_REPLY', $doubt_id, $doubt_recepient_push_data, $notification_data);
            }

            if ($doubt_answer->user->user_id != $user->user_id) { // Don't send self

                //Sent to Doubt Answer User
                $answer_recepient = $doubt_answer->user;

                $answer_recepient_push_data = [
                    'title' => trans('notification_title.doubt_replied', [], $answer_recepient->user_lang_code),
                    'body' => trans('notification_message.doubt_replied', ['name' => $user->name], $answer_recepient->user_lang_code),
                ];

                $this->send_push_notification($answer_recepient, 'DOUBT_REPLY', $doubt_id, $answer_recepient_push_data, $notification_data);
            }

            $success_message = trans('message.success.doubt_reply_created');
            return response()->json([
                'reply_id' => $doubt_reply->reply_id,
                'message' => $success_message
            ], 200, [], JSON_INVALID_UTF8_IGNORE);
        }

        return response()->json(['message' => $error_messages], 400, [], JSON_INVALID_UTF8_IGNORE);
    }

    /**
     * Update Doubt Tag/Category
     *
     * @param  number $doubt_id
     * @param  mixed $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateDoubtTag($doubt_id, Request $request)
    {
        $error_messages = trans('message.error.invalid_request');

        $user = Auth::user();

        $doubt = Doubt::where('user_id', $user->user_id)->where('doubt_id', $doubt_id)->first();
        if ($doubt && (int)$request->category_id) {
            $doubt->category_id = $request->category_id;
            $doubt->save();

            $success_message = trans('message.success.doubt_tag_updated');
            return response()->json(['message' => $success_message], 200, [], JSON_INVALID_UTF8_IGNORE);
        }

        return response()->json(['message' => $error_messages], 400, [], JSON_INVALID_UTF8_IGNORE);
    }

    /**
     * Save Doubt Image
     *
     * @param  number $doubt_id
     * @param  mixed $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveDoubtImage($doubt_id, Request $request)
    {
        $error_messages = trans('message.error.invalid_request');

        $user = Auth::user();

        $doubt = Doubt::where('user_id', $user->user_id)->where('doubt_id', $doubt_id)->first();

        if ($doubt) {
            $doubt_image = $request->file('doubt_image');
            if ($doubt_image) {
                $new_doubt_image_name = 'image_' . md5($doubt->doubt_id) . time() . '.' . $doubt_image->getClientOriginalExtension();

                $images_dirs = Config::get('siteglobal.images_dirs');
                $doubt_dir = $images_dirs['DOUBT'] . '/';

                $old_doubt_image = $doubt->doubt_image;

                $destination_path = Storage::disk('public')->path($doubt_dir);
                if ($doubt_image->move($destination_path, $new_doubt_image_name)) {

                    if (file_exists($destination_path . $old_doubt_image)) {
                        @unlink($destination_path . $old_doubt_image);
                    }

                    $doubt->doubt_image = $new_doubt_image_name;
                    $doubt->save();

                    try {
                        Utilities::getThumbImage($doubt_dir . $new_doubt_image_name, 800, 400);
                    } catch (Exception $e) {
                    }

                    $success_message = trans('message.success.doubt_image_updated');
                    return response()->json(['message' => $success_message], 200, [], JSON_INVALID_UTF8_IGNORE);
                }
            }
        }

        return response()->json(['message' => $error_messages], 400, [], JSON_INVALID_UTF8_IGNORE);
    }

    /**
     * Save Doubt Attachment
     *
     * @param  number $doubt_id
     * @param  mixed $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveDoubtAttachment($doubt_id, Request $request)
    {
        $error_messages = trans('message.error.invalid_request');

        $user = Auth::user();

        $doubt = Doubt::where('user_id', $user->user_id)->where('doubt_id', $doubt_id)->first();

        if ($doubt) {
            $doubt_attachment = $request->file('doubt_attachment');
            if ($doubt_attachment) {
                $new_doubt_attachment_name = 'attachment_' . md5($doubt->doubt_id) . time() . '.' . $doubt_attachment->getClientOriginalExtension();

                $images_dirs = Config::get('siteglobal.images_dirs');
                $doubt_dir = $images_dirs['DOUBT'] . '/';

                $old_doubt_attachment = $doubt->doubt_attachment;

                $destination_path = Storage::disk('public')->path($doubt_dir);
                if ($doubt_attachment->move($destination_path, $new_doubt_attachment_name)) {

                    if (file_exists($destination_path . $old_doubt_attachment)) {
                        @unlink($destination_path . $old_doubt_attachment);
                    }

                    $doubt->doubt_attachment = $new_doubt_attachment_name;
                    $doubt->save();

                    $success_message = trans('message.success.doubt_attachment_updated');
                    return response()->json(['message' => $success_message], 200, [], JSON_INVALID_UTF8_IGNORE);
                }
            }
        }

        return response()->json(['message' => $error_messages], 400, [], JSON_INVALID_UTF8_IGNORE);
    }

    /**
     * Update Doubt Upvote
     *
     * @param  mixed $doubt_id
     * @return void
     */
    public function upvoteDoubt($doubt_id)
    {
        $error_messages = trans('message.error.invalid_request');

        $user = Auth::user();

        $doubt = Doubt::find($doubt_id);
        if ($doubt) {
            $dup_item_info = UserSavedItem::where('user_id', $user->user_id)->where('item_type', 'DOUBT_UPVOTE')->where('item_type_id', $doubt->doubt_id)->first();

            $action_type = '';
            if (!$dup_item_info) {
                $user_saved_item = new UserSavedItem;
                $user_saved_item->user_id = $user->user_id;
                $user_saved_item->item_type = 'DOUBT_UPVOTE';
                $user_saved_item->item_type_id = $doubt_id;

                if ($user_saved_item->save()) {
                    $action_type = 'DOUBT_UPVOTED';
                }
            } else {
                $is_deleted = UserSavedItem::where('user_id', $user->user_id)->where('item_id', $dup_item_info->item_id)->where('item_type', 'DOUBT_UPVOTE')->delete();
                if ($is_deleted) {
                    $action_type = 'DOUBT_DOWNVOTED';
                }
            }

            if ($action_type != '') {
                $this->update_doubt_total_upvotes($doubt_id);
                if ($action_type == 'DOUBT_UPVOTED') {
                    $success_message = trans('message.success.doubt_upvoted');
                } else if ($action_type == 'DOUBT_DOWNVOTED') {
                    $success_message = trans('message.success.doubt_downvoted');
                }

                return response()->json(['message' => $success_message], 200, [], JSON_INVALID_UTF8_IGNORE);
            }
        }

        return response()->json(['message' => $error_messages], 400, [], JSON_INVALID_UTF8_IGNORE);
    }

    /**
     * Update Doubt Upvote
     *
     * @param  mixed $doubt_id
     * @return void
     */
    public function upvoteDoubtAnswer($answer_id)
    {
        $error_messages = trans('message.error.invalid_request');

        $user = Auth::user();

        $doubt_answer = DoubtAnswer::find($answer_id);
        if ($doubt_answer) {
            $dup_item_info = UserSavedItem::where('user_id', $user->user_id)->where('item_type', 'DOUBT_ANS_UPVOTE')->where('item_type_id', $answer_id)->first();

            $action_type = '';
            if (!$dup_item_info) {
                $user_saved_item = new UserSavedItem;
                $user_saved_item->user_id = $user->user_id;
                $user_saved_item->item_type = 'DOUBT_ANS_UPVOTE';
                $user_saved_item->item_type_id = $answer_id;

                if ($user_saved_item->save()) {
                    $action_type = 'DOUBT_ANS_UPVOTED';
                }
            } else {
                $is_deleted = UserSavedItem::where('user_id', $user->user_id)->where('item_id', $dup_item_info->item_id)->where('item_type', 'DOUBT_ANS_UPVOTE')->delete();
                if ($is_deleted) {
                    $action_type = 'DOUBT_ANS_DOWNVOTED';
                }
            }

            if ($action_type != '') {
                $this->update_doubt_answer_total_upvotes($answer_id);
                if ($action_type == 'DOUBT_ANS_UPVOTED') {
                    $success_message = trans('message.success.doubt_ans_upvoted');
                } else if ($action_type == 'DOUBT_ANS_DOWNVOTED') {
                    $success_message = trans('message.success.doubt_ans_downvoted');
                }

                return response()->json(['message' => $success_message], 200, [], JSON_INVALID_UTF8_IGNORE);
            }
        }

        return response()->json(['message' => $error_messages], 400, [], JSON_INVALID_UTF8_IGNORE);
    }


    /**
     * Save Doubt Answer Image
     *
     * @param  number $doubt_id
     * @param  mixed $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveDoubtAnswerImage($answer_id, Request $request)
    {
        $error_messages = trans('message.error.invalid_request');

        $user = Auth::user();

        $doubt_answer = DoubtAnswer::where('user_id', $user->user_id)->where('answer_id', $answer_id)->first();

        if ($doubt_answer) {
            $answer_image = $request->file('doubt_ans_image');
            if ($answer_image) {
                $new_answer_image_name = 'image_' . md5($answer_id) . time() . '.' . $answer_image->getClientOriginalExtension();

                $images_dirs = Config::get('siteglobal.images_dirs');
                $doubt_dir = $images_dirs['DOUBT'] . '/';

                $old_answer_image = $doubt_answer->answer_image;

                $destination_path = Storage::disk('public')->path($doubt_dir);
                if ($answer_image->move($destination_path, $new_answer_image_name)) {

                    if (file_exists($destination_path . $old_answer_image)) {
                        @unlink($destination_path . $old_answer_image);
                    }

                    $doubt_answer->answer_image = $new_answer_image_name;
                    $doubt_answer->save();

                    try {
                        Utilities::getThumbImage($doubt_dir . $new_answer_image_name, 800, 400);
                    } catch (Exception $e) {
                    }

                    $success_message = trans('message.success.doubt_ans_image_updated');
                    return response()->json(['message' => $success_message], 200, [], JSON_INVALID_UTF8_IGNORE);
                }
            }
        }

        return response()->json(['message' => $error_messages], 400, [], JSON_INVALID_UTF8_IGNORE);
    }

    /**
     * Save Doubt Image
     *
     * @param  number $doubt_id
     * @param  mixed $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveDoubtReplyImage($reply_id, Request $request)
    {
        $error_messages = trans('message.error.invalid_request');

        $user = Auth::user();

        $doubt_reply = DoubtReply::where('user_id', $user->user_id)->where('reply_id', $reply_id)->first();

        if ($doubt_reply) {
            $doubt_reply_image = $request->file('reply_image');
            if ($doubt_reply_image) {
                $new_doubt_reply_image_name = 'image_' . md5($reply_id) . time() . '.' . $doubt_reply_image->getClientOriginalExtension();

                $images_dirs = Config::get('siteglobal.images_dirs');
                $doubt_dir = $images_dirs['DOUBT'] . '/';

                $old_doubt_reply_image = $doubt_reply->doubt_image;

                $destination_path = Storage::disk('public')->path($doubt_dir);
                if ($doubt_reply_image->move($destination_path, $new_doubt_reply_image_name)) {

                    if (file_exists($destination_path . $old_doubt_reply_image)) {
                        @unlink($destination_path . $old_doubt_reply_image);
                    }

                    $doubt_reply->reply_image = $new_doubt_reply_image_name;
                    $doubt_reply->save();

                    try {
                        Utilities::getThumbImage($doubt_dir . $new_doubt_reply_image_name, 800, 400);
                    } catch (Exception $e) {
                    }

                    $success_message = trans('message.success.doubt_reply_image_updated');
                    return response()->json(['message' => $success_message], 200, [], JSON_INVALID_UTF8_IGNORE);
                }
            }
        }

        return response()->json(['message' => $error_messages], 400, [], JSON_INVALID_UTF8_IGNORE);
    }

    /**
     * Update Doubt Total Answers
     *
     * @param  mixed $doubt_id
     * @return void
     */
    private function update_doubt_total_answers($doubt_id)
    {
        $doubt = Doubt::find($doubt_id);
        if ($doubt) {
            $total_answers = DoubtAnswer::where('doubt_id', $doubt_id)->count();
            $doubt->total_answers = $total_answers;
            $doubt->save();
        }
    }

    /**
     * Update Answer Total Replies
     *
     * @param  mixed $answer_id
     * @return void
     */
    private function update_ans_total_replies($answer_id)
    {
        $doubt_answer = DoubtAnswer::find($answer_id);
        if ($doubt_answer) {
            $total_replies = DoubtReply::where('answer_id', $answer_id)->count();
            $doubt_answer->total_replies = $total_replies;
            $doubt_answer->save();
        }
    }

    /**
     * Update Total Upvoate
     *
     * @param  mixed $doubt_id
     * @return void
     */
    private function update_doubt_total_upvotes($doubt_id)
    {
        $doubt = Doubt::find($doubt_id);
        if ($doubt) {
            $total_doubt_upvoted = UserSavedItem::where('item_type', 'DOUBT_UPVOTE')->where('item_type_id', $doubt_id)->count();
            $doubt->doubt_upvote = $total_doubt_upvoted;
            $doubt->save();
        }
    }

    /**
     * Update Total Answer Upvoate
     *
     * @param  mixed $answer_id
     * @return void
     */
    private function update_doubt_answer_total_upvotes($answer_id)
    {
        $doubt_answer = DoubtAnswer::find($answer_id);
        if ($doubt_answer) {
            $total_doubt_ans_upvoted = UserSavedItem::where('item_type', 'DOUBT_ANS_UPVOTE')->where('item_type_id', $answer_id)->count();
            $doubt_answer->answer_upvote = $total_doubt_ans_upvoted;
            $doubt_answer->save();
        }
    }

    private function send_push_notification($recepient, $notification_type, $ntoification_type_id, $push_data, $notification_data)
    {
        $languages = Config::get('siteglobal.languages');
        $user = Auth::user();

        UserNotifications::sendPush($recepient->device_token, $push_data, $notification_data);

        $notification = new Notification;
        $notification->user_id = $recepient->user_id;
        $notification->notification_type = $notification_type;
        $notification->ntoification_type_id = $ntoification_type_id;
        $notification->notification_data = json_encode($notification_data);
        $notification->status = 0;
        $notification->save();

        foreach ($languages as $lang_code => $lang) {
            if ($notification_type == 'DOUBT_ANSWER') {
                $message = trans('notification_message.doubt_answered', ['name' => $user->name], $lang_code);
            } else if ($notification_type == 'DOUBT_REPLY') {
                $message = trans('notification_message.doubt_replied', ['name' => $user->name], $lang_code);
            }

            $notification_desc = new NotificationDesc;
            $notification_desc->notification_id = $notification->notification_id;
            $notification_desc->lang_code = $lang_code;
            $notification_desc->message = $message;
            $notification_desc->save();
        }
    }
}
