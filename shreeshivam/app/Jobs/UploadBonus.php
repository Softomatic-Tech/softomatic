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

class UploadBonus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
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
            activity()->log('Bonus Upload process Queued to Job List');
            $file = $request->file('csvfile');
            $history_id = $this->data['history_id'];
            $month = $this->data['month'];
            $target_dir = '/bonus_upload/';
            $filename = strtolower($file->getClientOriginalName()); 
            $extension = strtolower($file->getClientOriginalExtension());
            $target_file = $target_dir.$filename;
            if($file->move(base_path().'/bonus_upload/', $filename))
            {
                $history = DB::table('upload_history')->where('id',$history_id)->update(['file_upload_status'=>'Successful']);
                activity()->log('File with name '.$filename.' uploaded to server in location '.base_path().'/bonus_upload/');

                $msg=''; 
                $i=0;
                $inserted=0;
                $sal=0;
                $errormsg='';
               
                Schema::create('temp_bonus', function (Blueprint $table){
                    $table->integer('temp_id');
                    $table->string('emp_id');
                    $table->string('month');
                    $table->string('bonus_amt');
                    $table->timestamps();
                    $table->temporary();
                });

                
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

                if(!in_array('emp_id', $header) || !in_array('genesis_ledger_id', $header) || !in_array('bonus_amt', $header))
                {
                    Schema::drop('temp_bonus');
                    return redirect()->back()->with('alert-danger','headings should be proper. Please download sample file for reference');
                }
                
                $emp_id_array =DB::table('emp')->select('id')->where('status','active')->get();
                $emp_id_array = json_decode($emp_id_array,true);
                $emp_array=array();
                for($x=0; $x<sizeof($emp_id_array);$x++)
                {
                    $emp_array[$x]=$emp_id_array[$x]['id'];
                }

                foreach ($data as $value) {
                    $i++;
                
                $k=1;
                
                trim($value['emp_id']);
                trim($value['genesis_ledger_id']);
                trim($value['bonus_amt']);
                
              if($value['emp_id']=='')
              {
                $msg.=' emp_id not given in row no '.$i;
              }
              if($month=='')
              {
                $msg.=' month not given in row no '.$i;
              }
              if($value['bonus_amt']=='')
              {
                  $value['bonus_amt']=0;
                // $msg.=' bonus_amt not given in row no '.$i;
              }
              
              if($msg!='')
              {
                Schema::drop('temp_bonus');
                $history = DB::table('upload_history')->where('id',$history_id)->update(['process_status'=>'Failed']);
                $notification = DB::table('notification')->insert(['user_id'=>session('user_id'),'notification'=>'Bonus Upload Process','notification_status'=>'Failed','status'=>'active','link'=>'history']);
                activity()->log('Bonus upload process failed because - '.$msg);
                return redirect()->back()->with('alert-danger',$msg);
              }
            
                $emp_in_bonus = DB::table('bonus')->where('month',$month)->where('emp_id',$value['emp_id'])->count();
                if($emp_in_bonus>0)
                {
                    Schema::drop('temp_bonus');
                    activity()->log('Employee upload process failed because emp_id in row no '.$i.' already present in bonus for month '.$month);
                    $history = DB::table('upload_history')->where('id',$history_id)->update(['process_status'=>'Failed']);
                    $notification = DB::table('notification')->insert(['user_id'=>session('user_id'),'notification'=>'Bonus Upload Process','notification_status'=>'Failed','status'=>'active','link'=>'history']);
                    return redirect()->back()->with('alert-danger','emp_id in row no '.$i.' already present in bonus for month '.$month);
                }
                if(!in_array($value['emp_id'], $emp_array))
                {
                    Schema::drop('temp_bonus');
                    activity()->log('Employee upload process failed because emp_id in row no '.$i.' is not active!');
                    $history = DB::table('upload_history')->where('id',$history_id)->update(['process_status'=>'Failed']);
                    $notification = DB::table('notification')->insert(['user_id'=>session('user_id'),'notification'=>'Bonus Upload Process','notification_status'=>'Failed','status'=>'active','link'=>'history']);
                    return redirect()->back()->with('alert-danger','emp_id in row no '.$i.' is not active!');
                }
                

                $insertrow = array('emp_id'=>$value['emp_id'],'month'=>$month,'bonus_amt'=>$value['bonus_amt'],'created_at'=>date('Y-m-d H:i:s'));
                $bonus = DB::table('temp_bonus')->insert($insertrow);
                
                 if($bonus)
                    {
                        $inserted++;
                        
                    }                       
                }
                if($inserted==$i)
                { 
                    activity()->log('Employee Uploaded to temporary Table Successfully');

                    $bonus_record = (array) json_decode(DB::table('temp_bonus')->select('emp_id','month', 'bonus_amt')->get(),true);

                    if(sizeof($bonus_record))
                    {
                        $insert = DB::table('bonus')->insert($bonus_record);
                        
                        if($insert)
                        {
                            $history = DB::table('upload_history')->where('id',$history_id)->update(['process_status'=>'Successful']);
                             $notification = DB::table('notification')->insert(['user_id'=>session('user_id'),'notification'=>'Bonus Upload Process','notification_status'=>'Successful','status'=>'active','link'=>'history']);
                            activity()->log('Bonus Uploaded Successfully');
                            Schema::drop('temp_bonus');
                            return redirect()->back()->with('alert-success','Data inserted!');
                        }
                        else
                        {
                            $history = DB::table('upload_history')->where('id',$history_id)->update(['process_status'=>'Failed']);
                            $notification = DB::table('notification')->insert(['user_id'=>session('user_id'),'notification'=>'Bonus Upload Process','notification_status'=>'Failed','status'=>'active','link'=>'history']);
                            activity()->log('Bonus Upload Process Failed');
                            Schema::drop('temp_bonus');
                            return redirect()->back()->with('alert-danger','Data not inserted!');
                        }
                    }
                    else
                    {
                        $history = DB::table('upload_history')->where('id',$history_id)->update(['process_status'=>'Failed']);
                        $notification = DB::table('notification')->insert(['user_id'=>session('user_id'),'notification'=>'Bonus Upload Process','notification_status'=>'Failed','status'=>'active','link'=>'history']);
                        activity()->log('Bonus not Uploaded to Temporary Table');
                        Schema::drop('temp_bonus');
                        return redirect()->back()->with('alert-danger','data Not Inserted!');
                    }                 
                    
                    
                }
                else
                {
                    $history = DB::table('upload_history')->where('id',$history_id)->update(['process_status'=>'Failed']);
                    $notification = DB::table('notification')->insert(['user_id'=>session('user_id'),'notification'=>'Bonus Upload Process','notification_status'=>'Failed','status'=>'active','link'=>'history']);
                    activity()->log('Bonus not Uploaded to Temporary Table');
                    Schema::drop('temp_bonus');
                    return redirect()->back()->with('alert-danger','data Not Inserted!');
                }
            }
            else
            {
                $history = DB::table('upload_history')->where('id',$history_id)->update(['process_status'=>'Failed','file_upload_status'=>'Failed']);
                $notification = DB::table('notification')->insert(['user_id'=>session('user_id'),'notification'=>'Bonus Upload Process','notification_status'=>'Failed','status'=>'active','link'=>'history']);
                activity()->log('TDS not Uploaded');
                return redirect()->back()->with('alert-danger','data Not Inserted!');
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
}
