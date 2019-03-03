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

use App\Exception;
use \Illuminate\Database\QueryException;
date_default_timezone_set('Asia/Kolkata');

/*use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;*/

class LoginController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', ['only' => 'store']);
    }


    public function showlogin(Request $request){

        if(Session::has('username'))
           {
           return Redirect::to('/dashboard');}
        else
            return view('login');
    }
    public function show_emp_login(Request $request){

        if(Session::has('username'))
           return Redirect::to('/mobile-dashboard');
        else
            return view('emp-login');
    }

    public function login(Request $request){

        $rules = array(
        'username'    => 'required|email', // make sure the email is an actual email
        'password' => 'required|min:3' // password can only be alphanumeric and has to be greater than 3 characters
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('/')
            ->withErrors($validator) // send back all errors to the login form
            ->withInput(Input::except('password')); // send back the input (not the password) so that we can repopulate the form
        } 
        else {
            // create our user data for the authentication
            // $userdata = array(
            // 'email'     => Input::get('username'),
            // 'password'  => Input::get('password')
            // );

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
                    if($role!='admin' && $status!='active')
                    {
                         return redirect()->back()->with('status','User not active.');
                    }    
                    $desig = DB::table('emp')->where('email',$email)->value('designation'); 
                    $designation = DB::table('designation')->where('id',$desig)->value('designation'); 
                    // $role = $user->role;
                    Session::put('username',$username);
                    Session::put('useremail',$email);
                    Session::put('role',$role);
                    Session::put('designation',$designation);
                    Session::put('user_id',$user->id);
                    if($role=='admin' || $role=='hr')
                    {
                        $notification_list = DB::table('notification')->where('status','active')->where('notification_status','!=','Accepted')->Where('notification_status','!=','Rejected')->get();

                        Session::put('notification',sizeof($notification_list));
                        
                        Session::put('notification_list',$notification_list);
                    }
                    else
                    {
                       $notification_list = DB::table('notification')->where('status','active')->where('requester_id',Session::get('user_id'))->where('user_id','!=',Session::get('user_id'))->get();

                        Session::put('notification',sizeof($notification_list));
                        /*Session::put('notification',$notification);

                        $notification_list = DB::table('notification')->where('status','active')->where('user_id',Session::get('user_id'))->get();*/
                        Session::put('notification_list',$notification_list);
                    }

                    activity()->log('user logged in');
                    if($role=="purchase admin")
                    {
                        return Redirect::to('purchase_admin_dashboard');
                    }
                    else
                    {
                    return Redirect::to('dashboard');
                    }
                  //  return Redirect::to('dashboard');
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
    public function emp_login(Request $request){ 

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
               if($role=='purchase admin')
                {
                   
                    return Redirect::to('purchase_admin_dashboard');  
                }
                else
                {
                return Redirect::to('mobile-dashboard');
                }
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
        if($role=='employee')
            return redirect('employee-login')->withCookie(\Cookie::forget('laravel_token'))->with('action','Successfully Logout');
        else
            return redirect('/')->withCookie(\Cookie::forget('laravel_token'))->with('action','Successfully Logout');
    }
}

?>
