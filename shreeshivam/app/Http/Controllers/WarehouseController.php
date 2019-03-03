<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\Cache;
use App\Http\Controllers\Cookie;
use App\Http\Controllers\Controller;
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

class WarehouseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => 'store']);
    }
    public function login(Request $request){
       
        $rules = array(
        'username'    => 'required|email', // make sure the email is an actual email
        'password' => 'required|min:3' // password can only be alphanumeric and has to be greater than 3 characters
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('warehouse')
            ->withErrors($validator) // send back all errors to the login form
            ->withInput(Input::except('password')); // send back the input (not the password) so that we can repopulate the form
        } 
        else {
          

            if(Auth::attempt(['email' => $request->username, 'password' => $request->password]))
            { 
               $user = Auth::user(); 
             // if (Auth::attempt($userdata)) {
                $username = $user->name;
                $email = $user->email;

                try
                {
                    $role = DB::table('role')->where('role_id',$user->role_id)->value('role');  
                    $status = DB::table('emp')->where('email',$email)->value('status') ;
                    $desig = DB::table('emp')->where('email',$email)->value('designation'); 
                    $designation = DB::table('designation')->where('id',$desig)->value('designation'); 
                    // $role = $user->role;
                    Session::put('username',$username);
                    Session::put('useremail',$email);
                    Session::put('role',$role);
                    Session::put('designation',$designation);
                    Session::put('user_id',$user->id);
                    activity()->log('user logged in');
                   
                    if($role=='gate staff')
                    {
                       
                    return Redirect::to('gate_staff_dashboard');
                    }
                   else if($role=='warehouse staff')
                    {
                       
                    return Redirect::to('warehouse_staff_dashboard');
                    }
                   else if($role=='security staff')
                    {
                      
                    return Redirect::to('security_staff_dashboard');
                    }
                    else  if($role=='team leader')
                    {
                    return Redirect::to('team_leader_dashboard');
                    }
                    else if($role=='purchase admin')
                    {
                        return Redirect::to('purchase_admin_dashboard');  
                    }
                    else
                    {
                        return redirect()->back()->with('status','Invalid User');
                    }
                   
                }
                catch(QueryException $e)
                {
                    // activity()->log($e->getMessage());
                    return redirect()->back()->with('failure',"Database Query Error! [".$e->getMessage()." ]");
                }
                catch(Exception $e)
                {
                    // activity()->log($e->getMessage());
                    return redirect()->back()->with('alert-danger',$e->getMessage());
                }
            }
            else
            {        
                return redirect()->back()->with('status','Invalid Username or Password.');
            }

        }
    }
    public function logout(Request $request)
    {
        $role = session('role');
        activity()->log('user logged out');
        Auth::logout();
        \Cache::flush();
        Session::forget('username');
        Session::forget('email');
        Session::forget('password');
        Session::flush();
        if(($role=='gate staff') || ($role=='warehouse staff') || ($role=='security staff') || ($role=='team leader'))
        {
           
            return redirect('warehouse')->withCookie(\Cookie::forget('laravel_token'))->with('action','Successfully Logout');
        }
        else
        {
          
            return redirect('/')->withCookie(\Cookie::forget('laravel_token'))->with('action','Successfully Logout');
        }
    }
    public function reset_pass(Request $request){
        if(Session::get('username')!='')
        {
            activity()->log('Reset Password'.Session::get('user_id').' ');
            try{
                $user_id=$request->input('uid');
                
                $pass=bcrypt($request->input('password'));
                $exp=DB::table('users')
			    ->where('id', $user_id)
                ->update(['password' =>  $pass ,'updated_at' =>Carbon::now()]);
                if($exp)
                    {
                    activity()->log('Account with id '.$user_id.' Updated Successfully');
                    return redirect()->back()->with('alert-success','Password Reset Successfully!');
                    }
                else
                    {
                    activity()->log('Account with id '.$user_id.' cannot be updated');
                    return redirect()->back()->with('alert-danger','Password cannot be updated!');
                    }	
            }
            catch(QueryException $e)
            {
                activity()->log($e->getMessage());
                return redirect()->back()->with('alert-danger','Database Query Exception! ['.$e->getMessage().']');
            }
            catch(Exception $e)
            {
                activity()->log($e->getMessage());
                return redirect()->back()->with('alert-danger',$e->getMessage());
            }
        }
        else
        {
            return redirect('/')->with('status',"Please login First");
        }
    }
    public function index()
	{
		
        try
        {
        	if(Session::get('username')!='' && (session('role')=='gate staff'))
	        {
		        activity()->log('Fetching state');
				$state = DB::table('state')->get();
				return view('gate_staff_dashboard',compact('state'));
			}
	        else
	        {
	        	if(Session::get('username')=='')
	                return redirect('warehouse')->with('status',"Please login First");
	           
	        }
		} 
		catch(QueryException $e)
	   	{
	   		activity()->log($e->getMessage());
			return Redirect::to('gate_staff_dashboard')->with('alert-danger',"Database Query Error! [".$e->getMessage()." ]");
	   	}
		catch(Exception $e)
	   	{
	   		activity()->log($e->getMessage());
			return Redirect::to('gate_staff_dashboard')->with('alert-danger',$e->getMessage());
	   	}			 
    }
    public function get_acc_detail(){
       
        if(Session::get('username')!='')
        {
            activity()->log('Loading User Details '.Session::get('user_id').' ');

            try
            {
                $uid= session('user_id') ;
                $username= DB::table('users')->where('id',$uid)->value('email');
                $userid= DB::table('users')->where('id',$uid)->value('id');
                    return view('ware_acc',compact('username','userid'));
                
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
            return redirect('/')->with('status',"Please login First");
    }

    public function getcity(Request $request)
	{
		
        try
        {
        	if(Session::get('username')!='' && (session('role')=='gate staff'))
	        {
                activity()->log('Fetching city');
                $state=$request->state;

				  $city = DB::table('city')->where('state_id',$state)->get();
                                    
                  $output='';
                    
                  foreach($city as $key=>$cities)
                    {
                       
                    $output.='<option value="'.$cities->id.'">'.$cities->city.'</option>';
                    }
                        return $output;                
				
			}
	        else
	        {
	        	if(Session::get('username')=='')
	                return redirect('warehouse')->with('status',"Please login First");
	           
	        }
		} 
		catch(QueryException $e)
	   	{
	   		activity()->log($e->getMessage());
			return Redirect::to('gate_staff_dashboard')->with('alert-danger',"Database Query Error! [".$e->getMessage()." ]");
	   	}
		catch(Exception $e)
	   	{
	   		activity()->log($e->getMessage());
			return Redirect::to('gate_staff_dashboard')->with('alert-danger',$e->getMessage());
	   	}			 
    }
    public function get_form(Request $request)
	{

        try
        {
        	if(Session::get('username')!='' && (session('role')=='gate staff'))
	        {
                activity()->log('Submit Get Staff`s form');
                $rules = array(
                    'date'              => 'required|date|date_format:Y-m-d', 
                    'party_name'        =>  'required|string', 
                    'state '            =>  'required|numeric',
                    'city '             =>  'required|numeric',
                    'transport_name'    =>  'required|string', 
                    'packet_no'         =>  'required|string', 
                    'parcel_no'         =>  'required|string',
                    'bill_no'           =>  'required|string',
                    'quantity'          =>  'required|string',
                 );
                 $user_id= session('user_id') ;
                 $email=session('useremail');
                 $emp_id=DB::table('emp')->where('email',$email)->value('id');
                 $date=$request->input('date');
                 $party_name=$request->input('party_name');
                 $state=$request->input('state');
                 $city=$request->input('city');
                 $transport_name=$request->input('transport_name');
                 $packet_no=$request->input('packet_no');
                 $parcel_no=$request->input('parcel_no');
                 $bill_no=$request->input('bill_no');
                 $quantity=$request->input('quantity');
                 $transporter_sign=$request->input('transporter_sign');
                 $gate_staff_sign=$request->input('gate_staff_sign');
                 $insert=DB::table('gate_parcel_detail')->insert(['user_id'=>$user_id,'emp_id'=>$emp_id,'date'=>$date,'party_name'=>$party_name,'state'=>$state,'city'=>$city,
                 'transport_name'=>$transport_name,'packet_no'=>$packet_no,'parcel_no'=>$parcel_no,'bill_no'=>$bill_no,'quantity'=>$quantity,'transporter_sign'=>$transporter_sign,'gate_staff_sign'=>$gate_staff_sign,'created_at'=>now()]);
                 if($insert)
                 {
                    return redirect('gate_staff_dashboard')->with('alert-success','form is submitted successfully');  
                 }
                 else{
                    return redirect('gate_staff_dashboard')->with('alert-danger','form cannot be submitted ');  
                 }    
				
			}
	        else
	        {
	        	    if(Session::get('username')=='')
	                return redirect('warehouse')->with('status',"Please login First");
	           
	        }
		} 
		catch(QueryException $e)
	   	{
	   		activity()->log($e->getMessage());
			return Redirect::to('gate_staff_dashboard')->with('alert-danger',"Database Query Error! [".$e->getMessage()." ]");
	   	}
		catch(Exception $e)
	   	{
	   		activity()->log($e->getMessage());
			return Redirect::to('gate_staff_dashboard')->with('alert-danger',$e->getMessage());
	   	}			 
    }
    public function wareindex()
	{
		
        try
        {
        	if(Session::get('username')!='' && (session('role')=='warehouse staff'))
	        {
		        activity()->log('Fetching all detail for warehouse dashboard');
             $get_parcel_detail = DB::table('gate_parcel_detail')
                                    ->join('state','gate_parcel_detail.state','=','state.id')
                                    ->join('city','gate_parcel_detail.city','=','city.id')
                                    ->join('emp','gate_parcel_detail.emp_id','=','emp.id')
                                    ->select('gate_parcel_detail.party_name','gate_parcel_detail.transport_name','gate_parcel_detail.date',
                                    'gate_parcel_detail.parcel_no','gate_parcel_detail.packet_no','gate_parcel_detail.quantity','gate_parcel_detail.bill_no','gate_parcel_detail.transporter_sign'
                                    ,'gate_parcel_detail.gate_staff_sign','city.city','state.state','emp.first_name','emp.middle_name','emp.last_name')
                ->get();
              
				return view('warehouse_staff_dashboard',compact('get_parcel_detail'));
			}
	        else
	        {
	        	if(Session::get('username')=='')
	                return redirect('warehouse')->with('status',"Please login First");
	           
	        }
		} 
		catch(QueryException $e)
	   	{
	   		activity()->log($e->getMessage());
			return Redirect::to('gate_staff_dashboard')->with('alert-danger',"Database Query Error! [".$e->getMessage()." ]");
	   	}
		catch(Exception $e)
	   	{
	   		activity()->log($e->getMessage());
			return Redirect::to('gate_staff_dashboard')->with('alert-danger',$e->getMessage());
	   	}			 
    }
    public function gate_task_list()
	{
        
        try
        {
        	if(Session::get('username')!='' && (session('role')=='gate staff'))
	        {
                activity()->log('Fetching all detail for warehouse dashboard');
            
             $get_parcel_detail = DB::table('gate_parcel_detail')
                                        ->where('user_id',Session::get('user_id'))
                                    ->join('state','gate_parcel_detail.state','=','state.id')
                                    ->join('city','gate_parcel_detail.city','=','city.id')
                                    ->select('gate_parcel_detail.party_name','gate_parcel_detail.transport_name','gate_parcel_detail.date',
                                    'gate_parcel_detail.parcel_no','gate_parcel_detail.packet_no','gate_parcel_detail.quantity','gate_parcel_detail.bill_no','gate_parcel_detail.transporter_sign'
                                    ,'gate_parcel_detail.gate_staff_sign','city.city','state.state')->get();
                                  
				return view('gate_task_list',compact('get_parcel_detail'));
			}
	        else
	        {
	        	if(Session::get('username')=='')
	                return redirect('warehouse')->with('status',"Please login First");
	           
	        }
		} 
		catch(QueryException $e)
	   	{
	   		activity()->log($e->getMessage());
			return Redirect::to('gate_staff_dashboard')->with('alert-danger',"Database Query Error! [".$e->getMessage()." ]");
	   	}
		catch(Exception $e)
	   	{
	   		activity()->log($e->getMessage());
			return Redirect::to('gate_staff_dashboard')->with('alert-danger',$e->getMessage());
	   	}			 
    }
    public function adminindex()
	{
		
        try
        {
        	if(Session::get('username')!='' && (session('role')=='purchase admin'))
	        {
		        activity()->log('Fetching all detail for warehouse dashboard');
             $get_parcel_detail = DB::table('gate_parcel_detail')
                                    ->join('state','gate_parcel_detail.state','=','state.id')
                                    ->join('city','gate_parcel_detail.city','=','city.id')
                                    ->join('emp','gate_parcel_detail.emp_id','=','emp.id')
                                    ->select('gate_parcel_detail.party_name','gate_parcel_detail.transport_name','gate_parcel_detail.date',
                                    'gate_parcel_detail.parcel_no','gate_parcel_detail.packet_no','gate_parcel_detail.quantity','gate_parcel_detail.bill_no','gate_parcel_detail.transporter_sign'
                                    ,'gate_parcel_detail.gate_staff_sign','city.city','state.state','emp.first_name','emp.middle_name','emp.last_name')
                ->get();
              
				return view('purchase_admin_dashboard',compact('get_parcel_detail'));
			}
	        else
	        {
	        	if(Session::get('username')=='')
	                return redirect('warehouse')->with('status',"Please login First");
	           
	        }
		} 
		catch(QueryException $e)
	   	{
	   		activity()->log($e->getMessage());
			return Redirect::to('purchase_admin_dashboard')->with('alert-danger',"Database Query Error! [".$e->getMessage()." ]");
	   	}
		catch(Exception $e)
	   	{
	   		activity()->log($e->getMessage());
			return Redirect::to('purchase_admin_dashboard')->with('alert-danger',$e->getMessage());
	   	}			 
    }
    public function vendor_insert(Request $request)
	{
    
        try
        {
        	if(Session::get('username')!='')
	        {
                activity()->log('Submit vendor`s Master');
                $rules = array(
                    'vendor' => 'required', 
                    'vendor_id' => 'required', 
                    'gst_id_no' => 'required', 
                    'city_name' => 'required',
                    'mobile_no ' => 'required|numeric',
                    'email' => 'required|email', 
                    'address' => 'required|string',  
                    'logistics_applicable' => 'required', 
                    'bank_name ' => 'required|numeric',
                    'acc_no' => 'required', 
                    'pan_no' => 'required', 
                    'gst_category' => 'required', 
                    'gst_state_name' => 'required', 
                    'gst_state_code' => 'required'
                 );
                 
                $vendor=$request->input('vendor');
                $vendor_id=$request->input('vendor_id');
                $gst_id_no=$request->input('gst_id_no');
                $city_name=$request->input('city_name');
                $mobile_no=$request->input('mobile_no');
                $email=$request->input('email');
                $address=$request->input('address');
                $logistics_applicable=$request->input('logistics_applicable');
                $bank_name=$request->input('bank_name');
                $acc_no=$request->input('acc_no');
                $pan_no=$request->input('pan_no');
                $gst_category=$request->input('gst_category');
                $gst_state_name=$request->input('gst_state_name');
                $gst_state_code=$request->input('gst_state_code');
                
                $insert=DB::table('vendor')->insert(['vendor'=>$vendor,'vendor_id'=>$vendor_id,'gst_id_no'=>$gst_id_no,'city_name'=>$city_name,'mobile_no'=>$mobile_no, 'email'=>$email,'address'=>$address,'logistics_applicable'=>$logistics_applicable,'bank_name'=>$bank_name,'acc_no'=>$acc_no,'pan_no'=>$pan_no,'gst_category'=>$gst_category,'gst_state_name'=>$gst_state_name,'gst_state_code'=>$gst_state_code,'created_at'=>now()]);

                if($insert)
                {
                    return redirect('vendor-master')->with('alert-success','data is inserted successfully');  
                }
                else
                {
                    return redirect('vendor-master')->with('alert-danger','data cannot be inserted ');  
                }    
				
			}
	        else
	        {
	        	    if(Session::get('username')=='')
	                return redirect('warehouse')->with('status',"Please login First");
	           
	        }
		} 
		catch(QueryException $e)
	   	{
	   		activity()->log($e->getMessage());
			return Redirect::to('vendor-master')->with('alert-danger',"Database Query Error! [".$e->getMessage()." ]");
	   	}
		catch(Exception $e)
	   	{
	   		activity()->log($e->getMessage());
			return Redirect::to('vendor-master')->with('alert-danger',$e->getMessage());
	   	}			  
        
    }

    public function purchase_order_form(Request $request)
    {
        $vendor = DB::table('vendor')->select('id','vendor','vendor_id','city_name','mobile_no')->get();
        return view('purchase-order',compact('vendor'));
    }    
    
    public function purchase_insert(Request $request)
    {
    
        try
        {
            if(Session::get('username')!='')
            {
                activity()->log('Submit vendor`s Master');
                $rules = array(
                    'vendor' => 'required',
                    'owner_name' => 'required',
                    'office_address' => 'required',
                    'gst_no' => 'required',
                    'warehouse_address' => 'required',
                    'transport_name' => 'required',
                    'transport_phone_no' => 'required',
                    'vender_warehouse_manager' => 'required',
                    'ware_manager_no' => 'required',
                    'vendor_account_exe_name' => 'required',
                    'vendor_acc_exe_phone' => 'required',
                    'margin' => 'required',
                    'payment_team' => 'required',
                    'cd' => 'required',
                    'days' => 'required',
                    'stock_correction' => 'required',
                    'b_staff' => 'required',
                    'terms_of_trade' => 'required',
                    'date_of_order' => 'required',
                    'order_by' => 'required',
                    'time_line_of_delivery' => 'required',
                    'stock_date' => 'required',
                    'end_date' => 'required',
                    'pt_file' => 'required',
                    'marketing_exec_name' => 'required',
                    'marketing_exec_no' => 'required',
                    'weight_of_parcel' => 'required',
                    'status_parcel_received' => 'required',
                    'quantity' => 'required',
                    'purchase_cost' => 'required',
                    'size' => 'required',
                    'color' => 'required',
                    'product' => 'required',
                    'remark' => 'required'
                );
                 
                $imagearray = array();
                $images = DB::table('snapshot')->get();
                for($i=0;$i<sizeof($images);$i++)
                {
                    array_push($imagearray, $images[$i]->Image);
                }
                $insert=DB::table('purchase_order')->insert([
                    'vendor'=>$request->input('vendor'),
                    'owner_name'=>$request->input('owner_name'),
                    'office_address'=>$request->input('office_address'),
                    'gst_no'=>$request->input('gst_no'),
                    'warehouse_address'=>$request->input('warehouse_address'),
                    'transport_name'=>$request->input('transport_name'),
                    'transport_phone_no'=>$request->input('transport_phone_no'),
                    'vender_warehouse_manager'=>$request->input('vender_warehouse_manager'),
                    'ware_manager_no'=>$request->input('ware_manager_no'),
                    'vendor_account_exe_name'=>$request->input('vendor_account_exe_name'),
                    'vendor_acc_exe_phone'=>$request->input('vendor_acc_exe_phone'),
                    'margin'=>$request->input('margin'),
                    'payment_team'=>$request->input('payment_team'),
                    'cd'=>$request->input('cd'),
                    'days'=>$request->input('days'),
                    'stock_correction'=>$request->input('stock_correction'),
                    'b_staff'=>$request->input('b_staff'),
                    'terms_of_trade'=>$request->input('terms_of_trade'),
                    'date_of_order'=>$request->input('date_of_order'),
                    'order_by'=>$request->input('order_by'),
                    'time_line_of_delivery'=>$request->input('time_line_of_delivery'),
                    'stock_date'=>$request->input('stock_date'),
                    'end_date'=>$request->input('end_date'),
                    'pt_file'=>$request->input('pt_file'),
                    'marketing_exec_name'=>$request->input('marketing_exec_name'),
                    'marketing_exec_no'=>$request->input('marketing_exec_no'),
                    'weight_of_parcel'=>$request->input('weight_of_parcel'),
                    'status_parcel_received'=>$request->input('status_parcel_received'),
                    'quantity'=>$request->input('quantity'),
                    'purchase_cost'=>$request->input('purchase_cost'),
                    'size'=>$request->input('size'),
                    'color'=>$request->input('color'),
                    'product'=>$request->input('product'),
                    'remark'=>$request->input('remark'),
                    'images'=>json_encode($imagearray),
                    'created_at'=>now()
                ]);

                $deleteimages = DB::table('snapshot')->truncate();

                if($insert)
                {
                    return redirect('purchase-order')->with('alert-success','data is inserted successfully');  
                }
                else
                {
                    return redirect('purchase-order')->with('alert-danger','data cannot be inserted ');  
                }    
                
            }
            else
            {
                    if(Session::get('username')=='')
                    return redirect('warehouse')->with('status',"Please login First");
               
            }
        } 
        catch(QueryException $e)
        {
            activity()->log($e->getMessage());
            return Redirect::to('purchase-order')->with('alert-danger',"Database Query Error! [".$e->getMessage()." ]");
        }
        catch(Exception $e)
        {
            activity()->log($e->getMessage());
            return Redirect::to('purchase-order')->with('alert-danger',$e->getMessage());
        }             
        
    }

}
