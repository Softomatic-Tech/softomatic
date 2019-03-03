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
date_default_timezone_set('Asia/Kolkata');

class DeletepayslipController extends Controller
{
    public function index(Request $request)
    {
    	if(Session::get('username')!='' && (session('role')=='admin' || session('role')=='hr' || session('role')=='accountant' ))
        {
        	try
        	{
        		$branches = DB::table('branch')->get();
        		return view('deletepayslip',compact('branches'));
        	}
        	catch(QueryException $e)
		   	{
		   		activity()->log($e->getMessage());
				return Redirect::to('deletepayslip')->with('alert-danger',"Database Query Error! [".$e->getMessage()." ]");
		   	}
			catch(Exception $e)
		   	{
		   		activity()->log($e->getMessage());
				return Redirect::to('deletepayslip')->with('alert-danger',$e->getMessage());
		   	}	
        }
        else
        {
        	if(Session::get('username')=='')
                return redirect('/')->with('status',"Please login First");
            else
            return redirect('dashboard')->with('alert-danger',"Only admin , HR and accountant can add Bank");
        }
    }
    
     public function deletepayslip(Request $request)
    {
        if($request->input('zone')=='' )
        {
            return Redirect::to('deletepayslip')->with('alert-warning',"Select Branch First");
        }
        if(DB::table('payslip_master')->count()=='' || DB::table('payslip_master')->count()=='')
        {
             return Redirect::to('deletepayslip')->with('alert-info',"There is no data to delete");
        }
    	if(Session::get('username')!='' && (session('role')=='admin' || session('role')=='hr' || session('role')=='accountant' ))
        {
        	try
        	{
        	     $month=date('n');
        		 $branch = $request->input('zone');
        		 $employee =DB::table('emp')->join('payslip_master','emp.id', '=' , 'payslip_master.emp_id')->select('payslip_master.id','payslip_master.emp_id')
         ->where('emp.branch_location_id','=',$branch)->whereMonth('payslip_master.created_at','=',$month)->get();
        	     foreach($employee as $emp)
        	     {
        	         
        	         
        	         
        	          DB::table('payslip_master')->where('id', '=', $emp->id)->delete();
        	          
        	     }
        	     
        	      $payslip_latest =DB::table('emp')->join('payslip_latest','emp.id', '=' , 'payslip_latest.emp_id')->select('payslip_latest.id','payslip_latest.emp_id')
         ->where('emp.branch_location_id','=',$branch)->whereMonth('payslip_latest.created_at','=',$month)->get();
        	     foreach($payslip_latest as $latest)
        	         {
        	             DB::table('payslip_latest')->where('emp_id', '=', $latest->emp_id)->delete();
        	         }
        	         
        	     return Redirect::to('deletepayslip')->with('alert-success',"Payslip deleted sucessfully");
        	}
        	catch(QueryException $e)
		   	{
		   		activity()->log($e->getMessage());
				return Redirect::to('deletepayslip')->with('alert-danger',"Database Query Error! [".$e->getMessage()." ]");
		   	}
			catch(Exception $e)
		   	{
		   		activity()->log($e->getMessage());
				return Redirect::to('deletepayslip')->with('alert-danger',$e->getMessage());
		   	}	
        }
        else
        {
        	if(Session::get('username')=='')
                return redirect('/')->with('status',"Please login First");
            else
            return redirect('dashboard')->with('alert-danger',"Only admin , HR and accountant can add Bank");
        }
    }
}