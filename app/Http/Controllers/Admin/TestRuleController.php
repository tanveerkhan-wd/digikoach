<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class TestRuleController extends Controller
{
	/**
	 * Used for Admin live test
	 * @return redirect to Admin->quizTest
	 */
    public function index()
    {
    	$test_rule_Data = [];
    	//Test Rules Text
        $test_rules =Setting::where('text_key','test_rule_live_test_hi')
                ->orWhere('text_key','test_rule_live_test_en')->orWhere('text_key','test_rule_quizzes_test_hi')->orWhere('text_key','test_rule_quizzes_test_en')->get();
        foreach ($test_rules as $test_rule_value) {
            if($test_rule_value->text_key=='test_rule_live_test_hi'){
                $test_rule_Data['live_test_hi'] = $test_rule_value->text_value ? $test_rule_value->text_value : '';
            }
            if($test_rule_value->text_key=='test_rule_live_test_en'){
                $test_rule_Data['live_test_en'] = $test_rule_value->text_value ? $test_rule_value->text_value : '';
            }
            if($test_rule_value->text_key=='test_rule_quizzes_test_hi'){
                $test_rule_Data['quizzes_test_hi'] = $test_rule_value->text_value ? $test_rule_value->text_value : '';
            }
            if($test_rule_value->text_key=='test_rule_quizzes_test_en'){
                $test_rule_Data['quizzes_test_en'] = $test_rule_value->text_value ? $test_rule_value->text_value : '';
            }
        }

    	if (request()->ajax()) {
            return \View::make('admin.testRule.index')->with(['test_rule_Data'=>$test_rule_Data])->renderSections();
        }
    	return view('admin.testRule.index')->with(['test_rule_Data'=>$test_rule_Data]);
    }

    /**
     * Used for Admin edit editTestRule
     * @return redirect to Admin->edit editTestRule
     */
    public function editTestRule(Request $request)
    {
        $input = $request->all();
        
        $aTableData = '';
        
        if ($input['live_test_en']) {
            $address = $input['live_test_en'];
            $aTableData = Setting::where('text_key','test_rule_live_test_en')->update(['text_value'=>$address]);
        }
        if ($input['live_test_hi']) {
            $address = $input['live_test_hi'];
            $aTableData = Setting::where('text_key','test_rule_live_test_hi')->update(['text_value'=>$address]);
        }
        if ($input['quizzes_test_en']) {
            $address = $input['quizzes_test_en'];
            $aTableData = Setting::where('text_key','test_rule_quizzes_test_en')->update(['text_value'=>$address]);
        }
        if ($input['quizzes_test_hi']) {
            $address = $input['quizzes_test_hi'];
            $aTableData = Setting::where('text_key','test_rule_quizzes_test_hi')->update(['text_value'=>$address]);
        }

        $response['status'] = true;
        $response['message'] = "Test Rule Text Successfully Updated";
                
        return response()->json($response);
    }
}
