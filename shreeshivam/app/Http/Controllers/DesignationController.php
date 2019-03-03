<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use App\Exception;
use \Illuminate\Database\QueryException;
date_default_timezone_set('Asia/Kolkata');

class DesignationController extends Controller
{
	public function get_designation()
	{
		try
	    {
	    	if(Session::get('username')!='' && (session('role')=='admin' || session('role')=='hr'))
        	{
	        	activity()->log('Fetching designation ');
				$designation_view = DB::table('designation')
							 ->join('department','designation.department', '=' , 'department.id')
							->select('designation.id','designation.department','designation.designation','designation.created_at','department.department_name')
							 ->distinct()
							 ->orderby('designation.id')
							 ->get();
				 $depts = DB::table('department')->get();
				 return view('designation',compact('designation_view','depts'));
			}
	        else
	        {
	        	if(Session::get('username')=='')
	                return redirect('/')->with('status',"Please login First");
	            else
	            return redirect('dashboard')->with('alert-danger',"Only admin and HR can view designation");
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
	public function create_designation(Request $request)
	{		
		try
		{
			if(Session::get('username')!='')
        	{
				activity()->log('Trying to create designation');
				$designation = $request->input('designation');
				$department = $request->input('department');
				$dept=DB::table('designation')->insert(
				['designation'=>$designation,'department' => $department,'created_at'=>now()]
				);
			
				 if($dept)
				{
					activity()->log('Designation '.$designation.' Created Successfully');
					$request->session()->flash('alert-success', 'Designation Created Successfully!');
					return Redirect::to('designation');
				}
				else
				{
					activity()->log('Designation '.$designation.' cannot be created');
					$request->session()->flash('alert-danger', 'Designation cannot be created!');
					return Redirect::to('designation');
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
            return redirect()->back()->with('failure',"Database Query Error! [".$e->getMessage()." ]");
        }
		catch(Exception $e)
	   	{
	   	activity()->log($e->getMessage());
			return redirect()->back()->with('alert-danger',$e->getMessage());
	   	}
	
	}
	public function update_designation(Request $request)
		{

			try
			{
				if(Session::get('username')!='')
        		{
					$id=$request->input('id');
					activity()->log('Trying to update designation with id '.$id.' ');
					$department = $request->input('department');
					$designation = $request->input('designation');

					$dept=DB::table('designation')
					  ->where('id', $id)
					  ->update(['designation' => $designation,'department' => $department,'updated_at'=>Carbon::now()]);
			
					if($dept)
						{
						activity()->log('Designation with id '.$id.' Updated Successfully');
						$request->session()->flash('alert-success', 'Designation Updated Successfully!');
						return Redirect::to('designation');
						}
					else
						{
						activity()->log('Designation id '.$id.' cannot be updated');
						$request->session()->flash('alert-danger', 'Designation cannot be updated!');
						return Redirect::to('designation');
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
                return redirect()->back()->with('failure',"Database Query Error! [".$e->getMessage()." ]");
            } 
			catch(Exception $e)
		   	{
		   		activity()->log($e->getMessage());
				return redirect()->back()->with('alert-danger',$e->getMessage());
		   	}
		
		}
        public function delete_designation(Request $request)
			{
				
				try
				{
					if(Session::get('username')!='')
        			{
						$id=$request->input('desig_id');
						activity()->log('Trying to delete designation with id '.$id.' ');
						$desig_delete=DB::table("designation")->delete($id);
						if($desig_delete)
							{
								activity()->log('Designation with id '.$id.' Deleted');
								$request->session()->flash('alert-success', 'Designation Deleted Successfully!');
								return redirect()->back(); 
							}
						else
							{
								activity()->log('Designation with id '.$id.'  cannot be Deleted');
								$request->session()->flash('alert-danger', 'Designation cannot be deleted!');
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
	                return redirect()->back()->with('failure',"Database Query Error! [".$e->getMessage()." ]");
	            }
				catch(Exception $e)
			   	{
			   		activity()->log($e->getMessage());

					return redirect()->back()->with('alert-danger',$e->getMessage());
			   	}
			}				
}
