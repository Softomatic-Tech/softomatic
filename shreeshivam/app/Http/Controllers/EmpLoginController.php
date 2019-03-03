<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\Cache;
use App\Http\Controllers\Cookie;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
date_default_timezone_set('Asia/Kolkata');

class EmpLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => 'store']);
    }


    public function showlogin(Request $request){

        if(Session::has('username'))
           return Redirect::to('/mobile-dashboard');
        else
            return view('emp-login');
    }
    public function login(Request $request){ 

        $rules = array(
        'username'    => 'required|email',
        'password' => 'required|min:3' 
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('employee-login')
            ->withErrors($validator) 
            ->withInput(Input::except('password')); 
        } 
        else {
           
            if(Auth::attempt(['email' => $request->username, 'password' => $request->password])){ 
                $user = Auth::user(); 
                $username = $user->name;
                $email = $user->email;

                try
                {
                $role = DB::table('role')->where('role_id',$user->role_id)->value('role');
                $status = DB::table('emp')->where('email',$email)->value('status') ;
                    if($role!='admin' && $status!='active')
                    {
                         return redirect()->back()->with('status','User not active.');
                    }
                $desig = DB::table('emp')->where('email',$email)->value('designation'); 
                $designation = DB::table('designation')->where('id',$desig)->value('designation');          
                Session::put('username',$username);
                Session::put('useremail',$email);
                Session::put('role','employee');
                Session::put('user_id',$user->id);
                Session::put('designation',$designation);
                activity()->log('user logged in');
                return Redirect::to('mobile-dashboard');
                }
                catch(QueryException $e)
                {
                    return redirect()->back()->with('failure',"Database Query Error! [".$e->getMessage()." ]");
                }
                catch(Exception $e)
                {
                    return redirect()->back()->with('alert-danger',$e->getMessage());
                }
                
            }
            else {        
                return redirect()->back()->with('status','Invalid Username or Password.');
            }

        }
    }
}
