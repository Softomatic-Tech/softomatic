<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\emp;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Exception;
use \Illuminate\Database\QueryException;
ini_set('max_execution_time', 10000);
date_default_timezone_set('Asia/Kolkata');

class UploadEmployee implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $data;
    protected $file_upload;
    protected $retry;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
         $this->data = $data;
         $this->file_upload = false;
         $this->retry=0;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Request $request)
    {
       try
        {
            Schema::create('temp_emp', function (Blueprint $table) {
                    $table->integer('temp_emp_id');
                    $table->string('genesis_ledger_id');
                    $table->string('genesis_id');
                    $table->string('biometric_id');
                    $table->integer('branch_location_id');
                    $table->string('title');
                    $table->string('first_name');
                    $table->string('middle_name');
                    $table->string('last_name');
                    $table->string('blood_group')->nullable();
                    $table->string('email')->unique();
                    $table->datetime('dob');
                    $table->string('mobile');
                    $table->string('gender');
                    $table->string('category');
                    $table->string('marital_status');
                    $table->string('adhaar_number');
                    $table->string('pan_number');
                    $table->text('local_address');
                    $table->text('permanent_address');
                    $table->string('photo');
                    $table->string('emergency_call_number');
                    $table->string('emergency_call_person');
                    $table->string('department');
                    $table->string('designation');
                    $table->datetime('doj');
                    $table->string('status');
                    $table->string('esic_number');
                    $table->string('epf_number');
                    $table->string('lin_number');
                    $table->string('uan_number');
                    $table->string('acc_holder_name');
                    $table->string('acc_no');
                    $table->string('ifsc_code');
                    $table->string('bank_name');
                    $table->string('branch');
                    $table->integer('salary_id');
                    $table->string('esic_option');
                    $table->string('epf_option');
                    $table->timestamps();
                    $table->temporary();
                });

                Schema::create('temp_salary', function (Blueprint $table) {
                    $table->integer('temp_salary_id');
                    $table->integer('emp_id');
                    $table->text('salary');
                    $table->timestamps();
                    $table->temporary();
                });

                Schema::create('temp_user', function (Blueprint $table) {
                    $table->string('name');
                    $table->string('email');
                    $table->text('password');
                    $table->string('role_id');
                    $table->timestamps();
                    $table->temporary();
                });

            activity()->log('Employee Upload process Queued to Job List');
            $file = $request->file('csvfile');
            $history_id = $this->data['history_id'];
            $target_dir = '/employee_upload/';
            $filename = strtolower($file->getClientOriginalName()); 
            $extension = strtolower($file->getClientOriginalExtension());
            $target_file = $target_dir.$filename;
            if($file->move(base_path().'/employee_upload/', $filename))
            {
               $history = DB::table('upload_history')->where('id',$history_id)->update(['file_upload_status'=>'Successful']);
               activity()->log('File with name '.$filename.' uploaded to server in location '.base_path().'/employee_upload/');

                $msg=''; 
                $i=0;
                $inserted=0;
                $sal=0;
                $errormsg='';
                $max_emp_id = DB::table('emp')->max('id');
                $max_sal_id = DB::table('salary')->max('id');
                if($max_sal_id=='') $max_sal_id=0;
                if($max_emp_id=='') $max_emp_id=0;

                $header = NULL;
                $delimiter=',';
                $data = array();
                if(($handle = fopen(base_path().$target_file, 'r')) !== FALSE)
                {
                    while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
                    {
                        if(!$header)
                            $header = $row;
                        else
                            $data[] = array_combine($header, $row);
                    }
                    fclose($handle);
                }

                // if(!in_array('genesis_ledger_id') || !in_array('genesis_id') || !in_array('biometric_id') || !in_array('branch_location_id') || !in_array('title') || !in_array('first_name') || !in_array('middle_name') || !in_array('last_name') || !in_array('blood_group') || !in_array('email') || !in_array('dob') || !in_array('mobile') || !in_array('gender') || !in_array('category') || !in_array('marital_status') || !in_array('adhaar_number') || !in_array('pan_number') || !in_array('local_address') || !in_array('permanent_address') || !in_array('photo') || !in_array('emergency_call_number') || !in_array('emergency_call_person') || !in_array('department') || !in_array('designation') || !in_array('doj') || !in_array('status') || !in_array('esic_number') || !in_array('epf_number') || !in_array('lin_number') || !in_array('uan_number') || !in_array('acc_holder_name') || !in_array('acc_no') || !in_array('ifsc_code') || !in_array('bank_name') || !in_array('branch') || !in_array('salary') || !in_array('basic') || !in_array('epf_option') || !in_array('esic_option') || !in_array('role'))
                // {
                //     Schema::drop('temp_emp');
                //     Schema::drop('temp_user');
                //     Schema::drop('temp_salary');
                //     return redirect()->back()->with('alert-danger','headings should be proper. Please download sample file for reference');
                // }
                
                foreach ($data as $value){
                    $max_emp_id++;
                    $max_sal_id++;
                    $i++;
                
                $k=1;
                
                trim('genesis_ledger_id');
                trim('genesis_id');
                trim('biometric_id');
                trim('branch_location_id');
                trim('title');
                trim('first_name');
                trim('middle_name');
                trim('last_name');
                trim('blood_group');
                trim('email');
                trim('dob');
                trim('mobile');
                trim('gender');
                trim('category');
                trim('marital_status');
                trim('adhaar_number');
                trim('pan_number');
                trim('local_address');
                trim('permanent_address');
                trim('photo');
                trim('emergency_call_number');
                trim('emergency_call_person');
                trim('department');
                trim('designation');
                trim('doj');
                trim('status');
                trim('esic_number');
                trim('epf_number');
                trim('lin_number');
                trim('uan_number');
                trim('acc_holder_name');
                trim('acc_no');
                trim('ifsc_code');
                trim('bank_name');
                trim('branch');
                trim('salary');
                trim('basic');
                trim('epf_option');
                trim('esic_option');
                trim('role');
                
                if($value['department']=='')
                {
                  $msg.=' department not given in row no '.$i;
                }
                if($value['designation']=='')
                {
                  $msg.=' designation not given in row no '.$i;
                }
                if($value['first_name']=='')
                {
                  $msg.=' first_name not given in row no '.$i;
                }
                if($value['email']=='')
                {
                  $msg.=' email not given in row no '.$i;
                }
                /*if($value['mobile']=='')
                {
                  $msg.=' mobile not given in row no '.$i;
                }*/
                if($value['branch_location_id']=='')
                {
                  $msg.=' branch_location_id not given in row no '.$i;
                }
                if($value['salary']=='')
                {
                  $msg.=' salary not given in row no '.$i;
                }
                if($value['status']=='')
                {
                  $msg.=' status not given in row no '.$i;
                }

                if($msg!='')
                {
                  Schema::drop('temp_emp');
                  Schema::drop('temp_salary');
                  Schema::drop('temp_user');
                  $history = DB::table('upload_history')->where('id',$history_id)->update(['process_status'=>'Failed']);
                  activity()->log('Employee upload process failed because - '.$msg);
                  return redirect()->back()->with('alert-danger',$msg);
                }

                $department_id = DB::table('department')->select('id')->where('department_name',$value['department'])->value('id');
                $designation_id = DB::table('designation')->where('department',$department_id)->where('designation',$value['designation'])->value('id');

                if($department_id=='')
                {
                  Schema::drop('temp_emp');
                  Schema::drop('temp_salary');
                  Schema::drop('temp_user');
                  return redirect()->back()->with('alert-danger','Department Not found for row no '.$i);
                }
                if($designation_id=='')
                {
                  Schema::drop('temp_emp');
                  Schema::drop('temp_salary');
                  Schema::drop('temp_user');
                  return redirect()->back()->with('alert-danger','Designation Not found for row no '.$i);
                }
                $mobileregex = "/^\d+$/"; 
                if(!preg_match($mobileregex, $value['salary']))
                {
                  Schema::drop('temp_emp');
                  Schema::drop('temp_salary');
                  Schema::drop('temp_user');
                  return redirect()->back()->with('alert-danger','salary must be in digits in row no '.$i);
                }
                $insertrow = array('temp_emp_id'=>$max_emp_id,'genesis_ledger_id' =>$value['genesis_ledger_id'],'genesis_id' =>$value['genesis_id'],'biometric_id'=>$value['biometric_id'],'title'=>$value['title'],'first_name'=>$value['first_name'],'middle_name'=>$value['middle_name'],'last_name'=>$value['last_name'],'blood_group'=>$value['blood_group'],'email'=>$value['email'],'dob'=>$value['dob'],'mobile'=>$value['mobile'],'gender'=>$value['gender'],'category'=>$value['category'],'marital_status'=>$value['marital_status'],'adhaar_number'=>$value['adhaar_number'],'pan_number'=>$value['pan_number'],'local_address'=>$value['local_address'],'permanent_address'=>$value['permanent_address'],'emergency_call_number'=>$value['emergency_call_number'],'emergency_call_person'=>$value['emergency_call_person'],'department'=>$department_id,'designation'=>$designation_id,'doj'=>$value['doj'],'status'=>$value['status'],'esic_number'=>$value['esic_number'],'epf_number'=>$value['epf_number'],'lin_number'=>$value['lin_number'],'uan_number'=>$value['uan_number'],'acc_holder_name'=>$value['acc_holder_name'],'acc_no'=>$value['acc_no'],'ifsc_code'=>$value['ifsc_code'],'bank_name'=>$value['bank_name'],'branch'=>$value['branch'],'epf_option'=>$value['epf_option'],'esic_option'=>$value['esic_option'],'salary_id'=>$max_sal_id,'branch_location_id'=>$value['branch_location_id'],);

                $emp = DB::table('temp_emp')->insert($insertrow);
                
                $salary = array();
                $salary['salary'] = $value['salary'];
                $salary['basic'] =  $value['basic'];
                $salary = json_encode(array('emp_salary'=>$salary));
                if($emp)
                    {
                        $insertsalary = DB::table('temp_salary')->insert([
                            'temp_salary_id' => $max_sal_id,
                            'emp_id' =>  $max_emp_id,
                            'salary' => $salary
                        ]); 
                        /*if($insertsalary)
                        {
                            $update_sal_id = DB::table('temp_emp')->where('temp_emp_id',$max_emp_id)->update(['salary_id'=>$max_sal_id]);
                            if($update_sal_id)
                               $sal++;                       
                        }*/
                        /*else
                        {
                           activity()->log('Employee Salary not added');   
                        }*/            
                        $temp_user = DB::table('temp_user')->insert([
                            'name' => $value['first_name'],
                            'email' =>$value['email'],
                            'password' => bcrypt('1234'),
                            'role_id' => $value['role']
                        ]);
                        if(!$temp_user)
                        {
                             activity()->log('Employee with email '.$value['email'].' not added to users');   
                        }
                        if($temp_user && $insertsalary)
                        {
                          $inserted++;
                        }
                        else
                        {
                            if(!$temp_user)
                                $msg.='Login Details cannot be added for employee with email '.$value['email'];
                            if(!$insertsalary)
                                $msg.='Salary cannot be added for employee with email '.$value['email'];
                                activity()->log($msg);
                                Schema::drop('temp_emp');
                                Schema::drop('temp_salary');
                                Schema::drop('temp_user');
                                return redirect()->back()->with('alert-danger',$msg);
                        }
                        
                    }                       
                }
                if($inserted==$i)
                { 
                    activity()->log('Employee Uploaded to temporary Table Successfully');
                    $emp_record = (array) json_decode(DB::table('temp_emp')->select('genesis_ledger_id','genesis_id', 'biometric_id', 'branch_location_id', 'title', 'first_name', 'middle_name', 'last_name', 'email', 'dob', 'mobile', 'gender', 'category', 'marital_status' , 'adhaar_number', 'pan_number', 'local_address', 'permanent_address', 'photo', 'emergency_call_number', 'emergency_call_person', 'department', 'designation', 'doj', 'status', 'esic_number', 'epf_number', 'lin_number', 'uan_number', 'acc_holder_name', 'acc_no', 'ifsc_code', 'bank_name', 'branch', 'salary_id', 'esic_option', 'epf_option')->orderBy('temp_emp_id','asc')->get(),true);

                    $salary_record = (array) json_decode(DB::table('temp_salary')->select('emp_id','salary')->orderBy('temp_salary_id','asc')->get(),true);
                    $users_record = (array) json_decode(DB::table('temp_user')->select('name','email','password','role_id')->get(),true);

                    if(sizeof($emp_record)!=0 && sizeof($salary_record)!=0 && sizeof($users_record)!=0)
                    {
                        $insert = DB::table('emp')->insert($emp_record);
                        $insert_salary = DB::table('salary')->insert($salary_record);
                        $insert_user = DB::table('users')->insert($users_record);

                        if($insert && $insert_salary && $insert_user)
                        {
                            $history = DB::table('upload_history')->where('id',$history_id)->update(['process_status'=>'Successful']);
                            activity()->log('Employee Uploaded Successfully');
                            $notification = DB::table('notification')->insert(['user_id'=>session('user_id'),'notification'=>'Employee Upload Process','notification_status'=>'Successful','status'=>'active','link'=>'history']);

                            Schema::drop('temp_emp');
                            Schema::drop('temp_salary');
                            Schema::drop('temp_user');
                            return redirect()->back()->with('alert-success','Data inserted!');
                        }
                        else
                        {
                            $history = DB::table('upload_history')->where('id',$history_id)->update(['process_status'=>'Failed']);
                            activity()->log('Employee Uploaded Failed');
                            $notification = DB::table('notification')->insert(['user_id'=>session('user_id'),'notification'=>'Employee Upload Process','notification_status'=>'Failed','status'=>'active','link'=>'history']);

                            Schema::drop('temp_emp');
                            Schema::drop('temp_salary');
                            Schema::drop('temp_user');
                            return redirect()->back()->with('alert-danger','Data not inserted!');
                        }
                    }
                    else
                    {
                        $history = DB::table('upload_history')->where('id',$history_id)->update(['process_status'=>'Failed']);
                        activity()->log('Employee not Uploaded to Temporary Table');
                        $notification = DB::table('notification')->insert(['user_id'=>session('user_id'),'notification'=>'Employee Upload Process','notification_status'=>'Failed','status'=>'active','link'=>'history']);

                        Schema::drop('temp_emp');
                        Schema::drop('temp_salary');
                        Schema::drop('temp_user');
                        return redirect()->back()->with('alert-danger','data Not Inserted!');
                    }                 
                    
                    
                }
                else
                {
                    Schema::drop('temp_emp');
                    Schema::drop('temp_salary');
                    Schema::drop('temp_user');
                    $history = DB::table('upload_history')->where('id',$history_id)->update(['process_status'=>'Failed','file_upload_status'=>'Failed']);                    
                    activity()->log('Employee not Uploaded to Temporary Table');
                    $notification = DB::table('notification')->insert(['user_id'=>session('user_id'),'notification'=>'Employee Upload Process','notification_status'=>'Failed','status'=>'active','link'=>'history']);
                    return redirect()->back()->with('alert-danger','Data not inserted!');
                }
            }
        }
        catch(QueryException $e)
        {
            Schema::drop('temp_emp');
            Schema::drop('temp_salary');
            Schema::drop('temp_user');
            $history = DB::table('upload_history')->where('id',$history_id)->update(['process_status'=>'Failed']);            
            $notification = DB::table('notification')->insert(['user_id'=>session('user_id'),'notification'=>'Employee Upload Process','notification_status'=>'Failed','status'=>'active','link'=>'history']);
            activity()->log('Employee not Uploaded '.$e->getMessage());
            return redirect()->back()->with('alert-danger',"Database Query Error! [".$e->getMessage()." ]");
        } 
        catch(Exception $e)
        {
            Schema::drop('temp_emp');
            Schema::drop('temp_salary');
            Schema::drop('temp_user');
            $history = DB::table('upload_history')->where('id',$history_id)->update(['process_status'=>'Failed']);            
            $notification = DB::table('notification')->insert(['user_id'=>session('user_id'),'notification'=>'Employee Upload Process','notification_status'=>'Failed','status'=>'active','link'=>'history']);
            activity()->log('Employee not Uploaded '.$e->getMessage());
            return redirect()->back()->with('alert-danger',$e->getMessage());
        } 
    }

    public function retryUntil()
    {
        $this->retry+=1;
        return now()->addSeconds(60);
        activity()->log('No Of retry in employee upload'.$this->retry);
    }
}
