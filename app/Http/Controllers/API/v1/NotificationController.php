<?php

namespace App\Http\Controllers\API\v1;

use App;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\Notifications\NotificationPageCollection;

use App\Models\Notification;
use Auth;


class NotificationController extends Controller
{
    /**
     * Banner listing
     *
     * @param  mixed $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listing(Request $request)
    {
        $user = Auth::user();

        $notifications = Notification::with('desc')->where('user_id', $user->user_id)->orderBy('created_at', 'DESC')->paginate();

        return response()->json(new NotificationPageCollection($notifications), 200, [], JSON_INVALID_UTF8_IGNORE);
    }

    public function doClear($notification_id)
    {
        $user = Auth::user();
        if ($user) {
            $objNotification = Notification::where('user_id', $user->user_id);
            if ($notification_id != 'all') {
                $objNotification->where('notification_id', $notification_id);
            }

            $objNotification->delete();

            if ($notification_id != 'all') {
                $success_message = trans('message.success.notification_clear');
            } else {
                $success_message = trans('message.success.notification_clear_all');
            }

            return response()->json(['message' => $success_message], 200, [], JSON_INVALID_UTF8_IGNORE);
        } else {
            $error_messages = trans('message.error.invalid_user');
        }

        return response()->json(['message' => $error_messages], 401, [], JSON_INVALID_UTF8_IGNORE);
    }
}
