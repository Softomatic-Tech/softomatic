<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;


use App\Exception;
use \Illuminate\Database\QueryException;
date_default_timezone_set('Asia/Kolkata');


class NoticeController extends Controller
{
	public function get_notice()
	{
		if(Session::get('username')!='')
	    {
			$notice_view = DB::table('notice_board')
								->orderby('id','desc')
								->get();	 		 
			return view('notice-board',compact(['notice_view']));
		}
		else
		{
			return redirect('/')->with('status',"Please login First");
		}
	}
   public function create_notice(Request $request)
	{
	 try
	 {	
	 	if(Session::get('username')!='')
	    {
			$date = $request->input('date');
			$title = $request->input('title');
			$notice = $request->input('notice');
			
			$notice=DB::table('notice_board')->insert(
			['date' => $date,'title'=>$title,'notice'=>$notice,'created_at'=>Carbon::now('Asia/Kolkata')]
			);

			if($notice)
			{
			     activity()->log('Notice '.$notice.' Created Successfully');
				 $request->session()->flash('alert-success', 'Notice Created Successfully!');
				 return Redirect::to('notice-board');
			}
			else
			{
				activity()->log('Notice '.$notice.' cannot be created');
				$request->session()->flash('alert-danger', 'Notice cannot be created!');
				return Redirect::to('notice-board');
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
	public function update_notice(Request $request)
    {
		try
		{
			if(Session::get('username')!='')
	    	{
				$id=$request->input('id');
				$date = $request->input('date');
				$title = $request->input('title');
			    $notice = $request->input('notice');
				$notice=DB::table('notice_board')
					  ->where('id', $id)
					  ->update(['date' => $date,'title'=>$title,'notice'=>$notice,'updated_at'=>Carbon::now('Asia/Kolkata')]);
				if($notice)
				{
					activity()->log('Notice with id '.$id.' Updated Successfully');
					$request->session()->flash('alert-success', 'Notice Updated Successfully!');
					return Redirect::to('notice-board');
				}
				else
				{
					activity()->log('Notice with id '.$id.' cannot be updated');
					$request->session()->flash('alert-danger', 'Notice cannot be updated!');
					return Redirect::to('notice-board');
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
	public function delete_notice(Request $request)
	{
	  try
	    {
	    	if(Session::get('username')!='')
	    	{
				$id=$request->input('id');			
				$notice_delete=DB::table("notice_board")->delete($id);
				if($notice_delete)
				{
					activity()->log('Notice with id '.$id.' Deleted');
					$request->session()->flash('alert-success', 'Notice Deleted Successfully!');
					return redirect()->back(); 
				}
				else
				{
					activity()->log('Notice with id '.$id.'  cannot be Deleted');
				    $request->session()->flash('alert-danger', 'Notice cannot be deleted!');
					return redirect()->back(); 
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
}
