<?php

namespace App\Http\Middleware;
use Illuminate\Http\Request;
use Closure;
use Auth;
class CheckAdminAndSubAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->user_type == 0) {
            return $next($request);
        }
        if (Auth::check() && Auth::user()->user_type == 1) {
            
            if ($request->route()->uri=='admin/dashboard' || $request->route()->uri=='admin/profile' || $request->route()->uri=='admin/editProfile' || $request->route()->uri=='admin/changePasswordPost') {
                return $next($request);
            }
            $accessPriData = $request->session()->get('accessPriData');
        
            if($request->route()->uri=='admin/editQuestion/{id}' || $request->route()->uri=='admin/deleteQuestion' || $request->route()->uri=='admin/questionStatus' || $request->route()->uri=='admin/questions' || $request->route()->uri=='admin/addQuestion' || $request->route()->uri=='admin/addCsvQuestion' || $request->route()->uri=='admin/addQuestionPost' || $request->route()->uri=='admin/viewQuestion/{id}' || $request->route()->uri=='admin/liveTest/getCategoryData' || $request->route()->uri=='admin/getCategoryData' || $request->route()->uri=='admin/ckeditor/upload' || $request->route()->uri=='admin/quizTest/getCategoryData' )
            {

                return $next($request);
            }

            //LIVE TEST
            if ($request->route()->uri=='admin/liveTest' || $request->route()->uri=='admin/liveTests' || $request->route()->uri=='admin/viewLiveTest/{id}' && $accessPriData['Live_Test']->view==true) {
                return $next($request);
            }elseif ($request->route()->uri=='admin/addLiveTest' && $accessPriData['Live_Test']->add==true) {
                return $next($request);
            }elseif ($request->route()->uri=='admin/addLiveTestPost' && $accessPriData['Live_Test']->add==true) {
                return $next($request);
            }elseif ($request->route()->uri=='admin/editLiveTest/{id}' && $accessPriData['Live_Test']->edit==true) {
                return $next($request);
            }elseif ($request->route()->uri=='admin/deleteLiveTest' && $accessPriData['Live_Test']->delete==true) {
                return $next($request);
            }elseif($request->route()->uri=='admin/statusLiveTest' && $accessPriData['Live_Test']->status==true) 
            {
                return $next($request);
            }
            elseif ($request->route()->uri=='admin/liveTest/appearedStudents/{id}' || $request->route()->uri=='admin/appearedStudents' && $accessPriData['Live_Test']->students==true) {
                return $next($request);
            }elseif ($request->route()->uri=='admin/appearedStudents/viewUserTest/{id}' && $accessPriData['Live_Test']->students==true) {
                return $next($request);
            }
            
            //QUIZ TEST
            if ($request->route()->uri=='admin/quizTest'  || $request->route()->uri=='admin/viewQuizTest/{id}' && $accessPriData['Quizz_Test']->view==true) {
                return $next($request);
            }elseif ($request->route()->uri=='admin/addQuizTest' && $accessPriData['Quizz_Test']->add==true) {
                return $next($request);
            }elseif ($request->route()->uri=='admin/addQuizTestPost' && $accessPriData['Quizz_Test']->add==true) {
                return $next($request);
            }elseif ($request->route()->uri=='admin/editQuizTest/{id}' && $accessPriData['Quizz_Test']->edit==true) {
                return $next($request);
            }elseif ($request->route()->uri=='admin/deleteQuizTest' && $accessPriData['Quizz_Test']->delete==true || $request->route()->uri=='admin/statusQuizTest' && $accessPriData['Quizz_Test']->status==true) {
                return $next($request);
            }elseif ($request->route()->uri=='admin/quizTest/appearedStudents/{id}' || $request->route()->uri=='admin/quizTest/appearedStudents' && $accessPriData['Quizz_Test']->students==true) {
                return $next($request);
            }elseif ($request->route()->uri=='admin/diclareLiveTestResult' && $accessPriData['Quizz_Test']->annouch_result==true) {
                return $next($request);
            }elseif ($request->route()->uri=='admin/quiz/viewUserTest/{id}' && $accessPriData['Quizz_Test']->students==true) {
                return $next($request);
            }
            
            //PRACTICE TEST
            if ($request->route()->uri=='admin/practiceTest'  || $request->route()->uri=='admin/viewPracticeTest/{id}' && $accessPriData['Practice_Test']->view==true) {
                return $next($request);
            }elseif ($request->route()->uri=='admin/addPracticeTest' && $accessPriData['Practice_Test']->add==true) {
                return $next($request);
            }elseif ($request->route()->uri=='admin/addPracticeTestPost' && $accessPriData['Practice_Test']->add==true) {
                return $next($request);
            }elseif ($request->route()->uri=='admin/editPracticeTest/{id}' && $accessPriData['Practice_Test']->edit==true) {
                return $next($request);
            }elseif ($request->route()->uri=='admin/deletePracticeTest' && $accessPriData['Practice_Test']->delete==true || $request->route()->uri=='admin/statusPracticeTest' && $accessPriData['Practice_Test']->status==true) {
                return $next($request);
            }elseif ($request->route()->uri=='admin/practiceTest/appearedStudents/{id}' || $request->route()->uri=='admin/practiceTest/appearedStudents' && $accessPriData['Practice_Test']->students==true) {
                return $next($request);
            }elseif ($request->route()->uri=='admin/practice/viewUserTest/{id}' && $accessPriData['Practice_Test']->students==true) {
                return $next($request);
            }

            //QUIZ TEST
            if ($request->route()->uri=='admin/gkCa/quizTest'  || $request->route()->uri=='admin/gkCa/viewQuizTest/{id}' && $accessPriData['GK_CA_Quizz']->view==true) {
                return $next($request);
            }elseif ($request->route()->uri=='admin/gkCa/addQuizTest' && $accessPriData['GK_CA_Quizz']->add==true) {
                return $next($request);
            }elseif ($request->route()->uri=='admin/gkCa/addQuizTestPost' && $accessPriData['GK_CA_Quizz']->add==true) {
                return $next($request);
            }elseif ($request->route()->uri=='admin/gkCa/editQuizTest/{id}' && $accessPriData['GK_CA_Quizz']->edit==true) {
                return $next($request);
            }elseif ($request->route()->uri=='admin/gkCa/deleteQuizTest' && $accessPriData['GK_CA_Quizz']->delete==true || $request->route()->uri=='admin/gkCa/statusQuizTest' && $accessPriData['GK_CA_Quizz']->status==true) {
                return $next($request);
            }elseif ($request->route()->uri=='admin/gkCa/quizTest/appearedStudents/{id}' || $request->route()->uri=='admin/gkCa/quizTest/appearedStudents' && $accessPriData['GK_CA_Quizz']->students==true) {
                return $next($request);
            }elseif ($request->route()->uri=='admin/gkCa/quiz/viewUserTest/{id}' && $accessPriData['GK_CA_Quizz']->students==true) {
                return $next($request);
            }

            //NEWS AND ARTICLE
            if ($request->route()->uri=='admin/gkCa/articleNews' || $request->route()->uri=='admin/gkCa/viewArticleNews/{id}' && $accessPriData['Article_News']->view==true) {
                return $next($request);
            }elseif ($request->route()->uri=='admin/gkCa/addArticleNews' && $accessPriData['Article_News']->add==true) {
                return $next($request);
            }elseif ($request->route()->uri=='admin/gkCa/addArticleNewsPost' && $accessPriData['Article_News']->add==true) {
                return $next($request);
            }elseif ($request->route()->uri=='admin/gkCa/editArticleNews/{id}' || $request->route()->uri=='admin/gkCa/addArticleNewsPost' && $accessPriData['Article_News']->edit==true) {
                return $next($request);
            }elseif ($request->route()->uri=='admin/gkCa/deleteArticleNews' && $accessPriData['Article_News']->delete==true || $request->route()->uri=='admin/gkCa/statusArticleNews' && $accessPriData['Article_News']->status==true) {
                return $next($request);
            }

            //BLOG CATEGORY
            if ($request->route()->uri=='admin/blogCategory' && $accessPriData['Blog_Categories']->view==true) {
                return $next($request);
            }elseif ($request->route()->uri=='admin/addBlogCategory' && $accessPriData['Blog_Categories']->add==true) {
                return $next($request);
            }elseif ($request->route()->uri=='admin/editBlogCategory' && $accessPriData['Blog_Categories']->edit==true) {
                return $next($request);
            }elseif ($request->route()->uri=='admin/deleteBlogCategory' && $accessPriData['Blog_Categories']->delete==true || $request->route()->uri=='admin/statusBlogCategory' && $accessPriData['Blog_Categories']->status==true) {
                return $next($request);
            }
            //BLOG POST
            if ($request->route()->uri=='admin/blog' || $request->route()->uri=='admin/viewBlog/{id}' && $accessPriData['Blog_Post']->view==true) {
                return $next($request);
            }elseif ($request->route()->uri=='admin/addBlog' && $accessPriData['Blog_Post']->add==true) {
                return $next($request);
            }elseif ($request->route()->uri=='admin/editBlog/{id}' && $accessPriData['Blog_Post']->edit==true || in_array("POST", $request->route()->methods)==true && $request->route()->uri=='admin/addBlog' && $accessPriData['Blog_Post']->edit==true) {
                return $next($request);
            }elseif ($request->route()->uri=='admin/deleteBlog' && $accessPriData['Blog_Post']->delete==true || $request->route()->uri=='admin/statusBlog' && $accessPriData['Blog_Post']->status==true) {
                return $next($request);
            }

            //DOUBT SECTION
            if ($request->route()->uri=='admin/doubt' || $request->route()->uri=='admin/viewDoubt/{id}' && $accessPriData['Doubts']->view==true) {
                return $next($request);
            }elseif ($request->route()->uri=='admin/doubt/addAnswer' && $accessPriData['Doubts']->answer==true) {
                return $next($request);
            }elseif ($request->route()->uri=='admin/deleteDoubt' && $accessPriData['Doubts']->delete==true || $request->route()->uri=='admin/statusDoubt' && $accessPriData['Doubts']->status==true) {
                return $next($request);
            }
            
        }
        
        $error = "Access Prohibited";
        if (Auth::check()) {
            return redirect()->route('adminDashboard')->with('middleware_error',$error);
            die();    
        }else{
            return redirect()->back()->with('middleware_error',$error);
            die();
        }
    }
}
