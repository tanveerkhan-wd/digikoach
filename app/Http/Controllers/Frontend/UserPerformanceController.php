<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserAttempt;
use App\Models\ArticlesNews;
use App\Models\BlogPost;
use App\Models\Cms;
use App;

class UserPerformanceController extends Controller
{
    
	/*
    * FOR USER PERFORMANCE TAB
    */
    public function index(Request $request,$id)
    {

    	//Get GRAPH DATA for live test
        //Get GRAPH DATA for live test
        $testData = [];
        $getAllUserAttemData = UserAttempt::with('exam_desc','exam')->where('user_id',$id);
        $getAllAttemData = $getAllUserAttemData->whereHas('exam',function($exmTy){
                                $exmTy->where('exams_type','LIVE_TEST');
                                })->where('attempt_status','COMPLETED')->orderBy('attempted_on', 'desc')->take(5)->get();

        foreach ($getAllAttemData as $key => $value) {
            if (!empty($value->exam)) {
                $testData[$value->exam->exams_type]['user_percentage'][] = $value['user_percentage'] ?? 0;
                $testData[$value->exam->exams_type]['exam_name'][] = $value['exam_desc']['exam_name'] .'<br>'. date('d-M-Y', strtotime($value['attempted_on']));
            }
        }
        $getAllUserAttemData = UserAttempt::with('exam_desc','exam')->where('user_id',$id);
        $getAllAttemData = $getAllUserAttemData->whereHas('exam',function($exmTy){
                                $exmTy->where('exams_type','QUIZZES');
                                })->where('attempt_status','COMPLETED')->orderBy('attempted_on', 'desc')->take(5)->get();        
        foreach ($getAllAttemData as $key => $value) {
            if (!empty($value->exam)) {
                $testData[$value->exam->exams_type]['user_percentage'][] = $value['user_percentage'] ?? 0;
                $testData[$value->exam->exams_type]['exam_name'][] = $value['exam_desc']['exam_name'] .'<br>'. date('d-M-Y', strtotime($value['attempted_on']));
            }
        }
        $getAllUserAttemData = UserAttempt::with('exam_desc','exam')->where('user_id',$id);
        $getAllAttemData = $getAllUserAttemData->whereHas('exam',function($exmTy){
                                $exmTy->where('exams_type','PRACTICE_TEST');
                                })->where('attempt_status','COMPLETED')->orderBy('attempted_on', 'desc')->take(5)->get();        
        foreach ($getAllAttemData as $key => $value) {
            if (!empty($value->exam)) {
                $testData[$value->exam->exams_type]['user_percentage'][] = $value['user_percentage'] ?? 0;
                $testData[$value->exam->exams_type]['exam_name'][] = $value['exam_desc']['exam_name'] .'<br>'. date('d-M-Y', strtotime($value['attempted_on']));
            }
        }
        $getAllUserAttemData = UserAttempt::with('exam_desc','exam')->where('user_id',$id);
        $getAllAttemData = $getAllUserAttemData->whereHas('exam',function($exmTy){
                                $exmTy->where('exams_type','GK_CA');
                                })->where('attempt_status','COMPLETED')->orderBy('attempted_on', 'desc')->take(5)->get();        
        foreach ($getAllAttemData as $key => $value) {
            if (!empty($value->exam)) {
                $testData[$value->exam->exams_type]['user_percentage'][] = $value['user_percentage'] ?? 0;
                $testData[$value->exam->exams_type]['exam_name'][] = $value['exam_desc']['exam_name'] .'<br>'. date('d-M-Y', strtotime($value['attempted_on']));
            }
        }
      	return view('userPerformance.index')->with(['testData'=>$testData,'user_id'=>$id]);
    }


     /**
     * Used for Admin get liveTestPerformmanceChart
     * @return redirect to Admin->get liveTestPerformmanceChart
    */
    public function filterPerformmanceChart(Request $request)
    {
        $data =$request->all();
        $from = date('Y-m-d',strtotime($data['start_date']));
        $to = date('Y-m-d',strtotime($data['end_date']));
        //Get GRAPH DATA for live test
        $testData = [];
        $getAllUserAttemData = UserAttempt::with('exam_desc','exam')->whereBetween('attempted_on', [$from, $to])->where('user_id',$data['user_id'])->where('attempt_status','COMPLETED')->get();
        foreach ($getAllUserAttemData as $key => $value) {
            if (!empty($value->exam)) {
                $testData[$value->exam->exams_type]['user_percentage'][] = $value['user_percentage'] ?? 0;
                $testData[$value->exam->exams_type]['exam_name'][] = $value['exam_desc']['exam_name'] .'<br>'. date('d-M-Y', strtotime($value['attempted_on']));
            }
        }
        if (empty($testData)) {
            $gradata['percentage'] = [];
            $gradata['name'] = [];
        }else{
            if ($data['type']=='live') {
                $gradata['percentage'] = $testData['LIVE_TEST']['user_percentage'];
                $gradata['name'] = $testData['LIVE_TEST']['exam_name'];
            }elseif($data['type']=='quiz'){
                $gradata['percentage'] = $testData['QUIZZES']['user_percentage'];
                $gradata['name'] = $testData['QUIZZES']['exam_name'];
            }elseif($data['type']=='practice'){
                $gradata['percentage'] = $testData['PRACTICE_TEST']['user_percentage'];
                $gradata['name'] = $testData['PRACTICE_TEST']['exam_name'];
            }elseif($data['type']=='gk_ca'){
                $gradata['percentage'] = $testData['GK_CA']['user_percentage'];
                $gradata['name'] = $testData['GK_CA']['exam_name'];
            }
        }
        return $gradata;
    }


    /*
    * FOR gkCaArticle
    */
    public function gkCaArticle(Request $request,$id)
    {
        $local = $request->get('lang');
        App::setLocale($local);
        $article = ArticlesNews::with('desc')->where('articles_news_id',$id)->first();
        return view('otherPages.gkCaArticle',compact('article'));
    }


    /*
    * FOR blogData
    */
    public function blogData(Request $request,$id)
    {
        $local = $request->get('lang');
        App::setLocale($local);
        $blog = BlogPost::with('desc')->where('blog_post_id',$id)->first();
        return view('otherPages.blogPost',compact('blog'));
    }

    /*
    * FOR STATIC PAGE
    */
    public function staticPage(Request $request,$slug)
    {
        $local = $request->get('lang');
        App::setLocale($local);
        $data = Cms::with('desc')->where('slug',$slug)->first();
        return view('otherPages.staticPage',compact('data','slug'));
    }

    /*
    * FOR Share Url
    */
    public function redirect(Request $request,$redirect_type, $redirect_type_id)
    {
        $userAgent = $request->server('HTTP_USER_AGENT');
        if (strpos(strtolower( $userAgent ), 'iphone' ) !== false || strpos(strtolower( $userAgent ), 'android' ) !== false) {
            $url = env('DEEPLINK_URL_PREFIX') . $redirect_type . '/' . $redirect_type_id;
            return redirect()->away($url);
        }else if(strpos(strtolower( $userAgent ), 'android' ) !== false) {
            return redirect()->to('home');
        }
        return redirect()->to('home');
    }
}
