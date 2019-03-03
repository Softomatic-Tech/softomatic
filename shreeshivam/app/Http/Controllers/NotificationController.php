<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

use App\Exception;
use \Illuminate\Database\QueryException;
date_default_timezone_set('Asia/Kolkata');

class NotificationController extends Controller
{ 
	public function show(Request $request)
	{
		if(Session::get('username')!='')
	    { 
	    	try
	    	{
	    		if(Session::get('role')=='admin')
	    		{
	    			$notification = DB::table('notification')->join('users','users.id','=','notification.user_id')->select('users.email','notification.id','notification.notification','notification.link','notification.notification_status','notification.created_at')->where('status','active')->where('notification_status','!=','Accepted')->Where('notification_status','!=','Rejected')->orderBy('id','desc')->get();
	    		}
	    		else
	    		{
	    			$notification = DB::table('notification')->where('status','active')->where('requester_id',Session::get('user_id'))->where('user_id','!=',Session::get('user_id'))->orderBy('id','desc')->get();
	    		}
	    		return view('notification',compact('notification'));
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
		else
        {
            return redirect('/')->with('status',"Please login First");
        }
	}

	public function showtable(Request $request)
	{
		if(Session::get('username')!='')
	    { 
	    	try
	    	{
	    		if(Session::get('role')=='admin')
	    		{
	    			$notification = DB::table('notification')->join('users','users.id','=','notification.user_id')->select('users.email','notification.id','notification.notification','notification.link','notification.notification_status','notification.created_at')->where('status','active')->where('notification_status','!=','Accepted')->Where('notification_status','!=','Rejected')->get();
	    		}
	    		else
	    		{
	    			$notification = DB::table('notification')->where('status','active')->where('requester_id',Session::get('user_id'))->where('user_id','!=',Session::get('user_id'))->get();
	    		}
	    		return view('notification_table',compact('notification'));
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
		else
        {
            return redirect('/')->with('status',"Please login First");
        }
	}

    public function get_notification(Request $request)
	{
		try
		{
			if(Session::get('username')!='')
	        {   
	        	$out='';

	        	if(Session::get('role')=='admin')
                {
					$notification_list = DB::table('notification')->where('status','active')->where('notification_status','!=','Accepted')->Where('notification_status','!=','Rejected')->orderBy('id','desc')->get();
					Session::put('notification',sizeof($notification_list));

					/*$notification_list =DB::table('notification')->where('status','active')->get();*/
					$notify = (array) json_decode($notification_list,true);
					$length = sizeof($notification_list);
					for($i=0;$i<$length;$i++)
                    {
		     			  $out.='<li><a href="'.$notify[$i]['link'].'"><div class="menu-info"><h4>'.$notify[$i]['notification'].' :'.$notify[$i]['notification_status'].'</h4><p><i class="material-icons">access_time</i> '.$notify[$i]['created_at'].'</p></div></a></li>';
                    }
	                Session::put('notification_list',$notification_list);

	                $data = json_encode(array('notification'=>$length,'notification_list'=>$out));
					return $data;
				}
				else
				{
					$notification_list = DB::table('notification')->where('status','active')->where('requester_id',Session::get('user_id'))->where('user_id','!=',Session::get('user_id'))->get();

					Session::put('notification',sizeof($notification_list));

					/*$notification = DB::table('notification')->where('status','active')->where('requester_id',Session::get('user_id'))->where('user_id','!=',Session::get('user_id'))->get();*/
					$notify = (array) json_decode($notification_list,true);
					$length = sizeof($notification_list);
					for($i=0;$i<$length;$i++)
                    {
		     			  $out.='<li><a href="'.$notify[$i]['link'].'"><div class="menu-info"><h4>'.$notify[$i]['notification'].' :'.$notify[$i]['notification_status'].'</h4><p><i class="material-icons">access_time</i> '.$notify[$i]['created_at'].'</p></div></a></li>';
                    }
	                Session::put('notification_list',$notification_list);

	                $data = json_encode(array('notification'=>$length,'notification_list'=>$out));
					return $data;
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

	public function dismiss(Request $request)
	{
		$id = $request->id;
		$update = DB::table('notification')->where('id',$id)->update(['status'=>'inactive']);
		if($update)
			return 1;
		else
			return 0;
	}

	public function show_history()
	{
		if(Session::get('username')!='')
	    { 
	    	try
	    	{
	    		if(Session::get('role')=='admin')
                {
		    		$histories = DB::table('upload_history')->get();
					return view('history',compact('histories'));
				}
				else
				{
					$histories = DB::table('upload_history')->where('user_email',session('usermail'))->get();
					return view('history',compact('histories'));
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
		else
        {
            return redirect('/')->with('status',"Please login First");
        }

	}

	public function show_history_table()
	{
		if(Session::get('username')!='')
	    { 
	    	try
	    	{
	    		if(Session::get('role')=='admin')
                {
		    		$histories = DB::table('upload_history')->get();
					return view('history_table',compact('histories'));
				}
				else
				{
					$histories = DB::table('upload_history')->where('user_email',session('usermail'))->get();
					return view('history_table',compact('histories'));
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
		else
        {
            return redirect('/')->with('status',"Please login First");
        }

	}
}
