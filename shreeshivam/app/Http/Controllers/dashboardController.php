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

class dashboardController extends Controller
{
    public function index(){
        if(Session::get('username')!='')
        {
            activity()->log('Loading Dashboard for user with id '.Session::get('user_id').' ');

            try{
            $emp= DB::table('emp')->count();
            $notice= DB::table('notice_board')->get();        
           
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

            // $last_insert_id = DB::table('emp')->orderBy('id','desc')->limit('1','1')->value('id');
            // $last_insert_id++;

            return view('dashboard',compact('emp','notice'));
        }
        else
            return redirect('/')->with('status',"Please login First");
    }
}
