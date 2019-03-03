<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\File;
use App\Exception;
use \Illuminate\Database\QueryException;
date_default_timezone_set('Asia/Kolkata');

class AccountController extends Controller
{
    public function index(){
        if(Session::get('username')!='')
        {
            activity()->log('Loading User Details '.Session::get('user_id').' ');

            try
            {
                $uid= session('user_id') ;
                $user= DB::table('users')->where('id',$uid)->get();
                if(session('role')=='employee')
                    return view('mobile_account',compact('user'));
                else
                    return view('account',compact('user'));
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

    // public function mobile_index(){
         
    //     if(Session::get('username')!='')
    //     {
    //         activity()->log('Loading User Details '.Session::get('user_id').' ');

    //         try{
    //             $uid= session('user_id') ;
    //             $user= DB::table('users')->where('id',$uid)->get();
     
    //         }
    //         catch(QueryException $e)
    //         {
    //             activity()->log($e->getMessage());
    //             return redirect()->back()->with('alert-danger','Database Query Exception! ['.$e->getMessage().' ]');
    //         }
    //         catch(Exception $e)
    //         {
    //             activity()->log($e->getMessage());
    //             return redirect()->back()->with('alert-danger',$e->getMessage());
    //         }

    //         // $last_insert_id = DB::table('emp')->orderBy('id','desc')->limit('1','1')->value('id');
    //         // $last_insert_id++;

    //         return view('mobile_account',compact('user'));
    //     }
    //     else
    //         return redirect('/')->with('status',"Please login First");
    // }


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


}
