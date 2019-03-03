<?php
namespace App\Jobs;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\get;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Exception;
/*use App\Exception;*/
use \Illuminate\Database\QueryException;
ini_set('max_execution_time', 10000);
date_default_timezone_set('Asia/Kolkata');

class UploadAttendance implements ShouldQueue
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
            activity()->log('Attendance Upload process Queued to Job List');
            $file = $request->file('csvfile');
            $date = $this->data['attendance_date'];
            $history_id = $this->data['history_id'];
            $target_dir = 'daily_report_upload/';
            $filename = strtolower($file->getClientOriginalName()); 
            $extension = strtolower($file->getClientOriginalExtension());
            $target_file = $target_dir.$filename;
            $check = DB::table('daily_report')->where('date',$date)->count();
            if($check>0)
            {
                activity()->log('Attendance Upload Process Failed! Attendance Already Exist');
                $history = DB::table('upload_history')->where('id',$history_id)->update(['file_location'=>$target_file,'file_upload_status'=>'Failed','process_status'=>'Failed']);
                $notification = DB::table('notification')->insert(['user_id'=>session('user_id'),'notification'=>'Attendance Upload Process','notification_status'=>'Failed! Attendance Already Exist','status'=>'active','link'=>'history']);
                return redirect()->back()->with('alert-danger','Attendance Not Inserted!');
            }
            if($file->move(base_path().'/daily_report_upload/', $filename))
            {
                activity()->log('File with name '.$filename.' uploaded to server in location '.base_path().'/daily_report_upload/');
                 activity()->log('hellooo');
                $update_history = DB::table('upload_history')->where('id',$history_id)->update(['file_location'=>$target_file,'file_upload_status'=>'Successful']);

                $this->file_upload=true;

                Schema::create('temp_daily_report', function (Blueprint $table) {
                    $table->increments('temp_daily_report_id');
                    $table->string('emp_id');
                    $table->string('biometric_id');
                    $table->date('date');
                    $table->string('user_id');
                    $table->string('initial_in');
                    $table->string('final_out');
                    $table->string('total_working_hour');
                    $table->string('shift');
                    $table->text('attendance');
                    $table->timestamps();
                    $table->temporary();
                });

                $msg='';
                $final_out=''; 
                $initial_in='';
                $i=0;
                $inserted=0;
                $validations=''; $errormsg='';

                $header = NULL;
                $delimiter=',';
                $data = array();
                activity()->log('hello');
                if (($handle = fopen(base_path().'/'.$target_file, 'r')) !== FALSE)
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

                if(!in_array('E. Code') || !in_array('Shift') || !in_array('IN-1') || !in_array('OUT-1'))
                {
                    Schema::drop('temp_daily_report');
                    return redirect()->back()->with('alert-danger','headings should be proper. Please download sample file for reference');
                }
                
                foreach ($data as $value) {
                    $i++;
                    $working_hour = '';
                    $initial_in = '';
                    $final_out = '';
                    $time =0;
                    $hours = 0;
                    $minutes = 0;
                    $seconds=0;
                    $total_working_hour=0; 
                    $k=1;
                    $attendance = array();
                    while(true)
                    {   
                        trim($value['IN-'.$k]);
                        trim($value['OUT-'.$k]);
                        trim($value['Shift']);
                        trim($value['E. Code']);

                        if(array_key_exists('IN-'.$k,$value))
                        {   
                            if($value['IN-'.$k]!='')
                            {
                                if($k==1)
                                {
                                    $exp = explode(':',$value['IN-'.$k]);
                                    $hour = $exp[0];
                                    $min = $exp[1];
                                    $initial_in =$hour.':'.$min.':00';

                                }
                                $attendance['IN-'.$k] = $value['IN-'.$k];
                                if($value['OUT-'.$k])
                                { 
                                    $attendance['OUT-'.$k] = $value['OUT-'.$k];
                                    $interval = strtotime($value['OUT-'.$k]) - strtotime($value['IN-'.$k]);
                                    $total_working_hour+= $interval;
                                }
                                $k++;
                            }
                            else
                            {
                                $j=$k-1;
                                if($j>0 && $value['OUT-'.$j]!='')
                                {
                                    $exp = explode(':',$value['OUT-'.$j]);
                                    $hour = $exp[0];
                                    $min = $exp[1];
                                    $final_out =$hour.':'.$min.':00';
                                }
                                break;
                            }
                        }
                        else
                        { 
                            $j=$k-1;
                            if($j>0 && $value['OUT-'.$j]!='')
                            {
                                $exp = explode(':',$value['OUT-'.$j]);
                                $hour = $exp[0];
                                $min = $exp[1];
                                $final_out =$hour.':'.$min.':00';
                            }
                            break;
                        }
                    }
                                $seconds = $total_working_hour % 60;
                                $minutes = floor(($total_working_hour % 3600) / 60);
                                $hours = floor($total_working_hour / 3600);
                                $working_hour= $hours.":".$minutes.":".$seconds;

                    $myattendance = json_encode(array('report'=>$attendance));
                    $emp_id = DB::table('emp')->where('biometric_id',$value['E. Code'])->value('id');
                    $insertrow = array('emp_id'=>$emp_id,'biometric_id' => $value['E. Code'],'user_id' => Session::get('user_id'),'date' => $date,'initial_in' => $initial_in,'final_out' => $final_out,'total_working_hour' => $working_hour,'shift' => $value['Shift'],'attendance' => $myattendance);

                    $result = DB::table('temp_daily_report')->insert($insertrow);
                    
                    if(!$result)
                    {
                        $msg.="Row no : ".$i." cannot be inserted ";
                    }
                    else
                    {
                        $inserted++;
                    }                            
                }
                if($inserted==$i)
                {
                    activity()->log('Attendance Uploaded to temporary Table Successfully');
                    
                    $attendance_record = (array) json_decode(DB::table('temp_daily_report')->select('emp_id','biometric_id','date','user_id','initial_in','final_out','total_working_hour','shift','attendance')->get(),true);
                    
                    $insert = DB::table('daily_report')->insert($attendance_record);
                    
                    if($insert)
                    {
                        $history = DB::table('upload_history')->where('id',$history_id)->update(['process_status'=>'Successful']);

                        
                        activity()->log('Attendance Uploaded in Daily_report Table Successfully');

                        $notification = DB::table('notification')->insert(['user_id'=>session('user_id'),'notification'=>'Attendance Upload Process','notification_status'=>'Successful','status'=>'active','link'=>'history']);

                        Schema::drop('temp_daily_report');
                        return redirect()->back()->with('alert-success','Attendance inserted!');
                    }
                    else
                    {
                        $history = DB::table('upload_history')->where('id',$history_id)->update(['process_status'=>'Failed']);

                        
                        activity()->log('Attendance not uploaded in Daily_report Table');

                        $notification = DB::table('notification')->insert(['user_id'=>session('user_id'),'notification'=>'Attendance Upload Process','notification_status'=>'Failed','status'=>'active','link'=>'history']);

                        Schema::drop('temp_daily_report');
                        return redirect()->back()->with('alert-danger','Attendance Not Inserted!');
                    }
                }
                else
                {
                    $history = DB::table('upload_history')->where('id',$history_id)->update(['process_status'=>'Failed']);
                    
                    activity()->log('Attendance not Uploaded to Temporary Table');

                    $notification = DB::table('notification')->insert(['user_id'=>session('user_id'),'notification'=>'Attendance Upload Process','notification_status'=>'Failed','status'=>'active','link'=>'history']);

                    Schema::drop('temp_daily_report');
                    return redirect()->back()->with('alert-danger','Attendance Not Inserted!');
                }
            }
            else
            {
                
                $history = DB::table('upload_history')->where('id',$history_id)->update(['file_location'=>$target_file,'file_upload_status'=>'Failed','process_status'=>'Failed']);
                );
                activity()->log('csv File not uploaded!');
                $notification = DB::table('notification')->insert(['user_id'=>session('user_id'),'notification'=>'Attendance Upload Process','notification_status'=>'Failed','status'=>'active','link'=>'history']);
                Schema::drop('temp_daily_report');
                return redirect()->back()->with('alert-danger','File Upload Process Failed!');
            }
        }
        catch(QueryException $e)
        {
            if($this->file_upload==false)
                $upload = 'Failed';
            else
                $upload = 'Successful';

            $history = DB::table('upload_history')->where('id',$history_id)->update(['file_location'=>$target_file,'file_upload_status'=>$upload,'process_status'=>'Failed']);

            

            $notification = DB::table('notification')->insert(['user_id'=>session('user_id'),'notification'=>'Attendance Upload Process','notification_status'=>'Failed','status'=>'active','link'=>'history']);

            activity()->log('Attendance Upload Process failed '.$e->getMessage());
            return redirect()->back()->with('alert-danger',"Database Query Error! [".$e->getMessage()." ]");
        } 
        catch(Exception $e)
        {
            if($this->file_upload==false)
                $upload = 'Failed';
            else
                $upload = 'Successful';

            $history = DB::table('upload_history')->where('id',$history_id)->update(['file_location'=>$target_file,'file_upload_status'=>$upload,'process_status'=>'Failed']);

            

            $notification = DB::table('notification')->insert(['user_id'=>session('user_id'),'notification'=>'Attendance Upload Process','notification_status'=>'Failed','status'=>'active','link'=>'history']);

            activity()->log('Attendance Upload Process failed '.$e->getMessage());
            return redirect()->back()->with('alert-danger','Error '.$e->getMessage());
        } 
    }

    public function failed(Exception $exception)
    {
        if($this->file_upload==false)
        {
            activity()->log('Attendance Upload Process Failed! '.$e->getMessage());
            $history = DB::table('upload_history')->where('id',$history_id)->update(['file_location'=>$target_file,'file_upload_status'=>'Failed','process_status'=>'Failed']);
            $notification = DB::table('notification')->insert(['user_id'=>session('user_id'),'notification'=>'Attendance Upload Process','notification_status'=>'Failed','status'=>'active','link'=>'history']);
            return redirect()->back()->with('alert-danger','Attendance Upload Process Failed!');
        }
        else
        {
            activity()->log('Attendance Upload Process Failed! '.$e->getMessage());
            $history = DB::table('upload_history')->where('id',$history_id)->update(['process_status'=>'Failed']);
            $notification = DB::table('notification')->insert(['user_id'=>session('user_id'),'notification'=>'Attendance Upload Process','notification_status'=>'Failed','status'=>'active','link'=>'history']);
            return redirect()->back()->with('alert-danger','Attendance Upload Process Failed!');
        }
        
    }

    public function retryUntil()
    {
        $this->retry+=1;
        return now()->addSeconds(60);
        activity()->log('No Of retry in Attendance upload'.$this->retry);
         return redirect()->back()->with('alert-danger','retry '.$this->retry);
    }
}
