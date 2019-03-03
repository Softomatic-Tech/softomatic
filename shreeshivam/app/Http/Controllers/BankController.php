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

class BankController extends Controller
{
    public function get_form(Request $request)
    {
    	if(Session::get('username')!='' && (session('role')=='admin' || session('role')=='hr' || session('role')=='accountant' ))
        {
        	try
        	{
        		$banks = DB::table('bank')->leftjoin('branch','branch.id','=','bank.branch_location_id')->leftjoin('bank_list','bank_list.id','=','bank.bank_name')->select('bank_list.bank_name','bank_list.id as bank_list_id','bank.branch_location_id','bank.bank_branch','branch.branch','bank.bank_acc_no','bank.bank_ifsc_code','bank.bank_acc_no','bank.bank_acc_holder_name','bank.status','bank.created_at','bank.id')->get();
        		$bank_list = DB::table('bank_list')->get();
        		$branches = DB::table('branch')->get();
        		return view('bank',compact('banks','branches','bank_list'));
        	}
        	catch(QueryException $e)
		   	{
		   		activity()->log($e->getMessage());
				return Redirect::to('branch')->with('alert-danger',"Database Query Error! [".$e->getMessage()." ]");
		   	}
			catch(Exception $e)
		   	{
		   		activity()->log($e->getMessage());
				return Redirect::to('branch')->with('alert-danger',$e->getMessage());
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

    public function save(Request $request)
    {
    	if(Session::get('username')!='' && (session('role')=='admin' || session('role')=='hr' || session('role')=='accountant' ))
        {
        	try
        	{
		    	$rules = array(
		            'branch_location_id'    =>  'required|string',
		            'bank_name'  =>  'required|string',
		            'bank_branch'    => 'required|string',
		            'bank_acc_no' => 'required|numeric',
		            'bank_ifsc_code'    => 'required|string',
		            'bank_acc_holder_name'    => 'required|regex:/^[a-zA-Z ]+$/',
		            'status'    => 'required|string',
		         );
				$validator = Validator::make(Input::all(), $rules);
		        if ($validator->fails()) {
		            activity()->log('Add Bank process failed due to Validation Error');
		            return redirect()->back()->withErrors($validator)->withInput($request->all);
		        }
		        if($request->input('status')=='other')
		        {
		        	$insert=DB::table('bank')->insert([
		        	'branch_location_id' => $request->input('branch_location_id'),
		        	'bank_name'=> $request->input('bank_name'),
		        	'bank_branch'=> $request->input('bank_branch'),
		        	'bank_acc_no' => $request->input('bank_acc_no'),
		        	'bank_ifsc_code'=> $request->input('bank_ifsc_code'),
		        	'bank_acc_holder_name'=> $request->input('bank_acc_holder_name'),
		        	'status' => $request->input('status'),
		         	]);
		         	if($insert)
					{
					activity()->log('Bank Added Successfully');
					return redirect()->back()->with('alert-success', 'Bank Added Successfully!');
					}
					else
					{
					activity()->log('Bank Not Added');
					return redirect()->back()->with('alert-danger', 'Bank Not Added');
					}	
		        }
		        else
		        {
		        	$insert=DB::table('bank')->insert([
		        	'branch_location_id' => $request->input('branch_location_id'),
		        	'bank_name'=> $request->input('bank_name'),
		        	'bank_branch'=> $request->input('bank_branch'),
		        	'bank_acc_no' => $request->input('bank_acc_no'),
		        	'bank_ifsc_code'=> $request->input('bank_ifsc_code'),
		        	'bank_acc_holder_name'=> $request->input('bank_acc_holder_name'),
		        	'status' => $request->input('status'),
		        	'current_date' => date('Y-m-d')
		        	]);
		        	if($insert)
					{
					activity()->log('Bank Added Successfully');
					return redirect()->back()->with('alert-success', 'Bank Added Successfully!');
					}
					else
					{
					activity()->log('Bank Not Added');
					return redirect()->back()->with('alert-danger', 'Bank Not Added');
					}
		        }				

		    }
        	catch(QueryException $e)
		   	{
		   		activity()->log($e->getMessage());
				return Redirect::to('branch')->with('alert-danger',"Database Query Error! [".$e->getMessage()." ]");
		   	}
			catch(Exception $e)
		   	{
		   		activity()->log($e->getMessage());
				return Redirect::to('branch')->with('alert-danger',$e->getMessage());
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

    public function update(Request $request)
    {
    	if(Session::get('username')!='' && (session('role')=='admin' || session('role')=='hr' || session('role')=='accountant' ))
        {
        	try
        	{
		    	$rules = array(
		            'branch_location_id'    =>  'required|string',
		            'bank_name'  =>  'required|string',
		            'bank_branch'    => 'required|string',
		            'bank_acc_no' => 'required|numeric',
		            'bank_ifsc_code'    => 'required|string',
		            'bank_acc_holder_name'    => 'required|regex:/^[a-zA-Z ]+$/',
		            'status'    => 'required|string',
		         );
				$validator = Validator::make(Input::all(), $rules);
		        if ($validator->fails()) {
		            activity()->log('Update Employee process failed due to Validation Error');
		            return redirect()->back()->withErrors($validator)->withInput($request->all);
		        }
		        if($request->input('status')=='other')
		        {
		        	$insert=DB::table('bank')->where('id',$request->id)->update([
		        	'branch_location_id' => $request->input('branch_location_id'),
		        	'bank_name'=> $request->input('bank_name'),
		        	'bank_branch'=> $request->input('bank_branch'),
		        	'bank_acc_no' => $request->input('bank_acc_no'),
		        	'bank_ifsc_code'=> $request->input('bank_ifsc_code'),
		        	'bank_acc_holder_name'=> $request->input('bank_acc_holder_name'),
		        	'status' => $request->input('status'),
		         	]);
		         	if($insert)
					{
					activity()->log('Bank Updated Successfully');
					return redirect()->back()->with('alert-success', 'Bank Updated Successfully!');
					}
					else
					{
					activity()->log('Bank Not Updated');
					return redirect()->back()->with('alert-danger', 'Bank Not Updated');
					}	
		        }
		        else
		        {
		        	$insert=DB::table('bank')->where('id',$request->id)->update([
		        	'branch_location_id' => $request->input('branch_location_id'),
		        	'bank_name'=> $request->input('bank_name'),
		        	'bank_branch'=> $request->input('bank_branch'),
		        	'bank_acc_no' => $request->input('bank_acc_no'),
		        	'bank_ifsc_code'=> $request->input('bank_ifsc_code'),
		        	'bank_acc_holder_name'=> $request->input('bank_acc_holder_name'),
		        	'status' => $request->input('status'),
		        	'current_date' => date('Y-m-d')
		        	]);
		        	if($insert)
					{
					activity()->log('Bank Updated Successfully');
					return redirect()->back()->with('alert-success', 'Bank Updated Successfully!');
					}
					else
					{
					activity()->log('Bank Not Updated');
					return redirect()->back()->with('alert-danger', 'Bank Not Updated');
					}
		        }				

		    }
        	catch(QueryException $e)
		   	{
		   		activity()->log($e->getMessage());
				return Redirect::to('branch')->with('alert-danger',"Database Query Error! [".$e->getMessage()." ]");
		   	}
			catch(Exception $e)
		   	{
		   		activity()->log($e->getMessage());
				return Redirect::to('branch')->with('alert-danger',$e->getMessage());
		   	}	
        }
        else
        {
        	if(Session::get('username')=='')
                return redirect('/')->with('status',"Please login First");
            else
            return redirect('dashboard')->with('alert-danger',"Only admin , HR and accountant can update Bank");
        }
    }

}
