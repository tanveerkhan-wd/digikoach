<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\FrontHelper;
use App\Models\Doubt;
use App\Models\DoubtAnswer;
use App\Models\DoubtReply;
use App\Models\Category;
use App\Models\User;
use App\Models\Notification;
use App\Models\Translation;
use UserNotifications;
use Auth;
use Storage;
use File;
use Config;

class DoubtSectionController extends Controller
{
	/**
	 * Used for Admin AppUser
	 * @return redirect to Admin->AppUser
	 */
    public function index(Request $request)
    {
        if (request()->ajax()) {
            return \View::make('admin.doubtSection.index')->renderSections();
        }
    	return view('admin.doubtSection.index');
    }


    /**
     * Used for Admin get doubt
     * @return redirect to Admin->get doubt listing
    */
    public function getDoubts(Request $request)
    {
        
      /**
       * Used for Admin get doubt Listing
       */
        $data =$request->all();
        
        $accessPriData = !empty(session()->get('accessPriData')) ? session()->get('accessPriData') :'' ;

        $perpage = !empty( $data[ 'length' ] ) ? (int)$data[ 'length' ] : 10;
          
        $filter = isset( $data['search'] ) && is_string( $data['search'] ) ? $data['search'] : '';
        
        $search_category = isset( $data['search_category'] ) ? $data['search_category'] :'';
        
        $status_type = isset( $data['status_type'] ) ? $data['status_type'] :'';

        $test_type = isset( $data['test_type'] ) ? $data['test_type'] :'';

        $sort_type = isset( $data['order'][0]['dir'] ) && is_string( $data['order'][0]['dir'] ) ? $data['order'][0]['dir'] : '';    

        $sort_col =  isset($data['order'][0]['column']) ? $data['order'][0]['column'] :'';

        $sort_field = isset($data['columns'][$sort_col]['data']) ? $data['columns'][$sort_col]['data'] :'';
        
        $aTable = Doubt::with('user','category');
        //ACCESS PRIVILEGE
        if($accessPriData){
            $getAcc = [];
            if(!empty($accessPriData['Doubts']) && $accessPriData['Doubts']->view==true)
            {
                $getAcc['STATUS'] = $accessPriData['Doubts']->status==true ? true:false;
                $getAcc['DELETED'] = $accessPriData['Doubts']->delete==true ? true:false;
            }
        }

        if($filter){
            $aTable = $aTable->where('doubt_text', 'LIKE', '%' . $filter . '%' )->orWhereHas('user',function($query) use($filter){
                $query->where('name', 'LIKE', '%' . $filter . '%' );
            });
        }


        if ($status_type) {
            $status_type = $status_type=='Active'?1:0;
            $aTable = $aTable->where('status',$status_type);
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
            $value['index'] = $counter+1;

            $value['status_access'] = Auth::user()->user_type==0 ? true : $getAcc['STATUS'];
            $value['deleted_access'] = Auth::user()->user_type==0 ? true : $getAcc['DELETED'];
            
            $streams = Category::with('category_desc')->get()->toArray();
            $category = FrontHelper::buildtree($streams);
            $categoryIdForDoubt = FrontHelper::getSingleHeararcyofCat($category,$value['category_id']);
            $value['category_name'] = !empty($categoryIdForDoubt) ? $categoryIdForDoubt : 'NA';
            
            $value['doubt_text'] = substr($value['doubt_text'],0 ,100);
            $value['created_at'] = date('d-M-Y | h:i A',strtotime($value['created_at']));
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
   	* Used for Delete Douts
   	*/
    public function deleteDoubt(Request $request)
    {
    	$input = $request->all();
    	$cid = $input['cid'];
    	$response = [];

    	if(empty($cid)){
    		$response['status'] = false;
    	}else{

            $doubt = Doubt::where('doubt_id', $cid)->first();
            if (!empty($doubt->doubt_image)) {
                Storage::disk('public')->delete(Config::get('siteglobal.images_dirs.DOUBT').'/'.$doubt->doubt_image);
            }
            if (!empty($doubt->doubt_attachment)) {
                Storage::disk('public')->delete(Config::get('siteglobal.images_dirs.DOUBT').'/'.$doubt->doubt_attachment);
            }
            $doubtAns =  DoubtAnswer::where('doubt_id', $cid)->get();
            foreach ($doubtAns as $value) {
                if (!empty($value->answer_image)) {
                    Storage::disk('public')->delete(Config::get('siteglobal.images_dirs.DOUBT').'/'.$value->answer_image);   
                }
            }
            $doubtReply = DoubtReply::where('doubt_id', $cid)->get();
            foreach ($doubtReply as $value1) {
                if (!empty($value1->reply_image)) {
                    Storage::disk('public')->delete(Config::get('siteglobal.images_dirs.DOUBT').'/'.$value1->reply_image);   
                }    
            }

            Doubt::where('doubt_id', $cid)->delete();
            DoubtAnswer::where('doubt_id', $cid)->delete();
            DoubtReply::where('doubt_id', $cid)->delete();            

            $response['status'] = true;
            $response['message'] = "Doubt Successfully deleted";
        }
    	return response()->json($response);
    }

    /**
    * Used for status Douts
    */
    public function statusDoubt(Request $request)
    {
        $input = $request->all();
        $cid = $input['cid'];
        $response = [];
        if(empty($cid)){
            $response['status'] = false;
        }else{
            $aData = Doubt::where('doubt_id', $cid)->first();
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
    * Used for Delete Admin status Doubt
    */
    public function viewDoubt(Request $request,$id)
    {
        $aTable = Doubt::with('answers','category')->where('doubt_id',$id)->first();

        return view('admin.doubtSection.viewDoubt')->with(['data'=>$aTable]);
    }
    

    /**
    * Used for add Answer Douts
    */
    public function addDoubtAnswer(Request $request)
    {
        $input = $request->all();
        $cid = $input['pkCat'];
        $response = [];
        if(!empty($cid)){
        	$aData = new DoubtAnswer;
        	$aData->doubt_id = $cid;
        	$aData->user_id = Auth::id();
        	$aData->doubt_answer = $input['new_answer'];
            if ($aData->save()) {
                $getDoubt = Doubt::where('doubt_id',$cid)->first();
                $total_answers = $getDoubt->total_answers+1;
                Doubt::where('doubt_id',$cid)->update(['total_answers'=>$total_answers]);

                //SEND PUSH NOTIFICATIONS
                //GET NOTIFICATION TRANSLATE KEY
                $aNotificaTransMsg = Translation::where('group','notification_message')->where('key','doubt_answered')->first()->toArray();
                $aNotificaTransTitle = Translation::where('group','notification_title')->where('key','doubt_answered')->first()->toArray();
                $getUserData = User::where('user_id',$getDoubt->user_id)->first();
                $userLangCode = !empty($getUserData->user_lang_code) ? $getUserData->user_lang_code : 'en';
                $user_name = Auth::check() ? Auth::user()->name : 'Digikoach Admin';
                $bodyText = $user_name. ', ' .$aNotificaTransMsg['text'][$userLangCode];
                $titleText = $aNotificaTransTitle['text'][$userLangCode];
                $device_token = $getUserData->device_token;
                $notification = ['title' => $titleText, 'body' => $bodyText];

                $notification_data = [
                    'action' => 'DOUBT_ANSWER',
                    'doubt_id' =>  $cid
                ];
                
                try
                {
                    UserNotifications::sendPush($device_token, $notification, $notification_data);
                } catch (\Exception $e) {
                       
                }

                $response['status'] = true;
                $response['message'] = "Doubt Answer Successfully Updated";
            }else{
                $response['status'] = false;
                $response['message'] = "Something Went Wrong";
            }
        }
        return response()->json($response);
    }

    
    /**
    * Used for show model of Answer reply
    */
    public function getAnswerReply(Request $request)
    {
        $input = $request->all();
        $cid = $input['cid'];
        $response = [];
        if(!empty($cid)){
            $aData = DoubtReply::with('user')->where('answer_id',$cid)->get();
            foreach ($aData as $key => $value) {
                $value->userImagePath = Config::get('siteglobal.images_dirs.USERS');
                $value->doubtImagePath = Config::get('siteglobal.images_dirs.DOUBT');
                $value->date = date('d-M-Y | h:i A',strtotime($value->created_at)) ?? 'NA';
            }
            if (!$aData->isEmpty()) {
                $response['status'] = true;
                $response['data'] = $aData;
            }else{
                $response['status'] = false;
                $response['message'] = "No data found";
            }
        }
        return response()->json($response);
    }

}
