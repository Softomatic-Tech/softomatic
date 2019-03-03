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
use File;
use App\Exception;
use \Illuminate\Database\QueryException;
ini_set('max_execution_time', 3000);
date_default_timezone_set('Asia/Kolkata');

class EmpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getform()
    {
        if(Session::get('username')!='' && (session('role')=='admin' || session('role')=='hr'))
        {
            activity()->log('Loading Add Employee page for User with id '.Session::get('user_id').' ');

            try{
            $depts = DB::table('department')->get();
            /*$depts = DB::table('department')->where('department_name','!=','Admin')->orWhere('department_name','!=','admin')->get();*/
            $dept_id = $depts->first()->id;
            $desigs = DB::table('designation')->where('department',$dept_id)->get();
            $roles = DB::table('role')->get();
            $last_insert_id = DB::table('emp')->max('id') + 1;
            $branches = DB::table('branch')->get();
            $bank_list = DB::table('bank_list')->get();
            return view('add-emp',compact('depts','roles','last_insert_id','desigs','branches','bank_list'));
            }
            catch(QueryException $e)
            {
                activity()->log($e->getMessage());
                return redirect()->back()->with('alert-danger','Database Query Exception! ['.$e->getMessage().' ]');
            }
            catch(Exception $e)
            {
                activity()->log($e->getMessage());
                return redirect()->back()->with('alert-danger',$e->getMessage());
            }

            // $last_insert_id = DB::table('emp')->orderBy('id','desc')->limit('1','1')->value('id');
            // $last_insert_id++;

            
        }
        else
        {
            if(Session::get('username')=='')
                return redirect('/')->with('status',"Please login First");
            else
            return redirect('dashboard')->with('alert-danger',"Only admin and HR can add employee");
        }
    }

    public function getdesig(Request $request)
    {
        $dept_id = $request->dept_id;
        $options='<option></option>';
        try
        {
            $desigs = DB::table('designation')->where('department',$dept_id)->get();
            foreach($desigs as $key=>$value)
            {
                //return $value->id;
                $options.='<option value="'.$value->id.'">'.$value->designation.'</option>';
                // return $options;
            }
        }
        catch(QueryException $e)
        {
            activity()->log($e->getMessage());
            return redirect()->back()->with('alert-danger','Database Query Exception! ['.$e->getMessage().' ]');
        }
        catch(Exception $e)
        {
            activity()->log($e->getMessage());
            return redirect()->back()->with('alert-danger',$e->getMessage());
        }
        return $options;
    }

    public function create(Request $request)
    {
        try
        {
       
        activity()->log('Trying To Add Employee');
        $current_timestamp = Carbon::now()->timestamp;
        $msg='';
        $rules = array(
        'genesis_id'    =>  'nullable|string|unique:emp',
        'genesis_ledger_id'    =>  'required|string|unique:emp',
        'biometric_id'  =>  'nullable|string',
        'branch_location_id'    => 'required|string',
        'title' => 'required|string',
        'first_name'    => 'required|regex:/^[a-zA-Z ]+$/',
        'middle_name'    => 'nullable|regex:/^[a-zA-Z ]+$/',
        'last_name'    => 'required|regex:/^[a-zA-Z ]+$/',
        'email'    => 'required|email|unique:users|unique:emp', 
        'blood_group'    => 'nullable|string',
        'dob'    => 'nullable|date|date_format:Y-m-d',
        'mobile'    => 'required|numeric|digits:10',
        'gender'    => 'nullable|string',
        'category' => 'nullable|string',
        'marital_status'    => 'nullable|string',
        'local_address'    => 'nullable|string',
        'adhaar_number'    => 'nullable|numeric|digits:12|unique:emp',
        'pan_number'    => 'nullable|string|unique:emp|max:10|min:10|unique:emp',
        'permanent_address'    => 'nullable|string',
        'emergency_call_person' => 'nullable|regex:/^[a-zA-Z ]+$/',
        'emergency_call_number' => 'nullable|numeric|digits:10',
        'father_name' => 'nullable|regex:/^[a-zA-Z ]+$/',
        'father_dob' => 'nullable|date_format:Y-m-d',
        'father_adhaar' => 'nullable|numeric|digits:12',
        'father_place' => 'nullable|string',
        'mother_name' => 'nullable|regex:/^[a-zA-Z ]+$/',
        'mother_dob' => 'nullable|date_format:Y-m-d',
        'mother_adhaar' => 'nullable|numeric|digits:12',
        'mother_place' => 'nullable|string',
        'spouse_name' => 'nullable|regex:/^[a-zA-Z ]+$/',
        'spouse_gender' => 'nullable|string',
        'spouse_dob' => 'nullable|date_format:Y-m-d',
        'spouse_adhaar' => 'nullable|numeric|digits:12',
        'spouse_place' => 'nullable|string',
        'no_of_children' => 'nullable|string',
        'child_name' => 'nullable',
        'child_gender' => 'nullable',
        'child_dob' => 'nullable',
        'child_adhaar' => 'nullable',
        'child_place' => 'nullable',
        'department'    => 'required|string',
        'designation'    => 'required|string',
        'status'    => 'required|string',
        'esic_number' => 'nullable|numeric|digits:10',
        'epf_number' => 'nullable|string',
        'lin_number' => 'nullable|string',
        'uan_number' => 'nullable|numeric|digits:12',
        'esic_option' => 'nullable',
        'epf_option' => 'nullable',
        'salary' => 'required|numeric',
        'basic' => 'nullable|numeric',
        'doj'    => 'required|date|date_format:Y-m-d',
        'acc_holder_name'    => 'required|string',
        'acc_no'    => 'required|numeric',
        'ifsc_code'    => 'required|string',
        'bank_name' => 'required|numeric',
        'branch'    => 'required|string',
        'login_email' => 'required|email',
        'password' => 'nullable|string',
        'role' => 'required|numeric',
        'photo'    => 'nullable|image|max:10000'
        );

        if($request->input('password')=='')
        {
            $password='1234';
        }
        else
        {
            $password=$request->input('password');
        }
        $desig = DB::table('designation')->where('id',$request->department)->value('designation');
        if(strtolower($desig)=="fashion consultant")
        {
            if($request->input('genesis_id')=='')
            {
              return redirect()->back()->with('alert-danger',"Genesis Id is mandatory for Employee of Fashion Consultant")->withInput($request->all);  
            }
        }
        if($request->input('epf_option')==1)
        {
            if($request->input('epf_number')=='')
            {
                 return redirect()->back()->with('alert-danger',"EPF Number Required")->withInput($request->all);  
            }
        }
        if($request->input('esic_option')==1)
        {
            if($request->input('esic_number')=='')
            {
                 return redirect()->back()->with('alert-danger',"ESIC Number Required")->withInput($request->all);  
            }
        }

        $acc_no_limit1 = DB::table('bank_list')->where('id',$request->bank_name)->value('acc_no_limit');
        $acc_no_limit = explode(',', $acc_no_limit1);
        $accno_len = strlen((string)$request->acc_no);
        if(!in_array($accno_len, $acc_no_limit))
        {
            return redirect()->back()->with('alert-danger',"Acc no must be of ".$acc_no_limit1." digits")->withInput($request->all);  
        }

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            activity()->log('Add Employee process failed due to Validation Error');
            return redirect()->back()
            ->withErrors($validator)
            ->withInput($request->all);
        } 

            $esic_option='';
            $epf_option='';
            if($request->input('esic_option')=='')
            {
                $esic_option=0;
            }
            else
            {
                $esic_option=$request->input('esic_option');
            }

            if($request->input('epf_option')=='')
            {
                $epf_option=0;
            }
            else
            {
                $epf_option=$request->input('epf_option');
            }

            if($epf_option=='1')
            {
                if($request->basic=='')
                {
                    return redirect()->back()
                    ->with('alert-danger','Basic + DA required')
                    ->withInput($request->all);
                }
                else
                {
                    $basic= $request->basic;
                }
            }
            else
            {
                $basic = 0;
            }

            $salary = array();
            $salary['salary'] = $request->salary;
            $salary['basic'] = $basic;
            $salary = json_encode(array('emp_salary'=>$salary));            
            $email = DB::table('emp')->where('email', $request->email)->value('email');
            $mobile = DB::table('emp')->where('mobile', $request->mobile)->value('mobile');

            if($email!='')
            {
                $msg.='Email-Id is already registered';
            }
            if($mobile!='')
            {
                $msg.=' Mobile number is already registered';
            }
            if($email!='' || $mobile!='')
            {
                activity()->log('Add Employee process failed! '.$msg.'');
                return redirect()->back()->with('alert-danger',$msg)->withInput($request->all);
            }
            
            if($request->file('photo')=='')
            {
                $emp = emp::create([
                'genesis_id'    =>  $request->input('genesis_id'),
                'genesis_ledger_id'    =>  $request->input('genesis_ledger_id'),
                'biometric_id'  =>  $request->input('biometric_id'),
                'branch_location_id'  =>  $request->input('branch_location_id'),
                'title' => $request->input('title'),
                'first_name' => $request->input('first_name'),
                'middle_name' => $request->input('middle_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'dob' => $request->input('dob'),
                'mobile' => $request->input('mobile'),
                'blood_group' => $request->input('blood_group'),
                'marital_status' => $request->input('marital_status'),
                'gender' => $request->input('gender'),
                'category' => $request->input('category'),
                'adhaar_number' =>$request->input('adhaar_number'),
                'pan_number' => $request->input('pan_number'),
                'local_address' => $request->input('local_address'),                    
                'permanent_address' => $request->input('permanent_address'),
                'emergency_call_person' => $request->input('emergency_call_person'),
                'emergency_call_number' => $request->input('emergency_call_number'),
                'department' => $request->input('department'),                
                'designation' => $request->input('designation'),
                'doj' => $request->input('doj'),
                'status' => $request->input('status'),
                'esic_number' => $request->input('esic_number'),
                'epf_number' => $request->input('epf_number'),
                'lin_number' => $request->input('lin_number'),
                'uan_number' => $request->input('uan_number'),
                'esic_option' => $esic_option,
                'epf_option' => $epf_option,
                'acc_holder_name' => $request->input('acc_holder_name'),
                'acc_no' => $request->input('acc_no'),
                'ifsc_code' => $request->input('ifsc_code'),
                'bank_name' => $request->input('bank_name'),
                'branch' => $request->input('branch'),
                ]);
            }
            elseif($file->move(base_path().'/uploads/', $filename))
            {
                $target_dir = 'uploads/';
                $file = $request->file('photo');
                $extension = strtolower($file->getClientOriginalExtension()); // getting image extension
                $filename = $request->input('first_name').'_'.$request->input('middle_name').'_'.$request->input('last_name').'_'.$request->input('mobile').'_'.$current_timestamp.'.'.$extension;
                $target_file = $target_dir.$filename;

                if($extension!='png' && $extension!='jpg' && $extension!='jpeg')
                {
                    activity()->log('Add Employee process failed! Require file in png/jpg fromat');
                    return redirect()->back()->with('alert-danger','Please Upload png/jpg file only')->withInput($request->all);;
                }

                $emp = emp::create([
                'genesis_id'    =>  $request->input('genesis_id'),
                'genesis_ledger_id'    =>  $request->input('genesis_ledger_id'),
                'biometric_id'  =>  $request->input('biometric_id'),
                'branch_location_id'  =>  $request->input('branch_location_id'),
                'title' => $request->input('title'),
                'first_name' => $request->input('first_name'),
                'middle_name' => $request->input('middle_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'dob' => $request->input('dob'),
                'mobile' => $request->input('mobile'),
                'blood_group' => $request->input('blood_group'),
                'marital_status' => $request->input('marital_status'),
                'gender' => $request->input('gender'),
                'category' => $request->input('category'),
                'adhaar_number' =>$request->input('adhaar_number'),
                'pan_number' => $request->input('pan_number'),
                'local_address' => $request->input('local_address'),                    
                'permanent_address' => $request->input('permanent_address'),
                'emergency_call_person' => $request->input('emergency_call_person'),
                'emergency_call_number' => $request->input('emergency_call_number'),
                'department' => $request->input('department'),                
                'designation' => $request->input('designation'),
                'doj' => $request->input('doj'),
                'status' => $request->input('status'),
                'esic_number' => $request->input('esic_number'),
                'epf_number' => $request->input('epf_number'),
                'lin_number' => $request->input('lin_number'),
                'uan_number' => $request->input('uan_number'),
                'esic_option' => $esic_option,
                'epf_option' => $epf_option,
                'acc_holder_name' => $request->input('acc_holder_name'),
                'acc_no' => $request->input('acc_no'),
                'ifsc_code' => $request->input('ifsc_code'),
                'bank_name' => $request->input('bank_name'),
                'branch' => $request->input('branch'),
                'photo' => $target_file
                ]);    
            }
            else
            {
                activity()->log('Add Employee process failed! File cant be Uploaded');
                return redirect()->back()->with('alert-danger','File cant be Uploaded')->withInput($request->all);
            }
            if($emp)
            {
                activity()->log('Employee Added Successfully');  
                $emp_id = emp::max('id');
                $insertsalary = salary::create([
                    'emp_id' => $emp_id,
                    'salary' => $salary
                ]); 
                if($request->input('father_name')!='' || $request->input('mother_name')!='' || $request->input('spouse_name')!='')
                {
                    $child_array = array();
                    $father = json_encode(array('father_name'=>$request->input('father_name'),'father_dob'=>$request->input('father_dob'),'father_adhaar'=>$request->input('father_adhaar'),'father_place'=>$request->input('father_place')));
                    $mother = json_encode(array('mother_name'=>$request->input('mother_name'),'mother_dob'=>$request->input('mother_dob'),'mother_adhaar'=>$request->input('mother_adhaar'),'mother_place'=>$request->input('mother_place')));
                    $spouse = json_encode(array('spouse_name'=>$request->input('spouse_name'),'spouse_gender'=>$request->input('spouse_gender'),'spouse_dob'=>$request->input('spouse_dob'),'spouse_adhaar'=>$request->input('spouse_adhaar'),'spouse_place'=>$request->input('spouse_place')));
                    if($request->input('no_of_children')!=0 || $request->input('no_of_children')!='')
                    {
                        for($i=0;$i<$request->input('no_of_children');$i++)
                        {
                            $child = json_encode(array('child_name'=>$request->input('child_name')[$i],'child_gender'=>$request->input('child_gender')[$i],'child_dob'=>$request->input('child_dob')[$i],'child_adhaar'=>$request->input('child_adhaar')[$i],'child_place'=>$request->input('child_place')[$i]));

                            array_push($child_array, $child);
                        }
                    }

                    $family_insert = DB::table('family_detail')->insert(['father'=>$father,'mother'=>$mother,'spouse'=>$spouse,'children'=>json_encode($child_array)]);
                    
                }
                if($insertsalary)
                {
                    $sal_id = salary::max('id');
                    $update_sal_id = emp::where('id',$emp_id)->update(['salary_id'=>$sal_id]);
                    if($update_sal_id)
                        activity()->log('Salary Added for Employee  with id '.$request->input('emp_id'));
                    else
                        activity()->log('Salary ID not updated in Employee table for Employee-id '.$request->input('emp_id'));
                }
                else
                {
                   activity()->log('Employee Salary not added');   
                }            
                $user = User::create([
                    'name' => $request->input('title').' '.$request->input('first_name').' '.$request->input('middle_name').' '.$request->input('last_name'),
                    'email' => $request->input('email'),
                    'password' => bcrypt($password),
                    'role_id' => $request->input('role')
                ]);
                if(!$user)
                {
                     activity()->log('Employee not added to users');   
                }
                if($user && $insertsalary)
                {
                    activity()->log('Employee Salary Added Successfully');   
                    activity()->log('Employee Added to users Successfully');                        
                    return redirect()->back()->with('alert-success','Employee Added Successfully!');
                }
                else
                {
                    $msg='';
                    if(!$users)
                        $msg.='Login Details cannot be added ';
                    if(!$insertsalary)
                        $msg.='Employee Salary not added. ';
                    activity()->log($msg);
                    return redirect()->back()->with('alert-danger',$msg);
                }
            }
            else
            {
                activity()->log('Add Employee process failed!');
                unlink($target_file);
                return redirect()->back()->with('alert-danger','Add Employee process failed')->withInput($request->all);
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function get_emp_list(Request $request)
    {
        if(Session::get('username')!='' && (session('role')=='admin' || session('role')=='hr'))
        {
            // if(session('role')!='admin')
            //     return redirect('dashboard')->with('alert-danger','Only Admin can access Employee list');
            activity()->log('Fetching Employee List');
            try
            {
                $emps = emp::leftjoin('branch','emp.branch_location_id','=','branch.id')->select('branch.branch','emp.first_name','emp.middle_name','emp.last_name','emp.email','emp.mobile','emp.status','emp.id')->orderBy('id','asc')->get();
                return view('emp-list',compact('emps'));
            }
            catch(QueryException $e)
            {
                activity()->log($e->getMessage());
                return redirect()->back()->with('alert-danger','Database Query Error ['.$e->getMessage().' ]');
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
            return redirect('dashboard')->with('alert-danger',"Only admin and HR can see employee list");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\emp  $emp
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $id = $request->emp_id;
        activity()->log('Trying to Delete Employee with ID '.$id.' ');
        try
        {
            $email_id = emp::select('email')->where('id',$id)->value('email');
            $delete = emp::where('id',$id)->delete();
            if($delete)
            {
                $delete_users = User::where('email',$email_id)->delete();
                if($delete_users)
                    return redirect()->back()->with('alert-success','Employee Deleted Successfully from Employee and Users Table');
                else
                    return redirect()->back()->with('alert-success','Employee Deleted Successfully from Employee Table but not from Users Table');
            }
            else
            {
                return redirect()->back()->with('alert-success','Employee Cant be Deleted!');
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\emp  $emp
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        if(Session::get('username')!='')
        {
        $id = $request->id;
        activity()->log('Showing Edit employee page with employee id '.$id);
        try
        {
            $emps = emp::where('id',$id)->get();
            $family = DB::table('family_detail')->where('emp_id',$id)->get();
            if($family=='[]')
            {
                /*$family = json_encode(array('father'=>array('father_name'=>'','father_dob'=>'','father_adhaar'=>'','father_place'=>''),'mother'=>array('mother_name'=>'','mother_dob'=>'','mother_adhaar'=>'','mother_place'=>''),'spouse'=>array('spouse_name'=>'','spouse_gender'=>'','spouse_dob'=>'','spouse_adhaar'=>'','spouse_place'=>''),'child'=>array('child_name'=>'','child_gender'=>'','child_dob'=>'','child_adhaar'=>'','child_place'=>'')));*/
                $no_of_children = 0;
            }
            else
            {
                $no_of_children = sizeof(json_decode($family[0]->children,true));
            }
            $depts = DB::table('department')->get();
            /*$depts = DB::table('department')->where('department_name','!=','Admin')->orWhere('department_name','!=','admin')->get();*/
            $email = emp::where('id',$id)->limit('1','1')->value('email'); 
            $emp_role_id = User::where('email',$email)->value('role_id'); 
            $dept_id = emp::select('department')->where('id',$id)->value('department');
            $desigs = DB::table('designation')->where('department',$dept_id)->get();
            $roles = DB::table('role')->get();
            //$logins = User::where('email',$email)->get();
            $salaries = DB::table('salary')->select('salary')->where('id',$emps[0]->salary_id)->orderBy('id','DESC')->limit('1','1')->value('salary');
            $branches = DB::table('branch')->get();
            $bank_list = DB::table('bank_list')->get();
            //return $salary;
            return view('edit-emp',compact('emps','depts','desigs','roles','salaries','emp_role_id','branches','bank_list','family','no_of_children'));
        }
        catch(QueryException $e)
        {
            activity()->log($e->getMessage());
            return redirect()->back()->with('alert-danger','Database Query Error! ['.$e->getMessage().' ]');
        }
        catch(Exception $e)
        {
            activity()->log($e->getMessage());
            return redirect()->back()->with('alert-danger',$e->getMessage());
        }
        }
        else
            return redirect('/')->with('status',"Please login First");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\emp  $emp
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $file_uploaded = 0; 

        /*$i=0; $mydata=array(); $salary='';
        if($request->input('salary_type')!='') 
           { 
        foreach ($request->input('salary_type') as $salary_type) {
            $i++;
            $data[$salary_type]=$request->salary_value[$i];
        }
        $salary = json_encode(array('emp_salary'=>$data));
        }
        else
        {
           $salary = '';
        }*/
        //return $salary;
        if(Session::get('username')!='')
        {
        try
        {

            activity()->log('Trying To update Employee with id '.$request->emp_id);
            $old_email = emp::select('email')->where('id',$request->emp_id)->value('email');
            $old_roleid = User::select('role_id')->where('email',$old_email)->value('role_id');
            $old_name = User::select('name')->where('email',$old_email)->value('name');
            // if(($old_email!=$request->input('email')) || ($request->input('password')!=''))
            // {
            //     return 1;
            // }
            // else
            // {
            //     return 0;
            // }
            $current_timestamp = Carbon::now()->timestamp;
            $msg='';
            $rules = array(
            'genesis_id'    =>  'nullable|string',
            'genesis_ledger_id'    =>  'required|string',
            'biometric_id'  =>  'nullable|string',
            'branch_location_id'    => 'required|string',
            'title' => 'required|string',
            'first_name'    => 'required|regex:/^[a-zA-Z ]+$/',
            'middle_name'    => 'nullable|regex:/^[a-zA-Z ]+$/',
            'last_name'    => 'required|regex:/^[a-zA-Z ]+$/',
            'email'    => 'required|email', 
            'blood_group'    => 'nullable|string',
            'dob'    => 'nullable|date|date_format:Y-m-d',
            'mobile'    => 'required|numeric|digits:10',
            'gender'    => 'nullable|string',
            'category' => 'nullable|string',
            'marital_status'    => 'nullable|string',
            'local_address'    => 'nullable|string',
            'adhaar_number'    => 'nullable|numeric|digits:12',
            'pan_number'    => 'nullable|string|max:10|min:10',
            'permanent_address'    => 'nullable|string',
            'emergency_call_person' => 'nullable|regex:/^[a-zA-Z ]+$/',
            'emergency_call_number' => 'nullable|numeric|digits:10',
            'father_name' => 'nullable|regex:/^[a-zA-Z ]+$/',
            'father_dob' => 'nullable|date_format:Y-m-d',
            'father_adhaar' => 'nullable|numeric|digits:12',
            'father_place' => 'nullable|string',
            'mother_name' => 'nullable|regex:/^[a-zA-Z ]+$/',
            'mother_dob' => 'nullable|date_format:Y-m-d',
            'mother_adhaar' => 'nullable|numeric|digits:12',
            'mother_place' => 'nullable|string',
            'spouse_name' => 'nullable|regex:/^[a-zA-Z ]+$/',
            'spouse_gender' => 'nullable|string',
            'spouse_dob' => 'nullable|date_format:Y-m-d',
            'spouse_adhaar' => 'nullable|numeric|digits:12',
            'spouse_place' => 'nullable|string',
            'no_of_children' => 'nullable|string',
            'child_name' => 'nullable',
            'child_gender' => 'nullable',
            'child_dob' => 'nullable',
            'child_adhaar' => 'nullable',
            'child_place' => 'nullable',
            'department'    => 'required|string',
            'designation'    => 'required|string',
            'status'    => 'required|string',
            'esic_number' => 'nullable|numeric|digits:10',
            'epf_number' => 'nullable|string',
            'lin_number' => 'nullable|string',
            'uan_number' => 'nullable|numeric|digits:12',
            'esic_option' => 'nullable',
            'epf_option' => 'nullable',
            'salary' => 'required|numeric',
            'basic' => 'nullable|numeric',
            'doj'    => 'required|date|date_format:Y-m-d',
            'acc_holder_name'    => 'required|string',
            'acc_no'    => 'required|numeric',
            'ifsc_code'    => 'required|string',
            'bank_name' => 'required|numeric',
            'branch'    => 'required|string',
            'login_email' => 'required|email',
            'password' => 'nullable|string',
            'role' => 'nullable|numeric',
            'photo'    => 'nullable|image|max:10000'
            );

            $desig = DB::table('designation')->where('id',$request->department)->value('designation');
            if(strtolower($desig)=="fashion consultant")
            {
                if($request->input('genesis_id')=='')
                {
                  return redirect()->back()->with('alert-danger',"Genesis Id is mandatory for Employee of Fashion Consultant")->withInput($request->all);  
                }
            }
            if($request->input('epf_option')==1)
            {
                if($request->input('epf_number')=='')
                {
                     return redirect()->back()->with('alert-danger',"EPF Number Required")->withInput($request->all);  
                }
            }
            if($request->input('esic_option')==1)
            {
                if($request->input('esic_number')=='')
                {
                     return redirect()->back()->with('alert-danger',"ESIC Number Required")->withInput($request->all);  
                }
            }
        
            $acc_no_limit1 = DB::table('bank_list')->where('id',$request->bank_name)->value('acc_no_limit');
            if($acc_no_limit1!='')
            {
                $acc_no_limit = explode(',', $acc_no_limit1);
                $accno_len = strlen((string)$request->acc_no);
                if(!in_array($accno_len, $acc_no_limit))
                {
                    return redirect()->back()->with('alert-danger',"Acc no must be of ".$acc_no_limit1." digits")->withInput($request->all);  
                }
            }
            
            if($request->input('genesis_id')!='')
            {
                $check_unique = DB::table('emp')->where('id','!=',$request->emp_id)->where('genesis_id',$request->input('genesis_id'))->count();
                if($check_unique>0)
                {
                    $msg.=' Genesis ID already registered';
                }
            }
            if($request->input('genesis_ledger_id')!='')
            {
                $check_unique = DB::table('emp')->where('id','!=',$request->emp_id)->where('genesis_ledger_id',$request->input('genesis_ledger_id'))->count();
                if($check_unique>0)
                {
                    $msg.=' Genesis Ledger ID already registered';
                }
            }
            /*if($request->input('biometric_id')!='')
            {
                $check_unique = DB::table('emp')->where('id','!=',$request->emp_id)->where('biometric_id',$request->input('biometric_id'))->count();
                if($check_unique>0)
                {
                    $msg.=' Biometric ID already registered';
                }
            }*/
            if($request->input('pan_number')!='')
            {
                $check_unique = DB::table('emp')->where('id','!=',$request->emp_id)->where('pan_number',$request->input('pan_number'))->count();
                if($check_unique>0)
                {
                    $msg.=' Pan Number already registered';
                }
            }
            if($request->input('adhaar_number')!='')
            {
                $check_unique = DB::table('emp')->where('id','!=',$request->emp_id)->where('adhaar_number',$request->input('adhaar_number'))->count();
                if($check_unique>0)
                {
                    $msg.=' Adhaar Number already registered';
                }
            }
            
            $email = DB::table('emp')->where('email', $request->email)->where('id','!=',$request->emp_id)->value('email');
            $mobile = DB::table('emp')->where('mobile', $request->mobile)->where('id','!=',$request->emp_id)->value('mobile');
            if($email!='')
            {
                $msg.='Email-Id is already registered';
            }
            if($mobile!='')
            {
                $msg.=' Mobile number is already registered';
            }
            if($msg!='')
            {
                activity()->log('Update Employee process failed! '.$msg.'');
                return redirect()->back()->with('alert-danger',$msg)->withInput($request->all);
            }

            $validator = Validator::make(Input::all(), $rules);
            if ($validator->fails()) {
                activity()->log('Update Employee process failed due to Validation Error');
                return redirect()->back()->withErrors($validator)->withInput($request->all);
            }

            


            $esic_option='';
            $epf_option='';
            if($request->input('esic_option')=='')
            {
                $esic_option=0;
            }
            else
            {
                $esic_option=$request->input('esic_option');
            }

            if($request->input('epf_option')=='')
            {
                $epf_option=0;
            }
            else
            {
                $epf_option=$request->input('epf_option');
            }
            if($epf_option=='1')
            {
                if($request->basic=='' ||$request->basic=='0')
                {
                    return redirect()->back()
                    ->with('alert-danger','Basic + DA required')
                    ->withInput($request->all);
                }
                else
                {
                    $basic= $request->basic;
                }
            }
            else
            {
                $basic = 0;
            }

            $salary = array();
            $salary['salary'] = $request->salary;
            $salary['basic'] = $basic;
            $salary = json_encode(array('emp_salary'=>$salary));
            

            if($request->photo!='')
            {
                $target_dir = 'uploads/';
                $file = $request->file('photo');
                $extension = strtolower($file->getClientOriginalExtension()); 
                 $filename = $request->input('first_name').'_'.$request->input('middle_name').'_'.$request->input('last_name').'_'.$request->input('mobile').'_'.$current_timestamp.'.'.$extension;
                $target_file = $target_dir.$filename;

                if($extension!='png' && $extension!='jpg' && $extension!='jpeg')
                {
                    activity()->log('Update Employee process failed! Require file in png/jpg fromat');
                    return redirect()->back()->with('alert-danger','Please Upload png/jpg file only')->withInput($request->all);
                }
                 $file_uploaded = $file->move(base_path().'/uploads/', $filename);
                if($file_uploaded)
                { 
                    $emp = emp::where('id',$request->input('emp_id'))->update([
                    'genesis_id'    =>  $request->input('genesis_id'),
                    'genesis_ledger_id'    =>  $request->input('genesis_ledger_id'),
                    'biometric_id'  =>  $request->input('biometric_id'),
                    'branch_location_id'  =>  $request->input('branch_location_id'),
                    'title' => $request->input('title'),
                    'first_name' => $request->input('first_name'),
                    'middle_name' => $request->input('middle_name'),
                    'last_name' => $request->input('last_name'),
                    'email' => $request->input('email'),
                    'dob' => $request->input('dob'),
                    'mobile' => $request->input('mobile'),
                    'blood_group' => $request->input('blood_group'),
                    'marital_status' => $request->input('marital_status'),
                    'gender' => $request->input('gender'),
                    'category' => $request->input('category'),
                    'adhaar_number' =>$request->input('adhaar_number'),
                    'pan_number' => $request->input('pan_number'),
                    'local_address' => $request->input('local_address'),                    
                    'permanent_address' => $request->input('permanent_address'),
                    'emergency_call_person' => $request->input('emergency_call_person'),
                    'emergency_call_number' => $request->input('emergency_call_number'),
                    'department' => $request->input('department'),                    
                    'designation' => $request->input('designation'),
                    'doj' => $request->input('doj'),
                    'status' => $request->input('status'),
                    'esic_number' => $request->input('esic_number'),
                    'epf_number' => $request->input('epf_number'),
                    'lin_number' => $request->input('lin_number'),
                    'uan_number' => $request->input('uan_number'),
                    'esic_option' => $esic_option,
                    'epf_option' => $epf_option,
                    'reason_code_0'=>$request->input('reason_for_code'),
                    'last_working_day'=>$request->input('last_working_day'),
                    'acc_holder_name' => $request->input('acc_holder_name'),
                    'acc_no' => $request->input('acc_no'),
                    'ifsc_code' => $request->input('ifsc_code'),
                    'bank_name' => $request->input('bank_name'),
                    'branch' => $request->input('branch'),
                    'photo' => $target_file,
                    'updated_at'=>Carbon::now()
                    ]);
                                   
                }
                else
                {
                    activity()->log('Update Employee process failed! File cant be Uploaded');
                    return redirect()->back()->with('alert-danger','File cant be Uploaded')->withInput($request->all);
                }

            }
            else
            { 
                $emp = emp::where('id',$request->input('emp_id'))->update([
                    'genesis_id'    =>  $request->input('genesis_id'),
                    'genesis_ledger_id'    =>  $request->input('genesis_ledger_id'),
                    'biometric_id'  =>  $request->input('biometric_id'),
                    'branch_location_id'  =>  $request->input('branch_location_id'),
                    'title' => $request->input('title'),
                    'first_name' => $request->input('first_name'),
                    'middle_name' => $request->input('middle_name'),
                    'last_name' => $request->input('last_name'),
                    'email' => $request->input('email'),
                    'dob' => $request->input('dob'),
                    'mobile' => $request->input('mobile'),
                    'blood_group' => $request->input('blood_group'),
                    'marital_status' => $request->input('marital_status'),
                    'gender' => $request->input('gender'),
                    'category' => $request->input('category'),
                    'adhaar_number' =>$request->input('adhaar_number'),
                    'pan_number' => $request->input('pan_number'),
                    'local_address' => $request->input('local_address'),                    
                    'permanent_address' => $request->input('permanent_address'),
                    'emergency_call_person' => $request->input('emergency_call_person'),
                    'emergency_call_number' => $request->input('emergency_call_number'),
                    'department' => $request->input('department'),                    
                    'designation' => $request->input('designation'),
                    'doj' => $request->input('doj'),
                    'status' => $request->input('status'),
                    'esic_number' => $request->input('esic_number'),
                    'epf_number' => $request->input('epf_number'),
                    'lin_number' => $request->input('lin_number'),
                    'uan_number' => $request->input('uan_number'),
                    'esic_option' => $esic_option,
                    'epf_option' => $epf_option,
                    'reason_code_0'=>$request->input('reason_for_code'),
                    'last_working_day'=>$request->input('last_working_day'),
                    'acc_holder_name' => $request->input('acc_holder_name'),
                    'acc_no' => $request->input('acc_no'),
                    'ifsc_code' => $request->input('ifsc_code'),
                    'bank_name' => $request->input('bank_name'),
                    'branch' => $request->input('branch')
                ]);
            }

            if($emp)
            {
                activity()->log('Employee with id '.$request->input('emp_id').' Updated Successfully');

                if($request->input('father_name')!='' || $request->input('mother_name')!='' || $request->input('spouse_name')!='')
                {
                    $child_array = array();
                    $father = json_encode(array('father_name'=>$request->input('father_name'),'father_dob'=>$request->input('father_dob'),'father_adhaar'=>$request->input('father_adhaar'),'father_place'=>$request->input('father_place')));
                    $mother = json_encode(array('mother_name'=>$request->input('mother_name'),'mother_dob'=>$request->input('mother_dob'),'mother_adhaar'=>$request->input('mother_adhaar'),'mother_place'=>$request->input('mother_place')));
                    $spouse = json_encode(array('spouse_name'=>$request->input('spouse_name'),'spouse_gender'=>$request->input('spouse_gender'),'spouse_dob'=>$request->input('spouse_dob'),'spouse_adhaar'=>$request->input('spouse_adhaar'),'spouse_place'=>$request->input('spouse_place')));
                    if($request->input('no_of_children')!=0 || $request->input('no_of_children')!='')
                    {
                        for($i=0;$i<$request->input('no_of_children');$i++)
                        {
                            $child = json_encode(array('child_name'=>$request->input('child_name')[$i],'child_gender'=>$request->input('child_gender')[$i],'child_dob'=>$request->input('child_dob')[$i],'child_adhaar'=>$request->input('child_adhaar')[$i],'child_place'=>$request->input('child_place')[$i]));

                            array_push($child_array, $child);
                        }
                    }
                    $family_id = DB::table('family_detail')->where('emp_id',$request->input('emp_id'))->value('id');
                    if($family_id=='')
                        $family_update = DB::table('family_detail')->insert(['emp_id'=>$request->input('emp_id'),'father'=>$father,'mother'=>$mother,'spouse'=>$spouse,'children'=>json_encode($child_array)]);  
                    else
                        $family_update = DB::table('family_detail')->where('emp_id',$request->input('emp_id'))->update(['father'=>$father,'mother'=>$mother,'spouse'=>$spouse,'children'=>json_encode($child_array)]);                    
                }
                $old_salary_id = emp::where('id',$request->input('emp_id'))->value('salary_id');
                $old_sal = DB::table('salary')->select('salary')->where('id',$old_salary_id)->orderBy('id','desc')->limit('1','1')->value('salary');
                $salary_cmp = strcasecmp($salary, $old_sal);
                if($salary_cmp!=0)
                {
                    $sal_update = salary::create(['emp_id'=>$request->input('emp_id'),'salary'=>$salary]);
                    if($sal_update)
                    {
                        $sal_id = salary::max('id');
                        $update_sal_id = emp::where('id',$request->input('emp_id'))->update(['salary_id'=>$sal_id]);
                        if($update_sal_id)
                        activity()->log('Salary Updated for Employee with id '.$request->input('emp_id'));
                        else
                        {
                            salary::where('id',$sal_id)->delete();
                            activity()->log('Salary Updation failed for Employee with id '.$request->input('emp_id'));
                        }
                    }
                    else
                    {
                        activity()->log('Salary Updation failed for Employee with id '.$request->input('emp_id'));
                    }
                }
                
                $new_name = $request->input('title').' '.$request->input('first_name').' '.$request->input('middle_name').' '.$request->input('last_name');
                if($old_email!=$request->input('email') || $request->input('password')!='' || ($old_name!=$new_name) || ($old_roleid!=$request->input('role')))
                {
                    if($request->input('password')!='')
                    {
                        $user = User::where('email',$old_email)->update([
                            'name' => $request->input('title').' '.$request->input('first_name').' '.$request->input('middle_name').' '.$request->input('last_name'),
                            'email' => $request->input('email'),
                            'password' => bcrypt($request->input('password')),
                            'role_id' => $request->input('role')
                        ]);
                        if($user)
                        {
                            activity()->log('Employee with email '.$old_email.' Updated to email '.$request->input('email').' and password changed to '.$request->input('password').' in users table!');                        
                            return redirect()->back()->with('alert-success','Employee Updated Successfully!');
                        }
                        else
                        {
                            activity()->log('Employee with email '.$old_email.' cant be updated in users table');
                            
                            return redirect()->back()->with('alert-danger','Login Details cannot be added');
                        }
                    }
                    else
                    {
                        $user = User::where('email',$old_email)->update([
                            'name' => $request->input('title').' '.$request->input('first_name').' '.$request->input('middle_name').' '.$request->input('last_name'),
                            'email' => $request->input('email'),
                            'role_id' => $request->input('role')
                        ]);
                        if($user)
                        {
                            activity()->log('Employee with email '.$old_email.' Updated to email '.$request->input('email').' in users table!');                        
                            return redirect()->back()->with('alert-success','Employee Updated Successfully!');
                        }
                        else
                        {
                            activity()->log('Employee with email '.$old_email.' cant be updated in users table');
                            
                            return redirect()->back()->with('alert-danger','Login Details cannot be added');
                        }

                    }
                }
                else
                {
                    activity()->log('Employee with email '.$old_email.' Updated Successfully');                        
                    return redirect()->back()->with('alert-success','Employee Updated Successfully!');
                }
                
            }
            else
            {
                activity()->log('Update Employee process failed!');
                if($file_uploaded)unlink($target_file);
                return redirect()->back()->with('alert-danger','File cant be Uploaded')->withInput($request->all);
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
            return redirect('/')->with('status',"Please login First");
    }

    public function getDetails(Request $request)
    {
         
       
        //$user_id = $request->id;
       //$user_data = emp::where('id', $user_id)->get();
        $user_id = $request->id;
        $user_data= emp::where('emp.id', $user_id)
                            ->join('department','emp.department', '=' , 'department.id')
                            ->join('designation','emp.designation', '=' , 'designation.id')
                            ->join('branch','emp.branch_location_id', '=' , 'branch.id')
                            ->join('salary','emp.salary_id','=','salary.id')
                            ->select('emp.id','emp.title','emp.first_name','emp.middle_name','emp.last_name','emp.blood_group','emp.photo','emp.dob','emp.mobile','emp.email','emp.marital_status','emp.gender','emp.category','emp.adhaar_number','emp.pan_number','emp.local_address','emp.permanent_address','emp.emergency_call_person','emp.emergency_call_number','branch.branch as branch_location_name','emp.genesis_id','emp.genesis_ledger_id','emp.biometric_id','emp.epf_number','emp.esic_number','emp.lin_number','emp.uan_number','emp.reason_code_0','emp.last_working_day','emp.doj','emp.ifsc_code','emp.status','emp.esic_option','emp.epf_option','salary.salary','emp.acc_holder_name','emp.acc_no','emp.bank_name','emp.branch','department.department_name','designation.designation')
                            ->get();
        return response()->json($user_data);
        
    }
    
    public function get_emp_search(Request $request)
    {
        $emp_name = emp::select('title','first_name','middle_name','last_name','id')->get();
        return view('emp-search',compact('emp_name'));
    }

    public function get_emp_upload(Request $request)
    {
        $branches = DB::table('branch')->get();
        return view('upload-emp',compact('branches'));
    }

    public function get_emp_details(Request $request)
    {

        if(Session::get('username')!='')
        {
        $id = $request->id;
        activity()->log('Showing Edit employee page with employee id '.$id);
        try
        {
            $emps = emp::where('id',$id)->get();
            
            $depts = DB::table('department')->get();
            /*$depts = DB::table('department')->where('department_name','!=','Admin')->orWhere('department_name','!=','admin')->get();*/
            $email = emp::where('id',$id)->limit('1','1')->value('email'); 
            $emp_role_id = User::where('email',$email)->value('role_id'); 
            $dept_id = emp::select('department')->where('id',$id)->value('department');
            $desigs = DB::table('designation')->where('department',$dept_id)->get();
            $roles = DB::table('role')->get();
            $salary_id = emp::where('id',$id)->value('salary_id');
            $salaries = DB::table('salary')->select('salary')->where('id',$salary_id)->orderBy('id','DESC')->limit('1','1')->value('salary');
            $branches = DB::table('branch')->get();
            //return $salary;
            return view('editable-emp',compact('emps','depts','desigs','roles','salaries','emp_role_id','branches'));
        }
        catch(QueryException $e)
        {
            activity()->log($e->getMessage());
            return redirect()->back()->with('alert-danger','Database Query Error! ['.$e->getMessage().' ]');
        }
        catch(Exception $e)
        {
            activity()->log($e->getMessage());
            return redirect()->back()->with('alert-danger',$e->getMessage());
        }
        }
        else
            return redirect('/')->with('status',"Please login First");


        $id = $request->id;
        
        $emps = emp::where('id',$id)->get();
        
        $output='';
        foreach ($emps as $emp) {
                $output.= '<div class="row clearfix">
                <div class="col-md-12">
                                    <h4 class="">
                                        Personal Details
                                    </h4>

                                    <div class="col-md-6">
                                                    <div class="row clearfix">
                                        <div class="form-group">
                                            <div class="col-md-4 col-sm-6 col-xs-12">
                                                <label for="name">First Name</label>
                                            </div>
                                            <div class="col-md-8 col-sm-6 col-xs-12">
                                                
                                                   '.$emp->first_name.' 
                                                    
                                            </div>
                                        </div>
                                        </div>
                                        <div class="row clearfix">
                                        <div class="form-group">
                                            <div class="col-md-4 col-sm-6 col-xs-12">
                                                <label for="name">Middle Name</label>
                                            </div>
                                            <div class="col-md-8 col-sm-6 col-xs-12">
                                                
                                                   '.$emp->middle_name.'
                                            </div>
                                        </div>
                                        </div>
                                        <div class="row clearfix">
                                        <div class="form-group">
                                            <div class="col-md-4 col-sm-6 col-xs-12">
                                                <label for="name">Last Name</label>
                                            </div>
                                            <div class="col-md-8 col-sm-6 col-xs-12">
                                                
                                                 '.$emp->last_name.'
                                                    
                                            </div>
                                        </div>
                                        </div>
                                        <div class="row clearfix"><div class="form-group">
                                            <div class="col-md-4 col-sm-6 col-xs-12">
                                                <label for="blood_group">Blood Group</label>
                                            </div>
                                            <div class="col-md-8 col-sm-6 col-xs-12">
                                               
                                                  '.$emp->blood_group.'
                                            </div>
                                        </div>
                                        </div>
                                        <div class="row clearfix"><div class="form-group">
                                            <div class="col-md-4 col-sm-6 col-xs-12">
                                                <label for="email">Email</label>
                                            </div>
                                            <div class="col-md-8 col-sm-6 col-xs-12">
                                               '.$emp->email.'
                                                    
                                            </div>
                                        </div>
                                        </div>
                                        <div class="row clearfix"><div class="form-group">
                                            <div class="col-md-4 col-sm-6 col-xs-12">
                                                <label for="dob">Date Of Birth</label>
                                            </div>
                                            <div class="col-md-8 col-sm-6 col-xs-12">
                                                '.date('Y-m-d',strtotime($emp->dob)).'
                                            </div>
                                        </div>
                                        </div>
                                        <div class="row clearfix"><div class="form-group">
                                            <div class="col-md-4 col-sm-6 col-xs-12">
                                                <label for="mobile">Mobile</label>
                                            </div>
                                            <div class="col-md-8 col-sm-6 col-xs-12">
                                                 '.$emp->mobile.'
                                                   
                                            </div>
                                        </div>
                                        </div>
                                        <div class="row clearfix"><div class="form-group">
                                            <div class="col-md-4 col-sm-6 col-xs-12">
                                                <label for="gender">Gender</label>
                                            </div>
                                            <div class="col-md-8 col-sm-6 col-xs-12">
                                                
                                                       ';
                                                       if($emp->gender == 'female')
                                                           $output.='Female';
                                                        elseif($emp->gender == 'male')
                                                        $output.='Male';                                                  
                                                   
                                            $output.='</div>
                                        </div>
                                        </div>

                                        <div class="row clearfix"><div class="form-group">
                                            <div class="col-md-4 col-sm-6 col-xs-12">
                                                <label for="category">Category</label>
                                            </div>
                                            <div class="col-md-8 col-sm-6 col-xs-12">';
                                                
                                                        if($emp->category == 'GENERAL')
                                                            $output.='General';                                                        
                                                        if($emp->category == 'OBC')
                                                            $output.='OBC';
                                                       
                                                        if($emp->category == 'ST/SC')
                                                            $output.='ST/SC';
                                                    
                                                        if($emp->category == 'Other')
                                                            $output.='Other';
                                                        
                                           $output.=' </div>
                                        </div>
                                        </div>

                                        <div class="row clearfix"><div class="form-group">
                                            <div class="col-md-4 col-sm-6 col-xs-12">
                                                <label for="marital_status">Marital Status</label>
                                            </div>
                                            <div class="col-md-8 col-sm-6 col-xs-12">';
                                                
                                                if($emp->marital_status == 'married')
                                                    $output.='married';
                                                
                                                if($emp->marital_status == 'single')
                                                    $output.='single';
                                               
                                                if($emp->marital_status == 'other')
                                                    $output.='other';
                                            
                                                       
                                            $output.='</div>
                                        </div>                                      
                                    </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row clearfix"><div class="form-group">
                                            <div class="col-md-4 col-sm-6 col-xs-12">
                                                <label for="local_address">Adhaar Number</label>
                                            </div>
                                            <div class="col-md-8 col-sm-6 col-xs-12">
                                                '.$emp->adhaar_number.'
                                            </div>
                                        </div>
                                        </div>

                                        <div class="row clearfix"><div class="form-group">
                                            <div class="col-md-4 col-sm-6 col-xs-12">
                                                <label for="local_address">PAN Number</label>
                                            </div>
                                            <div class="col-md-8 col-sm-6 col-xs-12">
                                                '.$emp->pan_number.'
                                            </div>
                                        </div>
                                        </div>

                                        <div class="row clearfix">
                                        <div class="form-group">
                                            <div class="col-md-4 col-sm-6 col-xs-12">
                                                <label for="local_address">Local Address</label>
                                            </div>
                                            <div class="col-md-8 col-sm-6 col-xs-12">
                                               '.$emp->local_address.'
                                            </div>
                                        </div>
                                        </div>
                                       
                                        <div class="row clearfix"><div class="form-group">
                                            <div class="col-md-4 col-sm-6 col-xs-12">
                                                <label for="permanent_address">Permanent Address</label>
                                            </div>
                                            <div class="col-md-8 col-sm-6 col-xs-12">
                                                '.$emp->permanent_address.'
                                            </div>
                                        </div>  
                                        </div>
                                        <div class="row clearfix">                              
                                        <div class="form-group">
                                            <div class="col-md-4 col-sm-6 col-xs-12">
                                                <label for="photo">Photograph</label>
                                            </div>
                                            <div class="col-md-8 col-sm-6 col-xs-12">
                                                <img src="'.$emp->photo.'">
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                     
                                        <label><h4><u>On Emergency Contact To</u></h4></label>
                                        
                                            <div class="form-group">
                                                <div class="col-md-2 col-sm-6 col-xs-12">
                                                    <label for="emergency_call_person">Person Name</label>
                                                </div>
                                                <div class="col-md-4 col-sm-6 col-xs-12">
                                                    '.$emp->emergency_call_person .'
                                                </div>
                                            
                                                <div class="col-md-2 col-sm-6 col-xs-12">
                                                    <label for="emergency_call_number">Contact Number</label>
                                                </div>
                                                <div class="col-md-4 col-sm-6 col-xs-12">
                                                     '.$emp->emergency_call_number.'
                                                </div>
                                           
                                        </div>
                                    </div>
                                    </div>

                                    <div class="row clearfix">
                                        <div class="col-md-12">
                                        <h4>
                                            Company Details
                                        </h4>

                                        <div class="col-md-6">
                                                <div class="row clearfix">                                       
                                                        <div class="col-md-4 col-sm-6 col-xs-12">
                                                            <label for="genesis_id">Genesis Id</label>
                                                        </div>
                                                        <div class="col-md-8 col-sm-6 col-xs-12">
                                                           '.$emp->genesis_id.'
                                                        
                                                    </div>
                                                    </div>

                                                    <div class="row clearfix">
                                                        <div class="col-md-4 col-sm-6 col-xs-12">
                                                            <label for="branch_location_id">Branch</label>
                                                        </div>
                                                        <div class="col-md-8 col-sm-6 col-xs-12">
                                                          ';
                                                        $branch_name = DB::table('branch')->select('branch')->where('id',$emp->branch_location_id)->value('branch');

                                                          $output.=$branch_name;
                                                               
                                                        $output.='</div>
                                                    
                                                    </div>


                                                    <div class="row clearfix">
                                                    
                                                        <div class="col-md-4 col-sm-6 col-xs-12">
                                                            <label for="department">Department</label>
                                                        </div>
                                                        <div class="col-md-8 col-sm-6 col-xs-12">
                                                           ';
                         $department = DB::table('department')->select('department_name')->where('id',$emp->department)->value('department_name');

                        $output.=$department;
                                                                
                                                       $output.='</div>
                                                    
                                                    </div>
                                                    <div class="row clearfix">
                                                    
                                                        <div class="col-md-4 col-sm-6 col-xs-12">
                                                            <label for="designation">Designation</label>
                                                        </div>
                                                        <div class="col-md-8 col-sm-6 col-xs-12">
                                                            ';
                                                 $designation= DB::table('designation')->select('designation')->where('id',$emp->designation)->value('designation');
                                                $output.=$designation;
                                                                
                                                     $output.='</div>
                                                                                  
                                                </div>

                                                <div class="row clearfix">
                                                    
                                                        <div class="col-md-4 col-sm-6 col-xs-12">
                                                            <label for="doj">Date Of Joining</label>
                                                        </div>
                                                        <div class="col-md-8 col-sm-6 col-xs-12">
                                                            '.date('Y-m-d',strtotime($emp->doj)).'
                                                        </div>
                                                    
                                                    </div>

                                                <div class="row clearfix">
                                                   
                                                        <div class="col-md-4 col-sm-6 col-xs-12">
                                                            <label for="status">Status</label>
                                                        </div>
                                                        <div class="col-md-8 col-sm-6 col-xs-12">
                                                            ';
                                                        if($emp->status == 'active')
                                                           $output.='Active';
                                                        

                                                        if($emp->status == 'inactive')
                                                           $output.='Inactive';

                                                       $output.='</div>
                                                   
                                                </div>

                                                </div>
                                                <div class="col-md-6">

                                                    <div class="row clearfix">                                      
                                                        <div class="col-md-4 col-sm-6 col-xs-12">
                                                            <label for="emplayee_id">Biometric Id</label>
                                                        </div>
                                                        <div class="col-md-8 col-sm-6 col-xs-12">
                                                            '.$emp->biometric_id.'
                                                        </div>
                                                    
                                                    </div>

                                                    <div class="row clearfix">
                                                    
                                                        <div class="col-md-4 col-sm-6 col-xs-12">
                                                            <label for="esic_number">ESIC Number</label>
                                                        </div>
                                                        <div class="col-md-8 col-sm-6 col-xs-12">
                                                            '.$emp->esic_number.'
                                                        </div>
                                                    
                                                </div>
                                                <div class="row clearfix">
                                                   
                                                        <div class="col-md-4 col-sm-6 col-xs-12">
                                                            <label for="epf_number">EPF Number</label>
                                                        </div>
                                                        <div class="col-md-8 col-sm-6 col-xs-12">
                                                            '.$emp->epf_numbe.'
                                                        </div>
                                                    
                                                </div>

                                                <div class="row clearfix">
                                                    
                                                        <div class="col-md-4 col-sm-6 col-xs-12">
                                                            <label for="lin_number">LIN Number</label>
                                                        </div>
                                                        <div class="col-md-8 col-sm-6 col-xs-12">
                                                            '.$emp->lin_number.'
                                                        </div>
                                                    
                                                </div>

                                                <div class="row clearfix">
                                                    
                                                        <div class="col-md-4 col-sm-6 col-xs-12">
                                                            <label for="uan_number">UAN Number</label>
                                                        </div>
                                                        <div class="col-md-8 col-sm-6 col-xs-12">
                                                            '.$emp->uan_number.'
                                                        </div>
                                                    
                                                </div>

                                                    
                                            </div>

                                    </div>
                                    </div>

                                <div class="row clearfix">
                                <div class="col-md-6">
                                <h4 class="">
                                 Salary Details
                                </h4>

                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      ESIC : '; if($emp->esic_option=='1') $output.='Active'; else $output.='Inactive'; 

                                        
                                        $output.='
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        EPF : '; if($emp->epf_option=='1') $output.='Active';  else $output.='Inactive'; 
                                    $output.='</div>
                                    <table class="table table-stripped">
                                                    <tbody id="salary_body" name="salary_body">';

                                    $salaries = DB::table('salary')->where('id',$emp->salary_id)->value('salary');
                                 $i=0;
                                $salary = (array) json_decode($salaries,true);
                                if($salaries!='') 
                                {
                                
                                foreach($salary['emp_salary'] as $salary_type=>$salary_value){
                                 $i++; 
                                   $output.=' <tr>
                                        <td>
                                             '.$salary_type.'
                                        </td>
                                        <td>
                                            '.$salary_value.'
                                        </td>
                                        
                                    </tr>';
                                        }
                                 }   
                                $output.='</tbody>
                            </table>

                             </div>
                         </div>';

        }
        return $output;
    }

  public function get_file_form()
    {
        if(Session::get('username')!='' && (session('role')=='admin' || session('role')=='hr'))
        {
            $department = DB::table('department')->get();
            $file_type = DB::table('document_type')->get();
            $branch = DB::table('branch')->get();
            $document = DB::table('upload_docs')
            ->leftjoin('department','department.id','=','upload_docs.department')
            ->leftjoin('branch','branch.id','=','upload_docs.branch')
            ->leftjoin('emp','emp.id','=','upload_docs.emp_id')
            ->select('upload_docs.id','upload_docs.title as heading','upload_docs.file','upload_docs.branch','upload_docs.created_at',
            'department.department_name','branch.branch','emp.title','emp.first_name','emp.middle_name','emp.last_name')->where('upload_docs.status','active')->get();
            $doc = DB::table('emp_doc')
            ->join('emp','emp_doc.emp_id','=','emp.id')
            ->join('users','emp_doc.uploaded_by','=','users.id')
            ->join('document_type','emp_doc.document_name','=','document_type.id')
            ->select('users.id','users.email as user_email','emp.email as emp_email','emp_doc.document_name','emp_doc.emp_id','emp_doc.uploaded_by','emp_doc.path','users.name','emp_doc.verify','emp_doc.created_at','emp_doc.status','document_type.name as document')->orderBy('emp_doc.id','DESC')->get();
            activity()->log('Fetching Upload File Page');
           
            return view('emp-doc',compact('department','document','branch','file_type','doc'));
        }
        else
        {
            if(Session::get('username')=='')
                return redirect('/')->with('status',"Please login First");
            else
            return redirect('dashboard')->with('alert-danger',"Only admin and HR can access upload file page");
        }
    }

    public function get_emp(Request $request)
    {
       if(Session::get('username')!='') 
       {
            $branch = $request->branch;
            $employee=DB::table('emp')
                    ->where('branch_location_id',$branch)
                    ->get();
            $data = '<option></option>';
            foreach($employee as $key=>$emp)
            {
            $data.='<option value="'.$emp->id.'">'.$emp->title.' '. $emp->first_name.' '. $emp->middle_name.' '.$emp->last_name.'</option>';
            }
            return $data;
       }
       else
       {
            return redirect('/')->with('status',"Please login First");
       }
       
    }
    public function getemp(Request $request)
    {
       if(Session::get('username')!='') 
       {
            $branch = $request->branch;
            $department = $request->department;
            $employee=DB::table('emp')
                    ->where('branch_location_id',$branch)
                    ->where('department',$department)
                    ->get();
            $data = '<option></option>';
            foreach($employee as $key=>$emp)
            {
            $data.='<option value="'.$emp->id.'">'.$emp->title.' '. $emp->first_name.' '. $emp->middle_name.' '.$emp->last_name.'</option>';
            }
            return $data;
       }
       else
       {
            return redirect('/')->with('status',"Please login First");
       }
    }
    public function upload_file(Request $request)
    {
        if(Session::get('username')!='' && (session('role')=='admin' || session('role')=='hr'))
        {
            try
            {
                activity()->log('Trying to Upload file');
                 $rules = array(
                'file_type'    =>  'required|numeric',
                'file' =>     'required|file|mimes:jpeg,png,jpg,pdf,docx,doc,xls,xlsx,csv|max:1024',
                'branch' => 'required|numeric',
                 'department' => 'nullable|numeric',
                 'employee' => 'nullable|numeric',
     );
                $validator = Validator::make(Input::all(), $rules);
                if ($validator->fails()) {
                    activity()->log('upload process failed due to Validation Error');
                    return redirect()->back()->withErrors($validator)->withInput($request->all);
                }

                $file = $request->file;
                $upload_type = $request->input('file_type');
                $branch = $request->input('branch');
                $department  = $request->input('department');
                $employee  = $request->input('employee');
                if($request->has('file'))
                {
                    $folder=base_path().'/personal_doc/'.$employee;
                    if (!File::exists($folder)) {
                        File::makeDirectory($folder, 0775, true, true);
                    }
                  
                    $target_dir = 'personal_doc/'.$employee.'/';
                    $filename = strtolower($file->getClientOriginalName()); 
                    $extension = strtolower($file->getClientOriginalExtension());
                    $file_name = pathinfo($filename, PATHINFO_FILENAME);
                    $filename = $upload_type.'_'.$employee.'_'.strtotime(date('Y-m-d H:i:s')).'.'.$extension;
                    $target_file = $target_dir.$filename;
                    if($file->move(base_path().'/personal_doc/'.$employee.'/', $filename))
                    {
                        activity()->log('Upload file process Successful for user with id '.session('user_id'));
                        $add_to_upload = DB::table('emp_doc')->insert(['uploaded_by' => session('user_id'),'document_name'=>$upload_type,'emp_id'=>$employee,'path'=>$target_file,'created_at'=>now(),'status'=>'Pending','verify'=>'Pending']);
                        if($add_to_upload)
                         return redirect()->back()->with('alert-success',"Uploaded Successfully");
                        else
                        return redirect()->back()->with('alert-danger',"upload process not updated to Database");
                    }
                    else
                    {
                        activity()->log('Upload file process failed for user with id '.session('user_id'));
                        return redirect()->back()->with('alert-danger',"Upload Process Failed");
                    }
                }
                else
                {
                    return redirect()->back()->with('alert-danger','Request data does not have any files to import.');
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
        else
        {
            if(Session::get('username')=='')
                return redirect('/')->with('status',"Please login First");
            else
            return redirect('dashboard')->with('alert-danger',"Only admin and HR can access upload file page");
        }
    }
    

    
    public function get_doc_type()
    {

        if(Session::get('username')!='')
        {
          
          
            $file_type = DB::table('document_type')->get();
            
            $doc = DB::table('emp_doc')
            ->join('emp','emp_doc.emp_id','=','emp.id')
            ->join('users','emp_doc.uploaded_by','=','users.id')
            ->join('document_type','emp_doc.document_name','=','document_type.id')
            ->select('users.id','users.email as user_email','emp.email as emp_email','emp_doc.document_name','emp_doc.emp_id','emp_doc.uploaded_by','emp_doc.path','users.name','emp_doc.verify','emp_doc.created_at','emp_doc.status','document_type.name as document')->orderBy('emp_doc.id','DESC')->get();
          
           
            activity()->log('Fetching Upload File Page');
           
            return view('mobile_upload_doc',compact('file_type','doc'));
        }
        else
        {
            if(Session::get('username')=='')
                return redirect('/')->with('status',"Please login First");
            else
            return redirect('dashboard')->with('alert-danger',"Only admin and HR can access upload file page");
        }
    }
    public function upload_file_emp(Request $request)
    {
        if(Session::get('username')!='')
            {
        try
        {
            
        
                activity()->log('User ID '.session('user_id').' Trying To upload file');
                 $rules = array(
                'file_type'    =>  'required|numeric',
                'file' =>     'required|file|mimes:jpeg,png,jpg,pdf,docx,doc,xls,xlsx,csv|max:1024',
                
                     );
                $validator = Validator::make(Input::all(), $rules);
                if ($validator->fails()) {
                    activity()->log('upload process failed due to Validation Error');
                    return redirect()->back()->withErrors($validator)->withInput($request->all);
                }

                $file = $request->file;
                $upload_type = $request->input('file_type');
                $useremail = Session::get('useremail');
                $emp_id=DB::table('emp')->where('email',$useremail)->value('id');
                $branch =DB::table('emp')->where('id', $emp_id)->value('branch_location_id');
                $department  =DB:: table('emp')->where('id', $emp_id)->value('department');
                $employee  =$emp_id;
                if($request->has('file'))
                {
                    $folder=base_path().'/personal_doc/'.$employee;
                    if (!File::exists($folder)) {
                        File::makeDirectory($folder, 0775, true, true);
                    }
                  
                    $target_dir = 'personal_doc/'.$employee.'/';
                    $filename = strtolower($file->getClientOriginalName()); 
                    $extension = strtolower($file->getClientOriginalExtension());
                    $file_name = pathinfo($filename, PATHINFO_FILENAME);
                    $filename = $upload_type.'_'.$employee.'_'.strtotime(date('Y-m-d H:i:s')).'.'.$extension;
                    $target_file = $target_dir.$filename;
                    if($file->move(base_path().'/personal_doc/'.$employee.'/', $filename))
                    {
                        activity()->log('Upload file process Successful for user with id '.session('user_id'));
                        $add_to_upload = DB::table('emp_doc')->insert(['uploaded_by' => session('user_id'),'document_name'=>$upload_type,'emp_id'=>$employee,'path'=>$target_file,'created_at'=>now(),'status'=>'Pending','verify'=>'Pending']);
                        if($add_to_upload)
                         return redirect()->back()->with('alert-success',"Uploaded Successfully");
                        else
                        return redirect()->back()->with('alert-danger',"upload process not updated to Database");
                    }
                    else
                    {
                        activity()->log('Upload file process failed for user with id '.session('user_id'));
                        return redirect()->back()->with('alert-danger',"Upload Process Failed");
                    }
                }
                else
                {
                    return redirect()->back()->with('alert-danger','Request data does not have any files to import.');
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
        else
        {
            if(Session::get('username')=='')
                return redirect('/')->with('status',"Please login First");
            else
            return redirect('dashboard')->with('alert-danger',"Only admin and HR can access upload file page");
        }
    }
    public function show_doc_table()
    {
      
        try
        {
            if(Session::get('username')!='')
            {  
                $file_type = DB::table('document_type')->get();
              
                $doc = DB::table('emp_doc')
                ->join('emp','emp_doc.emp_id','=','emp.id')
                ->join('users','emp_doc.uploaded_by','=','users.id')->select('users.id','emp_doc.emp_id','emp_doc.document_name','emp_doc.uploaded_by','emp_doc.path','users.name','emp_doc.verify')->orderBy('emp_doc.id','DESC')->get();
               $doc=array('a','b');
                return view('mobile_upload_doc',compact('doc','file_type'));
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
    public function update_verify(Request $request)
    {
        
        try
        {
            if(Session::get('username')!='')
            {
                $id=$request->id;
                $verify = $request->verify;

                activity()->log(' Updating  Verify column of emp_doc table');

                $emp_doc=DB::table('emp_doc')
                  ->where('id', $id)
                  ->update(['verify' => $verify,'updated_at'=>Carbon::now()]);
        
                if($emp_doc)
                {
                    activity()->log('emp_doc with id '.$id.' and verify '.$verify.' Updated Successfully');
                    $request->session()->flash('success', 'Uploaded Successfully');
                    return redirect()->back();
                }
                else
                {
                    activity()->log('emp_doc with id '.$id.' and verify '.$verify.' cannot be updated');
                    $request->session()->flash('alert-danger', 'Employee Document cannot be updated!');
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
               return redirect()->back()->with('alert-danger',"Database Query Error! [".$e->getMessage()." ]");
        }
        catch(Exception $e)
        {
            activity()->log($e->getMessage());
               return redirect()->back()->with('alert-danger',$e->getMessage());
        }
    }

    public function delete_emp_doc(Request $request)
    { 
       
        try
        {
            if(Session::get('username')!='')
            {
          $id=$request->id;
          $image_path =$request->path;
            if($image_path!='')
            {
                if(file_exists($image_path)) {
                  File::delete($image_path);
                }
                
            }   
            $delete_resume = DB::table('emp_doc')->where('id',$id)->delete();
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

    public function add_doc_type(Request $request)
    {        
        try
        {
            if(Session::get('username')!='')
            {
                activity()->log('Trying to create Document Type');
                $doc_type = $request->doc_type;
                
                $doc_type=DB::table('document_type')->insert(
                ['name'=>$doc_type,'created_at'=>now()]
                );
                $doc=DB::table('document_type')->get();
                $data = '<option></option>';
                foreach($doc as $key=>$docs)
                {
                $data.='<option value="'.$docs->id.'">'.$docs->name.'</option>';
                }
                return $data;
                 if($doc_type)
                {
                    activity()->log('Document Type '.$doc_type.' Created Successfully');
                    $request->session()->flash('alert-success', 'Document Type Created Successfully!');
                    return redirect()->back()->with('alert-Success','Document Type Created Successfully!');
                }
                else
                {
                    activity()->log('Document Type  '.$doc_type.' cannot be created');
                    $request->session()->flash('alert-danger', 'Document Type cannot be created!');
                    return redirect()->back()->with('alert-danger','Document Type cannot be created!');
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

    public function mobile_add_doc_type(Request $request)
    {    
        try
        {
            if(Session::get('username')!='')
            {
                activity()->log('Trying to create Document Type');
                $doc_type = $request->doc_type;
                
                $doc_type=DB::table('document_type')->insert(
                ['name'=>$doc_type,'created_at'=>now()]
                );
                
               
                 if($doc_type)
                {
                    activity()->log('Document Type '.$doc_type.' Created Successfully');
                    $request->session()->flash('alert-success', 'Document Type Created Successfully!');
                    return Redirect::to('mobile_upload_doc');
                }
                else
                {
                    activity()->log('Document Type  '.$doc_type.' cannot be created');
                    $request->session()->flash('alert-danger', 'Document Type cannot be created!');
                    return Redirect::to('mobile_upload_doc');
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

    public function find_per_detail()
    {
      
        try
        {
            if(Session::get('username')!='')
            {  
                activity()->log("Fetch Employees id already exist or not in family_detail and family_detail_temp table");
                $emp_id = emp::select('id')->where('email',session('useremail'))->value('id');
                $count_temp =DB::table('family_detail_temp')->where('emp_id',$emp_id)->count(); 
                $count =DB::table('family_detail')->where('emp_id',$emp_id)->count(); 
                if($count_temp>0 || $count>0)
                {
                    $flag=1;
                }
                else
                {
                    $flag=0;
                }
                if($count_temp>0)
                {
                    $data=DB::table('family_detail_temp')->where('emp_id',$emp_id)->get(); 
                }
               else if($count>0)
                {
                    $data=DB::table('family_detail')->where('emp_id',$emp_id)->get();  
                }
                else
                {
                    $data='';
                }
                return view('mobile_personal_detail',compact('flag','data'));
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

    public function emp_personal_detail(Request $request)
    {  
        try
        {
            if(Session::get('username')!='')
            {
                activity()->log('Trying to create Employee Personal Detail');
                $if_married=$request->input('if_married');
                $if_children=$request->input('if_children');
                $emp_id = emp::select('id')->where('email',session('useremail'))->value('id');
                $father_name = $request->input('father_name');
                $father_dob = $request->input('father_dob');
                $father_aadhar = $request->input('father_aadhar');
                $father_cur_place = $request->input('father_cur_place');
                $mother_name = $request->input('mother_name');
                $mother_dob = $request->input('mother_dob');
                $mother_aadhar = $request->input('mother_aadhar');
                $mother_cur_place = $request->input('mother_cur_place');
                $father_array=array('father_name'=>$father_name,'father_dob'=>$father_dob,'father_aadhar'=>$father_aadhar,'father_place'=>$father_cur_place);
                $father_array= json_encode($father_array);
                $mother_array=array('mother_name'=>$mother_name,'mother_dob'=>$mother_dob,'mother_aadhar'=>$mother_aadhar,'mother_place'=>$mother_cur_place);
                $mother_array= json_encode($mother_array);
               if($if_married=='yes')
               {
                $spouse_name = $request->input('spouse_name');
                $spouse_dob = $request->input('spouse_dob');
                $spouse_aadhar = $request->input('spouse_aadhar');
                $spouse_gender = $request->input('spouse_gender');
                $spouse_cur_place = $request->input('spouse_cur_place');
                $spouse_array=array('spouse_name'=>$spouse_name,'spouse_dob'=>$spouse_dob,'spouse_gender'=>$spouse_gender,'spouse_aadhar'=>$spouse_aadhar,'spouse_place'=>$spouse_cur_place);
                $spouse_array= json_encode($spouse_array);
            }
               else{
                $spouse_array='';
               }
               if($if_children=='yes')
               {
                $child = $request->input('child');
                $child_dob = $request->input('child_dob');
                $child_gender = $request->input('child_gender');
                $child_aadhar = $request->input('child_aadhar');
                $child_cur_place = $request->input('child_cur_place');
                
                $child_array=array();
                $i=0;
                foreach($child as $children)
                {
                   $child_array[$i+1]=array('child_name'=>$children,'child_dob'=>$child_dob[$i],'child_gender'=>$child_gender[$i],'child_aadhar'=>$child_aadhar[$i],'child_place'=>$child_cur_place[$i]);
                   
                   $i++;
                }
                $child_array=json_encode($child_array);
                }
                else{
                    $child_array=''; 
                }
                $personal_detail=DB::table('family_detail_temp')->insert(['emp_id'=>$emp_id,'father'=>$father_array,'mother'=>$mother_array,'spouse'=>$spouse_array,'children'=>$child_array,'status'=>'pending','created_at'=>now()]);
                
              
                 if($personal_detail)
                {
                    $notification = DB::table('notification')->insert([
                        'user_id'=>Session::get('user_id'),'requester_id'=>$emp_id,'notification'=>'Personal Detail','notification_status'=>'pending','link'=>'personal_detail_list','status'=>'active']);
                    activity()->log('Family detail Created Successfully');
                    $request->session()->flash('alert-success', 'Personal Detail Created Successfully!');
                    return redirect()->back();
                }
                else
                {
                    activity()->log('Family detail cannot be created');
                    $request->session()->flash('alert-danger', 'Personal Detail cannot be created!');
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

    public function update_emp_personal_detail(Request $request)
    {  
        try
        {
            if(Session::get('username')!='')
            {
                activity()->log('Trying to Update Employee Personal Detail');
                $id=$request->input('id');
                $if_married=$request->input('if_married');
                $if_children=$request->input('if_children');
                $emp_id = emp::select('id')->where('email',session('useremail'))->value('id');
                $father_name = $request->input('father_name');
                $father_dob = $request->input('father_dob');
                $father_aadhar = $request->input('father_aadhar');
                $father_cur_place = $request->input('father_cur_place');
                $mother_name = $request->input('mother_name');
                $mother_dob = $request->input('mother_dob');
                $mother_aadhar = $request->input('mother_aadhar');
                $mother_cur_place = $request->input('mother_cur_place');
                $father_array=array('father_name'=>$father_name,'father_dob'=>$father_dob,'father_aadhar'=>$father_aadhar,'father_place'=>$father_cur_place);
                $father_array= json_encode($father_array);
                $mother_array=array('mother_name'=>$mother_name,'mother_dob'=>$mother_dob,'mother_aadhar'=>$mother_aadhar,'mother_place'=>$mother_cur_place);
                $mother_array= json_encode($mother_array);
               if($if_married=='yes')
               {
                $spouse_name = $request->input('spouse_name');
                $spouse_dob = $request->input('spouse_dob');
                $spouse_aadhar = $request->input('spouse_aadhar');
                $spouse_gender = $request->input('spouse_gender');
                $spouse_cur_place = $request->input('spouse_cur_place');
                $spouse_array=array('spouse_name'=>$spouse_name,'spouse_dob'=>$spouse_dob,'spouse_gender'=>$spouse_gender,'spouse_aadhar'=>$spouse_aadhar,'spouse_place'=>$spouse_cur_place);
                $spouse_array= json_encode($spouse_array);
            }
               else{
                $spouse_array='';
               }
               if($if_children=='yes')
               {
                $child = $request->input('child');
                $child_dob = $request->input('child_dob');
                $child_gender = $request->input('child_gender');
                $child_aadhar = $request->input('child_aadhar');
                $child_cur_place = $request->input('child_cur_place');
                
                $child_array=array();
                $i=0;
                foreach($child as $children)
                {
                   $child_array[$i+1]=array('child_name'=>$children,'child_dob'=>$child_dob[$i],'child_gender'=>$child_gender[$i],'child_aadhar'=>$child_aadhar[$i],'child_place'=>$child_cur_place[$i]);
                   
                   $i++;
                }
                $child_array=json_encode($child_array);
                }
                else{
                    $child_array=''; 
                }
                $count_temp =DB::table('family_detail_temp')->where('emp_id',$emp_id)->count(); 
                $count =DB::table('family_detail')->where('emp_id',$emp_id)->count(); 
                if($count_temp>0)
                {
                $personal_detail=DB::table('family_detail_temp')
                  ->where('id', $id)
                  ->update(['emp_id'=>$emp_id,'father'=>$father_array,'mother'=>$mother_array,'spouse'=>$spouse_array,'children'=>$child_array,'updated_at'=>Carbon::now()]);
                }
                if($count>0)
                {
                    $personal_detail=DB::table('family_detail')
                    ->where('id', $id)
                    ->update(['emp_id'=>$emp_id,'father'=>$father_array,'mother'=>$mother_array,'spouse'=>$spouse_array,'children'=>$child_array,'status'=>'accepted','updated_at'=>Carbon::now()]);    
                }
                 if($personal_detail)
                {
                    $notification = DB::table('notification')->insert([
                        'user_id'=>Session::get('user_id'),'requester_id'=>$emp_id,'notification'=>'Personal Detail','notification_status'=>'pending','link'=>'personal_detail_list','status'=>'active']);
                    activity()->log('Family detail Created Successfully');
                    $request->session()->flash('alert-success', 'Personal Detail Updated Successfully!');
                    return redirect()->back();
                }
                else
                {
                    activity()->log('Family detail cannot be created');
                    $request->session()->flash('alert-danger', 'Personal Detail cannot be Updated!');
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

    public function personal_detail_list()
    {
        if(Session::get('username')!='' && (session('role')=='admin' || session('role')=='hr'))
        {
            activity()->log('feching all uploaded pesonal detail of all employees!');
            try
            {
            $personal_detail_temp =DB::table('family_detail_temp')
            ->join('emp','family_detail_temp.emp_id', '=' , 'emp.id')
            ->select('family_detail_temp.status','family_detail_temp.id','family_detail_temp.emp_id','family_detail_temp.father','family_detail_temp.mother','family_detail_temp.spouse','family_detail_temp.children','emp.first_name','emp.middle_name','emp.last_name')
           ->get();
           $personal_detail =DB::table('family_detail')
            ->join('emp','family_detail.emp_id', '=' , 'emp.id')
            ->select('family_detail.status','family_detail.id','family_detail.emp_id','family_detail.father','family_detail.mother','family_detail.spouse','family_detail.children','emp.first_name','emp.middle_name','emp.last_name')
           ->get();
            return view('personal_detail_list',compact('personal_detail','personal_detail_temp'));
            }
            catch(QueryException $e)
            {
                activity()->log($e->getMessage());
                return redirect()->back()->with('alert-danger','Database Query Exception! ['.$e->getMessage().' ]');
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
            return redirect('dashboard')->with('alert-danger',"Only admin and HR can add employee");
        }
    }
    
    public function get_per_details(Request $request)
    {
        
        if(Session::get('username')!='' && (session('role')=='admin' || session('role')=='hr'))
        {
            activity()->log('fetch employees personal detail');

            try{
                $emp_id=$request->emp_id;
                $id=$request->id;
                $status=$request->status;
                if($status=='accepted')
                {
                    $user_data= DB::table('family_detail')
                    ->join('emp','family_detail.emp_id', '=' , 'emp.id')
                    ->select('family_detail.status','family_detail.id','family_detail.emp_id','family_detail.father','family_detail.mother','family_detail.spouse','family_detail.children','emp.first_name','emp.middle_name','emp.last_name')
                    ->where('emp_id',$emp_id)
                    ->get();
                }
                else
                {
                $user_data= DB::table('family_detail_temp')
                                    ->join('emp','family_detail_temp.emp_id', '=' , 'emp.id')
                                    ->select('family_detail_temp.status','family_detail_temp.id','family_detail_temp.emp_id','family_detail_temp.father','family_detail_temp.mother','family_detail_temp.spouse','family_detail_temp.children','emp.first_name','emp.middle_name','emp.last_name')
                                    ->where('emp_id',$emp_id)
                                    ->get();
                }
             $output='';
             $i=0;
              foreach ($user_data as $emp) 
              {
                date_diff(date_create('1970-02-01'), date_create('today'))->y;
                
                $father=json_decode($emp->father,true);
                $mother=json_decode($emp->mother,true);
                $spouse=json_decode($emp->spouse,true);
                $children=json_decode($emp->children,true);
                $father_age=date_diff(date_create($father['father_dob']), date_create('today'))->y;
                $mother_age=date_diff(date_create($mother['mother_dob']), date_create('today'))->y;
                $spouse_age=date_diff(date_create($spouse['spouse_dob']), date_create('today'))->y;
              $output.='
              <div class="modal-header bg-pink">
                            <h4 class="modal-title" id="defaultModalLabel"> Personal Detail :- '.$emp->first_name.' '.$emp->middle_name.' '.$emp->last_name.'</h4>
                        </div>
                        <div class="modal-body" >   
                           <div class="row clearfix">
                            <div class="col-md-8" id="blood_group">
                           <input type="hidden" name="id" class="form-control family_id" value='.$emp->id.'> 
                            </div>
                          </div> 
                         <div class="row clearfix">
                            <div class="col-md-3">
                                <label for="blood_group">Father`s Name:</label>
                            </div>
                            <div class="col-md-4" id="blood_group">
                            '.$father['father_name'].'
                            </div>
                            <div class="col-md-2">
                            <label for="blood_group">DOB:</label>
                        </div>
                        <div class="col-md-3" id="blood_group">
                        '.$father['father_dob'].'
                        </div>
                          </div> 
                        <div class="row clearfix">
                          <div class="col-md-3">
                          <label for="blood_group">Aadhar No:</label>
                      </div>
                      
                      <div class="col-md-2" id="blood_group">
                      '.$father['father_aadhar'].'
                      </div>
                         <div class="col-md-1 align="left">
                          <label for="blood_group">Age:</label>
                             </div>
                          <div class="col-md-1" id="blood_group">
                          '.$father_age.'yr
                          </div>
                          <div class="col-md-2">
                              <label for="blood_group">Current Place:</label>
                          </div>
                          <div class="col-md-3" id="blood_group">
                        '.$father['father_place'].'
                        </div>
                         </div> 
                        <div class="row clearfix">
                          
                      </div>  
                      <hr>
                <div class="row clearfix">
                    <div class="col-md-3">
                        <label for="blood_group">Mather`s Name</label>
                    </div>
                    <div class="col-md-4" id="blood_group">
                        '.$mother['mother_name'].'
                    </div>
                    <div class="col-md-2">
                        <label for="blood_group">DOB</label>
                    </div>
                    <div class="col-md-3" id="blood_group">
                        '.$mother['mother_dob'].'
                    </div>
                </div> 
                <div class="row clearfix">
                    <div class="col-md-3">
                        <label for="blood_group">Aadhar No</label>
                    </div>
                    <div class="col-md-2" id="blood_group">
                        '.$mother['mother_aadhar'].'
                     </div>
                    <div class="col-md-1">
                        <label for="blood_group">Age</label>
                    </div>
                    <div class="col-md-1" id="blood_group">
                        '.$mother_age.'yr
                    </div>
                    <div class="col-md-2">
                        <label for="blood_group">Current Place</label>
                    </div>
                    <div class="col-md-3" id="blood_group">
                        '.$mother['mother_place'].'
                    </div>
                </div> 
                 <hr>  
                <div class="row clearfix">
                    <div class="col-md-3">
                        <label for="blood_group">Spouse`s Name</label>
                    </div>
                    <div class="col-md-4" id="blood_group">
                        '.$spouse['spouse_name'].'
                    </div>
                    <div class="col-md-2">
                        <label for="blood_group">DOB</label>
                    </div>
                    <div class="col-md-3" id="blood_group">
                        '.$spouse['spouse_dob'].'
                    </div>
                </div> 
                <div class="row clearfix">
                    <div class="col-md-3">
                        <label for="blood_group">Aadhar No</label>
                    </div>
                    <div class="col-md-2" id="blood_group">
                        '.$spouse['spouse_aadhar'].'
                    </div>
                    <div class="col-md-1">
                        <label for="blood_group">Age</label>
                    </div>
                    <div class="col-md-1" id="blood_group">
                        '.$spouse_age.'yr
                    </div>
                    <div class="col-md-2">
                        <label for="blood_group">Current Place</label>
                    </div>
                    <div class="col-md-3" id="blood_group">
                        '.$spouse['spouse_place'].'
                    </div>
                </div> 
                <hr>';
                $total_child=sizeof($children); 
                $output.='
                <div class="row clearfix">
                <div class="col-md-4">
                    <label for="blood_group">No Of Child</label>
                </div>
                <div class="col-md-8" id="blood_group">
                 '.$total_child.'
                 </div>
               </div><hr> ';
                foreach($children as $child)
                {

                    $child_age=date_diff(date_create($child['child_dob']), date_create('today'))->y; 
                   
               $output.='<div class="row clearfix">
               <div class="col-md-3">
                   <label for="blood_group">Child`s Name</label>
               </div>
               <div class="col-md-4" id="blood_group">
                '.$child['child_name'].'
                </div>
                <div class="col-md-2">
                  <label for="blood_group">DOB</label>
              </div>
              <div class="col-md-3" id="blood_group">
              '.$child['child_dob'].'
              </div>
              </div> 
              <div class="row clearfix">
              <div class="col-md-3">
              <label for="blood_group">Aadhar No</label>
          </div>
          <div class="col-md-2" id="blood_group">
          '.$child['child_aadhar'].'
          </div>
              <div class="col-md-1">
              <label for="blood_group">Age</label>
                 </div>
              <div class="col-md-1" id="blood_group">
              '.$child_age.'yr
              </div>
              <div class="col-md-2">
              <label for="blood_group">Current Place</label>
          </div>
          <div class="col-md-3" id="blood_group">
        '.$child['child_place'].'
        </div>
            </div> 
            <hr>
          ';
                }
              }
              if($emp->status=='pending')
              {
             $output.='</div>
             <div class="modal-footer">
                 <button type="Button" class="btn  bg-green waves-effect accept">Accept</button>
                 <button type="Button" class="btn  bg-deep-orange waves-effect reject">Reject</button>
                 <button type="button" class="btn bg-blue-grey  waves-effect" data-dismiss="modal">close</button><br>
                 <br>
                 <div class="row clearfix" id="rejectdiv" style="display:none">
                 <div class="col-md-8" id="blood_group">
                 </div>
                 <div class="col-md-2">
                 <textarea class="form-control remark"></textarea>
                 </div>
                 <div class="col-md-2">
                 <button type="button" class="btn bg-teal waves-effect final_reject">submit</button>
                 </div>
             </div> ';
              }
              else{
                $output.='</div>
                <div class="modal-footer">
                   <button type="button" class="btn bg-blue-grey  waves-effect" data-dismiss="modal">close</button>
                </div> ';
              }
              return $output;

            }
            catch(QueryException $e)
            {
                activity()->log($e->getMessage());
                return redirect()->back()->with('alert-danger','Database Query Exception! ['.$e->getMessage().' ]');
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
            return redirect('dashboard')->with('alert-danger',"Only admin and HR can add employee");
        }
    }
    public function update_per_details(Request $request)
    {
        
        try
        {
            if(Session::get('username')!='')
            {
                $id=$request->id;
            

                activity()->log(' Updating Family Personal details for id '.$id.'vrify by admin as accept and remove data from family_detail_temp table  and insert in family_detail table');

                $family_detail=DB::table('family_detail_temp')
                  ->where('id', $id)
                  ->get();
                foreach($family_detail as $family_details )
                {
                   $emp_id=$family_details->emp_id; 
                   $father=$family_details->father;
                   $mother=$family_details->mother;
                   $spouse=$family_details->spouse;
                   $children=$family_details->children;
                   

                }
            
                $insert=DB::table('family_detail')->insert(['emp_id'=>$emp_id,'father'=>$father,'mother'=>$mother,'spouse'=>$spouse,'children'=>$children,'created_at'=>now()]);
                if($insert)
                {
                    activity()->log('remove data from family temp table');  
                    $delete=DB::table("family_detail_temp")->delete($id);
                }
                if($insert && $delete)
                {
                    activity()->log('Data in family_detail table inserted successfully');
                    $request->session()->flash('alert-success', 'Data verified successfully!');
                    return Redirect::to('personal_detail_list');
                }
                else
                {
                    activity()->log('Data in family_detail table  can not be inserted');
                    $request->session()->flash('alert-danger', 'Data cna not be verified!');
                    return Redirect::to('personal_detail_list');
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
    public function reject_per_details(Request $request)
    {
    
        try
        {
            if(Session::get('username')!='')
            {
                $id=$request->id;
                $remark=$request->remark;

                activity()->log('Personal detail of '.$id.' is rejected');

                $emp_id=DB::table('family_detail_temp')
                  ->where('id', $id)
                  ->value('emp_id');
                
            
                $update=DB::table('family_detail_temp')
                ->where('id', $id)
                ->update(['status' => 'rejected','remark'=>$remark,'updated_at'=>Carbon::now()]);
                
                if($update)
                {
                    $notification = DB::table('notification')->insert([
                        'user_id'=>Session::get('user_id'),'requester_id'=>$emp_id,'notification'=>'Your Personal detail is rejected Reson:  '.$remark,'notification_status'=>'Rejected','link'=>'mobile_personal_detail','status'=>'active']);
                    activity()->log('Personal Detail of '.$emp_id.' is verified successfully');
                    $request->session()->flash('alert-success', 'Personal Detail is verified successfully!');
                    return Redirect::to('personal_detail_list');
                }
                else
                {
                    return 2;
                    activity()->log('Personal Detail of '.$emp_id.' is connot be verified');
                    $request->session()->flash('alert-danger', 'Personal Detail is can not verified!');
                    return Redirect::to('personal_detail_list');
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
            return Redirect::to('personal_detail_list')->with('alert-danger',"Database Query Error! [".$e->getMessage()." ]");
        }
        catch(Exception $e)
        {
            activity()->log($e->getMessage());
            return Redirect::to('personal_detail_list')->with('alert-danger',$e->getMessage());
           }
        }




}
