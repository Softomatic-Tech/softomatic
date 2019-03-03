<?php

namespace App\Http\Controllers;

use App\emp;
use App\User;
use App\leaves;
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
date_default_timezone_set('Asia/Kolkata');

class LeaveController extends Controller
{
    public function show(Request $request)
    {
    	try
    	{
    		if(Session::get('username')!='')
	        {
		    	activity()->log('fetching Leave Page for user ID'.session('user_id'));
		    	if(session('role')!='admin' && session('role')!='hr')
		    	{
		    		/*$emp_id = emp::select('id')->where('email',session('useremail'))->value('id');*/
		    		$leaves_list = leaves::where('emp_id',session('user_id'))->orderBy('id','DESC')->get();
		    		if(Session::get('role')=='employee')
		    		{
		    			return view('mobile_leave',compact('leaves_list'));
		    		}
		    		else
		    		{
		    			return view('leave',compact('leaves_list'));
		    		}
		    	}
		    	else
		    	{
		    		$leaves_list = leaves::join('users','leaves.emp_id','=','users.id')->select('users.name','users.email','leaves.reason','leaves.from','leaves.to','leaves.status','leaves.id','leaves.emp_id', 'leaves.rejection_reason')->orderBy('leaves.id','DESC')->get();
		    		return view('leave',compact('leaves_list'));
		    	}
		    }
			else
	        {
	            return redirect('/')->with('status',"Please login First");
	        }
	    }
        catch(QueryException $e)
        {
            activity()->log($e->getMessage());
            return redirect()->back()->with('alert-danger',"Database Query Error! [ ".$e->getMessage()." ]");
        }
        catch(Exception $e)
        {
            activity()->log($e->getMessage());
            return redirect()->back()->with('alert-danger',$q->getMessage());
        }
    }

    /*public function show_mobile(Request $request)
    {
    	try
    	{
	    	activity()->log('fetching Leave Page for user ID'.session('user_id'));
	    		$emp_id = emp::select('id')->where('email',session('useremail'))->value('id');
	    		$leaves_list = leaves::where('emp_id',$emp_id)->orderBy('id','DESC')->get();
	    		return view('mobile_leave',compact('leaves_list'));
	    }
        catch(QueryException $e)
        {
            activity()->log($e->getMessage());
            return redirect()->back()->with('alert-danger',"Database Query Error! [ ".$e->getMessage()." ]");
        }
        catch(Exception $e)
        {
            activity()->log($e->getMessage());
            return redirect()->back()->with('alert-danger',$q->getMessage());
        }
    }*/

    public function showtable(Request $request)
    {
    	try
    	{
    		if(Session::get('username')!='')
	        {
    			$leaves_list = leaves::join('users','leaves.emp_id','=','users.id')->select('users.name','users.email','leaves.reason','leaves.from','leaves.to','leaves.status','leaves.id', 'leaves.rejection_reason','leaves.emp_id')->orderBy('leaves.id','DESC')->get();
	    		return view('leavetable',compact('leaves_list'));
    		}
			else
	        {
	            return redirect('/')->with('status',"Please login First");
	        }
	    }
        catch(QueryException $e)
        {
            activity()->log($e->getMessage());
            return redirect()->back()->with('alert-danger',"Database Query Error! [ ".$e->getMessage()." ]");
        }
        catch(Exception $e)
        {
            activity()->log($e->getMessage());
            return redirect()->back()->with('alert-danger',$q->getMessage());
        }
    }

