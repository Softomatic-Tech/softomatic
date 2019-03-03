<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\File;
use App\Exception;
use \Illuminate\Database\QueryException;
ini_set('max_execution_time', 300);
date_default_timezone_set('Asia/Kolkata');

class FeedbackController extends Controller
{
    public function get_feedback(Request $request)
    {
    	if(Session::get('username')!='')
      	{
        	try
        	{
		    	if(session('role')=='admin' || session('role')=='hr')
		    	{
		    		$feedback = DB::table('feedback')->leftjoin('emp','emp.email','=','feedback.user_email')->select('emp.first_name','emp.middle_name','emp.last_name','feedback.user_email','feedback.description','feedback.type','feedback.created_at')->orderBy('feedback.created_at','desc')->get();
		    		return view('feedback',compact('feedback'));
		    	}
		    	else
		    	{
		    		return view('feedback');
		    	}
	    	
    		}
	        catch(QueryException $e)
	        {
	              activity()->log($e->getMessage());
	              return redirect()->back()->with('alert-danger','Database Query Exception! ['.$e->getMessage().' ]');
	        }
	        catch(Exception $e)
	        {
	              activity()->log($e->getMessage());
	              return redirect()->back()->with('alert-danger',$e->getMessage());
	        }
	    }
	    else
	    {
	        if(Session::get('username')=='')
	            return redirect('/')->with('status',"Please login First");
	        else
	            return redirect('dashboard')->with('alert-danger',"Only admin and HR can access Feedback List");
	    }
    }

    public function get_mobile_feedback(Request $request)
    {
    	try
        {
        	return view('mobile_feedback');
    	}
        catch(QueryException $e)
        {
              activity()->log($e->getMessage());
              return redirect()->back()->with('alert-danger','Database Query Exception! ['.$e->getMessage().' ]');
        }
        catch(Exception $e)
        {
              activity()->log($e->getMessage());
              return redirect()->back()->with('alert-danger',$e->getMessage());
        }
	}

    public function save_feedback(Request $request)
    {
    	if(Session::get('username')!='')
      	{
        	try
        	{
        		$rules = array(
		        'feedback_type'    =>  'required|string',
		        'description'  =>  'required|string',
				);
				$validator = Validator::make(Input::all(), $rules);
				 if ($validator->fails()) {
	            activity()->log('Add Feedback process failed due to Validation Error');
	            return redirect()->back()
	            ->withErrors($validator)
	            ->withInput($request->all);
		        } 
        		$type = $request->feedback_type;
        		$description = $request->description;
        		$user_email = session('useremail');
        		$username = session('username');
        		$created_at = date('Y-m-d H:i:s');
        		$insert = DB::table('feedback')->insert([
        			'type'=>$type,
        			'description'=>$description,
        			'user_email'=>$user_email,
        			'created_at'=>$created_at
        		]);
        		if($insert)
        		{
        			$notification = DB::table('notification')->insert([
		        		'user_id'=>session('user_id'),'requester_id'=>session('user_id'),'notification'=>$username.' added a '.$type.' on feedback','notification_status'=>'','link'=>'feedback','status'=>'active']);
        			activity()->log('Feedback Saved Successfully');
	            	return redirect()->back()->with('alert-success',"Feedback Saved Successfully");
        		}
        		else
        		{
        			activity()->log('Feedback not Saved');
	            	return redirect()->back()->with('alert-danger',"Feedback not Saved!");
        		}
        	}
	        catch(QueryException $e)
	        {
	              activity()->log($e->getMessage());
	              return redirect()->back()->with('alert-danger','Database Query Exception! ['.$e->getMessage().' ]');
	        }
	        catch(Exception $e)
	        {
	              activity()->log($e->getMessage());
	              return redirect()->back()->with('alert-danger',$e->getMessage());
	        }
	    }
	    else
	    	return redirect('/')->with('status',"Please login First");
    }
}
