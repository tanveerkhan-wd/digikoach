<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\EmailMaster;
use Validator;
use Carbon\Carbon;
use Auth;
use Hash;
use View;
use Redirect;

class EmailTemplateController extends Controller
{
	/**
	 * Used for Admin Email Template
	 * @return redirect to Admin->EmailTemplate
	 */
	public function index(Request $request)
    {
    	if (request()->ajax()) {
            return \View::make('admin.emailTemplate.index')->renderSections();
        }
    	return view('admin.emailTemplate.index');
    }

	/**
	 * Used for Admin Email Template listing
 	 * @return redirect to Admin->EmailTemplate listing
	 */
    public function getEmailTemplates(Request $request)
    {
    	
      /**
       * Used for Admin Email Templates Listing
       */
    	$data =$request->all();
	    
	    $perpage = !empty( $data[ 'length' ] ) ? (int)$data[ 'length' ] : 10;
	      
	    $filter = isset( $data['search'] ) && is_string( $data['search'] ) ? $data['search'] : '';

	    $sort_type = isset( $data['order'][0]['dir'] ) && is_string( $data['order'][0]['dir'] ) ? $data['order'][0]['dir'] : '';	

	    $sort_col =  isset($data['order'][0]['column']) ? $data['order'][0]['column'] :'';

	    $sort_field = isset($data['columns'][$sort_col]['data']) ? $data['columns'][$sort_col]['data'] :'';

    	$template = new EmailMaster;

    	if($filter){
    		$template = $template->where( 'parameters', 'LIKE', '%' . $filter . '%' )->orWhere ( 'title', 'LIKE', '%' . $filter . '%' )->orWhere ( 'subject', 'LIKE', '%' . $filter . '%' );
    	}

    	$templateQuery = $template;

    	if($sort_col != 0){
    		$templateQuery = $templateQuery->orderBy($sort_field, $sort_type);
    	}else{
            $templateQuery = $templateQuery->orderBy('created_at', 'DESC');
        }

    	$total_template= $templateQuery->count();

		$offset = isset($data['start']) ? $data['start'] :'';
	     
	    $counter = $offset;
	    $templatedata = [];
	    $templates = $templateQuery->offset($offset)->limit($perpage)->get()->toArray();

       	foreach ($templates as $key => $value) {
            $value['index'] = $counter+1;
            $templatedata[$counter] = $value;
            $counter++;
      	}

	    $price = array_column($templatedata, 'index');

	    if($sort_col == 0){
	     	if($sort_type == 'desc'){
	     		array_multisort($price, SORT_DESC, $templatedata);
	     	}else{
	     		array_multisort($price, SORT_ASC, $templatedata);
	     	}
		}
	      $result = array(
	      	"draw" => $data['draw'],
			"recordsTotal" =>$total_template,
			"recordsFiltered" => $total_template,
	        'data' => $templatedata,
	      );

	       return response()->json($result);
    
    }    

    /**
     * Used for Admin Edit Email Tempate
     * @return redirect to Admin->Email Tempate Edit
     */
    public function editEmailTemplate(Request $request, $id)
    {
    	$data = EmailMaster::where('email_master_id',$id)->first();
    	
		return view('admin.emailTemplate.edit',compact('data'));    	
    }


	/**
     * Used for Admin Edit Email Tempate
     * @return redirect to Admin->Email Tempate Edit
     */
    public function editEmailTemplatePost(Request $request)
    {
        $response = [];
        $input = $request->all();
        try
        {
            $emailMaster = EmailMaster::where('email_master_id',$input['cid'])->first();
            $emailMaster->subject = $input['subject'];
            $emailMaster->content = $input['content'];
            $emailMaster->save();
            
            if($emailMaster){
            	$response['status'] = true;
                $response['message'] = "Email Template Successfully updated";
            }else{
                $response['status'] = false;
                $response['message'] = "Something Wrong Please try again Later";
            }
        }catch (\Exception $e) {
            $response['status'] = false;
            $response['message'] = "Error:" . $e->getMessage();
        }
        return response()->json($response);
    }
}
