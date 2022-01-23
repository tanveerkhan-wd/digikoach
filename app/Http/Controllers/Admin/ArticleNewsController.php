<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ArticlesNewsDesc;
use App\Models\ArticlesNews;
use App\Models\User;
use App\Models\Notification;
use App\Models\Translation;
use App\Models\UserSavedItem;
use App\Models\NotificationDesc;
use Carbon\Carbon;
use UserNotifications;
use Auth;

class ArticleNewsController extends Controller
{
    /**
	 * Used for Admin ArticleNews
	 * @return redirect to Admin->ArticleNews
	 */
    public function index(Request $request)
    {
    	if (request()->ajax()) {
            return \View::make('admin.gkCa.articlesNews')->renderSections();
        }
    	return view('admin.gkCa.articlesNews');
    }


    /**
	 * Used for Admin ArticleNews
 	 * @return redirect to Admin->ArticleNews listing
	 */
    public function getArticleNews(Request $request)
    {
    	
      /**
       * Used for Admin ArticleNews Listing
       */
    	$data =$request->all();

        $accessPriData = !empty(session()->get('accessPriData')) ? session()->get('accessPriData') :'' ;

	    $perpage = !empty( $data[ 'length' ] ) ? (int)$data[ 'length' ] : 10;
	      
	    $filter = isset( $data['search'] ) && is_string( $data['search'] ) ? $data['search'] : '';

        $category_type = isset($data['category_type']) ? $data['category_type'] : '';

	    $sort_type = isset( $data['order'][0]['dir'] ) && is_string( $data['order'][0]['dir'] ) ? $data['order'][0]['dir'] : '';	

	    $sort_col =  isset($data['order'][0]['column']) ? $data['order'][0]['column'] :'';

	    $sort_field = isset($data['columns'][$sort_col]['data']) ? $data['columns'][$sort_col]['data'] :'';
	    
    	$aTable = ArticlesNews::with('desc');

        //ACCESS PRIVILEGE
        if($accessPriData){
            $getAcc = [];
            if(!empty($accessPriData['Article_News']) && $accessPriData['Article_News']->view==true)
            {
                $getAcc['STATUS'] = $accessPriData['Article_News']->status==true ? true:false;
                $getAcc['DELETED'] = $accessPriData['Article_News']->delete==true ? true:false;
                $getAcc['EDIT'] = $accessPriData['Article_News']->edit==true ? true:false;
            }
        }

    	if($filter){
    		$aTable = $aTable->whereHas('desc', function($q) use($filter){
                $q->where('article_title', 'LIKE', '%' . $filter . '%' )
                	->orWhere('article_body', 'LIKE', '%' . $filter . '%' );
            });
    	}

        if (!empty($data['status_type']) && $data['status_type'] !=null ) {
            $statusData = $data['status_type']=='Active' ? 1 : 0;
            $aTable = $aTable->where('status','=',$statusData);
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
            $value['date'] = date('d-M-Y',strtotime($value['created_at']));

            $value['status_access'] = Auth::user()->user_type==0 ? true : $getAcc['STATUS'];
            $value['deleted_access'] = Auth::user()->user_type==0 ? true : $getAcc['DELETED'];
            $value['edit_access'] = Auth::user()->user_type==0 ? true : $getAcc['EDIT'];

            $value['index'] = $counter+1;
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
	 * Used for Admin add article news
 	 * @return redirect to Admin->add article news
	 */
    public function addArticleNews(Request $request)
    {
        return view('admin.gkCa.addArticleNews');
    }

    /**
	 * Used for Admin add Category
 	 * @return redirect to Admin->add Category
	 */
    public function addArticleNewsPost(Request $request)
    {
    	$response = [];
    	$input = $request->all();   
        $aData = [];
        $cdData = [];
        if(!empty($input['pkCat']) && $input['pkCat'] != null){
            $checkPrev = '';
            if(!empty($checkPrev)){
              $response['status'] = false;
              $response['message'] ="Article news title already exists with this name";
            }else{
        		$id = ArticlesNews::where('articles_news_id', $input['pkCat']);

                $aData['status'] = $input['status_type'];
	            $aData['meta_title'] = $input['seo_meta_title'];
                $aData['meta_description'] = $input['seo_meta_description'];

	            if ($id->update($aData)) {
                    $cdId = ArticlesNewsDesc::where('articles_news_id', $input['pkCat'])->get();
                    foreach ($cdId as $key => $value) {
                        if ($value->lang_code=='en') {
                            $cdData['lang_code'] = $value->lang_code;
                            $cdData['articles_news_id'] = $input['pkCat'];
                            $cdData['article_title'] = $input['title_en'];    
                            $cdData['article_body'] = $input['body_en'];    
                        }else{
                            $cdData['lang_code'] = $value->lang_code;
                            $cdData['articles_news_id'] = $input['pkCat'];
                            $cdData['article_title'] = $input['title_hi'];    
                            $cdData['article_body'] = $input['body_hi'];    
                        }
                        
                        $desc = ArticlesNewsDesc::where('articles_news_id',$input['pkCat'])->where('lang_code',$cdData['lang_code'])->update($cdData);
                    }

                    $response['status'] = true;
                    $response['message'] = "Article & News Successfully Updated";
                }
            }
    	}else{
        
            $checkPrev = ArticlesNewsDesc::where('article_title',$input['title_hi'])->orWhere('article_title',$input['title_en'])->first();
            if(!empty($checkPrev)){
              $response['status'] = false;
              $response['message'] ="Article & News title already exists with this name";
            }
            //Store Category
            $aTableData = new ArticlesNews;
            $aTableData->status = true;
            $aTableData->meta_title = $input['seo_meta_title'];
            $aTableData->meta_description = $input['seo_meta_description'];
            
            if ($aTableData->save()) {
            	for ($i=1; $i <= 2 ; $i++) { 
    	        	$aData[$i]['lang_code'] = $i==1 ? 'en' :'hi';
    	        	$aData[$i]['articles_news_id'] = $aTableData->articles_news_id;
    	        	$aData[$i]['article_title'] = $i==1 ? $input['title_en'] : $input['title_hi'];
    	        	$aData[$i]['article_body'] = $i==1 ? $input['body_en'] :$input['body_hi'];
            	}
            	$desc = ArticlesNewsDesc::insert($aData);
            }

            if ($desc) {

                //SEND EMAIL AND NOTIFICATIONS TO USERS
                $notifi = [];
                $addDataNot = [];
                $getUserCate = User::where('user_type',2)->whereNotNull('device_token')->where('deleted',false)->where('user_status',true)->get();
                if (!empty($getUserCate) || !$getUserCate->isEmpty()) {
                    
                    //GET NOTIFICATION TRANSLATE KEY
                    $aNotificaTransMsg = Translation::where('group','notification_message')->where('key','article_news_add_msg')->first()->toArray();
                    $aNotificaTransTitle = Translation::where('group','notification_title')->where('key','article_news_add_title')->first()->toArray();
                    foreach ($getUserCate as $key => $value) {
                        //SEND NOTIFICATION
                        if (!empty($value->device_token) || $value->device_token==!null) {
                            //GET NOTIFICATION TRANSLATE KEY
                            $userLangCode = !empty($value->user_lang_code) ? $value->user_lang_code : 'en';
                            
                            $bodyText = $aNotificaTransMsg['text'][$userLangCode];
                            $titleText = $aNotificaTransTitle['text'][$userLangCode];

                            $device_token = $value->device_token;
                            $notification = ['title' => $titleText, 'body' => $bodyText];

                            $notification_data = [
                                'action' => 'ARTICLE_NEWS_ADDED',
                                'articles_news_id' =>  $aTableData->articles_news_id
                            ];
                            
                            try
                            {
                                UserNotifications::sendPush($device_token, $notification, $notification_data);
                            } catch (\Exception $e) {
                                continue;   
                            }

                            $notifi = new Notification;
                            $notifi->user_id  = $value->user_id;
                            $notifi->notification_type  = 'ARTICLE_NEWS';
                            $notifi->ntoification_type_id = $aTableData->articles_news_id;
                            $notifi->notification_data = json_encode($notification_data);
                            $notifi->status = false;
                            $notifi->save();
                            if ($notifi) {
                                for ($i=1; $i <=2 ; $i++) { 
                                    $addDataNot[$i]['notification_id'] = $notifi->notification_id;
                                    $addDataNot[$i]['lang_code'] = $i==1 ? 'en' : 'hi';
                                    $addDataNot[$i]['message'] = $i==1 ? $aNotificaTransMsg['text']['en'] : $aNotificaTransMsg['text']['hi'];
                                }
                                NotificationDesc::insert($addDataNot);
                            }
                        }
                    }
                }
                
            	$response['status'] = true;
    	        $response['message'] = "Article & News Successfully Added";

            }else{
                $response['status'] = false;
                $response['message'] = "Something Wrong Please try again Later";
            }
        }
        return response()->json($response);
    }


    /**
    * Used for Edit Admin Category
    */
    public function editArticleNews(Request $request, $id)
    {	
        $aTable = ArticlesNews::with('article_desc')->where('articles_news_id','=',$id)->first();

        return view('admin.gkCa.editArticleNews')->with(['data'=>$aTable]);

    }

  	/**
   	* Used for Delete Admin article news
   	*/
    public function deleteArticleNews(Request $request)
    {
    	$input = $request->all();
    	$cid = $input['cid'];
    	$response = [];

    	if(empty($cid)){
    		$response['status'] = false;
    	}else{
            ArticlesNews::where('articles_news_id', $cid)->delete();
            ArticlesNewsDesc::where('articles_news_id', $cid)->delete();
            UserSavedItem::where('item_type','ARTICLE')->where('item_type_id',$cid)->delete();
            $response['status'] = true;
            $response['message'] = "Article News Successfully deleted";
        }
    	return response()->json($response);
    }

    /**
    * Used for Delete Admin article news
    */
    public function statusArticleNews(Request $request)
    {
        $input = $request->all();
        $cid = $input['cid'];
        $response = [];
        if(empty($cid)){
            $response['status'] = false;
        }else{
            $aData = ArticlesNews::where('articles_news_id', $cid)->first();
            $aData->status = $aData->status == 1 ? 0 : 1;
            if ($aData->update()) {
                $response['status'] = true;
                $response['message'] = "Status Successfully changed";
            }else{
                $response['status'] = false;
            }
        }
        return response()->json($response);
    }


    /**
    * Used for Delete Admin Article News
    */
    public function viewArticleNews(Request $request,$id)
    {
        $aTable = ArticlesNews::with('article_desc')->where('articles_news_id',$id)->first();

        return view('admin.gkCa.viewArticleNews')->with(['data'=>$aTable]);
    }
}
