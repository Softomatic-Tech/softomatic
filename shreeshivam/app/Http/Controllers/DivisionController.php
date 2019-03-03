<?php

namespace App\Http\Controllers;

use App\emp;
use App\User;
use App\salary;
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

class DivisionController extends Controller
{
    public function get_division(Request $request)
    {
    	if(Session::get('username')!='' && (session('role')=='admin'))
        {
        	activity()->log('Trying To Fetch Division Page');
        	$division = DB::table('division')->get();
    		return view('division',compact('division'));
    	}
       else
        {
        	if(Session::get('username')=='')
                return redirect('/')->with('status',"Please login First");
            else
            return redirect('dashboard')->with('alert-danger',"Only admin and HR can access division page");
        }
    }

    public function save_division(Request $request)
    {
    	if(Session::get('username')!='' && (session('role')=='admin'))
        {
        	 $current_timestamp = Carbon::now();
        	activity()->log('Trying To Save Division');

	  		$rules = array(
	        'division'    =>  'required|string',
	        'status' => 'required|string',
	    	);

	    	$validator = Validator::make(Input::all(), $rules);

	        if ($validator->fails()) {
	            return redirect()->back()
	            ->withErrors($validator)
	            ->withInput($request->all);
	        } 
	        try
	        {
	        	$div = DB::table('division')->insert(['division'=>$request->input('division'),'status'=>$request->input('status'),'created_at'=>$current_timestamp]);
	        	if($div)
	        	{
	        		activity()->log('Division Created with name '.$request->input('division'));
	        		return redirect()->back()->with('alert-success',"Division Created Successfully");
	        	}
                else
                {
                     activity()->log('Division Not Created name '.$request->input('division'));
                    return redirect()->back()->with('alert-danger',"Process Failed");
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
	            return redirect()->back()->with('alert-danger',$e->getMessage());
	        }

        }
       	else
        {
        	if(Session::get('username')=='')
                return redirect('/')->with('status',"Please login First");
            else
            return redirect('dashboard')->with('alert-danger',"Only admin and HR can access division page");
        }
    }

    public function update_division(Request $request)
    {
    	if(Session::get('username')!='' && (session('role')=='admin'))
        {
        	$current_timestamp = Carbon::now();
        	activity()->log('Trying To Update Division');

	  		$rules = array(
	        'division'    =>  'required|string',
	        'status' => 'required|string',
	        'id' =>	'required|numeric',
	    	);

	  		$id = $request->id;
	  		
	    	$validator = Validator::make(Input::all(), $rules);

	        if ($validator->fails()) {
	            return redirect()->back()
	            ->withErrors($validator)
	            ->withInput($request->all)->with('alert-danger','Validation Error');
	        } 
	        try
	        {
	        	$div = DB::table('division')->where('id',$id)->update(['division'=>$request->input('division'),'status'=>$request->input('status'),'updated_at'=>$current_timestamp]);
	        	if($div)
	        	{
	        		activity()->log('Division with id '.$id.' Updated with name = '.$request->input('division, status = '.$request->input('status')));
	        		return redirect()->back()->with('alert-success',"Division Updated Successfully");
	        	}
                else
                {
                    activity()->log('Division Not Updated with id '.$id);
                    return redirect()->back()->with('alert-danger',"Updation Process Failed");
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
	            return redirect()->back()->with('alert-danger',$e->getMessage());
	        }

        }
       	else
        {
        	if(Session::get('username')=='')
                return redirect('/')->with('status',"Please login First");
            else
            return redirect('dashboard')->with('alert-danger',"Only admin and HR can access Division Page");
        }
    }

    public function get_section(Request $request)
    {
    	if(Session::get('username')!='' && (session('role')=='admin'))
        {
        	activity()->log('Trying To Fetch Section Page');
            $division = DB::table('division')->where('status','active')->get();
        	$section = DB::table('section')->join('division','section.division_id','=','division.id')->select('section.id','section.division_id','section','division.division','section.status')->get();
    		return view('section',compact('section','division'));
    	}
       else
        {
        	if(Session::get('username')=='')
                return redirect('/')->with('status',"Please login First");
            else
            return redirect('dashboard')->with('alert-danger',"Only admin and HR can access section");
        }
    }

    public function getsection(Request $request)
    {
        if(Session::get('username')!='' && (session('role')=='admin'))
        {
            $section = DB::table('section')->where('status','active')->where('division_id',$request->division)->get();
            $output ='<option> --Select Section-- </option>';
            for($i=0;$i<sizeof($section);$i++)
            {
                $output.='<option value="'.$section[$i]->id.'">'.$section[$i]->section.'</option>';
            }
            return $output;
        }
       else
        {
            if(Session::get('username')=='')
                return redirect('/')->with('status',"Please login First");
            else
            return redirect('dashboard')->with('alert-danger',"Only admin and HR can access section");
        }
    }

    public function getsubsection(Request $request)
    {
        if(Session::get('username')!='' && (session('role')=='admin'))
        {
            $subsection = DB::table('sub_section')->where('status','active')->where('division_id',$request->division)->where('section_id',$request->section)->get();
            $output ='<option value=""> --Select Sub Section-- </option>';
            for($i=0;$i<sizeof($subsection);$i++)
            {
                $output.='<option value="'.$subsection[$i]->id.'">'.$subsection[$i]->sub_section.'</option>';
            }
            return $output;
        }
       else
        {
            if(Session::get('username')=='')
                return redirect('/')->with('status',"Please login First");
            else
            return redirect('dashboard')->with('alert-danger',"Only admin and HR can access sub section");
        }
    }

    public function save_section(Request $request)
    {
        if(Session::get('username')!='' && (session('role')=='admin'))
        {
             $current_timestamp = Carbon::now();
            activity()->log('Trying To Save Division');

            $rules = array(
            'section' => 'required|string',
            'division'    =>  'required|numeric',
            'status' => 'required|string',
            );

            $validator = Validator::make(Input::all(), $rules);

            if ($validator->fails()) {
                return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->all);
            } 
            try
            {
                $div = DB::table('section')->insert(['section'=>$request->input('section'),'division_id'=>$request->input('division'),'status'=>$request->input('status'),'created_at'=>$current_timestamp]);
                if($div)
                {
                    activity()->log('Section Created with name '.$request->input('section'));
                    return redirect()->back()->with('alert-success',"Section Created Successfully");
                }
                else
                {
                     activity()->log('Section Not Created with name '.$request->input('section'));
                    return redirect()->back()->with('alert-danger',"Process Failed");
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
                return redirect()->back()->with('alert-danger',$e->getMessage());
            }

        }
        else
        {
            if(Session::get('username')=='')
                return redirect('/')->with('status',"Please login First");
            else
            return redirect('dashboard')->with('alert-danger',"Only admin and HR can access section");
        }
    }

    public function update_section(Request $request)
    {
        if(Session::get('username')!='' && (session('role')=='admin'))
        {
             $current_timestamp = Carbon::now();
            activity()->log('Trying To Update Division');

            $rules = array(
            'section' => 'required|string',
            'division'    =>  'required|numeric',
            'status' => 'required|string',
            );

            $validator = Validator::make(Input::all(), $rules);

            if ($validator->fails()) {
                return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->all);
            } 
            try
            {
                $div = DB::table('section')->where('id',$request->id)->update(['section'=>$request->input('section'),'division_id'=>$request->input('division'),'status'=>$request->input('status'),'updated_at'=>$current_timestamp]);
                if($div)
                {
                    activity()->log('Section Updated with id '.$request->input('id'));
                    return redirect()->back()->with('alert-success',"Section Updated Successfully");
                }
                else
                {
                     activity()->log('Section Not Updated with id '.$request->input('id'));
                    return redirect()->back()->with('alert-danger',"Updation Process Failed");
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
                return redirect()->back()->with('alert-danger',$e->getMessage());
            }

        }
        else
        {
            if(Session::get('username')=='')
                return redirect('/')->with('status',"Please login First");
            else
            return redirect('dashboard')->with('alert-danger',"Only admin and HR can access section");
        }
    }

    public function get_sub_section(Request $request)
    {
    	if(Session::get('username')!='' && (session('role')=='admin'))
        {
        	activity()->log('Trying To Fetch Sub Section Page');

            $division = DB::table('division')->where('status','active')->get();
            $section = DB::table('section')->where('status','active')->get();
           
        	$sub_section = DB::table('sub_section')->join('division','sub_section.division_id','=','division.id')->join('section','sub_section.section_id','=','section.id')->select('sub_section.id','sub_section.division_id','division.division','section.section','sub_section.section_id','sub_section','sub_section.status')->get();
    		return view('sub-section',compact('sub_section','section','division'));
    	}
       else
        {
        	if(Session::get('username')=='')
                return redirect('/')->with('status',"Please login First");
            else
            return redirect('dashboard')->with('alert-danger',"Only admin and HR can access Sub Section");
        }
    }

    public function save_sub_section(Request $request)
    {
        if(Session::get('username')!='' && (session('role')=='admin'))
        {
             $current_timestamp = Carbon::now();
            activity()->log('Trying To Save Sub section');

            $rules = array(
            'sub_section' => 'required|string',
            'division'    =>  'required|numeric',
            'section'   =>  'required|numeric',
            'status' => 'required|string',
            );

            $validator = Validator::make(Input::all(), $rules);

            if ($validator->fails()) {
                return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->all);
            } 
            try
            {
                $div = DB::table('sub_section')->insert(['sub_section'=>$request->input('sub_section'),'division_id'=>$request->input('division'),'section_id'=>$request->input('section'),'status'=>$request->input('status'),'created_at'=>$current_timestamp]);
                if($div)
                {
                    activity()->log('Sub Section Created with name '.$request->input('sub_section'));
                    return redirect()->back()->with('alert-success',"Sub Section Created Successfully");
                }
                 else
                {
                     activity()->log('Sub Section Not Created ');
                    return redirect()->back()->with('alert-danger',"Process Failed");
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
                return redirect()->back()->with('alert-danger',$e->getMessage());
            }

        }
        else
        {
            if(Session::get('username')=='')
                return redirect('/')->with('status',"Please login First");
            else
            return redirect('dashboard')->with('alert-danger',"Only admin and HR can access sub section");
        }
    }

    public function update_sub_section(Request $request)
    {
        if(Session::get('username')!='' && (session('role')=='admin'))
        {
             $current_timestamp = Carbon::now();
            activity()->log('Trying To Update Sub Section');

            $rules = array(
            'sub_section' => 'required|string',
            'division'    =>  'required|numeric',
            'section'   =>  'required|numeric',
            'status' => 'required|string',
            );

            $validator = Validator::make(Input::all(), $rules);

            if ($validator->fails()) {
                return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->all);
            } 
            try
            {
                $div = DB::table('sub_section')->where('id',$request->id)->update(['sub_section'=>$request->input('sub_section'),'division_id'=>$request->input('division'),'section_id'=>$request->input('section'),'status'=>$request->input('status'),'updated_at'=>$current_timestamp]);
                if($div)
                {
                    activity()->log('Sub Section Updated with id '.$request->input('id'));
                    return redirect()->back()->with('alert-success',"Sub Section Updated Successfully");
                }
                else
                {
                     activity()->log('Sub Section Not Updated ');
                    return redirect()->back()->with('alert-danger',"Updation Process Failed");
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
                return redirect()->back()->with('alert-danger',$e->getMessage());
            }

        }
        else
        {
            if(Session::get('username')=='')
                return redirect('/')->with('status',"Please login First");
            else
            return redirect('dashboard')->with('alert-danger',"Only admin and HR can access sub section");
        }
    }

}
