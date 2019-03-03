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


class AdvanceController extends Controller
{
    public function index()
	{
        
		try
	    {
	    	if(Session::get('username')!='' && (session('role')=='admin' || session('role')=='hr'))
        	{
	        	activity()->log('Fetching employee detail');
                $emp = DB::table('emp')->get();
                $deduction = DB::table('advance')
                ->join('emp','advance.emp_id', '=' , 'emp.id')
                ->select('advance.advance','advance.advance_given_date','advance.deduction_start_date','advance.deduction_per_month','emp.title','emp.first_name','emp.middle_name','emp.last_name')
                 ->get();
				return view('advance',compact('emp','deduction'));
			}
	        else
	        {
	        	if(Session::get('username')=='')
	                return redirect('/')->with('status',"Please login First");
	            else
	            return redirect('dashboard')->with('alert-danger',"Only admin and HR can view advance");
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
    public function create_advance(Request $request)
	{		
		try
		{
			if(Session::get('username')!='')
        	{
                activity()->log('Trying to create advance');
              
                $emp = $request->input('emp');
				$advance = $request->input('total_advance');
				$deduction = $request->input('deduction');
				$advance_given_date = $request->input('advance_given_d');
				$deduction_start_date = $request->input('deduction_start_d');
				$advance=DB::table('advance')->insert(
				['advance'=>$advance,'advance_given_date'=>$advance_given_date,'deduction_start_date' =>$deduction_start_date,'deduction_per_month'=>$deduction,'emp_id'=>$emp,'user_id'=>Session::get('user_id'),'created_at'=>date('Y-m-d H:i:s')]
				);
			
				 if($advance)
				{
					activity()->log('advance '.$advance.' Created Successfully');
					$request->session()->flash('alert-success', 'advance Created Successfully!');
					return Redirect::to('advance');
				}
				else
				{
					activity()->log('advance '.$advance.' cannot be created');
					$request->session()->flash('alert-danger', 'advance cannot be created!');
					return Redirect::to('advance');
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
