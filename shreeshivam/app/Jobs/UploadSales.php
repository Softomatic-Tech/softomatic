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

class UploadSales implements ShouldQueue
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
            activity()->log('Sales Upload process Queued to Job List');

            $msg=''; 
            $i=0;
            $inserted=0; $inserted_target=0;
            $validations=''; $errormsg=''; $select_target=0;
            $file = $request->file('csvfile');
            $history_id = $this->data['history_id'];
            $target_dir = 'sales_upload/';
            $filename = strtolower($file->getClientOriginalName()); 
            $extension = strtolower($file->getClientOriginalExtension());
            $target_file = $target_dir.$filename;
            if($file->move(base_path().'/sales_upload/', $filename))
            { 
                activity()->log('File with name '.$filename.' uploaded to server in location '.base_path().'/sales_upload/');

                $history = DB::table('upload_history')->where('id',$history_id)->update(['file_location'=>$target_file,'file_upload_status'=>'Successful']);

                $this->file_upload=true;

                Schema::create('temp_sales', function (Blueprint $table) {
                    $table->increments('sales_id');
                    $table->string('branch');
                    $table->date('bill_date');
                    $table->date('bill_no');
                    $table->string('department');
                    $table->integer('barcode');
                    $table->integer('hsn_code');
                    $table->integer('items');
                    $table->bigInteger('net_amount');
                    $table->string('category2');
                    $table->string('aging');
                    $table->string('sales_person');
                    $table->string('sales_person_id');
                    $table->bigInteger('mrp');
                    $table->string('section');
                    $table->string('division');
                    $table->timestamps();
                    $table->temporary();
                });
                Schema::create('temp_target_sales', function (Blueprint $table) {
                    $table->increments('id');
                    $table->string('emp_id');
                    $table->integer('target_id')->nullable();
                    $table->bigInteger('actual_sale');                
                    $table->temporary();
                });

                $header = NULL;
                $delimiter=',';
                $data = array();
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

                if(!in_array('Source Site', $header) || !in_array('Bill Date', $header) || !in_array('Bill No', $header) || !in_array('Division', $header) || !in_array('Section', $header) || !in_array('Item Hsn Code', $header) || !in_array('Barcode', $header) || !in_array('Department', $header) || !in_array('Bill Qty SUM', $header) || !in_array('Net Amount SUM', $header) || !in_array('Category2', $header) || !in_array('Category5', $header) || !in_array('RSP', $header) || !in_array('Salesperson', $header) || !in_array('Salesperson No', $header))
                {
                    Schema::drop('temp_sales');
                    Schema::drop('temp_target_sales');
                    return redirect()->back()->with('alert-danger','headings should be proper. Please download sample file for reference');
                }

                $employee =array(); $k=0;
                foreach ($data as $value) {


                    trim('Source Site');
                    trim('Bill Date');
                    trim('Bill No');
                    trim('Division');
                    trim('Section');
                    trim('Item Hsn Code');
                    trim('Barcode');
                    trim('Department');
                    trim('Bill Qty SUM');
                    trim('Net Amount SUM');
                    trim('Category2');
                    trim('Category5');
                    trim('RSP');
                    trim('Salesperson');
                    trim('Salesperson No');
                    
                    $i++; $target_result=false;
                    $working_hour = '';
                    $total_working_hour=0; 
                    
                    $sales = array();
                    $mysales = json_encode(array('report'=>$sales));

                    $insertrow[$k] = array('branch' => $value['Source Site'],'bill_date' => date('Y-m-d',strtotime($value['Bill Date'])),'bill_no'=>$value['Bill No'],'division'=>$value['Division'],'section'=>$value['Section'],'hsn_code'=>$value['Item Hsn Code'],'barcode'=>$value['Barcode'],'department' => $value['Department'],'items' => $value['Bill Qty SUM'],'net_amount' => $value['Net Amount SUM'],'category2'=>$value['Category2'],'aging' => $value['Category5'], 'mrp' => $value['RSP'], 'sales_person' => $value['Salesperson'],'sales_person_id' => $value['Salesperson No']);
                    
                    $select_target = DB::table('target')->leftjoin('emp','emp.id','=','emp_id')->where('emp.genesis_id',$value['Salesperson No'])->where('start_date','<=',date('Y-m-d',strtotime($value['Bill Date'])))->where('end_date','>=',date('Y-m-d',strtotime($value['Bill Date'])))->value('target.id');

                    // if($select_target!='')
                    // {
                    if(!in_array($value['Salesperson No'],$employee))
                    {
                        array_push($employee, $value['Salesperson No']);
                         $insertrow_target[$value['Salesperson No']][0]= array('emp_id'=>$value['Salesperson No'],'target_id'=>$select_target,'actual_sale'=>$value['Net Amount SUM']);
                    }
                    else
                    { $p = sizeof($insertrow_target[$value['Salesperson No']]);
                        $insertrow_target[$value['Salesperson No']][$p]= array('emp_id'=>$value['Salesperson No'],'target_id'=>$select_target,'actual_sale'=>$value['Net Amount SUM']);
                    }

                        
                    $sales_result = DB::table('temp_sales')->insert($insertrow[$k]);
                    if(!$sales_result)
                    {
                        $msg.="Row no : ".$i." cannot be inserted ";
                    }
                    else
                    {
                        $inserted++;
                        $k++;
                    }            
                }
                if($inserted==$i)
                {
                    activity()->log('Sales Uploaded to temporary Table Successfully');
                    $x=0;$inn=0;
                    foreach($insertrow_target as $key => $value)
                    { 
                        $insert_target_sales = DB::table('temp_target_sales')->insert($value);
                        if($insert_target_sales) $inn++;
                        $x++;
                       
                    }
                    if($inn==$x)
                    {
                        $sales_record = (array) json_decode(DB::table('temp_sales')->select('branch','bill_date','bill_no','division','department','section','hsn_code','barcode','items','net_amount','category2','aging','mrp','sales_person','sales_person_id')->get(),true);
                        
                        $target_sales_record = (array) json_decode(DB::table('temp_target_sales')->select('emp_id','target_id','actual_sale')->get(),true);

                        $insert = DB::table('sales')->insert($sales_record);
                        $insert_target = DB::table('target_sales')->insert($target_sales_record);

                        Schema::drop('temp_sales');
                        Schema::drop('temp_target_sales');

                        if($insert && $insert_target)
                        {
                            $history = DB::table('upload_history')->where('id',$history_id)->update(['process_status'=>'Successful']);

                            
                            
                            activity()->log('Sales Uploaded Successfully');

                            $notification = DB::table('notification')->insert(['user_id'=>session('user_id'),'notification'=>'Sales Upload Process','notification_status'=>'Successful','status'=>'active','link'=>'history']);

                            return redirect()->back()->with('alert-success','Sales Data inserted!');
                        }
                        else
                        {
                            $history = DB::table('upload_history')->where('id',$history_id)->update(['process_status'=>'Failed']);

                            
                            
                            activity()->log('Sales not uploaded');
                            
                            $notification = DB::table('notification')->insert(['user_id'=>session('user_id'),'notification'=>'Sales Upload Process','notification_status'=>'Failed','status'=>'active','link'=>'history']);

                            return redirect()->back()->with('alert-danger','Sales Data not inserted!');
                        } 
                    }
                    else
                    {
                        $history = DB::table('upload_history')->where('id',$history_id)->update(['process_status'=>'Failed']);

                        
                        activity()->log('Sales Uploaded Process Failed!');

                        $notification = DB::table('notification')->insert(['user_id'=>session('user_id'),'notification'=>'Sales Upload Process','notification_status'=>'Failed','status'=>'active','link'=>'history']);

                        Schema::drop('temp_sales');
                        Schema::drop('temp_target_sales');
                        return redirect()->back()->with('alert-danger','Sales data Not Inserted!');
                    }  

                }
                else
                {
                    $history = DB::table('upload_history')->where('id',$history_id)->update(['process_status'=>'Failed']);
                    $notification = DB::table('notification')->insert(['user_id'=>session('user_id'),'notification'=>'Sales Upload Process','notification_status'=>'Failed','status'=>'active','link'=>'history']);
                    
                    activity()->log('Sales not Uploaded to Temporary Table');
                    Schema::drop('temp_sales');
                    Schema::drop('temp_target_sales');
                    return redirect()->back()->with('alert-danger','Sales data Not Inserted!');
                }
            }
            else
            {
                $history = DB::table('upload_history')->where('id',$history_id)->update(['file_location'=>$target_file,'file_upload_status'=>'Failed','process_status'=>'Failed']);
                $notification = DB::table('notification')->insert(['user_id'=>session('user_id'),'notification'=>'Sales Upload Process','notification_status'=>'Failed','status'=>'active','link'=>'history']);
                
                activity()->log('File not Uploaded to Server');
                return redirect()->back()->with('alert-danger','File Not Uploaded To Server');
            }
        }
        catch(QueryException $e)
        {
             if($this->file_upload==false)
                $upload = 'Failed';
            else
                $upload = 'Successful';

            $history = DB::table('upload_history')->where('id',$history_id)->update(['file_location'=>$target_file,'file_upload_status'=>$upload,'process_status'=>'Failed']);

            $notification = DB::table('notification')->insert(['user_id'=>session('user_id'),'notification'=>'Sales Upload Process','notification_status'=>'Failed','status'=>'active','link'=>'history']);

            
            activity()->log($e->getMessage());
            return redirect()->back()->with('alert-danger',"Database Query Error! [".$e->getMessage()." ]");
        } 
        catch(Exception $e)
        {
             if($this->file_upload==false)
                $upload = 'Failed';
            else
                $upload = 'Successful';

            $history = DB::table('upload_history')->where('id',$history_id)->update(['file_location'=>$target_file,'file_upload_status'=>$upload,'process_status'=>'Failed']);

            $notification = DB::table('notification')->insert(['user_id'=>session('user_id'),'notification'=>'Sales Upload Process','notification_status'=>'Failed','status'=>'active','link'=>'history']);

            
            activity()->log($e->getMessage());
            return redirect()->back()->with('alert-danger','Error '.$e->getMessage());
        } 
    }

    public function failed(Exception $exception)
    {
        if($this->file_upload==false)
        {
            activity()->log('Sales Upload Process Failed! '.$e->getMessage());
            $history = DB::table('upload_history')->where('id',$history_id)->update(['file_location'=>$target_file,'file_upload_status'=>'Failed','process_status'=>'Failed']);
            $notification = DB::table('notification')->insert(['user_id'=>session('user_id'),'notification'=>'Sales Upload Process','notification_status'=>'Failed','status'=>'active','link'=>'history']);
            return redirect()->back()->with('alert-danger','Sales Upload Process Failed!');
        }
        else
        {
            activity()->log('Sales Upload Process Failed! '.$e->getMessage());
            $history = DB::table('upload_history')->where('id',$history_id)->update(['process_status'=>'Failed']);
            $notification = DB::table('notification')->insert(['user_id'=>session('user_id'),'notification'=>'Sales Upload Process','notification_status'=>'Failed','status'=>'active','link'=>'history']);
            return redirect()->back()->with('alert-danger','Sales Upload Process Failed!');
        }
    }

    public function retryUntil()
    {
        $this->retry+=1;
        return now()->addSeconds(60);
        activity()->log('No Of retry in sales upload'.$this->retry);
    }
}
