<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\User;
use App\emp;
use App\salary;
use App\Jobs\UploadTDS;
use App\Jobs\UploadBonus;
use App\Jobs\UploadIncentive;
use App\Jobs\UploadExgratia;
use App\Jobs\UploadArrear;
use App\Jobs\UploadSales;
use App\Jobs\UploadEmployee;
use App\Jobs\UploadAttendance;
use App\Jobs\UploadMonthlyAttendance;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Exception;
use \Illuminate\Database\QueryException;
ini_set('max_execution_time', 300);
date_default_timezone_set('Asia/Kolkata');

class UploadController extends Controller
{

	public function get_file_form()
    {
        if(Session::get('username')!='' && (session('role')=='admin' || session('role')=='hr'))
        {
            $department = DB::table('department')->get();
            $branch = DB::table('branch')->get();
            $document = DB::table('upload_docs')
            ->leftjoin('department','department.id','=','upload_docs.department')
            ->leftjoin('branch','branch.id','=','upload_docs.branch')
            ->leftjoin('emp','emp.id','=','upload_docs.emp_id')
            ->select('upload_docs.id','upload_docs.title as heading','upload_docs.file','upload_docs.branch','upload_docs.created_at',
            'department.department_name','branch.branch','emp.title','emp.first_name','emp.middle_name','emp.last_name')->where('upload_docs.status','active')->get();
            activity()->log('Fetching Upload File Page');
            return view('file',compact('department','document','branch'));
          
        }
        else
        {
            if(Session::get('username')=='')
                return redirect('/')->with('status',"Please login First");
            else
            return redirect('dashboard')->with('alert-danger',"Only admin and HR can access upload file page");
        }
    }

	public function get_sales_form()
    {
    	if(Session::get('username')!='' && (session('role')=='admin' || session('role')=='hr'))
        {
        	activity()->log('Fetching Upload-Sales Page');
        	return view('upload-sales');
		}
        else
        {
        	if(Session::get('username')=='')
                return redirect('/')->with('status',"Please login First");
            else
            return redirect('dashboard')->with('alert-info',"Only admin and HR can access upload sales page");
        }
    }

    public function upload_sales_csv(Request $request){

    if(Session::get('username')!='' && (session('role')=='admin' || session('role')=='hr'))
    {
        try
        {
            activity()->log('Trying to Upload Sales');
            $csvfile = $request->csvfile;
           	
            if($request->has('csvfile'))
            {
                $target_dir = 'sales_upload/';
                $filename = strtolower($csvfile->getClientOriginalName()); 
                $extension = strtolower($csvfile->getClientOriginalExtension());
                $target_file = $target_dir.$filename;
                if($extension!='csv')
                { 
                    return 'Please Upload csv file only';
                	/*return redirect()->back()->with('alert-info','Please Upload csv file only');*/
                }

                $add_to_history = DB::table('upload_history')->insert(['user_email'=>session('useremail'),'upload_type'=>'Sales Report','file_location'=>$target_file,'file_upload_status'=>'In Progress','process_status'=>'In Progress','start_time'=>Carbon::now()]);
                $history_id = DB::table('upload_history')->max('id');
                $data = array(
                'history_id'=> $history_id,
                );

                UploadSales::dispatch($data,$csvfile);
                if(Session::has('alert-success'))
                { 
                    return Session::get('alert-success');                   
                    /*return redirect()->back()->with('alert-success',Session::get('alert-success'));*/
                }
                else
                {  
                    return Session::get('alert-danger');                  
                    /*return redirect()->back()->with('alert-info',Session::get('alert-info'));*/
                }
            }
            else
            {
                return 'Request data does not have any files to import.';
                /*return redirect()->back()->with('alert-info','Request data does not have any files to import.');*/
            }
        }
        catch(QueryException $e)
        {
            activity()->log($e->getMessage());
            return "Database Query Error! [".$e->getMessage()." ]";
            /*return redirect()->back()->with('alert-info',"Database Query Error! [".$e->getMessage()." ]");*/
        } 
        catch(Exception $e)
        {
            activity()->log($e->getMessage());
            return $e->getMessage();
            /*return redirect()->back()->with('alert-info',$e->getMessage());*/
        }
    }
    else
    {
        if(Session::get('username')=='')
            return redirect('/')->with('status',"Please login First");
        else
        return redirect('dashboard')->with('alert-info',"Only admin and HR can access upload sales page");
    }
    } 

    public function file_deactivate(Request $request)
    {
        if(Session::get('username')!='' && (session('role')=='admin' || session('role')=='hr'))
        {
            try
            {
                $id = $request->id;
                $update = DB::table('upload_docs')->where('id',$id)->update(['status'=>'inactive']);
                if($update)
                {
                    activity()->log('file deactivated Successfully by user with id '.session('user_id'));
                    return 1;
                }
                else
                {
                     activity()->log('file deactivate process failed');
                     return 0;
                }
            }
            catch(QueryException $e)
            {
                activity()->log($e->getMessage());
                return redirect()->back()->with('alert-info',"Database Query Error! [".$e->getMessage()." ]");
            } 
            catch(Exception $e)
            {
                activity()->log($e->getMessage());
                return redirect()->back()->with('alert-info',$e->getMessage());
            }
        }
        else
        {
            if(Session::get('username')=='')
                return redirect('/')->with('status',"Please login First");
            else
            return redirect('dashboard')->with('alert-info',"Only admin and HR can access upload file page");
        }
    }

