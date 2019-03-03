<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Exception;
use \Illuminate\Database\QueryException;
ini_set('max_execution_time', 300);
date_default_timezone_set('Asia/Kolkata');

class SalesController extends Controller
{
    public function get()
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
            return redirect('dashboard')->with('alert-danger',"Only admin and HR can add employee");
        }
    }

    public function upload_sales_csv(Request $request){

    if(Session::get('username')!='' && (session('role')=='admin' || session('role')=='hr'))
    {
    try
    {
        activity()->log('Trying to Upload Sales');
        $msg=''; 
        $i=0;
       	
       	$inserted=0; $inserted_target=0;
        $validations=''; $errormsg=''; $select_target=0;
        if($request->hasFile('csvfile')){ 
            $extension = strtolower($request->file('csvfile')->getClientOriginalExtension()); 
            if($extension!='csv')
            { 
            	return redirect()->back()->with('alert-danger','Please Upload csv file only');
            }
             

            $header = NULL;
            $delimiter=',';
            $data = array();
            if (($handle = fopen($request->file('csvfile'), 'r')) !== FALSE)
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
            Schema::create('temp_sales', function (Blueprint $table) {

                $table->increments('sales_id');
                $table->string('branch');
                $table->date('bill_date');
                $table->string('department');
                $table->integer('items');
                $table->bigInteger('net_amount');
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

            $employee =array(); $k=0;
            foreach ($data as $value) {
                $i++; $target_result=false;
                $working_hour = '';
                $total_working_hour=0; 
                
                $sales = array();
            	$mysales = json_encode(array('report'=>$sales));

            	$insertrow[$k] = array('branch' => $value['Source Site'],'bill_date' => date('Y-m-d',strtotime($value['Bill Date'])),'Department' => $value['Department'],'items' => $value['Bill Qty SUM'],'net_amount' => $value['Net Amount SUM'],'aging' => $value['Category5'], 'mrp' => $value['RSP'], 'sales_person' => $value['Salesperson'],'sales_person_id' => $value['Salesperson No']);
                
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
                
                $x=0;$inn=0;
                foreach($insertrow_target as $key => $value)
                { 
                    $insert_target_sales = DB::table('temp_target_sales')->insert($value);
                    if($insert_target_sales) $inn++;
                    $x++;
                   
                }
                
                 $sales_record = (array) json_decode(DB::table('temp_sales')->select('branch','bill_date','Department','items','net_amount','aging','mrp','sales_person','sales_person_id')->get(),true);
                 
                $target_sales_record = (array) json_decode(DB::table('temp_target_sales')->select('emp_id','target_id','actual_sale')->get(),true);

                $insert = DB::table('sales')->insert($sales_record);
                $insert_target = DB::table('target_sales')->insert($target_sales_record);

                Schema::drop('temp_sales');
                Schema::drop('temp_target_sales');

                if($insert && $insert_target)
                {
                    activity()->log('Sales Uploaded Successfully');
                    return redirect()->back()->with('alert-success','Data inserted!');
                }
                else
                {
                    activity()->log('Sales not uploaded');
                    return redirect()->back()->with('alert-danger','Data not inserted!');
                }  	
            }
            else
            {
                activity()->log('Sales Upload process failed');
            	return redirect()->back()->with('alert-danger','data Not Inserted!');
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

    } 
}
