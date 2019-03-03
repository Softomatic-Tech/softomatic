<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Exception;
use \Illuminate\Database\QueryException;
date_default_timezone_set('Asia/Kolkata');

class DepartmentController extends Controller
{
	
   public function __construct()
    {
        $this->middleware('auth', ['only' => 'store']);
    }	
 public function get_department()
	{
		
        try
        {
        	if(Session::get('username')!='' && (session('role')=='admin' || session('role')=='hr'))
	        {
		        activity()->log('Fetching All departments');
				$department_view = DB::table('department')
									->orderby('id','desc')
									->get();
				return view('department',compact('department_view'));
			}
	        else
	        {
	        	if(Session::get('username')=='')
	                return redirect('/')->with('status',"Please login First");
	            else
	            return redirect('dashboard')->with('alert-danger',"Only admin and HR can view department");
	        }
		} 
		catch(QueryException $e)
	   	{
	   		activity()->log($e->getMessage());
			return Redirect::to('department')->with('alert-danger',"Database Query Error! [".$e->getMessage()." ]");
	   	}
		catch(Exception $e)
	   	{
	   		activity()->log($e->getMessage());
			return Redirect::to('department')->with('alert-danger',$e->getMessage());
	   	}			 
	}
  public function create_department(Request $request)
	{
		
		try 
		{
			if(Session::get('username')!='')
        	{

				$department = $request->input('department');		
				activity()->log('Creating new department by name '.$department.' ');
				$dept=DB::table('department')->insert(
				['department_name' => $department,'created_at'=>now()]
				);

			 	if($dept)
				{
				activity()->log('Department '.$department.' Created Successfully');
				$request->session()->flash('alert-success', 'Department Created Successfully!');
				return Redirect::to('department');
				}
				else
				{
				activity()->log('Department '.$department.' cannot be created');
				$request->session()->flash('alert-danger', 'Department cannot be created!');
				return Redirect::to('department');
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
			return Redirect::to('department')->with('alert-danger',"Database Query Error! [".$e->getMessage()." ]");
	   	}	
	   	catch(Exception $e)
	   	{
	   		activity()->log($e->getMessage());
			return Redirect::to('department')->with('alert-danger',$e->getMessage());
	   	}
	
	}	
	public function update_department(Request $request)
    {
		
		try
		{
			if(Session::get('username')!='')
        	{
				$id=$request->input('id');
				$department = $request->input('department');

				activity()->log(' Updating department details for id '.$id.' with department name '.$department.' requested by user with id'.session('user_id').' ');

				$dept=DB::table('department')
				  ->where('id', $id)
				  ->update(['department_name' => $department,'updated_at'=>Carbon::now()]);
		
				if($dept)
				{
					activity()->log('Department with id '.$id.' Updated Successfully');
					$request->session()->flash('alert-success', 'Department Updated Successfully!');
					return Redirect::to('department');
				}
				else
				{
					activity()->log('Department with id '.$id.' cannot be updated');
					$request->session()->flash('alert-danger', 'Department cannot be updated!');
					return Redirect::to('department');
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
			return Redirect::to('department')->with('alert-danger',"Database Query Error! [".$e->getMessage()." ]");
	   	}
		catch(Exception $e)
	   	{
	   		activity()->log($e->getMessage());
			return Redirect::to('department')->with('alert-danger',$e->getMessage());
	   	}
	
	}		
					
	public function delete_department(Request $request)
	{

		try
		{
			if(Session::get('username')!='')
        	{
				$id=$request->input('dept_id');

				activity()->log('Deleting department id '.$id.' on request of user with id '.session('user_id').' ');
				$dept_delete=DB::table("department")->delete($id);
				if($dept_delete)
				{
					activity()->log('Department with id '.$id.' Deleted');
					$request->session()->flash('alert-success', 'Department Deleted Successfully!');
					return redirect()->back(); 
				}
				else
				{
					activity()->log('Department with id '.$id.'  cannot be Deleted');
				    $request->session()->flash('alert-danger', 'Department cannot be deleted!');
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
			return Redirect::to('department')->with('alert-danger',"Database Query Error! [".$e->getMessage()." ]");
	   	}
		catch(Exception $e)
	   	{
	   		activity()->log($e->getMessage());
			return Redirect::to('department')->with('alert-danger',$e->getMessage());
	   	}	
						
	}		
   
	
}