    public function tds_index(Request $request)
    {
        try
        {
            if(Session::get('username')!='')
            {
                activity()->log('Loading Upload TDS page');
                $branch=DB::table('branch')->get();
                return view('upload-tds',compact('branch'));
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
    
    public function upload_attendance_csv(Request $request){
		if(Session::get('username')!='')
        {
        try
        {
			activity()->log('Trying to Upload Attendance report');
	        $msg=''; $final_out=''; $initial_in='';
	        $i=0;
	        if($request->input('attendance_date')=='')
	            { 
	            	return redirect()->back()->with('alert-info','Please Select Attendance Date')->withinput($request->all);
	            }
	       	$attendance_date = $request->input('attendance_date');//date('2018-06-01');
	       	$inserted=0;
	        $validations=''; $errormsg='';
	        if($request->hasFile('csvfile')){ 
	        	$csvfile = $request->file('csvfile');
	        	$extension = strtolower($csvfile->getClientOriginalExtension()); 
	        	$target_dir = '/daily_report_upload/';
            	$filename = strtolower($csvfile->getClientOriginalName()); 
            	$target_file = $target_dir.$filename;
	            if($extension!='csv')
	            { 
                    return 'Please Upload csv file only';
	                /*return redirect()->back()->with('alert-info','Please Upload csv file only');*/
	            }

	            $add_to_history = DB::table('upload_history')->insert(['user_email'=>session('useremail'),'upload_type'=>'Attendance Report','file_location'=>$target_file,'file_upload_status'=>'In Progress','process_status'=>'In Progress','start_time'=>Carbon::now()]);
	            $history_id = DB::table('upload_history')->max('id');
	            
	            $data = array(
            	'attendance_date'=> $attendance_date,
            	'history_id'=> $history_id,
	          	);

	            /*Session::put('upload_attendance','pending');*/
	        	$var=UploadAttendance::dispatch($data,$csvfile);
               if(Session::has('alert-success'))
                {
                    return session('alert-success');
                    /*return redirect()->back()->with('alert-success',Session::get('alert-success'));*/
                }
                elseif(Session::has('alert-danger'))
                {
                    return session('alert-danger');
                    /*return redirect()->back()->with('alert-info',Session::get('alert-info'));*/
                }
	        }
	        else
	        {
	            return redirect()->back()->with('alert-info','Request data does not have any files to import.');
	        }  
	    }
        catch(QueryException $e)
        {
            activity()->log($e->getMessage());
            return redirect()->back()->with('alert-info','Database Query Error! ['.$e->getMessage().' ]');
        }
        catch(Exception $e)
        {
            activity()->log($e->getMessage());
            return redirect()->back()->with('alert-info',$e->getMessage());
        }
	    }
	    else
	        return redirect('/')->with('status',"Please login First");
    } 

    public function upload_emp_csv(Request $request){

        if(Session::get('username')!='')
        {
        try
        {

        $msg=''; 
        $i=0;
        
        $inserted=0;
        $errormsg='';
        if($request->hasFile('csvfile')){ 
            $target_dir = '/employee_upload/';
            $filename = strtolower($request->file('csvfile')->getClientOriginalName()); 
            $extension = strtolower($request->file('csvfile')->getClientOriginalExtension());
            $target_file = $target_dir.$filename;
            $extension = strtolower($request->file('csvfile')->getClientOriginalExtension()); 
            if($extension!='csv')
            { 
                return 'Please Upload csv file only';
                /*return redirect()->back()->with('alert-info','Please Upload csv file only');*/
            }
            $csvfile = $request->file('csvfile');           

                $add_to_history = DB::table('upload_history')->insert(['user_email'=>session('useremail'),'upload_type'=>'Employee','file_location'=>$target_file,'file_upload_status'=>'In Progress','process_status'=>'In Progress','start_time'=>Carbon::now()]);
                $history_id = DB::table('upload_history')->max('id');
                
                $data = array(
                'history_id'=> $history_id,
                );

                /*Session::put('upload_employee','pending');*/
                UploadEmployee::dispatch($data,$csvfile);
               if(Session::has('alert-success'))
                {
                    return session('alert-success');
                    /*return redirect()->back()->with('alert-success',Session::get('alert-success'));*/
                }
                elseif(Session::has('alert-danger'))
                {
                    return session('alert-danger');
                    /*return redirect()->back()->with('alert-info',Session::get('alert-info'));*/
                }
        }
        else
        {
            return redirect()->back()->with('alert-info','Request data does not have any files to import.');
        } 

    }
    catch(QueryException $e)
    {
        activity()->log($e->getMessage());
        return redirect()->back()->with('alert-info','Database Query Error! ['.$e->getMessage().' ]');
    }
    catch(Exception $e)
    {
        activity()->log($e->getMessage());
        return redirect()->back()->with('alert-info',$e->getMessage());
    }
    }
    else
        return redirect('/')->with('status',"Please login First");
    }

    public function upload_tds_csv(Request $request){
        
        if(Session::get('username')!='')
        {
        try
        {

        $msg=''; 
        $i=0;
        
        $inserted=0;
        $errormsg='';
        $month = $request->month;
        $year = $request->year;
        if($request->hasFile('csvfile')){ 
            $target_dir = '/tds_upload/';
            $filename = strtolower($request->file('csvfile')->getClientOriginalName()); 
            $extension = strtolower($request->file('csvfile')->getClientOriginalExtension());
            $target_file = $target_dir.$filename;
            $extension = strtolower($request->file('csvfile')->getClientOriginalExtension()); 
            if($extension!='csv')
            { 
                return 'Please Upload csv file only';
                /*return redirect()->back()->with('alert-info','Please Upload csv file only');*/
            }
            if($year=='' || $month=='')
            {
                return 'Month and year field are required!';
            }
            $csvfile = $request->file('csvfile');           

                $add_to_history = DB::table('upload_history')->insert(['user_email'=>session('useremail'),'upload_type'=>'TDS','file_location'=>$target_file,'file_upload_status'=>'In Progress','process_status'=>'In Progress','start_time'=>Carbon::now()]);
                $history_id = DB::table('upload_history')->max('id');
                
                $data = array(
                'history_id'=> $history_id,
                'month'=> $month,
                'year'=> $year,
                );

                /*Session::put('upload_tds','pending');*/
                UploadTDS::dispatch($data,$csvfile);
               if(Session::has('alert-success'))
                {
                    return session('alert-success');
                    /*return redirect()->back()->with('alert-success',Session::get('alert-success'));*/
                }
                elseif(Session::has('alert-danger'))
                {
                    return session('alert-danger');
                    /*return redirect()->back()->with('alert-info',Session::get('alert-info'));*/
                }
        }
        else
        {
            return redirect()->back()->with('alert-info','Request data does not have any files to import.');
        } 

    }
    catch(QueryException $e)
    {
        activity()->log($e->getMessage());
        return redirect()->back()->with('alert-info','Database Query Error! ['.$e->getMessage().' ]');
    }
    catch(Exception $e)
    {
        activity()->log($e->getMessage());
        return redirect()->back()->with('alert-info',$e->getMessage());
    }
    }
    else
        return redirect('/')->with('status',"Please login First");
    }


     public function upload_file(Request $request)
    {
        if(Session::get('username')!='' && (session('role')=='admin' || session('role')=='hr'))
        {
            try
            {
                activity()->log('Trying to Upload file');
                 $rules = array(
                'file_type'    =>  'required|string',
                'file' =>     'required|file|mimes:jpeg,png,jpg,gif,svg,pdf,docx,doc,webm,mp4,3gp|max:2048',
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
                    $target_dir = 'file_upload\\';
                    $filename = strtolower($file->getClientOriginalName()); 
                    $extension = strtolower($file->getClientOriginalExtension());
                    $file_name = pathinfo($filename, PATHINFO_FILENAME);
                    $filename = $file_name.'_'.strtotime(date('Y-m-d H:i:s')).'.'.$extension;
                    $target_file = $target_dir.$filename;
                    if($file->move(base_path().'\\file_upload\\', $filename))
                    {
                        activity()->log('Upload file process Successful for user with id '.session('user_id'));
                        $add_to_upload = DB::table('upload_docs')->insert(['user_id' => session('user_id'),'title'=>$upload_type,'branch'=>$branch,'department'=>$department,'emp_id'=>$employee,'file'=>$target_file,'status'=>'active']);
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

    public function get_company_docs()
    {
       $company_docs=DB::table('upload_docs')->whereNull('emp_id')->get();
       return view('mobile_company-docs',compact('department','document','branch'));
    }

    public function daily_report_sample(Request $request){ 
       $export_data="E. Code,Shift,IN-1,OUT-1,IN-2,OUT-2";
        return response($export_data)
                ->header('Content-Type','application/csv')               
                ->header('Content-Disposition', 'attachment; filename="daily_report_sample.csv"')
                ->header('Pragma','no-cache')
                ->header('Expires','0');    
    }

    public function sales_sample(Request $request){ 
       $export_data="Source Site,Bill Date,Department,Bill Qty SUM,Net Amount SUM,Category5,RSP,Salesperson,Salesperson No";
        return response($export_data)
                ->header('Content-Type','application/csv')               
                ->header('Content-Disposition', 'attachment; filename="sales_sample.csv"')
                ->header('Pragma','no-cache')
                ->header('Expires','0');    
    }

    public function employee_sample(Request $request){ 
       $export_data="genesis_ledger_id,genesis_id,biometric_id,branch_location_id,title,first_name,middle_name,last_name,blood_group,email,dob,mobile,gender,category,marital_status,adhaar_number,pan_number,local_address,permanent_address,photo,emergency_call_number,emergency_call_person,department,designation,doj,status,esic_number,epf_number,lin_number,uan_number,acc_holder_name,acc_no,ifsc_code,bank_name,branch,salary,basic,epf_option,esic_option,role";
        return response($export_data)
                ->header('Content-Type','application/csv')               
                ->header('Content-Disposition', 'attachment; filename="employee_sample.csv"')
                ->header('Pragma','no-cache')
                ->header('Expires','0');    
    }

    public function employee_download(Request $request){ 
        $zone=$request->zone;
        if($zone=='' || $zone==NULL)
        {
            return Redirect::to('upload-emp')->with('alert-danger','Please select Zone first to download sample file');
        }
        $emp_sample=DB::table('emp')->join('salary','emp.salary_id','=','salary.id')->join('department','department.id','=','emp.department')->join('designation','designation.id','=','emp.designation')->join('bank_list','emp.bank_name','=','bank_list.id')->join('users','users.email','=','emp.email')->select('emp.id','emp.genesis_ledger_id','emp.genesis_id','emp.biometric_id','emp.branch_location_id','emp.title','emp.first_name','emp.middle_name','emp.last_name','emp.blood_group','emp.email','emp.dob','emp.mobile','emp.gender','emp.category','emp.marital_status','emp.adhaar_number','emp.pan_number','emp.local_address','emp.permanent_address','emp.photo','emp.emergency_call_number','emp.emergency_call_person','department.department_name','designation.designation','emp.doj','emp.status','emp.esic_number','emp.epf_number','emp.lin_number','emp.uan_number','emp.acc_holder_name','emp.acc_no','emp.ifsc_code','bank_list.bank_name','emp.branch','salary.salary','emp.epf_option','emp.esic_option','users.role_id')->where('emp.status','active')->where('branch_location_id','=',$zone)->get();
        $tot_record_found=0;
        if(count($emp_sample)>0){
            $tot_record_found=1;
            //First Methos          
            $export_data="emp_id,genesis_ledger_id,genesis_id,biometric_id,branch_location_id,title,first_name,middle_name,last_name,blood_group,email,dob,mobile,gender,category,marital_status,adhaar_number,pan_number,local_address,permanent_address,photo,emergency_call_number,emergency_call_person,department,designation,doj,status,esic_number,epf_number,lin_number,uan_number,acc_holder_name,acc_no,ifsc_code,bank_name,branch,salary,basic,epf_option,esic_option,role\n";
            foreach($emp_sample as $value){
                $role = DB::table('role')->where('role_id',$value->role_id)->value('role');
                $sal = json_decode($value->salary,true);
                $salary = $sal['emp_salary']['salary'];
                $basic = $sal['emp_salary']['basic'];
                $local_address = str_replace(',', ' ', $value->local_address);
                $permanent_address = str_replace(',', ' ', $value->permanent_address);

                $export_data.=$value->id.','.$value->genesis_ledger_id.','.$value->genesis_id.','.$value->biometric_id.','.$value->branch_location_id.','.$value->title.','.$value->first_name.','.$value->middle_name.','.$value->last_name.','.$value->blood_group.','.$value->email.','.$value->dob.','.$value->mobile.','.$value->gender.','.$value->category.','.$value->marital_status.','.$value->adhaar_number.','.$value->pan_number.','.$local_address.','.$permanent_address.','.$value->photo.','.$value->emergency_call_number.','.$value->emergency_call_person.','.$value->department_name.','.$value->designation.','.$value->doj.','.$value->status.','.$value->esic_number.','.$value->epf_number.','.$value->lin_number.','.$value->uan_number.','.$value->acc_holder_name.','.$value->acc_no.','.$value->ifsc_code.','.$value->bank_name.','.$value->branch.','.$salary.','.$basic.','.$value->epf_option.','.$value->esic_option.','.$role."\n";
            }
            return response($export_data)
                ->header('Content-Type','application/csv')               
                ->header('Content-Disposition', 'attachment; filename="employee_download.csv"')
                ->header('Pragma','no-cache')
                ->header('Expires','0');                     
        }
        return view('upload-emp',['record_found' =>$tot_record_found]);    
    }

    /*public function tds_sample(Request $request){ 
       $export_data="emp_id,month,tds_amt";
        return response($export_data)
                ->header('Content-Type','application/csv')               
                ->header('Content-Disposition', 'attachment; filename="tds_sample.csv"')
                ->header('Pragma','no-cache')
                ->header('Expires','0');    
    }

    public function get_monthly_sample(Request $request){ 
        $zone=$request->zone;
        if($zone=='' || $zone==NULL)
        {
            return Redirect::to('upload-attendance')->with('alert-danger','Please select Zone first to download sample file');
        }
        $emp_sample=DB::table('emp')->select('id','biometric_id','branch_location_id')->where('branch_location_id','=',$zone)->get();
        $tot_record_found=0;
        if(count($emp_sample)>0){
            $tot_record_found=1;
            //First Methos          
            $export_data="emp_id,biometric_id,total_present\n";
            foreach($emp_sample as $value){
               $export_data.=$value->id.','.$value->biometric_id."\n";
            }
            return response($export_data)
                ->header('Content-Type','application/csv')               
                ->header('Content-Disposition', 'attachment; filename="download.csv"')
                ->header('Pragma','no-cache')
                ->header('Expires','0');                     
        }
        return view('upload-attendance',['record_found' =>$tot_record_found]);    
    }*/

    public function tds_sample(Request $request){ 
        
        $zone=$request->zone;
        if($zone=='' || $zone==NULL)
        {
            return Redirect::to('upload-tds')->with('alert-danger','Please select Zone first to download sample file');
        }
        $countries=DB::table('emp')->select('id','biometric_id','branch_location_id','first_name','middle_name','last_name')->where('status','active')->where('branch_location_id','=',$zone)->get();
        $tot_record_found=0;
        if(count($countries)>0){
            $tot_record_found=1;
            //First Methos          
            $export_data="emp_id,month,tds_amt,name \n";
            foreach($countries as $value){
                $name=$value->first_name.' '.$value->middle_name.' '.$value->last_name;
               $export_data.=$value->id.','.' '.','.' '.','. $name."\n";
            }
            return response($export_data)
                ->header('Content-Type','application/csv')               
                ->header('Content-Disposition', 'attachment; filename="tds_sample.csv"')
                ->header('Pragma','no-cache')
                ->header('Expires','0');                     
        }
        return view('upload-tds',['record_found' =>$tot_record_found]);     
    }

    public function get_monthly_sample(Request $request){ 
        $zone=$request->zone;
        if($zone=='' || $zone==NULL)
        {
            return Redirect::to('upload-attendance')->with('alert-danger','Please select Zone first to download sample file');
        }
        $countries=DB::table('emp')->select('id','biometric_id','branch_location_id','first_name','middle_name','last_name')->where('status','active')->where('branch_location_id','=',$zone)->get();
        $tot_record_found=0;
        if(count($countries)>0){
            $tot_record_found=1;
            //First Methos          
            $export_data="emp_id,biometric_id,total_present,name \n";
            foreach($countries as $value){
                $name=$value->first_name.' '.$value->middle_name.' '.$value->last_name;
               $export_data.=$value->id.','.$value->biometric_id.','.' '.','. $name."\n";
            }
            return response($export_data)
                ->header('Content-Type','application/csv')               
                ->header('Content-Disposition', 'attachment; filename="monthly_report_sample.csv"')
                ->header('Pragma','no-cache')
                ->header('Expires','0');                     
        }
        return view('upload-attendance',['record_found' =>$tot_record_found]);    
    }

    public function index(Request $request)
    {
        try
        {
            if(Session::get('username')!='')
            {
                activity()->log('Loading Upload Attendance Page');
                $branch=DB::table('branch')->get();
                return view('upload-attendance',compact('branch'));
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

    public function upload_monthly_attendance(Request $request){

        if(Session::get('username')!='')
        {
        try
        {

        
        if($request->hasFile('csvfile')){ 
            
            $extension = strtolower($request->file('csvfile')->getClientOriginalExtension()); 
            if($extension!='csv')
            { 
                return redirect()->back()->with('alert-danger','Please Upload csv file only');
            }
             
            $target_dir = '/daily_report/';
            $filename = strtolower($request->file('csvfile')->getClientOriginalName()); 
            $extension = strtolower($request->file('csvfile')->getClientOriginalExtension());
            $target_file = $target_dir.$filename;
            $month=$request->input('month');
            $year=$request->input('year');
            $csvfile = $request->file('csvfile');           

                $add_to_history = DB::table('upload_history')->insert(['user_email'=>session('useremail'),'upload_type'=>'TDS','file_location'=>$target_file,'file_upload_status'=>'In Progress','process_status'=>'In Progress','start_time'=>Carbon::now()]);
                $history_id = DB::table('upload_history')->max('id');
                
                $data = array(
                'history_id'=> $history_id,
                'month'=> $month,
                'year'=>$year
                );

                /*Session::put('upload_tds','pending');*/
                UploadMonthlyAttendance::dispatch($data,$csvfile);
               if(Session::has('alert-success'))
                {
                    return session('alert-success');
                    /*return redirect()->back()->with('alert-success',Session::get('alert-success'));*/
                }
                elseif(Session::has('alert-danger'))
                {
                    return session('alert-danger');
                    /*return redirect()->back()->with('alert-info',Session::get('alert-info'));*/
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


    public function incentive_index(Request $request)
    {
        try
        {
            if(Session::get('username')!='')
            {
                activity()->log('Loading Upload Incentive Page');
                $branch=DB::table('branch')->get();
                return view('upload-incentive',compact('branch'));
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

    public function upload_incentive_csv(Request $request){
        
        if(Session::get('username')!='')
        {
        try
        {

        $msg=''; 
        $i=0;
        
        $inserted=0;
        $errormsg='';
        if($request->hasFile('csvfile') && $request->input('month')!=''){ 
            $target_dir = '/incentive_upload/';
            $filename = strtolower($request->file('csvfile')->getClientOriginalName()); 
            $extension = strtolower($request->file('csvfile')->getClientOriginalExtension());
            $target_file = $target_dir.$filename;
            $extension = strtolower($request->file('csvfile')->getClientOriginalExtension()); 
            if($extension!='csv')
            { 
                return 'Please Upload csv file only';
                /*return redirect()->back()->with('alert-info','Please Upload csv file only');*/
            }
            $csvfile = $request->file('csvfile');           

                $add_to_history = DB::table('upload_history')->insert(['user_email'=>session('useremail'),'upload_type'=>'Incentive','file_location'=>$target_file,'file_upload_status'=>'In Progress','process_status'=>'In Progress','start_time'=>Carbon::now()]);
                $history_id = DB::table('upload_history')->max('id');
                
                $data = array(
                'history_id'=> $history_id,
                'month'=> $request->month,
                );
                /*Session::put('upload_tds','pending');*/
                UploadIncentive::dispatch($data,$csvfile);
               if(Session::has('alert-success'))
                {
                    return session('alert-success');
                    /*return redirect()->back()->with('alert-success',Session::get('alert-success'));*/
                }
                elseif(Session::has('alert-danger'))
                {
                    return session('alert-danger');
                    /*return redirect()->back()->with('alert-info',Session::get('alert-info'));*/
                }
        }
        else
        {
            return redirect()->back()->with('alert-info','All fields are required.');
        } 

    }
    catch(QueryException $e)
    {
        activity()->log($e->getMessage());
        return redirect()->back()->with('alert-info','Database Query Error! ['.$e->getMessage().' ]');
    }
    catch(Exception $e)
    {
        activity()->log($e->getMessage());
        return redirect()->back()->with('alert-info',$e->getMessage());
    }
    }
    else
        return redirect('/')->with('status',"Please login First");
    }

    public function incentive_sample(Request $request){ 
        
        $zone=$request->zone;
        if($zone=='' || $zone==NULL)
        {
            return Redirect::to('upload-incentive')->with('alert-danger','Please select Zone first to download sample file');
        }
        $countries=DB::table('emp')->select('id','genesis_ledger_id','branch_location_id','first_name','middle_name','last_name')->where('status','active')->where('branch_location_id','=',$zone)->get();
        $tot_record_found=0;
        if(count($countries)>0){
            $tot_record_found=1;
            //First Methos          
            $export_data="emp_id,genesis_ledger_id,incentive_amt,name \n";
            foreach($countries as $value){
                $name=$value->first_name.' '.$value->middle_name.' '.$value->last_name;
               $export_data.=$value->id.','.$value->genesis_ledger_id.','.' '.','. $name."\n";
            }
            return response($export_data)
                ->header('Content-Type','application/csv')               
                ->header('Content-Disposition', 'attachment; filename="incentive_sample.csv"')
                ->header('Pragma','no-cache')
                ->header('Expires','0');                     
        }
        return view('upload-incentive',['record_found' =>$tot_record_found]);     
    }

    public function ex_gratia_index(Request $request)
    {
        try
        {
            if(Session::get('username')!='')
            {
                activity()->log('Loading Upload Incentive Page');
                $branch=DB::table('branch')->get();
                return view('upload-ex_gratia',compact('branch'));
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

    public function upload_ex_gratia_csv(Request $request){
        
        if(Session::get('username')!='')
        {
        try
        {

        $msg=''; 
        $i=0;
        
        $inserted=0;
        $errormsg='';
        if($request->hasFile('csvfile') && $request->input('month')!=''){ 
            $target_dir = '/ex_gratia_upload/';
            $filename = strtolower($request->file('csvfile')->getClientOriginalName()); 
            $extension = strtolower($request->file('csvfile')->getClientOriginalExtension());
            $target_file = $target_dir.$filename;
            $extension = strtolower($request->file('csvfile')->getClientOriginalExtension()); 
            if($extension!='csv')
            { 
                return 'Please Upload csv file only';
                /*return redirect()->back()->with('alert-info','Please Upload csv file only');*/
            }
            $csvfile = $request->file('csvfile');           

                $add_to_history = DB::table('upload_history')->insert(['user_email'=>session('useremail'),'upload_type'=>'Incentive','file_location'=>$target_file,'file_upload_status'=>'In Progress','process_status'=>'In Progress','start_time'=>Carbon::now()]);
                $history_id = DB::table('upload_history')->max('id');
                
                $data = array(
                'history_id'=> $history_id,
                'month'=> $request->month,
                );
                /*Session::put('upload_tds','pending');*/
                UploadExgratia::dispatch($data,$csvfile);
               if(Session::has('alert-success'))
                {
                    return session('alert-success');
                    /*return redirect()->back()->with('alert-success',Session::get('alert-success'));*/
                }
                elseif(Session::has('alert-danger'))
                {
                    return session('alert-danger');
                    /*return redirect()->back()->with('alert-info',Session::get('alert-info'));*/
                }
        }
        else
        {
            return redirect()->back()->with('alert-info','All fields are required.');
        } 

    }
    catch(QueryException $e)
    {
        activity()->log($e->getMessage());
        return redirect()->back()->with('alert-info','Database Query Error! ['.$e->getMessage().' ]');
    }
    catch(Exception $e)
    {
        activity()->log($e->getMessage());
        return redirect()->back()->with('alert-info',$e->getMessage());
    }
    }
    else
        return redirect('/')->with('status',"Please login First");
    }

    public function ex_gratia_sample(Request $request){ 
        
        $zone=$request->zone;
        if($zone=='' || $zone==NULL)
        {
            return Redirect::to('upload-ex_gratia')->with('alert-danger','Please select Zone first to download sample file');
        }
        $countries=DB::table('emp')->select('id','genesis_ledger_id','branch_location_id','first_name','middle_name','last_name')->where('status','active')->where('branch_location_id','=',$zone)->get();
        $tot_record_found=0;
        if(count($countries)>0){
            $tot_record_found=1;
            //First Methos          
            $export_data="emp_id,genesis_ledger_id,ex_gratia_amt,name \n";
            foreach($countries as $value){
                $name=$value->first_name.' '.$value->middle_name.' '.$value->last_name;
               $export_data.=$value->id.','.$value->genesis_ledger_id.','.' '.','. $name."\n";
            }
            return response($export_data)
                ->header('Content-Type','application/csv')               
                ->header('Content-Disposition', 'attachment; filename="ex_gratia_sample.csv"')
                ->header('Pragma','no-cache')
                ->header('Expires','0');                     
        }
        return view('upload-ex_gratia',['record_found' =>$tot_record_found]);     
    }


    public function arrear_index(Request $request)
    {
        try
        {
            if(Session::get('username')!='')
            {
                activity()->log('Loading Upload Incentive Page');
                $branch=DB::table('branch')->get();
                return view('upload-arrear',compact('branch'));
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

    public function upload_arrear_csv(Request $request){
        
        if(Session::get('username')!='')
        {
        try
        {

        $msg=''; 
        $i=0;
        
        $inserted=0;
        $errormsg='';
        if($request->hasFile('csvfile') && $request->input('month')!=''){ 
            $target_dir = '/arrear_upload/';
            $filename = strtolower($request->file('csvfile')->getClientOriginalName()); 
            $extension = strtolower($request->file('csvfile')->getClientOriginalExtension());
            $target_file = $target_dir.$filename;
            $extension = strtolower($request->file('csvfile')->getClientOriginalExtension()); 
            if($extension!='csv')
            { 
                return 'Please Upload csv file only';
                /*return redirect()->back()->with('alert-info','Please Upload csv file only');*/
            }
            $csvfile = $request->file('csvfile');           

                $add_to_history = DB::table('upload_history')->insert(['user_email'=>session('useremail'),'upload_type'=>'Incentive','file_location'=>$target_file,'file_upload_status'=>'In Progress','process_status'=>'In Progress','start_time'=>Carbon::now()]);
                $history_id = DB::table('upload_history')->max('id');
                
                $data = array(
                'history_id'=> $history_id,
                'month'=> $request->month,
                );
                /*Session::put('upload_tds','pending');*/
                UploadArrear::dispatch($data,$csvfile);
               if(Session::has('alert-success'))
                {
                    return session('alert-success');
                    /*return redirect()->back()->with('alert-success',Session::get('alert-success'));*/
                }
                elseif(Session::has('alert-danger'))
                {
                    return session('alert-danger');
                    /*return redirect()->back()->with('alert-info',Session::get('alert-info'));*/
                }
        }
        else
        {
            return redirect()->back()->with('alert-info','All fields are required.');
        } 

    }
    catch(QueryException $e)
    {
        activity()->log($e->getMessage());
        return redirect()->back()->with('alert-info','Database Query Error! ['.$e->getMessage().' ]');
    }
    catch(Exception $e)
    {
        activity()->log($e->getMessage());
        return redirect()->back()->with('alert-info',$e->getMessage());
    }
    }
    else
        return redirect('/')->with('status',"Please login First");
    }

    public function arrear_sample(Request $request){ 
        
        $zone=$request->zone;
        if($zone=='' || $zone==NULL)
        {
            return Redirect::to('upload-arrear')->with('alert-danger','Please select Zone first to download sample file');
        }
        $countries=DB::table('emp')->select('id','genesis_ledger_id','branch_location_id','first_name','middle_name','last_name')->where('status','active')->where('branch_location_id','=',$zone)->get();
        $tot_record_found=0;
        if(count($countries)>0){
            $tot_record_found=1;
            //First Methos          
            $export_data="emp_id,genesis_ledger_id,arrear_amt,name \n";
            foreach($countries as $value){
                $name=$value->first_name.' '.$value->middle_name.' '.$value->last_name;
               $export_data.=$value->id.','.$value->genesis_ledger_id.','.' '.','. $name."\n";
            }
            return response($export_data)
                ->header('Content-Type','application/csv')               
                ->header('Content-Disposition', 'attachment; filename="arrear_sample.csv"')
                ->header('Pragma','no-cache')
                ->header('Expires','0');                     
        }
        return view('upload-arrear',['record_found' =>$tot_record_found]);     
    }


    public function bonus_index(Request $request){
         try
        {
        if(Session::get('username')!='')
        {
       
            $branch=DB::table('branch')->get();
            return view('upload-bonus',compact('branch'));
      
        }
        else
        {
            return redirect()->back()->with('alert-info','Request data does not have any files to import.');
        } 

     }
    catch(QueryException $e)
    {
        activity()->log($e->getMessage());
        return redirect()->back()->with('alert-info','Database Query Error! ['.$e->getMessage().' ]');
    }
    catch(Exception $e)
    {
        activity()->log($e->getMessage());
        return redirect()->back()->with('alert-info',$e->getMessage());
    }
}

public function upload_bonus_csv(Request $request)
{
        
        if(Session::get('username')!='')
        {
        try
        {
        // $msg=''; 
        // $i=0;
        // $inserted=0;
        // $errormsg='';
        if($request->hasFile('csvfile')){ 
            $target_dir = '/bonus_upload/';
            $filename = strtolower($request->file('csvfile')->getClientOriginalName()); 
            $extension = strtolower($request->file('csvfile')->getClientOriginalExtension());
            $target_file = $target_dir.$filename;
            $extension = strtolower($request->file('csvfile')->getClientOriginalExtension()); 
            if($extension!='csv')
            { 
                return 'Please Upload csv file only';
                /*return redirect()->back()->with('alert-info','Please Upload csv file only');*/
            }
            $csvfile = $request->file('csvfile');           

                $add_to_history = DB::table('upload_history')->insert(['user_email'=>session('useremail'),'upload_type'=>'BONUS','file_location'=>$target_file,'file_upload_status'=>'In Progress','process_status'=>'In Progress','start_time'=>Carbon::now()]);
                $history_id = DB::table('upload_history')->max('id');
                
                $data = array(
                'history_id'=> $history_id,
                'month'=>$request->input('month')
                );

                /*Session::put('upload_tds','pending');*/
                UploadBonus::dispatch($data,$csvfile);
               if(Session::has('alert-success'))
                {
                    return session('alert-success');
                    /*return redirect()->back()->with('alert-success',Session::get('alert-success'));*/
                }
                elseif(Session::has('alert-danger'))
                {
                    return session('alert-danger');
                    /*return redirect()->back()->with('alert-info',Session::get('alert-info'));*/
                }
        }
        else
        {
            return redirect()->back()->with('alert-info','Request data does not have any files to import.');
        } 

    }
    catch(QueryException $e)
    {
        activity()->log($e->getMessage());
        return redirect()->back()->with('alert-info','Database Query Error! ['.$e->getMessage().' ]');
    }
    catch(Exception $e)
    {
        activity()->log($e->getMessage());
        return redirect()->back()->with('alert-info',$e->getMessage());
    }
    }
    else
        {
        return redirect('/')->with('status',"Please login First");
        }
    }

    public function bonus_sample(Request $request){ 
        
        $zone=$request->zone;
        if($zone=='' || $zone==NULL)
        {
            return Redirect::to('upload-bonus')->with('alert-danger','Please select Zone first to download sample file');
        }
        $countries=DB::table('emp')->select('id','genesis_ledger_id','branch_location_id','first_name','middle_name','last_name')->where('status','active')->where('branch_location_id','=',$zone)->get();
        $tot_record_found=0;
        if(count($countries)>0){
            $tot_record_found=1;
            //First Methos          
            $export_data="emp_id,genesis_ledger_id,bonus_amt,name \n";
            foreach($countries as $value){
               $name=$value->first_name.' '.$value->middle_name.' '.$value->last_name;
               $export_data.=$value->id.','.$value->genesis_ledger_id.','.'  '.','. $name."\n";
            }
            return response($export_data)
                ->header('Content-Type','application/csv')               
                ->header('Content-Disposition', 'attachment; filename="bonus_sample.csv"')
                ->header('Pragma','no-cache')
                ->header('Expires','0');                     
        }
        return view('upload-bonus',['record_found' =>$tot_record_found]);     
    }
}
