<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Exception;
use \Illuminate\Database\QueryException;
date_default_timezone_set('Asia/Kolkata');

class HolidayController extends Controller
{
   public function __construct()
    {
        $this->middleware('auth', ['only' => 'store']);
    }
	public function get_holiday()
	{ 	
		try
	 	{
	 		if(Session::get('username')!='')
        	{
		 	activity()->log('Trying to fetch holidays');
			$holidays_view = DB::table('holidays')
								->orderby('id','desc')
								->get();			 
						 
			return view('holidays',compact('holidays_view'));
			}
			else
	        {
	            return redirect('/')->with('status',"Please login First");
	        }
		} 
        catch(QueryException $e)
        {
            activity()->log($e->getMessage());
            return redirect()->back()->with('alert-danger',"Database Query Error! [".$e->getMessage()." ]");
        }
        catch(Exception $e)
        {
            activity()->log($e->getMessage());
            return redirect()->back()->with('alert-danger',$e->getMessage());
        }
	}
   public function create_holiday(Request $request)
	{
		try
		{	
		 	if(Session::get('username')!='')
	        {
				$date = $request->input('date');
				$title = $request->input('title');
				$event = $request->input('event');
				
				$holiday=DB::table('holidays')->insert(
				['date' => $date,'title'=>$title,'event'=>$event,'created_at'=>Carbon::now('Asia/Kolkata')]
				);

				if($holiday)
				{
			     activity()->log('Department '.$holiday.' Created Successfully');
				 $request->session()->flash('alert-success', 'Holidays Created Successfully!');
				 return Redirect::to('holidays');
				}
				else
				{
					activity()->log('Department '.$holiday.' cannot be created');
					$request->session()->flash('alert-danger', 'Holidays cannot be created!');
					return Redirect::to('holidays');
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
	        return redirect()->back()->with('alert-danger',"Database Query Error! [".$e->getMessage()." ]");
	    }
	    catch(Exception $e)
	    {
	        activity()->log($e->getMessage());
	        return redirect()->back()->with('alert-danger',$e->getMessage());
	    }			
	}

   public function update_holiday(Request $request)
    {
		try
			{
				if(Session::get('username')!='')
	        	{
					$id=$request->input('id');
					$date = $request->input('date');
					$title = $request->input('title');
					$event = $request->input('event');
					$dept=DB::table('holidays')
						  ->where('id', $id)
						  ->update(['date' => $date,'title'=>$title,'event'=>$event,'updated_at'=>Carbon::now('Asia/Kolkata')]);
				if($dept)
					{
					activity()->log('Holidays with id '.$id.' Updated Successfully');
					$request->session()->flash('alert-success', 'Holidays Updated Successfully!');
					return Redirect::to('holidays');
					}
				else
					{
					activity()->log('Holidays with id '.$id.' cannot be updated');
					$request->session()->flash('alert-danger', 'Holidays cannot be updated!');
					return Redirect::to('holidays');
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
		        return redirect()->back()->with('alert-danger',"Database Query Error! [".$e->getMessage()." ]");
		    }
		    catch(Exception $e)
		    {
		        activity()->log($e->getMessage());
		        return redirect()->back()->with('alert-danger',$e->getMessage());
		    }
	
	}

public function delete_holiday(Request $request)
	{
	  try
	    {
	    	if(Session::get('username')!='')
	        {
				$id=$request->input('id');			
				$dept_delete=DB::table("holidays")->delete($id);
				if($dept_delete)
				{
					activity()->log('Holidays with id '.$id.' Deleted');
					$request->session()->flash('alert-success', 'Holidays Deleted Successfully!');
					return redirect()->back(); 
				}
				else
				{
					activity()->log('Holidays with id '.$id.'  cannot be Deleted');
				    $request->session()->flash('alert-danger', 'Holidays cannot be deleted!');
					return redirect()->back(); 
				}								
				return redirect()->back(); 
			}
			else
	        {
	            return redirect('/')->with('status',"Please login First");
	        }
	   	}	
	   	catch(QueryException $e)
	    {
	        activity()->log($e->getMessage());
	        return redirect()->back()->with('alert-danger',"Database Query Error! [".$e->getMessage()." ]");
	    }
	    catch(Exception $e)
	    {
	        activity()->log($e->getMessage());
	        return redirect()->back()->with('alert-danger',$e->getMessage());
	    }
	
   
	}	
}
