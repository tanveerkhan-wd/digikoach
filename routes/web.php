<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('home',function(){
	return redirect()->route('websiteHome');
});

Route::namespace('Frontend')->group(function () {
	Route::get('/','HomeController@index')->name('websiteHome');
	Route::post('/contactQueryPost','ContactController@contactQueryPost');
	Route::get('user/performance/{user_id}','UserPerformanceController@index');
	Route::post('user/filter/performance','UserPerformanceController@filterPerformmanceChart');
	Route::get('gkCa/article/{id}','UserPerformanceController@gkCaArticle');
	Route::get('blog/{id}','UserPerformanceController@blogData');
	Route::get('page/{slug}','UserPerformanceController@staticPage');
	Route::get('redirect/{type}/{id}','UserPerformanceController@redirect');
});


//Auth::routes();

//Admin Routes
/*Login Routes*/

Route::get('admin/', function(){
	return redirect()->route('adminLoginForm');
});

Route::get('admin/login','UserController@index')->name('adminLoginForm');
Route::post('/loginPost','UserController@loginPost')->name('adminLoginFormPost');
Route::get('logout','UserController@logout')->name('adminLogout');
Route::get('forgotPassword','UserController@forgotPassword')->name('forgotPassword');
Route::post('forgotPasswordPost','UserController@forgotPasswordPost')->name('forgotPasswordPost');
Route::get('/resetPassword/{token}', 'UserController@resetPassword')->name('resetPassword');
Route::get('/sub-admin/setPassword/{token}', 'UserController@setPassword')->name('setPassword');
Route::post('/resetPasswordPost', 'UserController@resetPasswordPost')->name('resetPasswordPost');
Route::post('/changePasswordPost', 'UserController@changePasswordPost');
Route::get('/verifyOtp/{id}', 'UserController@getVerifyOtp')->name('getVerifyOtp');
Route::post('/verifyOtpPost', 'UserController@verifyOtpPost')->name('verifyOtpPost');

