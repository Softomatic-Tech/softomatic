<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\Exception;
use \Illuminate\Database\QueryException;
date_default_timezone_set('Asia/Kolkata');

class BranchController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth', ['only' => 'store']);
    }	
 public function Index()
	{
		
		if(Session::get('username')!='' && (session('role')=='admin' || session('role')=='hr'))
        {
			
        activity()->log('Fetching All departments');
        try
        {
		$branch_view = DB::table('branch')
							->orderby('id','desc')
							->get();
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
					 
		return view('branch',compact('branch_view'));
		 }
        else
        {
        	if(Session::get('username')=='')
                return redirect('/')->with('status',"Please login First");
            else
            return redirect('dashboard')->with('alert-danger',"Only admin and HR can add employee");
        }
	}
	 public function create_branch(Request $request)
	{
		if(Session::get('username')!='')
        {
		$branch = $request->input('branch');
		$address = $request->input('address');
		activity()->log('Creating new Branch by name '.$branch.' ');
		try {

		$branch=DB::table('branch')->insert(
		['branch' => $branch,'address'=>$address,'created_at'=>now()]
		);

		 if($branch)
			{
			activity()->log('Branch '.$branch.' Created Successfully');
			$request->session()->flash('alert-success', 'Branch Created Successfully!');
			return Redirect::to('branch');
			}
		else
			{
			activity()->log('Branch '.$branch.' cannot be created');
			$request->session()->flash('alert-danger', 'Branch cannot be created!');
			return Redirect::to('branch');
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
    	    return redirect('/')->with('status',"Please login First");
        }
	
	}
public function update_branch(Request $request)
    {
    	if(Session::get('username')!='')
        {
		$id=$request->input('id');
		$branch = $request->input('branch');
		$address = $request->input('address');
		activity()->log(' Updating Branch details for id '.$id.' with Branch name '.$branch.' requested by user with id'.session('user_id').' ');


		try
		{
		$branch=DB::table('branch')
			  ->where('id', $id)
			  ->update(['branch' => $branch,'address'=>$address,'updated_at'=>Carbon::now()]);
	
		if($branch)
			{
			activity()->log('Branch with id '.$id.' Updated Successfully');
			$request->session()->flash('alert-success', 'Branch Updated Successfully!');
			return Redirect::to('branch');
			}
		else
			{
			activity()->log('Branch with id '.$id.' cannot be updated');
			$request->session()->flash('alert-danger', 'Branch cannot be updated!');
			return Redirect::to('branch');
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
    	    return redirect('/')->with('status',"Please login First");
        }
	
	}		
	public function delete_branch(Request $request)
	{
		if(Session::get('username')!='')
        {

		$id=$request->input('dept_id');

		activity()->log('Deleting Branch id '.$id.' on request of user with id '.session('user_id').' ');


		try
		{
		$branch=DB::table("branch")->delete($id);
			if($branch)
				{
					activity()->log('Branch with id '.$id.' Deleted');
					$request->session()->flash('alert-success', 'Branch Deleted Successfully!');
					return redirect()->back(); 
				}
			else
				{
					activity()->log('Branch with id '.$id.'  cannot be Deleted');
				    $request->session()->flash('alert-danger', 'Branch cannot be deleted!');
					return redirect()->back(); 
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
    	    return redirect('/')->with('status',"Please login First");
        }
	}

	public function save_working_hour(Request $request)
	{
		if(Session::get('username')!='')
        {
        	try
			{
				activity()->log('Trying To add Working Hour');
				$rules = array(
		            'full_day_from'    =>  'required|string',
		            'full_day_to'  =>  'required|string',
		            'half_day_from'    => 'required|string',
		            'half_day_to' => 'required|string',
		        );
				$validator = Validator::make(Input::all(), $rules);
		        if ($validator->fails()){
		            activity()->log('Add working hour process failed due to Validation Error');
		            return redirect()->back()->withErrors($validator)->withInput($request->all);
		        }
		        $count = DB::table('working_hour')->count();
		        if($count==0)
		        {
		        	$insert = DB::table('working_hour')->insert([
		        		'full_day_from'=>$request->input('full_day_from'),
		        		'full_day_to'=>$request->input('full_day_to'),
		        		'half_day_from'=>$request->input('half_day_from'),
		        		'half_day_to'=>$request->input('half_day_to'),
		        		'created_at'=>date('Y-m-d H:i:s'),
		        	]);
		        	if($insert)
		        	{
		        		return redirect()->back()->with('alert-success','Working Hour Added Successfully.');
		        	}
		        	else
		        	{
		        		return redirect()->back()->with('alert-danger','Working Hour Not Added.');
		        	}
		        }
		        else
		        {
		        	return redirect()->back()->with('alert-danger','Working Hour already added. Please click edit to make changes.');
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
    	    return redirect('/')->with('status',"Please login First");
        }
	}

	public function working_hour_form(Request $request)
	{
		if(Session::get('username')!='')
        {
        	try
			{				
		        $working = DB::table('working_hour')->get();
		        return view('working_hour',compact('working'));
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
    	    return redirect('/')->with('status',"Please login First");
        }
	}

	public function update_working_hour(Request $request)
	{
		if(Session::get('username')!='')
        {
        	try
			{
				activity()->log('Trying To add Working Hour');
				$rules = array(
					'id'	=>  'required|numeric',
		            'full_day_from'    =>  'required|string',
		            'full_day_to'  =>  'required|string',
		            'half_day_from'    => 'required|string',
		            'half_day_to' => 'required|string',
		        );
				$validator = Validator::make(Input::all(), $rules);
		        if ($validator->fails()){
		            activity()->log('Add working hour process failed due to Validation Error');
		            return redirect()->back()->with('alert-danger','Validation Error');
		        }

		        $update = DB::table('working_hour')->where('id',$request->input('id'))->update([
		        	'full_day_from'=>$request->input('full_day_from'),
	        		'full_day_to'=>$request->input('full_day_to'),
	        		'half_day_from'=>$request->input('half_day_from'),
	        		'half_day_to'=>$request->input('half_day_to'),
		        ]);

		        if($update)
	        	{
	        		return redirect()->back()->with('alert-success','Working Hour Updated Successfully.');
	        	}
	        	else
	        	{
	        		return redirect()->back()->with('alert-danger','Working Hour Not updated.');
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
    	    return redirect('/')->with('status',"Please login First");
        }
	}		
   			
  
}
