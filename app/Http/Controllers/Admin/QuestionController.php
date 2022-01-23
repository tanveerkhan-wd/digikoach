<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\FrontHelper;
use App\Models\CategoriesDesc;
use App\Models\Category;
use App\Models\Question;
use App\Models\QuestionDesc;
use App\Models\QuestionOption;
use App\Models\QuestionOptionDesc;
use App\Models\QuestionMedia;
use App\Models\UserSavedItem;
use App\Models\ImageMedia;
use Carbon\Carbon;
use Redirect;
use Config;
use Storage;
use File;
use Auth;

class QuestionController extends Controller
{
    /**
	 * Used for Admin questions
	 * @return redirect to Admin->questions
	 */
    public function index(Request $request)
    {	
        $arrMergeCatIDForQue = [];
        
    	$streams = Category::with('category_desc')->get()->toArray();
        $category = FrontHelper::buildtree($streams);
        //GIVE ACCESSS TO CATEGORY
        $accessPriData = session()->get('accessPriData');
        if (!empty($accessPriData) && Auth::user()->user_type==1) {
            $cateIdForQue[] = $accessPriData['Question_Bank_Live_Test']->categories==true ? $accessPriData['Question_Bank_Live_Test']->categories : []; 
            $cateIdForQue[] = $accessPriData['Question_Bank_Quizz_Test']->categories==true ? $accessPriData['Question_Bank_Quizz_Test']->categories : [];
            $cateIdForQue[] = $accessPriData['Question_Bank_Practice_Test']->categories==true ? $accessPriData['Question_Bank_Practice_Test']->categories : [];
            $cateIdForQue[] = $accessPriData['Question_Bank_GK_CA_Test']->categories==true ? $accessPriData['Question_Bank_GK_CA_Test']->categories : [];
            $cateIdForQue =  array_filter($cateIdForQue);
            $countCategoryIdForQue = count($cateIdForQue);
            if ($countCategoryIdForQue==1) {
                    $arrMergeCatIDForQue = $cateIdForQue[0];
            }elseif($countCategoryIdForQue==2){
                $arrMergeCatIDForQue = array_merge($cateIdForQue[0],$cateIdForQue[1]);
                $arrMergeCatIDForQue = array_unique($arrMergeCatIDForQue);
            }elseif($countCategoryIdForQue==3){
                $arrMergeCatIDForQue = array_merge($cateIdForQue[0],$cateIdForQue[1],$cateIdForQue[2]);
                $arrMergeCatIDForQue = array_unique($arrMergeCatIDForQue);
            }elseif($countCategoryIdForQue==4){
                $arrMergeCatIDForQue = array_merge($cateIdForQue[0],$cateIdForQue[1],$cateIdForQue[2],$cateIdForQue[3]);
                $arrMergeCatIDForQue = array_unique($arrMergeCatIDForQue);
            }
        }
        
        $getCategoryWithSubCat = FrontHelper::getCategoryWithSubCat($category,$arrMergeCatIDForQue);

        if (request()->ajax()) {
            return \View::make('admin.question.index')->with(['getCategoryWithSubCat'=>$getCategoryWithSubCat])->renderSections();
        }
    	return view('admin.question.index')->with(['getCategoryWithSubCat'=>$getCategoryWithSubCat]);
    }

    /**
	 * Used for Admin addQuestions
	 * @return redirect to Admin->addQuestions
	 */
    public function addQuestion(Request $request)
    {
        $accessPriData = session()->get('accessPriData');

        $getCatgory = [];
        if(!empty($accessPriData['Question_Bank_Live_Test']) && $accessPriData['Question_Bank_Live_Test']->view==true)
        {
            $getCatgory[] = $accessPriData['Question_Bank_Live_Test']->categories==true ? $accessPriData['Question_Bank_Live_Test']->categories :'' ;
        }

        if(!empty($accessPriData['Question_Bank_Quizz_Test']) && $accessPriData['Question_Bank_Quizz_Test']->view==true)
        {
            $getCatgory[] = $accessPriData['Question_Bank_Quizz_Test']->categories ? $accessPriData['Question_Bank_Quizz_Test']->categories : '';
        }

        if(!empty($accessPriData['Question_Bank_Practice_Test']) && $accessPriData['Question_Bank_Practice_Test']->view==true)
        {
            $getCatgory[] = $accessPriData['Question_Bank_Practice_Test']->categories ? $accessPriData['Question_Bank_Practice_Test']->categories : '';
        }

        $getAllCat = [];
        $getCatgory = array_filter($getCatgory);
        foreach ($getCatgory as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $getAllCat[] = $value1;
            }
        }
        if (Auth::user()->user_type==0) {
           $parent_category = Category::with('category_desc')->where('status',1)->where('parent_category',0)->get(); 
        }else{
    	   $parent_category = Category::with('category_desc')->whereIn('category_id',$getAllCat)->where('status',1)->where('parent_category',0)->get();
        }