Route::prefix('admin')->namespace('Admin')->group(function () {
	
	Route::post('/token-save','DashboardController@saveToken');
	Route::group(['middleware'=>'CheckAdminAndSubAdmin'],function(){
		Route::get('/dashboard','DashboardController@dashboard')->name('adminDashboard');
		Route::get('/profile', 'DashboardController@profile')->name('adminProfile');
		Route::post('/editProfile', 'DashboardController@editProfile');
		Route::post('/changePasswordPost', 'DashboardController@changePasswordPost');

		/*__ Routes For Admin Live Test __*/
		Route::get('/liveTest','LiveTestController@index');
		Route::post('/liveTests','LiveTestController@getLiveTest');
		Route::get('/addLiveTest','LiveTestController@addLiveTest');
		Route::post('/addLiveTestPost','LiveTestController@addLiveTestPost');
		Route::post('/liveTest/getCategoryData','LiveTestController@getCategoryData');
		Route::get('/viewLiveTest/{id}','LiveTestController@viewLiveTest');
		Route::get('/editLiveTest/{id}','LiveTestController@editLiveTest');
		Route::post('/deleteLiveTest','LiveTestController@deleteLiveTest');
		Route::post('/statusLiveTest','LiveTestController@statusLiveTest');
		Route::get('/liveTest/appearedStudents/{id}','LiveTestController@appearedStudents');
		Route::post('/appearedStudents','LiveTestController@appearedStudentsPost');
		Route::post('/diclareLiveTestResult','LiveTestController@diclareLiveTestResult');
		Route::get('/appearedStudents/viewUserTest/{id}','LiveTestController@appearedStudentsViewUserTest');


		/*__ Routes For Admin Question Bank __*/
		Route::get('/questions','QuestionController@index');
		Route::post('/questions','QuestionController@getQuestions');
		Route::get('/addQuestion','QuestionController@addQuestion');
		Route::post('/addQuestionPost','QuestionController@addQuestionPost');
		Route::post('/getCategoryData','QuestionController@getCategoryData');
		Route::post('/questionStatus','QuestionController@questionStatus');
		Route::post('/deleteQuestion','QuestionController@deleteQuestion');
		Route::get('/viewQuestion/{id}','QuestionController@viewQuestion');
		Route::get('/editQuestion/{id}','QuestionController@editQuestion');
		Route::post('/addCsvQuestion','QuestionController@addCsvQuestion');
		Route::post('/deleteQuestionMedia', 'QuestionController@deleteQuestionMedia');
		Route::post('ckeditor/upload', 'QuestionController@upload')->name('ckeditor.upload');
		Route::post('question/add_new_image', 'QuestionController@uploadQuestionImage');
		Route::get('/queImage/remove/{id}','QuestionController@queImageRemove');
		//Route::post('/getQuestionTypeCate','QuestionController@getQuestionTypeCate');

		
		
		/*__ Routes For Admin Quizzes __*/
		Route::get('/quizTest','QuizzController@index');
		Route::post('/quizTest','QuizzController@getQuizTest');
		Route::get('/addQuizTest','QuizzController@addQuizTest');
		Route::post('/addQuizTestPost','QuizzController@addQuizTestPost');
		Route::post('/quizTest/getCategoryData','QuizzController@getCategoryData');
		Route::get('/viewQuizTest/{id}','QuizzController@viewQuizTest');
		Route::get('/editQuizTest/{id}','QuizzController@editQuizTest');
		Route::post('/deleteQuizTest','QuizzController@deleteQuizTest');
		Route::post('/statusQuizTest','QuizzController@statusQuizTest');
		Route::get('/quizTest/appearedStudents/{id}','QuizzController@appearedStudents');
		Route::post('/quizTest/appearedStudents','QuizzController@appearedStudentsPost');
		Route::get('/quiz/viewUserTest/{id}','QuizzController@viewUserTest');


		/*__ Routes For Admin Quizzes __*/
		Route::get('/practiceTest','PracticeTestController@index');
		Route::post('/practiceTest','PracticeTestController@getPracticeTest');
		Route::get('/addPracticeTest','PracticeTestController@addPracticeTest');
		Route::post('/addPracticeTestPost','PracticeTestController@addPracticeTestPost');
		Route::post('/practiceTest/getCategoryData','PracticeTestController@getCategoryData');
		Route::get('/viewPracticeTest/{id}','PracticeTestController@viewPracticeTest');
		Route::get('/editPracticeTest/{id}','PracticeTestController@editPracticeTest');
		Route::post('/deletePracticeTest','PracticeTestController@deletePracticeTest');
		Route::post('/statusPracticeTest','PracticeTestController@statusPracticeTest');
		Route::get('/practiceTest/appearedStudents/{id}','PracticeTestController@appearedStudents');
		Route::post('/practiceTest/appearedStudents','PracticeTestController@appearedStudentsPost');
		Route::get('/practice/viewUserTest/{id}','PracticeTestController@viewUserTest');

		Route::prefix('gkCa')->group(function () {
			
			/*__ Routes For Admin GK CA Article and News  __*/
			Route::get('/articleNews','ArticleNewsController@index');
			Route::post('/articleNews','ArticleNewsController@getArticleNews');
			Route::get('/addArticleNews','ArticleNewsController@addArticleNews');
			Route::get('/editArticleNews/{id}','ArticleNewsController@editArticleNews');
			Route::get('/viewArticleNews/{id}','ArticleNewsController@viewArticleNews');
			Route::post('/addArticleNewsPost','ArticleNewsController@addArticleNewsPost');
			Route::post('/deleteArticleNews','ArticleNewsController@deleteArticleNews');
			Route::post('/statusArticleNews','ArticleNewsController@statusArticleNews');

			/*__ Routes For Admin GK CA Quiz Test  __*/
			Route::get('/quizTest','GkCaQuizTestController@index');
			Route::post('/quizTest','GkCaQuizTestController@getQuizTest');
			Route::get('/addQuizTest','GkCaQuizTestController@addQuizTest');
			Route::post('/addQuizTestPost','GkCaQuizTestController@addQuizTestPost');
			Route::get('/editQuizTest/{id}','GkCaQuizTestController@editQuizTest');
			Route::get('/viewQuizTest/{id}','GkCaQuizTestController@viewQuizTest');
			Route::post('/deleteQuizTest','GkCaQuizTestController@deleteQuizTest');
			Route::post('/statusQuizTest','GkCaQuizTestController@statusQuizTest');
			Route::get('/quizTest/appearedStudents/{id}','GkCaQuizTestController@appearedStudents');
			Route::post('/quizTest/appearedStudents','GkCaQuizTestController@appearedStudentsPost');
			Route::get('/quiz/viewUserTest/{id}','GkCaQuizTestController@viewUserTest');


		});

		/* __ Routes For Admin Blog Category __ */
		Route::get('/blogCategory','BlogCategoryController@index');
		Route::post('/blogCategory','BlogCategoryController@getBlogCategory');
		Route::post('/addBlogCategory','BlogCategoryController@addBlogCategory');
		Route::post('/editBlogCategory','BlogCategoryController@editBlogCategory');
		Route::post('/deleteBlogCategory','BlogCategoryController@deleteBlogCategory');
		Route::post('/statusBlogCategory','BlogCategoryController@statusBlogCategory');

		/* __ Routes For Admin Blog Post __ */
		Route::get('/blog','BlogController@index');
		Route::post('/blog','BlogController@getBlog');
		Route::get('/addBlog','BlogController@getAddBlog');
		Route::post('/addBlog','BlogController@addBlog');
		Route::get('/editBlog/{id}','BlogController@editBlog');
		Route::post('/deleteBlog','BlogController@deleteBlog');
		Route::post('/statusBlog','BlogController@statusBlog');
		Route::get('/viewBlog/{id}','BlogController@viewBlog');
		
		/* __ Routes For Admin Doubt __ */
		Route::get('/doubt','DoubtSectionController@index');
		Route::post('/doubt','DoubtSectionController@getDoubts');
		Route::post('/doubt/addAnswer','DoubtSectionController@addDoubtAnswer');
		Route::get('/viewDoubt/{id}','DoubtSectionController@viewDoubt');
		Route::post('/deleteDoubt','DoubtSectionController@deleteDoubt');
		Route::post('/statusDoubt','DoubtSectionController@statusDoubt');
		Route::post('/getAnswerReply','DoubtSectionController@getAnswerReply');

	});

	Route::group(['middleware'=>'CheckSubAdmin'],function(){
		
	});


	Route::group(['middleware'=>'CheckAdmin'],function(){
		
		/* __ Routes For Admin Basic  Settings __ */
		Route::post('/live_test/chart/data','DashboardController@liveTestChartData');
		Route::post('/student_acc_creation/chart/data','DashboardController@stuAccCreationChartData');
		Route::post('/quiz_test/chart/data','DashboardController@quizTestChartData');
		Route::post('/practice_test/chart/data','DashboardController@practiceTestChartData');
		Route::post('/gk_quiz_test/chart/data','DashboardController@gkQuizTestChartData');

		Route::get('/notifications', 'DashboardController@notification')->name('see.notification');
		Route::post('/changeNotificationStatus', 'DashboardController@changeNotificationStatus');

		/* __ Routes For Admin Email Templates __ */
		Route::get('/emailTemplates','EmailTemplateController@index');
		Route::post('/emailTemplates','EmailTemplateController@getEmailTemplates');
		Route::get('/emailTemplate/edit/{id}','EmailTemplateController@editEmailTemplate');
		Route::post('/emailTemplate/edit','EmailTemplateController@editEmailTemplatePost');

		/* __ Routes For Admin Category Master __ */
		Route::get('/category','CategoryController@index');
		Route::post('/category','CategoryController@getCategory');
		Route::post('/addcategory','CategoryController@addcategory');
		Route::post('/editCategory','CategoryController@editCategory');
		Route::post('/deleteCategory','CategoryController@deleteCategory');
		Route::post('/statusCategory','CategoryController@statusCategory');
		
		
		/* __ Routes For Admin CMS  __ */
		Route::get('/cms','CmsController@index');
		Route::post('/cms','CmsController@getCms');
		Route::post('/addCms','CmsController@addCms');
		Route::get('/editCms/{id}','CmsController@editCms');
		Route::post('/statusCms','CmsController@statusCms');

		/* __ Routes For Admin Translation  __ */
		Route::get('/translation','TranslationController@index');
		Route::post('/translation','TranslationController@getTranslation');
		Route::post('/addTranslation','TranslationController@addTranslation');
		Route::post('/editTranslation','TranslationController@editTranslation');
		Route::post('/deleteTranslation','TranslationController@deleteTranslation');

		/* __ Routes For Admin Setting  __ */
		Route::get('/setting','SettingController@index');
		Route::post('/editHeader','SettingController@editHeader');
		Route::post('/editAbout','SettingController@editAbout');
		Route::post('/editDownloadLink','SettingController@editDownloadLink');
		Route::post('/editSeo','SettingController@editSeo');
		Route::post('/editContactSet','SettingController@editContactSet');
		
		/* __ Routes For Admin Master Banner Image  __ */
		Route::get('/bannerImage','BannerImageController@index');
		Route::post('/bannerImage','BannerImageController@getBannerImage');
		Route::post('/addBannerImage','BannerImageController@addBannerImage');
		Route::post('/editBannerImage','BannerImageController@editBannerImage');
		Route::post('/deleteBannerImage','BannerImageController@deleteBannerImage');

		/* __ Routes For Admin Master Banner Image  __ */
		Route::get('/feature','FeatureController@index');
		Route::post('/updateFeature','FeatureController@updateFeature');

		/* __ Routes For Admin Master Testimonial __ */
		Route::get('/testimonial','TestimonialController@index');
		Route::post('/testimonial','TestimonialController@getTestimonial');
		Route::post('/addTestimonial','TestimonialController@addTestimonial');
		Route::post('/editTestimonial','TestimonialController@editTestimonial');
		Route::post('/deleteTestimonial','TestimonialController@deleteTestimonial');

		/* __ Routes For Admin Students __ */
		Route::get('/appUsers','AppUserController@index');
		Route::post('/appUsers','AppUserController@getAppUsers');
		Route::get('/editAppUser/{id}','AppUserController@editAppUser');
		Route::post('/editAppUser','AppUserController@editAppUserPost');
		Route::post('/statusAppUser','AppUserController@statusAppUser');
		Route::post('/deleteAppUser','AppUserController@deleteAppUser');
		Route::get('/viewAppUser/{id}','AppUserController@viewAppUser');	
		Route::post('/getAppUserCategory','AppUserController@getAppUserCategory');
		Route::post('/appUser/liveTests','AppUserController@getLiveTest');
		Route::post('/appUser/practiceTest','AppUserController@getPracticeTest');	
		Route::post('/appUser/quizTest','AppUserController@getQuizTest');
		Route::post('/appUser/gkQuizTest','AppUserController@getGkQuizTest');
		Route::get('appUser/viewUserTest/{id}','AppUserController@viewUserTest');	
		/*__ Routes For Admin App User Performance Tab __*/
		Route::post('/liveTestPerformmanceChart','AppUserController@liveTestPerformmanceChart');		

		
		/*__ Routes For Admin Test Rule  __*/
		Route::get('/testRule','TestRuleController@index');
		Route::post('/editTestRule','TestRuleController@editTestRule');

		/*__ Routes For Admin SUB ADMIN  __*/
		Route::get('/subAdmin','SubAdminController@index');
		Route::post('/subAdmins','SubAdminController@getSubAdmins');		
		Route::get('/addSubAdmin','SubAdminController@addSubAdmin');		
		Route::post('/addSubAdminPost','SubAdminController@addSubAdminPost');		
		Route::get('/editSubAdmin/{id}','SubAdminController@editSubAdmin');		
		Route::get('/viewSubAdmin/{id}','SubAdminController@viewSubAdmin');		
		Route::post('/statusSubAdmin','SubAdminController@statusSubAdmin');		
		Route::post('/deleteSubAdmin','SubAdminController@deleteSubAdmin');	
		Route::get('/accessPrivileges/{id}','SubAdminController@accessPrivileges');
		Route::post('/accessPrivilegePost','SubAdminController@accessPrivilegePost');

		/*__ Routes For Admin Image Media  __*/
		Route::get('/imageMedia','ImageMediaController@index');
		Route::post('/imageMedias','ImageMediaController@getImageMedia');		
		Route::get('/addImageMedia','ImageMediaController@addImageMedia');		
		Route::post('/addImageMediaPost','ImageMediaController@addImageMediaPost');		
		Route::post('/deleteImageMedia','ImageMediaController@deleteImageMedia');		
		


	});

});
/*
Route::get('/home', 'HomeController@index')->name('home');
*/