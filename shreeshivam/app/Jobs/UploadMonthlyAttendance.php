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

class UploadMonthlyAttendance implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $data;
    protected $file_upload;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
         $this->data = $data;
         $this->file_upload = false;
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
            $month = $this->data['month'];
            $year = $this->data['year'];
            $history_id = $this->data['history_id'];
            $target_dir = '/daily_report_upload/';
            $filename = strtolower($file->getClientOriginalName()); 
            $extension = strtolower($file->getClientOriginalExtension());
            $target_file = $target_dir.$filename;
                  

           /* $check = DB::table('daily_report')->where('date',$date)->count();
            if($check>0)
            {
                activity()->log('Attendance Upload Process Failed! Attendance Already Exist');
                $history = DB::table('upload_history')->where('id',$history_id)->update(['file_location'=>$target_file,'file_upload_status'=>'Failed','process_status'=>'Failed']);
                $notification = DB::table('notification')->insert(['user_id'=>session('user_id'),'notification'=>'Attendance Upload Process','notification_status'=>'Failed! Attendance Already Exist','status'=>'active','link'=>'history']);
                return redirect()->back()->with('alert-danger','Attendance Not Inserted!');
            }*/

            if($request->file('csvfile')->move(base_path().'/daily_report_upload/', $filename))
            {
                activity()->log('File with name '.$filename.' uploaded to server in location '.base_path().'\\daily_report\\');
                $this->file_upload = true;
                $update_history = DB::table('upload_history')->where('id',$history_id)->update(['file_location'=>$target_file,'file_upload_status'=>'Successful']);
                Schema::create('temp_daily_report', function (Blueprint $table) {
                    $table->increments('temp_daily_report_id');
                    $table->string('emp_id');
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
                $i=0;
                
                $inserted=0;
                $errormsg='';
                $datearray=array();
                $datearray1=array();
                $datearraywithholiday=array();
                $datesunarray=array();
                $fulldatearray=array();
                $holidayarray=array();
                $empid='';
                $date='';
                $no=1;$notinserted=0;
                $msg='data not inserted';
                $insert=0;
                $remaining=0;
                $inin='09:00:00';
                $inout='19:00:00';
                $remainingfloor=0;
                $remainingdec=0;
                
                $timestamp = strtotime($year.'-'.$month.'-01');
                $day = date('D', $timestamp);
                $k=0;
                $total_days=cal_days_in_month(CAL_GREGORIAN,$month,$year);
                //return $first_sunday = date('d', strtotime('First Sunday Of '.$month.' '.$year));
                for($a=1;$a<=$total_days;$a++)
                {  
                     if($no<10)
                     {
                         $no='0'.$no;
                     }
                     else{
                         $no=$no;
                     }
                     $date=$year.'-'.$month.'-'.$no;
                     if(date('N',strtotime($year.'-'.$month.'-'.$a))!=7)
                     {
                     $datearray[$a]=$date;
                     $datearraywithholiday[]=$date;
                     }
                     
                     if(date('N',strtotime($year.'-'.$month.'-'.$a))==7)
                     {
                     $datesunarray[$a]=$date;

                     }
                     $fulldatearray[$a]=$date;
                     $no=$a+1;
                }
                $abc=array();
                $holidays = DB::table('holidays')->whereMonth('date',$month)->select('date')->get();
                foreach($holidays as $key=>$h)
                {
                    $holidayarray[]=$h->date;
                }

                foreach($datearraywithholiday as $key => $value) {
                    if(!in_array($value,$holidayarray)) {
                        $datearray1[]=$value;
                    }
                
                }
                $datearray=array_diff($datearray,$holidayarray);  

                $header = NULL;
                $delimiter=',';
                $data = array();
                if (($handle = fopen(base_path().$target_file, 'r')) !== FALSE)
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
                
                if(!in_array('emp_id', $header) || !in_array('total_present', $header))
                {
                    Schema::drop('temp_daily_report');
                    return redirect()->back()->with('alert-danger','headings should be proper. Please download sample file for reference');
                }

                foreach ($data as $value) {


                    trim($value['total_present']);
                    trim($value['emp_id']);
                    

                    $inin='09:00:00';
                    $inout='19:00:00';
                    $total_working_hr='10:00:00';
                    $attendance = array();
                    $attendance['IN-1'] = $inin;
                    $attendance['OUT-1'] = $inout;
                    $myattendance = json_encode(array('report'=>$attendance));
                    $i=0;
                    $b=0;
                    $remainingfloor=0;
                    $remainingdec=0;
                    $remaining=0;
                    $size=sizeof($datearray);
                    $sundaysize=sizeof($datesunarray);
                    $holidaysize=sizeof($holidayarray);
                    $count=DB::table('daily_report')->where('emp_id',$value['emp_id'])->wheremonth('date',$month)->count();
                    if($total_days<$value['total_present'])
                    {
                        $history = DB::table('upload_history')->where('id',$history_id)->update(['file_location'=>$target_file,'process_status'=>'Failed']);
                        return redirect()->back()->with('alert-danger','Attendance cannot be greater than total days of month for emp_id '.$value['emp_id']);
                    }
                    if($size<($count+ceil($value['total_present'])))
                            {
                                
                                $i= $size-$count;
                                $remainingfloor=$value['total_present']-$i;
                                $remaining=floor($remainingfloor);
                                $remainingdec=$remaining-$remainingfloor;
                                
                                //$msg.=' attendance for employee'.$value['emp_id'].' for '.$remaining.' days not inserted! total present days exceeds total working days\n ';
                            }
                            else
                            {
                                $i=floor($value['total_present']);                               
                                $remainingdec=$i-$value['total_present'];
                            }
                        
                    if($remaining!=0)
                    {                        
                        if(($remaining+$size)<=$total_days)
                        {                            
                            $datearr=array_merge($datearray1,$datesunarray);
                            $datearray1=array_merge($datearr,$holidayarray);
                            $i=$i+$remaining;
                        }                        
                        else
                        {                            
                            $datearr=array_merge($datearray1,$datesunarray);
                            $datearray1=array_merge($datearr,$holidayarray);
                            $over=($remaining+$size)-$total_days;
                            $i=($remaining+$size)-$over;
                            $remaining=($remaining+$size)-$total_days;

                            $msg.=' attendance for employee'.$value['emp_id'].' for '.$remaining.' days not inserted! total present days exceeds total working days\n ';
                        }
                    }

                    if($count==0)
                    {                     
                        while($b<$i)
                        {                        
                            $insertrow = array('user_id'=>Session::get('user_id'),'attendance'=>$myattendance,'emp_id' => $value['emp_id'],'date' =>$datearray1[$b],'initial_in'=>$inin,'final_out'=>$inout,'total_working_hour'=>$total_working_hr);
                            $daily_report = DB::table('temp_daily_report')->insert($insertrow);
                            $b++;
                            // if($b==$i-1)
                            // {
                                
                            // }
                            if(!$daily_report)
                            {
                                $notinserted++;
                            }
                            
                        }
                        if($remainingdec!=0)
                                {

                                    $inin='09:00:00';
                                    $inout='14:00:00';
                                    $total_working_hr='05:00:00';
                                    $attendance['IN-1'] = $inin;
                                    $attendance['OUT-1'] = $inout;
                                    $myattendance = json_encode(array('report'=>$attendance));
                                    $insertrow = array('user_id'=>Session::get('user_id'),'attendance'=>$myattendance,'emp_id' => $value['emp_id'],'date' =>$datearray1[$b],'initial_in'=>$inin,'final_out'=>$inout,'total_working_hour'=>$total_working_hr);
                                    $daily_report = DB::table('temp_daily_report')->insert($insertrow);
                                    $b++;
                                    if(!$daily_report)
                                    {
                                        $notinserted++;
                                    }
                                }
                    }

                    else
                    {
                        activity()->log('Attendance Upload Process Failed! Attendance Already Exist');
                        $history = DB::table('upload_history')->where('id',$history_id)->update(['file_location'=>$target_file,'process_status'=>'Failed']);
                        $notification = DB::table('notification')->insert(['user_id'=>session('user_id'),'notification'=>'Attendance Upload Process','notification_status'=>'Failed! Attendance Already Exist','status'=>'active','link'=>'history']);
                        return redirect()->back()->with('alert-danger','Attendance Already Exist for emp id '.$value['emp_id']);
                    }
                }
                
                if($notinserted==0)
                {
                    // if($remaining==0)
                    // {
                        activity()->log('Attendance Uploaded to temporary Table Successfully');
                    
                        $attendance_record = (array) json_decode(DB::table('temp_daily_report')->select('emp_id','date','user_id','initial_in','final_out','total_working_hour','shift','attendance')->get(),true);
                        
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
                    return redirect()->back()->with('alert-danger',"Data Not Inserted");
                }
            }
            else
            {
                $history = DB::table('upload_history')->where('id',$history_id)->update(['file_location'=>$target_file,'file_upload_status'=>'Failed','process_status'=>'Failed']);
                activity()->log('csv File not uploaded!');
                $notification = DB::table('notification')->insert(['user_id'=>session('user_id'),'notification'=>'Attendance Upload Process','notification_status'=>'Failed','status'=>'active','link'=>'history']);
                /*Schema::drop('temp_daily_report');*/
                activity()->log('File with name '.$filename.' not uploaded to server. Process Failed!');
                return redirect()->back()->with('alert-danger','File Not Uploaded To server. Process Failed!');
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

}
