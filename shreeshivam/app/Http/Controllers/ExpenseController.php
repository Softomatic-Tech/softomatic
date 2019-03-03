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

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get_expense()
    {
        if(Session::get('username')!='' && (session('role')=='admin' || session('role')=='hr'))
        {
            activity()->log('Fetching designation ');
            try
            {
                $expenses = DB::table('create_expense')
                                ->orderby('id','desc')
                                ->get();
                return view('expense',compact('expenses'));
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
     public function create_expense(Request $request)
	{
        try
        {
            if(Session::get('username')!='')
            {
        	    $expense_title=$request->input('expense_title');
                $amount=$request->input('amount');
                $on_date=$request->input('on_date');
                $description=$request->input('description');
        		
        		$exp=DB::table('create_expense')->insert(
        		['expense_title' => $expense_title ,'amount'=>$amount ,'on_date'=>$on_date,'description'=>$description ,'created_at' =>now()]
        		);
        	
        		 if($exp)
    			{
        			activity()->log('expense '.$exp.' Created Successfully');
        		    return redirect('expense')->with('alert-success','Expense created');
    			}
        		else
    			{
        			activity()->log('expense '.$exp.' cannot be created');
        			return redirect('expense')->with('alert-danger','Expense cannot be created!');
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
    public function update_expense(Request $request)
    {
        try
        {
            if(Session::get('username')!='')
            {
        		$id=$request->input('id');
        		$expense_title=$request->input('expense_title');
                $amount=$request->input('amount');
                $on_date=$request->input('on_date');
                $description=$request->input('description');
                
        		$exp=DB::table('create_expense')
        			  ->where('id', $id)
        			  ->update(['expense_title' => $expense_title , 'amount' => $amount ,'on_date' =>$on_date ,'description' =>$description ,'updated_at' =>Carbon::now()]);
        	
        		if($exp)
    			{
        			activity()->log('Expense with id '.$id.' Updated Successfully');
        			return redirect('expense')->with('alert-success','Expense Updated Successfully!');
    			}
        		else
    			{
        			activity()->log('Expense with id '.$id.' cannot be updated');
        			return redirect('expense')->with('alert-danger','Expense cannot be updated!');
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
    
    public function delete_expense(Request $request)
	{
        try
        {
            if(Session::get('username')!='')
            {
            	$id=$request->input('dept_id');
            	$exp_delete=DB::table("create_expense")->delete($id);
            	if($exp_delete)
            	{
            		activity()->log('Expense with id '.$id.' Deleted');
                    return redirect('expense')->with('alert-success','Expense Deleted Successfully!');
            		
            	}
            	else
            	{
            		activity()->log('Expense with id '.$id.'  cannot be Deleted');
                   
                    return redirect()->back()->with('alert-danger','Expense cannot be deleted!');
            		 
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
    /*public function index()
    {
        try
        {
              //return Expense::all();orderBy('created_at','desc');
            $expenses=Expense::orderBy('id','desc')->paginate(10);
            return view('expense')->with('expenses',$expenses);
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
    }*/

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    /*public function create()
    {   
        return view('expense');
    }*/

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        try
        {
            if(Session::get('username')!='')
            {
                $expense =new Expense;
                $expense->expense_title=$request->input('expense_title');
                $expense->amount=$request->input('amount');
                $expense->on_date=$request->input('on_date');
                $expense->description=$request->input('description');
                $expense->save();  
                return redirect('expense')->with('alert-success','Expense created');
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*public function show($id)
    {
        
    }*/

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(Session::get('username')!='')
        {
            return  $expense = Expense::find($id);
            return view('expense')->with('expense', $expense);
        }
        else
        {
            return redirect('/')->with('status',"Please login First");
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try
        {
            if(Session::get('username')!='')
            {
                $expense =Expense::find($id);
                $expense->title=$request->input('expense_title_u');
                $expense->title=$request->input('amount_u');
                $expense->title=$request->input('on_date_u');
                $expense->title=$request->input('description_u');
                $post->save();
                return redirect('expense')->with('alert-success','Expense Updated');
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
