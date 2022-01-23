<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'prefix' => 'v1',
    'namespace' => 'API\v1',
    ['middleware' => ['return-json']]
], function ($router) {
    Route::get('translations', 'TranslationController@getTranslations');
    Route::get('cms/{slug}', 'CMSController@getCMSInfo');

    Route::post('user/register', 'AuthController@register');
    Route::post('user/login', 'AuthController@login');

    Route::post('user/forgot-password-process', 'AuthController@forgotPasswordProcess');
    Route::post('user/verify-password-process', 'AuthController@verifyPasswordProcess');
    Route::put('user/reset-password', 'AuthController@resetPasswordProcess');

    Route::group(['middleware' => ['jwt.auth', 'is-soft-deleted']], function () {
        Route::get('categories/search', 'CategoryController@getSearchCategories');
        Route::get('categories/{parent_cat_id}', 'CategoryController@subCategories');
        Route::get('categories', 'CategoryController@index');
        Route::get('category/{cat_id}', 'CategoryController@getCategory');

        Route::get('banners', 'BannerController@listing');

        Route::get('blogs', 'BlogController@listing');
        Route::get('blogs/latest', 'BlogController@latest');
        Route::get('blog/categories', 'BlogController@categories');
        Route::get('blog/{blog_id}', 'BlogController@detail');

        Route::get('articles', 'ArticleNewsController@listing');
        Route::get('articles/latest', 'ArticleNewsController@latest');
        Route::get('article/{article_id}', 'ArticleNewsController@detail');

        Route::post('user/logout', 'AuthController@logout');
        Route::get('user/info', 'AuthController@me');
        Route::post('user/verification', 'UserController@userVerification');
        Route::post('user/send-verification', 'UserController@sendUserVerification');
        Route::put('user/change-password', 'UserController@changePassword');
        Route::put('user/update-mobile', 'UserController@updateMobile');
        Route::put('user/update-email', 'UserController@updateEmail');
        Route::put('user/update-profile', 'UserController@updateProfile');
        Route::post('user/update-photo', 'UserController@updatePhoto');
        Route::put('user/change-language/{lang_code}', 'UserController@changeLanguage');

        Route::get('user/level', 'UserController@getUserLevel');
        Route::get('user/sub-levels', 'UserController@getUserLevels');
        Route::put('user/update-level', 'UserController@updateLevel');
        Route::put('user/update-sub-levels', 'UserController@updateSubLevel');

        Route::post('user/saved-item/{item_type}/create', 'UserController@createSavedItem');
        Route::get('user/saved-items/{item_type}', 'UserController@getSavedItems');
        Route::delete('user/saved-item/{item_id}', 'UserController@removeSavedItem');

        Route::post('user/deactivate-account', 'UserController@deactivateAccount');

        Route::get('user/challange/search', 'UserController@searchChallangeUsers');
        Route::post('user/challange/{exam_id}', 'UserController@sendChallange');
        Route::get('user/challange/{challenge_id}', 'UserController@getChallangeInfo');
        Route::get('user/challange/{challenge_id}/attempts', 'ExamController@getChallengeAttempts');
        Route::put('user/challange/{challenge_id}/request', 'UserController@updateChallangeRequest');

        Route::post('exam/{exam_id}/register', 'ExamController@registerExam');
        Route::get('exam/{exam_id}', 'ExamController@getExamInfo');
        Route::get('exam/{exam_id}/questions', 'QuestionController@getExamQuestions');
        Route::get('exam/attempt/{user_attempt_id}', 'ExamController@getExamAttempt');
        Route::get('exam/attempt/{user_attempt_id}/challenge', 'ExamController@getAttemptChallenges');
        Route::get('exam/{exam_type}/rules', 'ExamController@getExamRules');
        Route::get('exam/{exam_type}/list', 'ExamController@getExams');
        Route::get('exam/{exam_type}/upcoming', 'ExamController@getUpcomingExams');

        Route::post('exam/response', 'ExamController@saveExamResponse');
        Route::post('exam/response/seen', 'ExamController@saveSeenResponse');
        Route::post('exam/{exam_id}/start', 'ExamController@startExam');
        Route::post('exam/{user_attempt_id}/finish', 'ExamController@finishExam');

        Route::get('question/{quest_id}', 'QuestionController@getQuestion');

        Route::get('notifications', 'NotificationController@listing');
        Route::post('notifications/clear/{noti_id}', 'NotificationController@doClear');

        Route::post('doubt/create', 'DoubtController@createDoubt');
        Route::post('doubt/{doubt_id}/image', 'DoubtController@saveDoubtImage');
        Route::post('doubt/{doubt_id}/attachment', 'DoubtController@saveDoubtAttachment');
        Route::post('doubt/{doubt_id}/answer/create', 'DoubtController@createDoubtAnswer');
        Route::post('doubt/{doubt_id}/answer/{ans_id}/reply/create', 'DoubtController@createDoubtReply');
        Route::post('doubt/{doubt_id}/upvote', 'DoubtController@upvoteDoubt');
        Route::post('doubt/answer/{ans_id}/upvote', 'DoubtController@upvoteDoubtAnswer');
        Route::post('doubt/answer/{ans_id}/image', 'DoubtController@saveDoubtAnswerImage');
        Route::post('doubt/answer/reply/{reply_id}/image', 'DoubtController@saveDoubtReplyImage');

        Route::put('doubt/{doubt_id}/tag', 'DoubtController@updateDoubtTag');

        Route::get('doubts', 'DoubtController@getDoubts');
        Route::get('doubts/my-doubt-answers', 'DoubtController@getMyDoubtAnswers');
        Route::get('doubt/{doubt_id}', 'DoubtController@getDoubtDetail');
        Route::get('doubt/{doubt_id}/answers', 'DoubtController@getDoubtAnswers');
        Route::get('doubt/{doubt_id}/answer/{ans_id}/replies', 'DoubtController@getDoubtReplies');

        Route::delete('doubt/{doubt_id}', 'DoubtController@deleteDoubt');
        Route::delete('doubt/answer/{answer_id}', 'DoubtController@deleteDoubtAnswer');

        Route::get('search/{search_type}', 'SearchController@search');
    });
});
