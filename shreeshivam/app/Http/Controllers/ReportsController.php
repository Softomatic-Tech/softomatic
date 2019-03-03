<?php

namespace App\Http\Controllers;

use App\emp;
use App\User;
use App\salary;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\File;
use App\Exception;
use \Illuminate\Database\QueryException;


class ReportsController extends Controller
{
     public function index()
    {
      if(Session::get('username')!='' && (session('role')=='admin' || session('role')=='hr'))
      {
        try{
          activity()->log('Loading Reports page for User with id '.Session::get('user_id').' ');

          $branch = DB::table('branch')->get();
         
          return view('report', compact('branch' ));
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
      {
          if(Session::get('username')=='')
              return redirect('/')->with('status',"Please login First");
          else
          return redirect('dashboard')->with('alert-danger',"Only admin and HR can access payslip page");
      }
    }
    
    public function provision(Request $request)
    {
        $type = 'Provision Report';
        $branch=$request->input('branch');
        $month=$request->input('month');
        $month_name = date("F", mktime(0, 0, 0, $month, 10));
        $year= date('Y');
        $flag=0;
        $employee =DB::table('emp')->join('payslip_master','emp.id', '=' , 'payslip_master.emp_id')->select('emp.first_name','payslip_master.tds','emp.middle_name','emp.last_name','emp.branch_location_id','payslip_master.gross_salary','payslip_master.epf','payslip_master.esic','payslip_master.professional_tax','payslip_master.net_payable','payslip_master.net_salary')->where('emp.status','active')
         ->where('emp.branch_location_id','=',$branch)->where('payslip_master.month','=',$month)->get();
        if(sizeof($employee)==0)
        {
            $flag=0;
        }
        else
        {
            $flag=1;
        }
        $value='';
        $i=1;
        $table='<thead>
                    <tr class="bg-orange">
                        <th>#</th>
                        <th>Name</th>
                        <th>Gross Sal</th>
                        <th>EPF Ded</th>
                        <th>ESIC Ded</th>
                        <th>TDS Ded </th>
                        <th>Labour Tax Ded </th>
                        <th>Professional Tax </th>
                        <th>Staff Salary ADV Payable </th>
                        <th>Narration</th>
                        <th>Narration</th>
                        <th>Narration</th>
                        <th>Narration</th>
                        <th>Narration</th>
                        <th>Narration</th>
                    </tr>
                </thead>
                <tbody>
              
                ';
                foreach($employee as $emp){
                    $table.=' <tr> <td>'.$i.'</td>';
                    $table.='  <td>'.$emp->first_name.' '.$emp->middle_name.' '.$emp->last_name.'</td>';
                    $table.='  <td>'.$emp->gross_salary.'</td>';
                    $table.='  <td>'.$emp->epf.'</td>';
                    $table.='  <td>'.$emp->esic.'</td>';
                    $table.='  <td>'.$emp->tds.'</td>';
                    $table.='  <td> 0 </td>';
                    $table.='  <td>'.$emp->professional_tax.'</td>';
                    $table.='  <td>'.$emp->net_salary.'</td>';
                    $table.='<td> Being salary expanse gross for the month of  '.$month_name.'  '. $year.' of '.$emp->first_name.' '.$emp->middle_name.' '.$emp->last_name.'</td>';
                    $table.='<td> Being EPF deduction for the month of  '.$month_name.'  '. $year.' of '.$emp->first_name.' '.$emp->middle_name.' '.$emp->last_name.'</td>';
                    $table.='<td> Being ESIC deduction for the month of  '.$month_name.'  '. $year.' of '.$emp->first_name.' '.$emp->middle_name.' '.$emp->last_name.'</td>';
                    $table.='<td> Being TDS deduction for the month of  '.$month_name.'  '. $year.' of '.$emp->first_name.' '.$emp->middle_name.' '.$emp->last_name.'</td>';
                    $table.='<td> Being Labour tax deduction for the month of  '.$month_name.'  '. $year.' of '.$emp->first_name.' '.$emp->middle_name.' '.$emp->last_name.'</td>';
                    $table.='<td> Being salary provision for the month of  '.$month_name.'  '. $year.' of '.$emp->first_name.'</td></tr>';
                    $i++;
                }
                $table.='</tbody>';
                
         return redirect('various-reports ')->with(['table'=>$table,'flag'=>$flag]);   
    }

     public function current_bank(Request $request)
    {
        
        $branch=$request->input('branch1');
        $month=$request->input('month1');
        $month_name = date("F", mktime(0, 0, 0, $month, 10));
        $year= date('Y');
        $flag=0;
        $current_bank = DB::table('bank')->where('status','current')->where('branch_location_id',$branch)->value('bank_name');
        $employee =DB::table('emp')->join('payslip_master','emp.id', '=' , 'payslip_master.emp_id')->rightjoin('bank_list','bank_list.id','=','emp.bank_name')->select('emp.acc_holder_name','emp.branch_location_id','emp.acc_no','emp.ifsc_code','bank_list.bank_name','emp.branch','payslip_master.net_payable')->where('emp.status','active')->where('emp.bank_name',$current_bank)->where('emp.branch_location_id','=',$branch)->where('payslip_master.month','=',$month)->get();

        if(sizeof($employee)==0)
        {
            $flag=0;
        }
        else
        {
            $flag=1;
        }

        $value='';
        $i=1;
        $table='<thead>
                    <tr class="bg-orange">
                        <th>#</th>
                        <th>Name</th>
                        <th>Account no</th>
                        <th>IFSC Code</th>
                        <th>Bank Name</th>
                        <th>Branch </th>
                        <th>Chq No </th>
                        <th>Amount </th>                   
                    </tr>
                </thead>
                <tbody>
              
                ';
                foreach($employee as $emp){
                    $table.=' <tr> <td>'.$i.'</td>';
                    $table.='  <td>'.$emp->acc_holder_name.'</td>';
                    $table.='  <td>'.$emp->acc_no.'</td>';
                    $table.='  <td>'.$emp->ifsc_code.'</td>';
                    $table.='  <td>'.$emp->bank_name.'</td>';
                    $table.='  <td>'.$emp->branch.'</td>';
                    $table.='  <td> 0 </td>';
                    $table.='  <td>'.$emp->net_payable.'</td></tr>';
                    $i++;
                }
                $table.='</tbody>';
                
         return redirect('various-reports')->with(['table'=>$table,'flag'=>$flag]);   
    }

    public function other_bank(Request $request)
    {
        
        $branch=$request->input('branch1');
        $month=$request->input('month1');
        $month_name = date("F", mktime(0, 0, 0, $month, 10));
        $year= date('Y');
        $flag=0;
        $current_bank = DB::table('bank')->where('status','current')->where('branch_location_id','=', $branch)->value('bank_name');
        // return $emp_list_cur = DB::table('emp')->where('bank_name', '=' , $current_bank)->value('id');
        
        $employee =DB::table('emp')->join('payslip_master','emp.id', '=' , 'payslip_master.emp_id')->rightjoin('bank_list','bank_list.id','=','emp.bank_name')->select('emp.acc_holder_name','emp.branch_location_id','emp.acc_no','emp.ifsc_code','emp.branch','payslip_master.net_payable','bank_list.bank_name','bank_list.id','emp.id')->where('emp.status','active')->where('emp.bank_name','!=',$current_bank)->where('emp.branch_location_id','=',$branch)->where('payslip_master.month','=',$month)->get();
        if(sizeof($employee)==0)
        {
            $flag=0;
        }
        else
        {
            $flag=1;
        }
        $value='';
        $i=1;
        $table='<thead>
                    <tr class="bg-orange">
                        <th>#</th>
                        <th>Name</th>
                        <th>Account no</th>
                        <th>IFSC Code</th>
                        <th>Bank Name</th>
                        <th>Branch </th>
                        <th>Chq No </th>
                        <th>Amount </th>                   
                    </tr>
                </thead>
                <tbody>
              
                ';
                foreach($employee as $emp){
                    $table.=' <tr> <td>'.$i.'</td>';
                    $table.='  <td>'.$emp->acc_holder_name.'</td>';
                    $table.='  <td>'.$emp->acc_no.'</td>';
                    $table.='  <td>'.$emp->ifsc_code.'</td>';
                    $table.='  <td>'.$emp->bank_name.'</td>';
                    $table.='  <td>'.$emp->branch.'</td>';
                    $table.='  <td> 0 </td>';
                    $table.='  <td>'.$emp->net_payable.'</td></tr>';
                    $i++;
                }
            
                $table.='</tbody>';
                
         return redirect('various-reports')->with(['table'=>$table,'flag'=>$flag]);   
    }

    public function epf(Request $request)
    {
        
        $branch=$request->input('branch3');
        $month=$request->input('month3');
        $flag = 0;
          $mymonth = date('M',strtotime(date('Y-'.$month.'-d')));
          $year = date('Y');
          $first_sunday = date('d', strtotime('First Sunday Of '.$mymonth.' '.$year));
          $number_of_sundays=1;
          $total_days=cal_days_in_month(CAL_GREGORIAN, $month, date('Y'));
          $days = $total_days-$first_sunday;
          while(true)
          {
            $days=$days-7;
            if($days>=0)
            $number_of_sundays++;
          else
            break;
          }
          $holidays = DB::table('holidays')->whereMonth('date',$month)->count();
          $holidays = $number_of_sundays+$holidays;
          $total_working_days=$total_days-$holidays;


        // $month_name = date("F", mktime(0, 0, 0, $month, 10));
        // $year= date('Y');
        $employee =DB::table('emp')->join('payslip_master','emp.id', '=' , 'payslip_master.emp_id')->select('emp.first_name','emp.middle_name','emp.last_name','emp.uan_number','payslip_master.net_payable','payslip_master.gross_salary','payslip_master.attendance_based_sal','payslip_master.epf','payslip_master.attendance')->where('emp.status','active')
         ->where('emp.branch_location_id','=',$branch)->where('payslip_master.month','=',$month)->where('emp.epf_option','=','1')->get();
         if(sizeof($employee)==0)
        {
            $flag=0;
        }
        else
        {
            $flag=1;
        }
        $value='';
        $i=1;
        $table='<thead>
                    <tr class="bg-orange">
                        <th>#</th>
                        <th>UAN</th>
                        <th>MEMBER NAME</th>
                        <th>GROSS WAGES</th>
                        <th>EPF WAGES</th>
                        <th>EPS WAGES  </th>
                        <th>EDLI WAGES </th>
                        <th>EPF CONTRI REMITTED </th>
                        <th>EPS CONTRI REMITTED </th>
                        <th>EPF EPS CONTRI REMITTED </th> 
                        <th>NCP DAYS </th>                    
                    </tr>
                </thead>
            <tbody>
              
                ';
                foreach($employee as $emp){
                    $eps= $emp->attendance_based_sal;
                    $eps_f= round($eps*8.33/100);
                    $epf_ecs=$emp->epf-$eps_f;
                    $table.=' <tr> <td>'.$i.'</td>';
                    $table.='  <td>'.$emp->uan_number.'</td>';
                    $table.='  <td>'.$emp->first_name.' '.$emp->middle_name.' '.$emp->last_name.'</td>';
                    $table.='  <td>'.$emp->gross_salary.'</td>';
                    $table.='  <td>'.$emp->attendance_based_sal.'</td>';
                    $table.='  <td>'.$emp->attendance_based_sal.'</td>';
                    $table.='  <td>'.$emp->attendance_based_sal.'</td>';
                    $table.='  <td>'.$emp->epf.'</td>';
                    $table.='  <td>'.$eps_f.'</td>';
                    $table.='  <td>'.$epf_ecs.' </td>';
                    $table.='  <td>'.floor($total_working_days-$emp->attendance).'</td></tr>';
                    $i++;
                }
                $table.='</tbody>';
                
         return redirect('various-reports')->with(['table'=>$table,'flag'=>$flag]);   
    }

    public function esic(Request $request)
    {
        
        $branch=$request->input('branch2');
        $month=$request->input('month2');
        $flag=0;
        $employee =DB::table('emp')->join('payslip_master','emp.id', '=' , 'payslip_master.emp_id')->select('emp.first_name','emp.middle_name','emp.last_name','emp.esic_number','emp.reason_code_0','emp.last_working_day','payslip_master.net_payable','payslip_master.gross_salary','payslip_master.esic','payslip_master.attendance')->where('emp.status','active')
         ->where('emp.branch_location_id','=',$branch)->where('payslip_master.month','=',$month)->where('emp.esic_option','=','1')->get();
        if(sizeof($employee)==0)
        {
            $flag=0;
        }
        else
        {
            $flag=1;
        }

        $value='';
        $i=1;
        $table='<thead>
                    <tr class="bg-orange">
                        <th>#</th>
                        <th>IP NUMBER</th>
                        <th>IP NAME</th>
                        <th>NO OF DAYS FOR WHICH WAGES PAID/PAYABLE DURING MONTH</th>
                        <th>TOTAL MONTHLY WAGES</th>
                        <th>REASON FOR CODE 0  </th>
                        <th>LAST WORKING DAY </th>
                                           
                    </tr>
                </thead>
            <tbody>
              
                ';
                foreach($employee as $emp){
                    
                    $table.=' <tr> <td>'.$i.'</td>';
                    $table.='  <td>'.$emp->esic_number.'</td>';
                    $table.='  <td>'.$emp->first_name.' '.$emp->middle_name.' '.$emp->last_name.'</td>';
                    $table.='  <td>'.floor($emp->attendance).'</td>';
                    $table.='  <td>'.$emp->gross_salary.'</td>';
                    $table.='  <td>'.$emp->reason_code_0.'</td>';
                    $table.='  <td>'.$emp->last_working_day.'</td></tr>';
                    $i++;
                }
                $table.='</tbody>';
                
         return redirect('various-reports')->with(['table'=>$table,'flag'=>$flag]);   
    }
}