    public function showtable_emp(Request $request)
    {
    	try
    	{
    		if(Session::get('username')!='')
	        {
		    	/*$emp_id = emp::select('id')->where('email',session('useremail'))->value('id');*/
				$leaves_list = leaves::where('emp_id',session('user_id'))->orderBy('id','DESC')->get();
				return view('leavetable_emp',compact('leaves_list'));
			}
			else
	        {
	            return redirect('/')->with('status',"Please login First");
	        }
		}
        catch(QueryException $e)
        {
            activity()->log($e->getMessage());
            return redirect()->back()->with('alert-danger',"Database Query Error! [ ".$e->getMessage()." ]");
        }
        catch(Exception $e)
        {
            activity()->log($e->getMessage());
            return redirect()->back()->with('alert-danger',$q->getMessage());
        }
    }
    public function submit(Request $request)
    {
    	try
        {
        	if(Session::get('username')!='')
	        {
	        	if(Session::get('useremail')=='admin@gmail.com')
	        	{
	        		return redirect()->back()->with('alert-danger',"Only Employee can apply for leave");
	        	}
	        	
		    	activity()->log('User ID '.session('user_id').' Trying To Apply for leave');
				$rules = array(
				'from'    => 'required|date|date_format:Y-m-d',
				'to'    => 'required|date|date_format:Y-m-d',
				'reason'    => 'required|string',
				'other_reason'		=> 'nullable|string'
				);
				$validator = Validator::make(Input::all(), $rules);

		        if ($validator->fails()) {
		            activity()->log('Apply for leave not submitted due to Validation Error');
		            return redirect()->back()
		            ->withErrors($validator)
		            ->withInput($request->all);
		        } 
		        $reason = $request->reason;
		        if($request->reason=='Other')
		        {
		        	if($request->other_reason=='')
		        	{
		        		return redirect()->back()->with('alert-danger',"Please fill other reason")->withInput($request->all);
		        	}
		        	else
		        	{
		        		$reason = $request->other_reason;
		        	}
		        }
		        /*$emp_id = DB::table('emp')->select('id')->where('email',session('useremail'))->value('id');*/
		        $leave = leaves::create([
		        	'emp_id' => session('user_id'),
		        	'user_id' => session('user_id'),
		        	'reason' => $reason,
		        	'from' => $request->from,
		        	'to' => $request->to,
		        	'status' => 'Pending',
		        	'created_at' => Carbon::now()
		        ]);
		        if($leave)
		        {
		        	/*$max_id = leaves::max('id');*/
		        	$notification = DB::table('notification')->insert([
		        		'user_id'=>Session::get('user_id'),'requester_id'=>session('user_id'),'notification'=>'Leave Application','notification_status'=>'Pending','link'=>'leave','status'=>'active']);
		        	/*$notification = DB::table('notification')->insert([
		        		'user_id'=>Session::get('user_id'),'requester_id'=>session('user_id'),'table_id'=>$max_id,'notification'=>'Leave Application','notification_status'=>'Pending','link'=>'leave','status'=>'active']);*/

		        	activity()->log('Applied for leave Successfully');
		        	return redirect()->back()->with('alert-success','Applied For Leave Successfully');
		        }
		    }
			else
	        {
	            return redirect('/')->with('status',"Please login First");
	        }
        }
        catch(QueryException $e)
        {
            activity()->log($e->getMessage());
            return redirect()->back()->with('alert-danger',"Database Query Error! [ ".$e->getMessage()." ]");
        }
        catch(Exception $e)
        {
            activity()->log($e->getMessage());
            return redirect()->back()->with('alert-danger',$q->getMessage());
        }
	}

	public function action(Request $request)
	{
		activity()->log('trying to update status of leave ID '.$request->id);
		try
		{
			if(Session::get('username')!='')
	        {
	        	if($request->emp_id==session('user_id'))
	        	{
	        		return redirect()->back();
	        	}
				$leave_id = $request->id;
				$action = $request->action;
				$reason = $request->reason;
				$leave_action = leaves::where('id',$leave_id)->update([
					'status'=>$action,
					'rejection_reason'=>$reason,
					'user_id'=>session('user_id'),
					'updated_at'=>Carbon::now()
				]);
				if($leave_action)
				{
					$emp_id = DB::table('leaves')->where('id',$leave_id)->value('emp_id');
					$leaves_update = DB::table('leaves_update')->insert([
						'user_id' =>session('user_id'),
						'leaves_id' => $leave_id,
						'status'=>$action,
						'rejection_reason'=>$reason,
						'updated_at'=>Carbon::now()
					]);
					
					/*$notification_update = DB::table('notification')->where('table_id',$leave_id)->update(['status'=>'inactive']);*/
					$notification = DB::table('notification')->insert([
		        		'user_id'=>Session::get('user_id'),'requester_id'=>$emp_id,'notification'=>'Leave Application ','notification_status'=>$action,'link'=>'leave','status'=>'active']);
					/*$notification = DB::table('notification')->insert([
		        		'user_id'=>Session::get('user_id'),'requester_id'=>$emp_id,'table_id'=>$leave_id,'notification'=>'Leave Application ','notification_status'=>$action,'link'=>'leave','status'=>'active']);*/

					activity()->log('status updated to '.$action.' successfully for leave ID '.$request->id);
					return redirect()->back()->with('success','Leave Application Updated Successfully');
				}
			}
			else
	        {
	            return redirect('/')->with('status',"Please login First");
	        }
		}
		catch(QueryException $e)
        {
            activity()->log($e->getMessage());
            return redirect()->back()->with('alert-danger',"Database Query Error! [ ".$e->getMessage()." ]");
        }
        catch(Exception $e)
        {
            activity()->log($e->getMessage());
            return redirect()->back()->with('alert-danger',$q->getMessage());
        }
	}
	public function getpending(Request $request)
	{
		try
		{
			if(Session::get('username')!='')
	        {
				$pending = DB::table('leaves')->where('status','Pending')->count();
				Session::put('pending_leaves',$pending);
				return $pending;
			}
			else
	        {
	            return redirect('/')->with('status',"Please login First");
	        }
		}
        catch(QueryException $e)
        {
            activity()->log($e->getMessage());
            return redirect()->back()->with('alert-danger',"Database Query Error! [ ".$e->getMessage()." ]");
        }
        catch(Exception $e)
        {
            activity()->log($e->getMessage());
            return redirect()->back()->with('alert-danger',$q->getMessage());
        }
	}
}
