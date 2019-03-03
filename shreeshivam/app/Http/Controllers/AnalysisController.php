<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use DB;
use App\Exception;
use \Illuminate\Database\QueryException;
date_default_timezone_set('Asia/Kolkata');

class AnalysisController extends Controller
{ 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get_zone()
    {
        if(Session::get('username')!='' && (session('role')=='admin' || session('role')=='hr'))
        {
            activity()->log('Fetching designation ');
            try
            {
                $branch = DB::table('branch')->get();
                $emp = DB::table('emp')->where('genesis_id','!=','')->get();
                return view('zoneanalysis',compact('emp','branch'));
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
            return redirect('dashboard')->with('alert-danger',"Only admin and HR can add employee");
        }
    }

     public function barchart(Request $request)
    { 
        if(Session::get('username')!='' && (session('role')=='admin' || session('role')=='hr'))
        {
            activity()->log('Fetching designation ');
            try
            { 
                $branch_p='';
                $sales=array();
                $branch_n=array();
              
                $branch_n[]='mon';
                
                $branch = DB::table('branch')->get();
                $zone=$request->input('zone');
                $month=$request->input('month');
                $users = DB::table('sales')
                      ->whereIn('branch' , $zone)->groupBy('branch')->wheremonth('bill_date','=',$month)
                     ->get();
                foreach($users as $user){
                  
                  $mrp= DB::table('sales')->where('branch','=', $user->branch)->sum('net_amount');  
                   
                   if($user->branch==1)
                   {
                       $branch_n[]='Corporate' ;
                   }
                   elseif($user->branch==2)
                   {
                       $branch_n[]='Raipur' ;
                   }
                   elseif($user->branch==3)
                   {
                       $branch_n[]='Bilaspur' ;
                   }
                   elseif($user->branch==4)
                   {
                       $branch_n[]='Durg' ;
                   }
                   elseif($user->branch==5)
                   {
                       $branch_n[]='Nagpur' ;
                   }
                   elseif($user->branch==6)
                   {
                       $branch_n[]='Jabalpur' ;
                   }
                   elseif($user->branch==7)
                   {
                       $branch_n[]='Bhopal' ;
                   }
                     $sales[]=(int)$mrp;
                                                               
                }

                    $sales1=array('2018/09');
                    $sales2=array('2018/09');
                    $sales4=array('2018/10');
                    $sales5=NULL;
                    $sales3=NULL;
                    $sales=array_merge($sales1,$sales);
 
                    return $out=[
                        $branch_n , 
                        $sales,$sales3,$sales5  ];
    
                return view('expense',compact('branch','output'));
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
            return redirect('dashboard')->with('alert-danger',"Only admin and HR can add employee");
        }
    }

    public function employee_comp(Request $request)
    { 
        if(Session::get('username')!='' && (session('role')=='admin' || session('role')=='hr'))
        {
            activity()->log('Fetching designation ');
            try
            { 
                $branch_p='';
                $sales=array();
                $branch_n=array();
                $branch_n[]='Month';            
                $branch = DB::table('branch')->get();
                $month=$request->input('month');
                $empid=$request->input('zone');
                $users =DB::table('emp')->join('sales','emp.genesis_id', '=' , 'sales.sales_person_id')->select('sales.mrp','sales.branch','emp.first_name','emp.middle_name','emp.last_name','sales.sales_person_id')->whereIn('emp.id' , $empid)->groupBy('sales.sales_person_id')
                ->wheremonth('bill_date','=',$month)->get();
                
                foreach($users as $user){
                  
                    $mrp= DB::table('sales')->where('sales_person_id','=',$user->sales_person_id)->sum('mrp');  
                    $branch_n[]=$user->first_name.' '.$user->middle_name.' '.$user->last_name; 
                    $sales[]= (int)$mrp;                                                             
                }

              $sales1=array('sep');
             
              $sales=array_merge($sales1,$sales);
                     
               return $out=[
                    $branch_n , 
                    $sales  ];
     
                return $out;
                return view('expense',compact('branch','output'));
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
            return redirect('dashboard')->with('alert-danger',"Only admin and HR can add employee");
        }
    }

     public function get_employee()
    {
        if(Session::get('username')!='' && (session('role')=='admin' || session('role')=='hr'))
        {
            activity()->log('Fetching designation ');
            try
            {
                $branch = DB::table('branch')->get();         
                $emp = DB::table('emp')->where('genesis_id','!=',' ')->get();
                return view('employeeanalysis',compact('emp','branch'));
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
            return redirect('dashboard')->with('alert-danger',"Only admin and HR can add employee");
        }
    }

    public function get_brand()
    {
        if(Session::get('username')!='' && (session('role')=='admin' || session('role')=='hr'))
        {
            activity()->log('Fetching designation ');
            try
            {
                $branch = DB::table('branch')->get();
                 $brand=  DB::table('sales')->groupBy('category2')->get();
                $emp = DB::table('emp')->where('genesis_id','!=',' ')->get();
                return view('brandanalysis',compact('emp','branch','brand'));
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
            return redirect('dashboard')->with('alert-danger',"Only admin and HR can add employee");
        }
    }

    public function piechart(Request $request)
    { 
        if(Session::get('username')!='' && (session('role')=='admin' || session('role')=='hr'))
        {
            activity()->log('Fetching designation ');
            try
            { 
                $branch_p='';
                $final=array();
                $sales=array();
                $branch_n=array();
                $branch_n[]='Brand';   
                $sales1=array( ); 
                $sales1[]='Sale';
                $final[]=['Brand','Sale'];
                $brand=$request->input('brand');
                $month=$request->input('month');
                $empid=$request->input('zone');
                $empid123=$request->input('month');
                 
                $users =DB::table('sales')->whereIn('branch' , $empid)->whereIn('category2' , $brand)->groupBy('category2')->wheremonth('bill_date','=',$month)->get();
                              
                foreach($users as $user){    

                //  return $user->branch;
                $mrp= DB::table('sales')->where('category2','=', $user->category2)->where('branch',$user->branch)->sum('mrp');  
                $final[]=[$user->category2 ,(int)$mrp ]; 
                    // $final[]= (int)$mrp;                                                             
                }
                return $final;
              $sales=array_merge($sales1,$sales);
             
               return $out=[
                    $branch_n , 
                    $sales  ];
     
                
                return view('expense',compact('branch','output'));
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
            return redirect('dashboard')->with('alert-danger',"Only admin and HR can add employee");
        }
    }

}