        //ACCESS PRIVILEGE
        $questionType = [];
        if(!empty($accessPriData['Question_Bank_Live_Test']) && $accessPriData['Question_Bank_Live_Test']->view==true || Auth::user()->user_type==0){
            $questionType['LIVE_TEST'] =  'Live Test';
        }
        if(!empty($accessPriData['Question_Bank_Quizz_Test']) && $accessPriData['Question_Bank_Quizz_Test']->view==true || Auth::user()->user_type==0){
            $questionType['QUIZZES'] = 'Quizzes';
        }
        if(!empty($accessPriData['Question_Bank_Practice_Test']) && $accessPriData['Question_Bank_Practice_Test']->view==true || Auth::user()->user_type==0){
            $questionType['PRACTICE_TEST'] = 'Practice Test';
        }
        if(!empty($accessPriData['Question_Bank_GK_CA_Test']) && $accessPriData['Question_Bank_GK_CA_Test']->view==true || Auth::user()->user_type==0){
            $questionType['GK_CA'] = 'Gk & Ca';
        }
    	if (request()->ajax()) {
            return \View::make('admin.question.addQuestion')->with(['questionType'=>$questionType,'parent_category'=>$parent_category])->renderSections();
        }
    	return view('admin.question.addQuestion')->with(['questionType'=>$questionType,'parent_category'=>$parent_category]);
    }


    /**
	 * Used for Admin select nth level category
	 * @return redirect to Admin->addQuestions
	 */
    public function getCategoryData(Request $request)
    {
    	$data = $request->all();
        $subCate = Category::with('category_desc')->where('parent_category',$data['cid'])->get();

        if ($subCate->isNotEmpty()) {
            $response['status'] = true;
            $response['data'] = $subCate;
        }
        else if($subCate->isEmpty()){
            $response['data'] = 'not_found';
        }
        else{
        	$response['status'] = false;
            $response['message'] = "Something Went Wrong";
        }

        return response()->json($response);    
    }

    /**
     * Used for Admin add ckeditorUpload
     * @return redirect to Admin->add ckeditorUpload
     */
    public function upload(Request $request)
    {
        if($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName.'_'.time().'.'.$extension;
            $request->file('upload')->move(public_path('/ckeditor_image/'), $fileName);
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('public/ckeditor_image/'.$fileName); 
            $msg = 'Image uploaded successfully'; 
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
            @header('Content-type: text/html; charset=utf-8'); 
            echo $response;
        }

    }

    /**
     * Used for Admin add uploadQuestionImage
     * @return redirect to Admin->add uploadQuestionImage
     */
    public function uploadQuestionImage(Request $request)
    {
        $addQuesMedia = [];
        $input = $request->all();
        $type = $input['type'];
        $lang_code = $input['lang_code'];
        $images = !empty($request->file('images'))?$request->file('images'):[];
        foreach ($images as $key=> $file) {
            $gen_rand = rand(100,99999).time();
            $image_path = $file;
            $extension = $image_path->getClientOriginalExtension();
            Storage::disk('public')->put(Config::get('siteglobal.images_dirs.QUESTIONS').'/'.$gen_rand.'.'.$extension,  File::get($image_path));
            $storeData = new QuestionMedia;
            $storeData->media_file = $gen_rand.'.'.$extension;
            $storeData->lang_code = $lang_code;
            $storeData->media_int_type = $type;
            $storeData->save();
            $storeData->directory = Config::get('siteglobal.images_dirs.QUESTIONS');
            $addQuesMedia[] = $storeData;
        }
        if (!empty($addQuesMedia)) {
            $response['status'] = true;
            $response['message'] ="Image Added";
            $response['data'] =$addQuesMedia;
            
        }else{
            $response['status'] = false;
            $response['message'] ="Something Went Wrong";
        }
        return response()->json($response);          
    }
    
    public function queImageRemove(Request $request,QuestionMedia $id) {
        if ($id->media_file) {
            Storage::disk('public')->delete(Config::get('siteglobal.images_dirs.QUESTIONS').'/'.$id->media_file);
        }
        $id->delete();
        return true;
    }

    /**
	 * Used for Admin add addQuestionPost
 	 * @return redirect to Admin->add addQuestionPost
	 */
    public function addQuestionPost(Request $request)
    {
    	$response = [];
    	$input = $request->all();
        $addQuesMedia = [];
        $addData = [];
        $editData = [];
        $editOpt = [];
        $editOptDec = [];
        try
        {
            //UPLOAD BULK DATA
            $bulk_upload = isset($input['bulk_upload']) && $input['bulk_upload']=='yes' ? 'YES':false;
            $bulk_upload_add = isset($input['bulk_upload_add']) && $input['bulk_upload_add']=='yes' ? 'YES':false;
            if ($bulk_upload_add || $bulk_upload) {
                for ($bulk_i=0; $bulk_i < count($input['bulk_question_en']); $bulk_i++) { 
                    //COUNT OPTINS
                    
                    for ($optLoop=0; $optLoop < 5 ; $optLoop++) { 
                        $aGetOpt['en'][$optLoop] = !empty($input['bulk_opt_en'][$optLoop+1]) && isset($input['bulk_opt_en'][$optLoop+1][$bulk_i]) ? $optLoop+1: false;
                        $aGetOpt['hi'][$optLoop] = !empty($input['bulk_opt_hi'][$optLoop+1]) && isset($input['bulk_opt_hi'][$optLoop+1][$bulk_i]) ?  $optLoop+1: false;
                        $aGetOpt['me'][$optLoop] = isset($input['option_media']) && isset($input['option_media'][$bulk_i][$optLoop+1]) ?  $optLoop+1: false;
                    }
                    $cntOptioEn = 0;
                    $cntOptioHi = 0;
                    $cntOptioMe =0;
                    foreach ($aGetOpt['en'] as $go_value) {
                        if ($go_value) {
                            $cntOptioEn = $go_value;
                        }
                    }
                    foreach ($aGetOpt['hi'] as $go_value1) {
                        if ($go_value1) {
                            $cntOptioHi = $go_value1;
                        }
                    }
                    foreach ($aGetOpt['me'] as $go_value2) {
                        if ($go_value2) {
                            $cntOptioMe = $go_value2;
                        }
                    }
                    $getCountOption = 0;
                    if ($cntOptioEn >= $cntOptioHi && $cntOptioEn >=$cntOptioMe ) {
                        $getCountOption = $cntOptioEn;
                    }elseif ($cntOptioHi >= $cntOptioEn && $cntOptioHi >=$cntOptioMe ) {
                        $getCountOption = $cntOptioHi;
                    }elseif ($cntOptioMe >= $cntOptioHi && $cntOptioMe >=$cntOptioEn ) {
                        $getCountOption = $cntOptioMe;
                    }
                    
                    for ($i=0; $i <2 ; $i++) { 
                        //VALIDATION FOR OPTION ORDER
                        if($input['bulk_opt_order'][$i+1][$bulk_i]==null) {
                            $response['status'] = false;
                            $response['message'] ="Please Fill Option Order & Minimum 2 option required";
                        }
                    }
                    //VALIDATION FOR OPTIONS
                    for ($opt=0; $opt < $getCountOption ; $opt++) { 
                        if ($input['bulk_opt_en'][$opt+1][$bulk_i]==null && !isset($input['opt_me_en'][$bulk_i]) && !isset($input['opt_me_en'][$bulk_i][$opt+1]) || $input['bulk_opt_hi'][$opt+1][$bulk_i]==null && !isset($input['opt_me_hi'][$bulk_i]) && !isset($input['opt_me_hi'][$bulk_i][$opt+1])) {
                            $response['status'] = false;
                            $response['message'] ="Please Fill Option Field ";
                        }
                    }
                    //VALIDATION FOR MARKS AND RIGHT OPTIONS
                    if ($input['bulk_marks'][$bulk_i]==null || $input['bulk_right_opt'][$bulk_i]==null) {
                        $response['status'] = false;
                        $response['message'] ="Please Fill Marks and right option";
                    }
                    if ($getCountOption==0) {
                        $response['status'] = false;
                        $response['message'] ="Please Add minimum one option";
                    }

                    if ($input['bulk_solution_en'][$bulk_i]==null && !isset($input['sol_me_en'][$bulk_i]) || $input['bulk_solution_hi'][$bulk_i]==null && !isset($input['sol_me_hi'][$bulk_i])) {
                        $response['status'] = false;
                        $response['message'] ="Please Fill Solution";   
                    }
                    if ($input['bulk_question_en'][$bulk_i]==null && !isset($input['que_me_en'][$bulk_i]) || $input['bulk_question_hi'][$bulk_i]==null && !isset($input['que_me_hi'][$bulk_i])) {
                        $response['status'] = false;
                        $response['message'] ="Please Fill Questions";   
                    }
                    //VALIDATION FOR ALL DATA
                    if ($input['bulk_question_en'][$bulk_i]==null && $input['bulk_question_hi'][$bulk_i]==null && $input['bulk_solution_en'][$bulk_i]==null && $input['bulk_solution_hi'][$bulk_i]==null && !isset($input['question_media']) && $input['bulk_marks'][$bulk_i]==null && $input['bulk_right_opt'][$bulk_i]==null) 
                    {
                        $response['status'] = false;
                        $response['message'] ="You Can Not Select Empty Row";
                    }
                    if (!empty($response)) {
                        return response()->json($response);
                    }
                }
                
                $desc = false;
                $media = false;
                for ($bulk_i=0; $bulk_i < count($input['bulk_question_en']); $bulk_i++) {
                    //COUNT OPTINS
                    for ($optLoop=0; $optLoop < 5 ; $optLoop++) { 
                        $aGetOpt['en'][$optLoop] = !empty($input['bulk_opt_en'][$optLoop+1]) && isset($input['bulk_opt_en'][$optLoop+1][$bulk_i]) ? $optLoop+1: false;
                        $aGetOpt['hi'][$optLoop] = !empty($input['bulk_opt_hi'][$optLoop+1]) && isset($input['bulk_opt_hi'][$optLoop+1][$bulk_i]) ?  $optLoop+1: false;
                        $aGetOpt['me'][$optLoop] = isset($input['option_media']) && isset($input['option_media'][$bulk_i][$optLoop+1]) ?  $optLoop+1: false;
                    }
                    $cntOptioEn = 0;
                    $cntOptioHi = 0;
                    $cntOptioMe =0;
                    foreach ($aGetOpt['en'] as $go_value) {
                        if ($go_value) {
                            $cntOptioEn = $go_value;
                        }
                    }
                    foreach ($aGetOpt['hi'] as $go_value1) {
                        if ($go_value1) {
                            $cntOptioHi = $go_value1;
                        }
                    }
                    foreach ($aGetOpt['me'] as $go_value2) {
                        if ($go_value2) {
                            $cntOptioMe = $go_value2;
                        }
                    }
                    $getCountOption = 0;
                    if ($cntOptioEn >= $cntOptioHi && $cntOptioEn >=$cntOptioMe ) {
                        $getCountOption = $cntOptioEn;
                    }elseif ($cntOptioHi >= $cntOptioEn && $cntOptioHi >=$cntOptioMe ) {
                        $getCountOption = $cntOptioHi;
                    }elseif ($cntOptioMe >= $cntOptioHi && $cntOptioMe >=$cntOptioEn ) {
                        $getCountOption = $cntOptioMe;
                    }

                    if (!isset($input['added_question_id'][$bulk_i]) ) {
                        $que = new Question;
                        $que->category_id = $input['category']? $input['category']:false;
                        $que->marks = $input['bulk_marks'][$bulk_i];
                        $que->question_type = $input['question_type'];
                        if($que->save()){

                            if (isset($input['question_media'][$bulk_i]) && !empty($input['question_media'][$bulk_i])) {
                                $questionmedia = QuestionMedia::whereIn('media_id', $input['question_media'][$bulk_i])->update(['media_int_id' =>  $que->questions_id ]);
                            }

                            for ($i=1; $i <= 2 ; $i++) { 
                                $addData[$i]['lang_code'] = $i==1 ? 'en' :'hi';
                                $addData[$i]['questions_id'] = $que->questions_id;
                                $addData[$i]['question_text'] = $i==1 ?  $input['bulk_question_en'][$bulk_i]:$input['bulk_question_hi'][$bulk_i] ;
                                $addData[$i]['solution_text'] = $i==1 ?  $input['bulk_solution_en'][$bulk_i]: $input['bulk_solution_hi'][$bulk_i];
                                $addData[$i]['created_at'] = now();
                            }
                            $ques_desc = QuestionDesc::insert($addData);
                            unset($addData);
                            if ($ques_desc) {
                                
                                for ($i=0; $i < $getCountOption ; $i++) 
                                { 
                                        
                                    $addOption = new QuestionOption;
                                    $addOption->questions_id = $que->questions_id;
                                    $addOption->option_order = !empty($input['bulk_opt_order'][$i+1][$bulk_i]) ? (int)$input['bulk_opt_order'][$i+1][$bulk_i] : 0;
                                    $addOption->is_valid  =  $input['bulk_right_opt'][$bulk_i]==$i+1 ? true : false;
                                    
                                    if($addOption->save()){

                                        //ADD OPTIONS MEDIA
                                    if (isset($input['option_media']) && isset($input['option_media'][$bulk_i][$i+1])) {
                                        $questionmedia = QuestionMedia::whereIn('media_id', $input['option_media'][$bulk_i][$i+1])->update(['media_int_id' =>$addOption->question_options_id]);
                                    }
                                        for ($j=1; $j <= 2 ; $j++) { 
                                            $aData[$j]['lang_code'] = $j==1 ? 'en' :'hi';
                                            $aData[$j]['question_options_id'] = $addOption->question_options_id;
                                            $aData[$j]['option_text'] = $j==1 ? $input['bulk_opt_en'][$i+1][$bulk_i] : $input['bulk_opt_hi'][$i+1][$bulk_i];
                                        
                                        }
                                        $desc = QuestionOptionDesc::insert($aData);

                                    }
                                }
                            }

                        }//END STORE QUESTION IF
                
                    }else if( isset($input['added_question_id'][$bulk_i]) && !empty($input['added_question_id'][$bulk_i]) ){

                        //UPDATE BULK EXISTING QUESTION 
                        $questionId = $input['added_question_id'][$bulk_i];
                        $question = Question::where('questions_id',$questionId)->first();
                        $question->marks = $input['bulk_marks'][$bulk_i];
                        if ($question->update()) {
                            
                            if (isset($input['question_media'][$bulk_i]) && !empty($input['question_media'][$bulk_i])) {
                                $questionmedia = QuestionMedia::whereIn('media_id', $input['question_media'][$bulk_i])->update(['media_int_id' =>  $question->questions_id ]);
                            }

                            $getQuestionDesc = QuestionDesc::where('questions_id',$questionId)->get();
                            foreach ($getQuestionDesc as $qd_key => $qd_value) {
                                $addData['question_text'] = $qd_value->lang_code=='en' ? $input['bulk_question_en'][$bulk_i] : $input['bulk_question_hi'][$bulk_i];
                                $addData['solution_text'] = $qd_value->lang_code=='en' ?  $input['bulk_solution_en'][$bulk_i]: $input['bulk_solution_hi'][$bulk_i];
                                $ques_desc = QuestionDesc::where('question_descs_id',$qd_value->question_descs_id)->update($addData);
                            }
                            unset($addData);

                            // UPDATE OPTIONS
                            QuestionOption::where('questions_id',$questionId)->delete();
                            for ($i=0; $i < $getCountOption ; $i++) 
                            { 

                                $addOption = new QuestionOption;
                                $addOption->questions_id = $questionId;
                                $addOption->option_order = !empty($input['bulk_opt_order'][$i+1][$bulk_i]) ? (int)$input['bulk_opt_order'][$i+1][$bulk_i] : 0;
                                $addOption->is_valid  =  $input['bulk_right_opt'][$bulk_i]==$i+1 ? true : false;
                                if($addOption->save()){

                                    //ADD OPTIONS MEDIA
                                    
                                    //UPDATE BULK QUESTION AND SOLUTION IMAGES
                                    if (isset($input['option_media']) && isset($input['option_media'][$bulk_i][$i+1])) {
                                        $questionmedia = QuestionMedia::whereIn('media_id', $input['option_media'][$bulk_i][$i+1])->update(['media_int_id' =>$addOption->question_options_id]);
                                    }
                                    //END----ADD QUESTION AND SOLUTION MEDIA//

                                    for ($j=1; $j <= 2 ; $j++) { 
                                        $aData[$j]['lang_code'] = $j==1 ? 'en' :'hi';
                                        $aData[$j]['question_options_id'] = $addOption->question_options_id;
                                        $aData[$j]['option_text'] = $j==1 ? $input['bulk_opt_en'][$i+1][$bulk_i] : $input['bulk_opt_hi'][$i+1][$bulk_i];
                                    
                                    }
                                    $desc = QuestionOptionDesc::insert($aData);
                                    
                                }

                            }//END FOR LOOP OPTION
                            
                                
                        }

                    }
                }//END FOR LOOP
                
                if ($desc || $media) {
                    $response['status'] = true;
                    $response['message'] ="Bulk Question Successfully Added";
                    $response['data'] =  isset($input['bulk_upload']) ? "bulk_upload" : 'bulk_upload_add';
                    $response['questions_id'] = isset($input['bulk_upload_add']) && isset($que) ? $que->questions_id : '';
                }else{
                    $response['status'] = false;
                    $response['message'] ="Something Went Wrong";
                }

                return response()->json($response);
                
            }//END BULK QUETION STORE IF
            //======================================================================//

            //UPLOAD USING CSV
            $upload_csv = isset($input['upload_csv']) && $input['upload_csv']=='yes' ? true : false;
            if ( $upload_csv ) 
            {
                $extension = '';
                if($request->hasFile('csv_file')){
                    $image_path = $request->file('csv_file');
                    $extension = $image_path->getClientOriginalExtension();
                }else{
                    $response['status'] = false;
                    $response['message'] ="There is no file exists!";
                    return response()->json($response);
                }
                if ($extension == 'csv') 
                {
                    $gen_rand = rand(100,99999).time();
                    $image_path = $request->file('csv_file');
                    $extension = $image_path->getClientOriginalExtension();

                    Storage::disk('public')->put(Config::get('siteglobal.images_dirs.CSV').'/'.$gen_rand.'.'.$extension,  File::get($image_path));
                    $csv_file = public_path('storage').'/'.Config::get('siteglobal.images_dirs.CSV').'/'.$gen_rand.'.'.$extension;
                    $file = fopen($csv_file,"r");

                    $importData_arr = array();
                    $i = 0;

                    while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
                        $num = count($filedata );
                        if($i == 0){
                            $i++;
                            continue; 
                        }
                        for ($c=0; $c < $num; $c++) {
                            $importData_arr[$i][] = $filedata [$c];
                        }
                        $i++;
                    }
                    fclose($file);
                    if (count($importData_arr)<=0) {
                        $response['status'] = false;
                        $response['message'] ="No Data Found";
                    }
                    
                    foreach($importData_arr as $importData){
                        /*=== Check for empty fields ===*/
                        if (empty(trim($importData[1])) && empty(trim($importData[2])) || empty(trim($importData[3])) && empty(trim($importData[4]))) {
                            $response['status'] = false;
                            $response['message'] ="Question Can not be empty!";
                        }
                        if (empty(trim($importData[5])) && empty(trim($importData[6])) || empty(trim($importData[7])) && empty(trim($importData[8]))) {
                            $response['status'] = false;
                            $response['message'] ="Solution Can not be empty!";
                        }
                        if (empty(trim($importData[10])) && empty(trim($importData[11])) || empty(trim($importData[12])) && empty(trim($importData[13]))) {
                            $response['status'] = false;
                            $response['message'] ="Option 1 Can not be empty!";
                        }
                        if (empty(trim($importData[15])) && empty(trim($importData[16])) || empty(trim($importData[17])) && empty(trim($importData[18]))) {
                            $response['status'] = false;
                            $response['message'] ="Option 2 Can not be empty, minimum 2 required!";
                        }
                        if (empty(trim($importData[14])) || empty(trim($importData[19])) ) {
                            $response['status'] = false;
                            $response['message'] ="Option Order Can not be empty, minimum 2 required!";
                        }
                        /*== /--END ==*/
                        
                        $checkIsNumeric = array_filter([$importData[9],$importData[14],$importData[19],$importData[24],$importData[29],$importData[34],$importData[35]]);

                        foreach ($checkIsNumeric as $testcase) { 
                            if (!is_numeric($testcase)) { 
                                $response['status'] = false;
                                $response['message'] ="Marks, Option Order, and Right Options only numeric field is allowed.";
                            } 
                        }
                        if (empty($importData[9]) || empty($importData[35])) {
                            $response['status'] = false;
                            $response['message'] ="Marks and Right Option value can not be empty.";
                        }
                        
                    }
                    if (!empty($response) && $response['status'] == false) {
                        Storage::disk('public')->delete(Config::get('siteglobal.images_dirs.CSV').'/'.$gen_rand.'.'.$extension);
                        return response()->json($response);
                    }
                    
                    foreach($importData_arr as $importData){
                        $que = new Question;
                        $que->category_id = $input['category'];
                        $que->marks = $importData[9];
                        $que->question_type = $input['question_type'];
                        if($que->save()){
                            //ADD QUESTION  MEDIA//
                            
                            $image_media_que_id_en = explode(",", $importData[2]);
                            $imageMedia = ImageMedia::whereIn('image_media_id',$image_media_que_id_en)->get()->toArray();
                            if (!empty($imageMedia)) {
                                foreach ($imageMedia as $key => $value) {
                                    $addQuesMedia[$key]['media_file'] = $value['file'];
                                    $addQuesMedia[$key]['media_int_id'] = $que->questions_id;
                                    $addQuesMedia[$key]['lang_code'] = 'en';
                                    $addQuesMedia[$key]['media_int_type'] = 'QUESTION';
                                }
                                QuestionMedia::insert($addQuesMedia);
                            }

                            $image_media_que_id_hi = explode(",", $importData[4]);
                            $imageMedia = ImageMedia::whereIn('image_media_id',$image_media_que_id_hi)->get()->toArray();
                            if (!empty($imageMedia)) {
                                foreach ($imageMedia as $key => $value) {
                                    $addQuesMedia[$key]['media_file'] = $value['file'];
                                    $addQuesMedia[$key]['media_int_id'] = $que->questions_id;
                                    $addQuesMedia[$key]['lang_code'] = 'hi';
                                    $addQuesMedia[$key]['media_int_type'] = 'QUESTION';
                                }
                                QuestionMedia::insert($addQuesMedia);
                            }
                            //ADD SOLUTION MEDIA//
                            $image_media_sol_id_en = explode(",", $importData[6]);
                            $imageMedia = ImageMedia::whereIn('image_media_id',$image_media_sol_id_en)->get()->toArray();
                            if (!empty($imageMedia)) {
                                foreach ($imageMedia as $key => $value) {
                                    $addQuesMedia[$key]['media_file'] = $value['file'];
                                    $addQuesMedia[$key]['media_int_id'] = $que->questions_id;
                                    $addQuesMedia[$key]['lang_code'] = 'en';
                                    $addQuesMedia[$key]['media_int_type'] = 'SOLUTION';
                                }
                                QuestionMedia::insert($addQuesMedia);
                            }
                            $image_media_sol_id_hi = explode(",", $importData[8]);
                            $imageMedia = ImageMedia::whereIn('image_media_id',$image_media_sol_id_hi)->get()->toArray();
                            if (!empty($imageMedia)) {
                                foreach ($imageMedia as $key => $value) {
                                    $addQuesMedia[$key]['media_file'] = $value['file'];
                                    $addQuesMedia[$key]['media_int_id'] = $que->questions_id;
                                    $addQuesMedia[$key]['lang_code'] = 'hi';
                                    $addQuesMedia[$key]['media_int_type'] = 'SOLUTION';
                                }
                                QuestionMedia::insert($addQuesMedia);
                            }
                            //END----ADD QUESTION AND SOLUTION MEDIA//

                            for ($i=1; $i <= 2 ; $i++) { 
                                $addData[$i]['lang_code'] = $i==1 ? 'en' :'hi';
                                $addData[$i]['questions_id'] = $que->questions_id;
                                $addData[$i]['question_text'] = $i==1 ? $importData[1] : $importData[3];
                                $addData[$i]['solution_text'] = $i==1 ? $importData[5] : $importData[7];
                            }
                            $ques_desc = QuestionDesc::insert($addData);   
                            if ($ques_desc) {
                                    $countOptioEn = [$importData[10],$importData[15],$importData[20],$importData[25],$importData[30]];
                                    $countOptioHi = [$importData[12],$importData[17],$importData[22],$importData[27],$importData[32]];
                                    $countOrder =   [$importData[14],$importData[19],$importData[24],$importData[29],$importData[34]];
                                    $countOptioEn = array_filter($countOptioEn);
                                    $countOrder = array_filter($countOrder);
                                    $countOptioHi = array_filter($countOptioHi);
                                    $countAllOpt=[];
                                    $Nodatafound = false;
                                    if (empty($importData[10]) && empty($importData[11])) {
                                        $Nodatafound = true;
                                    }else{
                                        $countAllOpt[0]= 1;
                                    }
                                    if (empty($importData[15]) && empty($importData[16])) {
                                        $Nodatafound = true;
                                    }else{
                                        $countAllOpt[1]= 1;
                                    }
                                    if (empty($importData[20]) && empty($importData[21])) {
                                        
                                    }else{
                                        $countAllOpt[2]= 1;
                                    }
                                    if (empty($importData[25]) && empty($importData[26])) {
                                        
                                    }else{
                                        $countAllOpt[3]= 1;
                                    }
                                    if (empty($importData[30]) && empty($importData[31])) {
                                        
                                    }else{
                                        $countAllOpt[4]= 1;
                                    }

                                    if (!empty($countAllOpt)/*count($countOptioHi) == count($countOptioEn) && count($countOrder)==count($countOptioEn) && count($countOrder)==count($countOptioHi)*/) 
                                    {
                                        
                                        for ($i=0; $i < count($countAllOpt) ; $i++) { 
                                            
                                            $addOption = new QuestionOption;
                                            $addOption->questions_id = $que->questions_id;
                                            $addOption->option_order = !empty($countOrder) ? $countOrder[$i] : '';
                                            $addOption->is_valid  =  $importData[35]==$i+1 ? true : false;

                                            if($addOption->save()){
                                                //ADD OPTIONS MEDIA
                                                if ($i==0) {
                                                    $image_media_opt_1_en = explode(
                                                        ",", $importData[11]);
                                                    $imageMedia = ImageMedia::whereIn('image_media_id',$image_media_opt_1_en)->get()->toArray();
                                                
                                                    if (!empty($imageMedia)) {
                                                        foreach ($imageMedia as $key => $value) {
                                                            $addQuesMedia[$key]['media_file'] = $value['file'];
                                                            $addQuesMedia[$key]['media_int_id'] = $addOption->question_options_id;
                                                            $addQuesMedia[$key]['lang_code'] = 'en';
                                                            $addQuesMedia[$key]['media_int_type'] = 'OPTION';
                                                        }
                                                        QuestionMedia::insert($addQuesMedia);
                                                    }

                                                    $image_media_opt_1_hi = explode(
                                                        ",", $importData[13]);
                                                    $imageMedia = ImageMedia::whereIn('image_media_id',$image_media_opt_1_hi)->get()->toArray();
                                                    if (!empty($imageMedia)) {
                                                        foreach ($imageMedia as $key => $value) {
                                                            $addQuesMedia[$key]['media_file'] = $value['file'];
                                                            $addQuesMedia[$key]['media_int_id'] = $addOption->question_options_id;
                                                            $addQuesMedia[$key]['lang_code'] = 'hi';
                                                            $addQuesMedia[$key]['media_int_type'] = 'OPTION';
                                                        }
                                                        QuestionMedia::insert($addQuesMedia);
                                                    }
                                                }


                                                //ADD OPTIONS MEDIA2 
                                                if ($i==1) {
                                                    $image_media_opt_2_en = explode(
                                                        ",", $importData[16]);
                                                    $imageMedia = ImageMedia::whereIn('image_media_id',$image_media_opt_2_en)->get()->toArray();
                                                    if (!empty($imageMedia)) {
                                                        foreach ($imageMedia as $key => $value) {
                                                            $addQuesMedia[$key]['media_file'] = $value['file'];
                                                            $addQuesMedia[$key]['media_int_id'] = $addOption->question_options_id;
                                                            $addQuesMedia[$key]['lang_code'] = 'en';
                                                            $addQuesMedia[$key]['media_int_type'] = 'OPTION';
                                                        }
                                                        QuestionMedia::insert($addQuesMedia);
                                                    }

                                                    $image_media_opt_2_hi = explode(
                                                        ",", $importData[18]);
                                                    $imageMedia = ImageMedia::whereIn('image_media_id',$image_media_opt_2_hi)->get()->toArray();
                                                    if (!empty($imageMedia)) {
                                                        foreach ($imageMedia as $key => $value) {
                                                            $addQuesMedia[$key]['media_file'] = $value['file'];
                                                            $addQuesMedia[$key]['media_int_id'] = $addOption->question_options_id;
                                                            $addQuesMedia[$key]['lang_code'] = 'hi';
                                                            $addQuesMedia[$key]['media_int_type'] = 'OPTION';
                                                        }
                                                        QuestionMedia::insert($addQuesMedia);
                                                    }
                                                }

                                                //ADD OPTIONS MEDIA3
                                                if ($i==2) {
                                                    $image_media_opt_3_en = explode(
                                                        ",", $importData[21]);
                                                    $imageMedia = ImageMedia::whereIn('image_media_id',$image_media_opt_3_en)->get()->toArray();
                                                    if (!empty($imageMedia)) {
                                                        foreach ($imageMedia as $key => $value) {
                                                            $addQuesMedia[$key]['media_file'] = $value['file'];
                                                            $addQuesMedia[$key]['media_int_id'] = $addOption->question_options_id;
                                                            $addQuesMedia[$key]['lang_code'] = 'en';
                                                            $addQuesMedia[$key]['media_int_type'] = 'OPTION';
                                                        }
                                                        QuestionMedia::insert($addQuesMedia);
                                                    }

                                                    $image_media_opt_3_hi = explode(
                                                        ",", $importData[23]);
                                                    $imageMedia = ImageMedia::whereIn('image_media_id',$image_media_opt_3_hi)->get()->toArray();
                                                    if (!empty($imageMedia)) {
                                                        foreach ($imageMedia as $key => $value) {
                                                            $addQuesMedia[$key]['media_file'] = $value['file'];
                                                            $addQuesMedia[$key]['media_int_id'] = $addOption->question_options_id;
                                                            $addQuesMedia[$key]['lang_code'] = 'hi';
                                                            $addQuesMedia[$key]['media_int_type'] = 'OPTION';
                                                        }
                                                        QuestionMedia::insert($addQuesMedia);
                                                    }
                                                }

                                                //ADD OPTIONS MEDIA4
                                                if ($i==3) {
                                                    $image_media_opt_4_en = explode(
                                                        ",", $importData[26]);
                                                    $imageMedia = ImageMedia::whereIn('image_media_id',$image_media_opt_4_en)->get()->toArray();
                                                    if (!empty($imageMedia)) {
                                                        foreach ($imageMedia as $key => $value) {
                                                            $addQuesMedia[$key]['media_file'] = $value['file'];
                                                            $addQuesMedia[$key]['media_int_id'] = $addOption->question_options_id;
                                                            $addQuesMedia[$key]['lang_code'] = 'en';
                                                            $addQuesMedia[$key]['media_int_type'] = 'OPTION';
                                                        }
                                                        QuestionMedia::insert($addQuesMedia);
                                                    }

                                                    $image_media_opt_4_hi = explode(
                                                        ",", $importData[28]);
                                                    $imageMedia = ImageMedia::whereIn('image_media_id',$image_media_opt_4_hi)->get()->toArray();
                                                    if (!empty($imageMedia)) {
                                                        foreach ($imageMedia as $key => $value) {
                                                            $addQuesMedia[$key]['media_file'] = $value['file'];
                                                            $addQuesMedia[$key]['media_int_id'] = $addOption->question_options_id;
                                                            $addQuesMedia[$key]['lang_code'] = 'hi';
                                                            $addQuesMedia[$key]['media_int_type'] = 'OPTION';
                                                        }
                                                        QuestionMedia::insert($addQuesMedia);
                                                    }
                                                }

                                                //ADD OPTIONS MEDIA5
                                                if ($i==4) {
                                                    $image_media_opt_5_en = explode(
                                                        ",", $importData[31]);
                                                    $imageMedia = ImageMedia::whereIn('image_media_id',$image_media_opt_5_en)->get()->toArray();
                                                    if (!empty($imageMedia)) {
                                                        foreach ($imageMedia as $key => $value) {
                                                            $addQuesMedia[$key]['media_file'] = $value['file'];
                                                            $addQuesMedia[$key]['media_int_id'] = $addOption->question_options_id;
                                                            $addQuesMedia[$key]['lang_code'] = 'en';
                                                            $addQuesMedia[$key]['media_int_type'] = 'OPTION';
                                                        }
                                                        QuestionMedia::insert($addQuesMedia);
                                                    }

                                                    $image_media_opt_5_hi = explode(
                                                        ",", $importData[33]);
                                                    $imageMedia = ImageMedia::whereIn('image_media_id',$image_media_opt_5_hi)->get()->toArray();
                                                    if (!empty($imageMedia)) {
                                                        foreach ($imageMedia as $key => $value) {
                                                            $addQuesMedia[$key]['media_file'] = $value['file'];
                                                            $addQuesMedia[$key]['media_int_id'] = $addOption->question_options_id;
                                                            $addQuesMedia[$key]['lang_code'] = 'hi';
                                                            $addQuesMedia[$key]['media_int_type'] = 'OPTION';
                                                        }
                                                        QuestionMedia::insert($addQuesMedia);
                                                    }
                                                }
                                                
                                                if (isset($countOptioEn) && isset($countOptioEn[$i])) {
                                                    $valEn = $countOptioEn[$i];
                                                }else{
                                                    $valEn = '';
                                                }
                                                if (isset($countOptioHi) && isset($countOptioHi[$i])) {
                                                    $valHi = $countOptioHi[$i];
                                                }else{
                                                    $valHi = '';
                                                }
                                                for ($j=1; $j <= 2 ; $j++) { 
                                                    $aData[$j]['lang_code'] = $j==1 ? 'en' :'hi';
                                                    $aData[$j]['question_options_id'] = $addOption->question_options_id;
                                                    $aData[$j]['option_text'] = $j==1 ? $valEn : $valHi;
                                                }
                                                
                                                $desc = QuestionOptionDesc::insert($aData);
                                                if ($desc) {
                                                    $response['status'] = true;
                                                    $response['message'] ="Question Successfully Added";
                                                    $response['data'] = 'save';
                                                }
                                            }
                                        }

                                    }else{
                                        $response['status'] = false;
                                        $response['message'] ="Please Check Your 'CSV' file!";
                                        return response()->json($response);
                                    }

                            }
                        }

                    }
                    return response()->json($response);
                }else{
                    $response['status'] = false;
                    $response['message'] ="Please upload valid 'CSV' file!";
                    return response()->json($response);
                }
            }
            //--END-- UPLOAD USING CSV
            
            //======================================================================//
            //ADD MANUALLY
            if($bulk_upload_add==false && $bulk_upload==false){
             
                $inputOptionenhi = array_merge($input['option_en'],$input['option_hi']);
                if (!empty($inputOptionenhi) && count($inputOptionenhi)<4) {
                    $response['status'] = false;
                    $response['message'] ="Please add 2 or more options ";
                    return response()->json($response); 
                }

                if (!isset($input['correct_option'])) {
                    $response['status'] = false;
                    $response['message'] ="Please select correct option";
                    return response()->json($response);  
                }       
                
                foreach ($input['option_order'] as $key => $value) {
                    if (empty($value) || $value==null) {
                        $response['status'] = false;
                        $response['message'] ="Please Fill options order field";
                        return response()->json($response);          
                    }
                }

                $aOption_order = array_count_values($input['option_order']);
                $aOptionOrderResult = array();
                foreach($aOption_order as $key=>$val){
                    if($val >1){
                      $aOptionOrderResult[] = $key;
                    }
                }
                if (isset($aOptionOrderResult) && !empty($aOptionOrderResult)) {
                    $response['status'] = false;
                    $response['message'] ="Option order is not valid";
                    return response()->json($response);
                }
                //QUESTION ADD SOLUTION AUTHENTICATE
                if (empty($input['question_en']) && !isset($input['que_me_en']) || empty($input['question_hi']) && !isset($input['que_me_hi'])){               
                    $response['status'] = false;
                    $response['message'] ="Please Fill Question field";
                    return response()->json($response);
                }
                if(empty($input['solution_en']) && !isset($input['sol_me_en']) || empty($input['solution_hi']) && !isset($input['sol_me_hi'])){
                    $response['status'] = false;
                    $response['message'] ="Please Fill Solution field";
                    return response()->json($response);   
                }
                $cntOpt = isset($input['option_en']) ? count($input['option_en']) : 0;
                for ($j=0; $j <$cntOpt ; $j++) {
                    if ($input['option_en'][$j]==null && !isset($input['opt_me_en'][$j+1]) || $input['option_hi'][$j]==null && !isset($input['opt_me_hi'][$j+1])) {
                        $response['status'] = false;
                        $response['message'] ="Please Fill options field ";
                        return response()->json($response);
                    }
                }
                //END CHECK OPTION ORDER
                //EDIT QUESTION AND OPTIONS
                if(!empty($input['pkCat']) && $input['pkCat'] != null){
                    
                    $question = Question::where('questions_id',$input['pkCat'])->first();
                    $question->category_id = $input['category']?$input['category']:false;
                    $question->marks = $input['marks'];
                    $question->question_type = $input['question_type'];
                    if ($question->update()) {

                        //UPDATE MEDIA
                        if (isset($input['question_media']) && !empty($input['question_media'])) {
                            $questionmedia = QuestionMedia::whereIn('media_id', $input['question_media'])->update(['media_int_id' =>  $question->questions_id ]);
                        }
                        $getQuestionDesc = QuestionDesc::where('questions_id',$input['pkCat'])->get();
                        foreach ($getQuestionDesc as $qd_key => $qd_value) {
                            
                                $addData['lang_code'] = $qd_value->lang_code;
                                $addData['questions_id'] = $qd_value->questions_id;
                                $addData['question_text'] = $qd_value->lang_code=='en' ? $input['question_en'] : $input['question_hi'];
                                $addData['solution_text'] = $qd_value->lang_code=='en' ? $input['solution_en'] : $input['solution_hi'];
                                $ques_desc = QuestionDesc::where('question_descs_id',$qd_value->question_descs_id)->update($addData);
                        }

                        //EDIT QUESTION OPTION DATA
                        if ($ques_desc) {
                            $getOptions = QuestionOption::where('questions_id',$input['pkCat'])->delete();
                            //ADD QUESTION OPTION DATA
                                $countOpt_en = count($input['option_en']);
                                for ($i=0; $i < $countOpt_en ; $i++) { 
                                    $addOption = new QuestionOption;
                                    $addOption->questions_id = $input['pkCat'];
                                    $addOption->option_order = !empty($input['option_order'][$i]) ? $input['option_order'][$i] : '';
                                    
                                    $addOption->is_valid  =  $input['correct_option']==$i+1 ? true : false;

                                    if($addOption->save()){

                                        //UPDATE MEDIA
                                        if (isset($input['option_media']) && isset($input['option_media'][$i+1])) {
                                            $questionmedia = QuestionMedia::whereIn('media_id', $input['option_media'][$i+1])->update(['media_int_id' =>$addOption->question_options_id]);
                                        }

                                        if (!empty($input['question_option_ids'])) {
                                            foreach ($input['question_option_ids'] as $key => $value) {

                                                if ($key==$i && isset($value['en'])) {
                                                    foreach($value['en'] as $dd=> $value1) {
                                                        $addQuesMedia[$dd]['media_file'] = $value1;
                                                        $addQuesMedia[$dd]['media_int_id'] = $addOption->question_options_id;
                                                        $addQuesMedia[$dd]['lang_code'] = 'en';
                                                        $addQuesMedia[$dd]['media_int_type'] = 'OPTION';
                                                    }
                                                    QuestionMedia::insert($addQuesMedia);
                                                }
                                                if ($key==$i && isset($value['hi'])) {
                                                    foreach($value['hi'] as $dd1=> $value1) {
                                                        $addQuesMedia[$dd1]['media_file'] = $value1;
                                                        $addQuesMedia[$dd1]['media_int_id'] = $addOption->question_options_id;
                                                        $addQuesMedia[$dd1]['lang_code'] = 'hi';
                                                        $addQuesMedia[$dd1]['media_int_type'] = 'OPTION';
                                                    }
                                                    QuestionMedia::insert($addQuesMedia);
                                                }

                                            }
                                        }

                                        for ($j=1; $j <= 2 ; $j++) { 
                                            $aData[$j]['lang_code'] = $j==1 ? 'en' :'hi';
                                            $aData[$j]['question_options_id'] = $addOption->question_options_id;
                                            $aData[$j]['option_text'] = $j==1 ? $input['option_en'][$i] : $input['option_hi'][$i];
                                        }
                                        $desc = QuestionOptionDesc::insert($aData);
                                        if ($desc) {
                                            $response['status'] = true;
                                            $response['message'] ="Question Successfully Updated";
                                            $response['data'] = 'save';
                                        }
                                }
                            }
                            
                        }
                    }

            	}
                else{
                    
                    //ADD QUESTION AND OPTIONS
                    //CHECK IF QUESTION ALREADY EXTS OR NOT
                    if (empty($input['question_en']) || empty($input['question_hi'])) {
                        $checkPre = 0;        
                    }else{
                        $checkPre = QuestionDesc::where('question_text',$input['question_en'])->orWhere('question_text',$input
                            ['question_hi'])->count();
                    }
                    if ($checkPre >0 ) {
                    	$response['status'] = false;
                      	$response['message'] ="Question already exists";
                    }else{
                    	$question = new Question;
                    	$question->category_id = $input['category'] ? $input['category']:false;
                    	$question->marks = $input['marks'];
                    	$question->question_type = $input['question_type'];
                    	if ($question->save()) {
                    		for ($i=1; $i <= 2 ; $i++) {
        	    	        	$addData[$i]['lang_code'] = $i==1 ? 'en' :'hi';
        	    	        	$addData[$i]['questions_id'] = $question->questions_id;
        	                    $addData[$i]['question_text'] = $i==1 ? $input['question_en'] : $input['question_hi'];
        	                    $addData[$i]['solution_text'] = $i==1 ? $input['solution_en'] : $input['solution_hi'];
                    		}
                    		$ques_desc = QuestionDesc::insert($addData);
                            if (isset($input['question_media']) && !empty($input['question_media'])) {
                                $questionmedia = QuestionMedia::whereIn('media_id', $input['question_media'])->update(['media_int_id' =>  $question->questions_id ]);
                            }
                            //ADD QUESTION OPTION DATA
                    		    $countOpt_en = count($input['option_en']);
                    			for ($i=0; $i < $countOpt_en ; $i++) { 
                    				$addOption = new QuestionOption;
        	            			$addOption->questions_id = $question->questions_id;
        	            			$addOption->option_order = !empty($input['option_order'][$i]) ? $input['option_order'][$i] : '';
        	            			
                                    $addOption->is_valid  =  $input['correct_option']==$i+1 ? true : false;

        	            			if($addOption->save()){
                                        //UPDATE QUESTION
                                        if (isset($input['option_media']) && isset($input['option_media'][$i+1])) {
                                            $questionmedia = QuestionMedia::whereIn('media_id', $input['option_media'][$i+1])->update(['media_int_id' =>$addOption->question_options_id]);
                                        }

        	            				for ($j=1; $j <= 2 ; $j++) { 
        				    	        	$aData[$j]['lang_code'] = $j==1 ? 'en' :'hi';
        				    	        	$aData[$j]['question_options_id'] = $addOption->question_options_id;
        				                    $aData[$j]['option_text'] = $j==1 ? $input['option_en'][$i] : $input['option_hi'][$i];
        				            	}
        				            	$desc = QuestionOptionDesc::insert($aData);
                
        	            			}
                    			}
        		        		if ($desc) {
                                    $response['status'] = true;
                  					$response['message'] ="Question Successfully Added";
                  					if (isset($input['save_and_add_new']) && !empty($input['save_and_add_new'])) {
                  						$response['data'] = 'add_new';
                  					}else{
                  						$response['data'] = 'save';
                  					}
        		        		}
                    		
                    	}

                    }

                }
            }    
        }catch (\Exception $e) {
            $response['status'] = false;
            $response['message'] = "Error:" . $e->getMessage();
        }

        return response()->json($response);
    }


    /**
	 * Used for Admin get Questions
 	 * @return redirect to Admin->get Questions listing
	*/
    public function getQuestions(Request $request)
    {
    	
      /**
       * Used for Admin get Questions Listing
       */
    	$data =$request->all();
	    
        $accessPriData = !empty(session()->get('accessPriData')) ? session()->get('accessPriData') :'' ;

	    $perpage = !empty( $data[ 'length' ] ) ? (int)$data[ 'length' ] : 10;
	      
	    $filter = isset( $data['search'] ) && is_string( $data['search'] ) ? $data['search'] : '';
	    
        $search_category = isset( $data['search_category'] ) ? $data['search_category'] :'';

	    $used_unused_que = isset( $data['question_used_unused'] ) ? $data['question_used_unused'] :'';
	    
        $question_type = isset( $data['question_type'] ) ? $data['question_type'] : '';

	    $status_type = isset( $data['status_type'] ) ? $data['status_type'] :'';

	    $sort_type = isset( $data['order'][0]['dir'] ) && is_string( $data['order'][0]['dir'] ) ? $data['order'][0]['dir'] : '';	

	    $sort_col =  isset($data['order'][0]['column']) ? $data['order'][0]['column'] :'';

	    $sort_field = isset($data['columns'][$sort_col]['data']) ? $data['columns'][$sort_col]['data'] :'';
	    
    	$aTable = Question::with('question_desc','category_desc','category');

        //ACCESS PRIVILEGE
        if($accessPriData){
            $getType = [];
            $getAcc = [];
            $streams = Category::with('category_desc')->get()->toArray();
            $category = FrontHelper::buildtree($streams);

            if(!empty($accessPriData['Question_Bank_Live_Test']) && $accessPriData['Question_Bank_Live_Test']->view==true)
            {
                $getType[] = 'LIVE_TEST';
                $getAcc['LIVE_TEST']['STATUS'] = $accessPriData['Question_Bank_Live_Test']->status==true ? true:false;
                $getAcc['LIVE_TEST']['DELETED'] = $accessPriData['Question_Bank_Live_Test']->delete==true ? true:false;
                $getAcc['LIVE_TEST']['EDIT'] = $accessPriData['Question_Bank_Live_Test']->edit==true ? true:false; 
                $cateIdForLiveQue = $accessPriData['Question_Bank_Live_Test']->categories==true ? $accessPriData['Question_Bank_Live_Test']->categories : [];
                $categoryIdForQue['LIVE_TEST'] = FrontHelper::getAllChilCategory($category,$cateIdForLiveQue);
                

            }

            if(!empty($accessPriData['Question_Bank_Quizz_Test']) && $accessPriData['Question_Bank_Quizz_Test']->view==true)
            {
                $getType[]= 'QUIZZES';
                $getAcc['QUIZZES']['STATUS'] = $accessPriData['Question_Bank_Quizz_Test']->status==true ? true:false;
                $getAcc['QUIZZES']['DELETED'] = $accessPriData['Question_Bank_Quizz_Test']->delete==true ? true:false;
                $getAcc['QUIZZES']['EDIT'] = $accessPriData['Question_Bank_Quizz_Test']->edit==true ? true:false;
                $cateIdForLiveQue = $accessPriData['Question_Bank_Quizz_Test']->categories==true ? $accessPriData['Question_Bank_Quizz_Test']->categories : [];
                $categoryIdForQue['QUIZZES'] = FrontHelper::getAllChilCategory($category,$cateIdForLiveQue);
            }

            if(!empty($accessPriData['Question_Bank_Practice_Test']) && $accessPriData['Question_Bank_Practice_Test']->view==true)
            {
                $getType[]= 'PRACTICE_TEST';
                $getAcc['PRACTICE_TEST']['STATUS'] = $accessPriData['Question_Bank_Practice_Test']->status==true ? true:false;
                $getAcc['PRACTICE_TEST']['DELETED'] = $accessPriData['Question_Bank_Practice_Test']->delete==true ? true:false;
                $getAcc['PRACTICE_TEST']['EDIT'] = $accessPriData['Question_Bank_Practice_Test']->edit==true ? true:false;
                $cateIdForLiveQue = $accessPriData['Question_Bank_Practice_Test']->categories==true ? $accessPriData['Question_Bank_Practice_Test']->categories : [];
                $categoryIdForQue['PRACTICE_TEST'] = FrontHelper::getAllChilCategory($category,$cateIdForLiveQue);
            }

            if(!empty($accessPriData['Question_Bank_GK_CA_Test']) && $accessPriData['Question_Bank_GK_CA_Test']->view==true)
            {
                $getType[]= 'GK_CA';
                $getAcc['GK_CA']['STATUS'] = $accessPriData['Question_Bank_GK_CA_Test']->status==true ? true:false;
                $getAcc['GK_CA']['DELETED'] = $accessPriData['Question_Bank_GK_CA_Test']->delete==true ? true:false;
                $getAcc['GK_CA']['EDIT'] = $accessPriData['Question_Bank_GK_CA_Test']->edit==true ? true:false;
                $cateIdForLiveQue = $accessPriData['Question_Bank_GK_CA_Test']->categories==true ? $accessPriData['Question_Bank_GK_CA_Test']->categories : [];
                $categoryIdForQue['GK_CA'] = FrontHelper::getAllChilCategory($category,$cateIdForLiveQue);
            }

            if ($question_type) {
                $aTable = $aTable->where('question_type',"=",$question_type)->whereIn('category_id',$categoryIdForQue[$question_type]);
            }else{

                foreach ($getType as $key => $value) {
                    if(array_key_exists($value,$categoryIdForQue)){
                        $aTable = $aTable->orWhere(function($queCate) use($categoryIdForQue,$value){
                            $queCate->where('question_type',"=",$value)->whereIn('category_id',$categoryIdForQue[$value]);
                        });
                    }
                    if($filter){
                        $aTable = $aTable->whereHas('question_desc', function($query) use($filter){
                            $query->where('question_text', 'LIKE', '%' . $filter . '%' );
                        });
                    }

                    if ($search_category) {
                        $aTable = $aTable->where('category_id',$search_category);
                    }

                    if ($status_type) {
                        $status_type = $status_type=='Active'?1:0;
                        $aTable = $aTable->where('status',$status_type);
                    }

                    if ($used_unused_que) {
                        if ($used_unused_que=='UNUSED') {
                            $aTable = $aTable->where('isAssignable',true);
                        }else{
                            $aTable = $aTable->where('isAssignable',false);
                        }
                    }
                }

            }
        }
    	if($filter){
    		$aTable = $aTable->whereHas('question_desc', function($query) use($filter){
                $query->where('question_text', 'LIKE', '%' . $filter . '%' );
            });
    	}

        if ($question_type && Auth::user()->user_type==0) {
            $aTable = $aTable->where('question_type',$question_type);
        }
    	if ($search_category) {
    		$aTable = $aTable->where('category_id',$search_category);
    	}

    	if ($status_type) {
            $status_type = $status_type=='Active'?1:0;
    		$aTable = $aTable->where('status',$status_type);
    	}

        if ($used_unused_que) {
            if ($used_unused_que=='UNUSED') {
                $aTable = $aTable->where('isAssignable',true);
            }else{
                $aTable = $aTable->where('isAssignable',false);
            }
        }

    	$aTableQuery = $aTable;

    	if($sort_col != 0){
    		$aTableQuery = $aTableQuery->orderBy($sort_field, $sort_type);
    	}else{
            $aTableQuery = $aTableQuery->orderBy('created_at', 'DESC');
        }

    	$total_table_data= $aTableQuery->count();

		$offset = isset($data['start']) ? $data['start'] :'';
	     
	    $counter = $offset;
	    $aTabledata = [];
	    $aTables = $aTableQuery->offset($offset)->limit($perpage)->get()->toArray();
        foreach ($aTables as $key => $value) {
            if ($value['category_id']==0 || empty($value['category_id'])) {
                $value['category'] = Config::get('siteglobal.gk_ca_category');
            }else{
            $streams = Category::with('category_desc')->get()->toArray();
            $category = FrontHelper::buildtree($streams);
            $categoryIdForDoubt = FrontHelper::getSingleHeararcyofCat($category,$value['category_id']);
            $cate = FrontHelper::getSingleHeararcyofCat($category,$value['category_id']);
            $value['category'] = $cate;
            }
            $value['index'] = $counter+1;
            if (Auth::user()->user_type==0) {
                $access = true;
                $value['status_access'] = $access; 
                $value['deleted_access'] = $access;
                $value['edit_access'] = $access;
            }else{
                $status_access = isset($getAcc[$value['question_type']]) ? $getAcc[$value['question_type']]['STATUS'] : false;
                $deleted_access = isset($getAcc[$value['question_type']]) ? $getAcc[$value['question_type']]['DELETED'] : false;
                $edit_access = isset($getAcc[$value['question_type']])? $getAcc[$value['question_type']]['EDIT'] : false;

                $value['status_access'] = $status_access; 
                $value['deleted_access'] = $deleted_access;
                $value['edit_access'] = $edit_access;
            }

            $queMediaFile = QuestionMedia::where('media_int_type','QUESTION')->where('lang_code','en')->where('media_int_id',$value['questions_id']);
            $countImage = $queMediaFile->count();
            if ($countImage>=1) {
                $queMediaFile = $queMediaFile->first()->toArray();
                $question_image_link = Config::get('siteglobal.images_dirs.QUESTIONS').'/'.$queMediaFile['media_file'];
            }else{
                $question_image_link = false;
            }
            $value['question_image'] =  $question_image_link;
            $aTabledata[$counter] = $value;
            $counter++;
      	}
        
	    $price = array_column($aTabledata, 'index');

	    if($sort_col == 0){
	     	if($sort_type == 'desc'){
	     		array_multisort($price, SORT_DESC, $aTabledata);
	     	}else{
	     		array_multisort($price, SORT_ASC, $aTabledata);
	     	}
		}
	      $result = array(
	      	"draw" => $data['draw'],
			"recordsTotal" =>$total_table_data,
			"recordsFiltered" => $total_table_data,
	        'data' => $aTabledata,
	      );

	       return response()->json($result);
    }     

    /**
     * Used for Admin editQuestions
     * @return redirect to Admin->edit Questions
     */
    public function editQuestion(Request $request,$id)
    {
        $getQueOptId = [];
        $getQueMedia = [];

        //ACCESS PRIVILEGE DATA
        $accessPriData = session()->get('accessPriData');
        $getCatgory = [];
        if(!empty($accessPriData['Question_Bank_Live_Test']) && $accessPriData['Question_Bank_Live_Test']->view==true)
        {
            $getCatgory[] = $accessPriData['Question_Bank_Live_Test']->categories==true ? $accessPriData['Question_Bank_Live_Test']->categories :'' ;
        }

        if(!empty($accessPriData['Question_Bank_Quizz_Test']) && $accessPriData['Question_Bank_Quizz_Test']->view==true)
        {
            $getCatgory[] = $accessPriData['Question_Bank_Quizz_Test']->categories ? $accessPriData['Question_Bank_Quizz_Test']->categories : '';
        }

        if(!empty($accessPriData['Question_Bank_Practice_Test']) && $accessPriData['Question_Bank_Practice_Test']->view==true)
        {
            $getCatgory[] = $accessPriData['Question_Bank_Practice_Test']->categories ? $accessPriData['Question_Bank_Practice_Test']->categories : '';
        }

        $getAllCat = [];
        $getCatgory = array_filter($getCatgory);
        foreach ($getCatgory as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $getAllCat[] = $value1;
            }
        }
        if (Auth::user()->user_type==0) {
           $parent_category = Category::with('category_desc')->where('status',1)->where('parent_category',0)->get(); 
        }else{
           $parent_category = Category::with('category_desc')->whereIn('category_id',$getAllCat)->where('status',1)->where('parent_category',0)->get();
        }

        //ACCESS PRIVILEGE Question Type
        $questionType = [];
        if(!empty($accessPriData['Question_Bank_Live_Test']) && $accessPriData['Question_Bank_Live_Test']->view==true || Auth::user()->user_type==0){
            $questionType['LIVE_TEST'] =  'Live Test';
        }
        if(!empty($accessPriData['Question_Bank_Quizz_Test']) && $accessPriData['Question_Bank_Quizz_Test']->view==true || Auth::user()->user_type==0){
            $questionType['QUIZZES'] = 'Quizzes';
        }
        if(!empty($accessPriData['Question_Bank_Practice_Test']) && $accessPriData['Question_Bank_Practice_Test']->view==true || Auth::user()->user_type==0){
            $questionType['PRACTICE_TEST'] = 'Practice Test';
        }
        if(!empty($accessPriData['Question_Bank_GK_CA_Test']) && $accessPriData['Question_Bank_GK_CA_Test']->view==true || Auth::user()->user_type==0){
            $questionType['GK_CA'] = 'Gk & Ca';
        }
        
        $getQuestions = Question::with('question_both_lang','category_desc')->where('questions_id',$id)->first()->toArray();

        //SHOW CATEGORY RELATED TO PARENT
        $getAllCateRelatParent = [];
        if ($getQuestions['category_id']) {
            
        $checkParent = Category::select('parent_category')->where('category_id',$getQuestions['category_id'])->first();
        $getAllCateRelatParent = Category::with('desc_en')->where('parent_category',$checkParent->parent_category)->get();
        // SHOW CATEGORY RELATED TO PARENT--/
        }

        $getOptions = QuestionOption::with('question_option_desc')->where('questions_id',$id)->get()->toArray();
        foreach ($getOptions as $key => $value) {
            $getQueOptId[] =  $value['question_options_id'];
        }
        $getQueOptId[] =  $getQuestions['questions_id'];
        $getQueMedia = QuestionMedia::whereIn('media_int_id',$getQueOptId)->get()->toArray();

        return view('admin.question.editQuestion')->with(['getAllCateRelatParent'=>$getAllCateRelatParent,'questionType'=>$questionType,'getQueMedia'=>$getQueMedia,'getQuestions'=>$getQuestions,'getOptions'=>$getOptions,'parent_category'=>$parent_category]);
    
    }

    /**
	 * Used for Admin Questions
	 * @return redirect to Admin->edit Questions
	 */
    public function viewQuestion(Request $request,$id)
    {
        $cate ='';
        $getQuestions = Question::with('question_both_lang','category_desc')->where('questions_id',$id)->first()->toArray();
        if ($getQuestions['category_id']==0 || empty($getQuestions['category_id'])) {
                $getQuestions['category_name'] = Config::get('siteglobal.gk_ca_category');
            }else{
            $streams = Category::with('category_desc')->get()->toArray();
            $category = FrontHelper::buildtree($streams);
            foreach ($category as $ckey => $cvalue){
                if ($cvalue['category_id']==$getQuestions['category_id']) {
                    $cate = $cvalue['category_desc'][0]['name'];
                }
                
                if(!empty($cvalue['children'])){
                foreach ($cvalue['children'] as $key1 => $value1){
                    if ($value1['category_id']==$getQuestions['category_id']) {
                        $cate = $cvalue['category_desc'][0]['name'].' > '.$value1['category_desc'][0]['name'];
                    }

                        if(!empty($value1['children'])){
                        foreach ($value1['children'] as $key2 => $value2) {
                            if ($value2['category_id']==$getQuestions['category_id']) {
                                $cate = $cvalue['category_desc'][0]['name'].' > '.$value1['category_desc'][0]['name']. ' > ' .$value2['category_desc'][0]['name'];
                            }
     
                            if(!empty($value2['children'])){
                            foreach ($value2['children'] as $key3 => $value3){
                                if ($value3['category_id'] == $getQuestions['category_id']) {
                                    $cate = $cvalue['category_desc'][0]['name'].' > '.$value1['category_desc'][0]['name']. ' > ' .$value2['category_desc'][0]['name'].' > '.$value3['category_desc'][0]['name'];
                                }
                                
                                if(!empty($value3['children'])){
                                foreach ($value3['children'] as $key4 => $value4){
                                    if ($value4['category_id']==$getQuestions['category_id']) {
                                        $cate = $cvalue['category_desc'][0]['name'].' > '.$value1['category_desc'][0]['name']. ' > ' .$value2['category_desc'][0]['name'].' > '.$value3['category_desc'][0]['name'].' > '.$value4['category_desc'][0]['name'];
                                    }
                                }
                                }
                            }
                            }

                        }
                        }
                    
                    }
                    }

                }
                $getQuestions['category_name'] = $cate;
            }
        $getOptions = QuestionOption::with('question_option_desc')->where('questions_id',$id)->orderBy('option_order','ASC')->get()->toArray();
        
        foreach ($getOptions as $key => $value) {
            $getQueOptId[] =  $value['question_options_id'];
        }
        $getQueOptId[] =  $getQuestions['questions_id'];
        $getQueMedia = QuestionMedia::whereIn('media_int_id',$getQueOptId)->get()->toArray();
    	return view('admin.question.viewQuestion')->with(['getQueMedia'=>$getQueMedia,'getQuestions'=>$getQuestions,'getOptions'=>$getOptions]);
    
    }



  	/**
   	* Used for Delete Admin Question
   	*/
    public function deleteQuestion(Request $request)
    {
    	$input = $request->all();
    	$cid = $input['cid'];
    	$response = [];
        
    	if(empty($cid)){
    		$response['status'] = false;
    	}else{
    		$checkQues = Question::where('questions_id', $cid)->where('exam_id',null)->first();

    		if (!empty($checkQues)) {
        		$question = Question::where('questions_id', $cid)->delete();
                UserSavedItem::where('item_type','QUESTION')->where('item_type_id',$cid)->delete();
	        	
	        	if($question){
		            $response['status'] = true;
		            $response['message'] = "Question Successfully deleted";
	        	}else{
	        		$response['status'] = false;
		            $response['message'] = "Something Went Wrong";
	        	}

    		}else{
    			$response['status'] = false;
	            $response['message'] = "Question already exists in exams";
    		}

        }
    	return response()->json($response);
    }

    /**
    * Used for Delete Admin Question
    */
    public function questionStatus(Request $request)
    {
        $input = $request->all();
        $cid = $input['cid'];
        $response = [];

        if(empty($cid)){
            $response['status'] = false;
        }else{
            $question = Question::where('questions_id', $cid)->first();
            $question->status = $question->status == 1 ? 0 : 1;
            if ($question->update()) {
                $response['status'] = true;
                $response['message'] = "Status Successfully changed";
            }else{
                $response['status'] = false;
            }
        }
        return response()->json($response);
    }

    /**
    * Used for Admin  deleteQuestionMedia
    */
    public function deleteQuestionMedia(Request $request)
    {
        $input = $request->all();
        $cid = $input['cid'];
        $response = [];
        
        if(empty($cid)){
            $response['status'] = false;
        }else{
            $checkQuesMedia = QuestionMedia::where('media_id', $cid)->first();

            if (!empty($checkQuesMedia->media_file)) {
                Storage::disk('public')->delete(Config::get('siteglobal.images_dirs.QUESTIONS').'/'.$checkQuesMedia->media_file);
            }
            
            $question = QuestionMedia::where('media_id', $cid)->delete();
            
            if($question){
                $response['status'] = true;
                $response['message'] = "Image Successfully Removed";
            }else{
                $response['status'] = false;
                $response['message'] = "Something Went Wrong";
            }

        }
        return response()->json($response);
        
    }



}
