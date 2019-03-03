<?php
namespace App\Http\Controllers;
use App\emp;
use App\User;
use App\salary;
use App\Http\Traits\SalaryTraits;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\File;
use App\Exception;
use \Illuminate\Database\QueryException;

ini_set('max_execution_time', 3000);
date_default_timezone_set('Asia/Kolkata');



class PayslipController extends Controller
{   
    use SalaryTraits;
    public function index()
    {
        // Log::info("View Generate bill page by ".session('username'));

      if(Session::get('username')!='' && (session('role')=='admin' || session('role')=='hr'))
      {
        try{
          activity()->log('Loading Create Payslip page for User with id '.Session::get('user_id').' ');

          $branch = DB::table('branch')->get();
         
          return view('create-payslip', compact('branch'));
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

    public function get_payslip_list()
    {
      if(Session::get('username')!='' && (session('role')=='admin' || session('role')=='hr'))
      {
        try{
          activity()->log('Loading Create Payslip page for User with id '.Session::get('user_id').' ');
          $branch = DB::table('branch')->get();
         
          return view('payslip-list', compact('branch'));
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

    public function get_final_salary(Request $request)
    {
        if(Session::get('username')!='' && (session('role')=='admin' || session('role')=='hr'))
            {
            try{
            $branch=$request->branch;
            $month=$request->month;
            $salary=json_encode(DB::table('payslip_master')->leftjoin('emp','emp.id','=','payslip_master.emp_id')->join('payslip_latest','payslip_master.id', '=' ,'payslip_latest.master_id')->select('emp.first_name','emp.middle_name','emp.last_name','payslip_master.actual_attendance','payslip_master.attendance','payslip_master.over_time','payslip_master.salary','payslip_master.basic'
            ,'payslip_master.hra','payslip_master.other','payslip_master.emp_id','payslip_master.incentive','payslip_master.arrear','payslip_master.gross_salary','payslip_master.exgratia','payslip_master.bonus','payslip_master.epf','payslip_master.esic','payslip_master.tds','payslip_master.advance','payslip_master.professional_tax','payslip_master.net_salary','payslip_master.other_deduction','payslip_master.remark','payslip_master.attendance_based_sal','payslip_master.other_addition','payslip_master.net_payable')->where('payslip_latest.month','=',$month)->where('emp.branch_location_id','=',$branch)->get());
            
               
          
          activity()->log('Sending Generated Salary Details To payslip list page');
          return view('payslip-list-data',compact('salary'));

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
          return redirect('dashboard')->with('alert-danger',"Only admin and HR can access payslip");
      }
    }


    public function salary(Request $request)
    {
        

      if(Session::get('username')!='' && (session('role')=='admin' || session('role')=='hr'))
      {
        try{
            activity()->log('Loading Salary Details ');
            $branch_id=$request->id;    
             $month=$request->month;
            $year=$request->year;
            
            // Get Month details function
            $monthdetail = json_decode($this->getMonthDetails($month),true);
            // End Month details function
            $holidays = $monthdetail['holidays'];
            $total_working_days = $monthdetail['total_working_days'];
            $emp=DB::table('emp')->where('status','active')->where('branch_location_id',$branch_id)->orderBy('id','asc')->get();
            // activity()->log('Trying to create salary for the month of '.$month); 
           foreach($emp as $emps)
            {
             
            
             $count_payslip=DB::table('payslip_master')->where('emp_id',$emps->id)->where('month',$month)->count();
             if($count_payslip>0)
             {  
                $flag=false;
                return view('payslip-withdata',compact('flag'));
             }            
                $designation=DB::table('designation')->select('designation')->where('id',$emps->designation)->value('designation');
                $department=DB::table('department')->select('department_name')->where('id',$emps->department)->value('department_name');
                $att_salary=0;
                $attendance_based_basic=0;
                $net_payable=0;
                $other_deduction=0;
                $emp_target=array();
                $target=array();
                $aging=array();
                $team_target=array(); 
                $incentive=0;
                $total_incentive=0;
                $team_id='';
                $team_name='';
                $aging_bonus=0;
                $hra=0;
                $other=0;
                $deduction_per_month=0;
                $tax_remark='';
                $tax=0;
                $team_achieved=0;
                $advance_remark='';
                $advance_left=0;
                $target_count=0;
                $aging_array=array();
                $incentive_target='';
                $advance_deduction=0;
                $advance_id=0;
                $advance_left=0;
                $full_day = 0;
                $half_day = 0;
                $full_day_salary=0;
                $half_day_salary=0;
                $overtime_day = 0;
                $overtime_half_day = 0;
                $overtime_days=0;
                $overtime_hours=0;
                $overtime_salary=0;
                $overtime = 0;
                $overmin=0;
                $advance=0;
                $arrear=0;
                $other_bonus=0;
                $exgratia=0;
                $tds=0;
                $epf_amount=0;
                $esic_amount=0;  
                $gross_salary=0;              
                $team=DB::table('team')->get();
                $attendance=0;

 // Salary Calculation Starts--------------------------------------------------------------------------------

                $salary=DB::table('salary')->select('salary')->where('id',$emps->salary_id)->value('salary');
                $sal=json_decode($salary,true);             
                $emp_salary=$sal['emp_salary']['salary'];
                $emp_basic_salary = $sal['emp_salary']['basic'];
              
              if($sal['emp_salary']['basic']==0 || $sal['emp_salary']['basic']=='')
              {
                 $sal['emp_salary']['basic'] = 0;
                $emp_basic_salary=$sal['emp_salary']['salary'];
                
              }

                $workinghour = json_decode($this->workingHour(),true);
                $full_day_from = $workinghour['full_day_from'];
                $full_day_to =  $workinghour['full_day_to'];
                $half_day_from = $workinghour['half_day_from'];
                $half_day_to = $workinghour['half_day_to'];

                $attendance_array = json_decode($this->getAttendance($month,$year,$emps->id,$full_day_from,$full_day_to,$half_day_from,$half_day_to,$full_day,$half_day,$total_working_days,$overtime_day,$overtime_half_day,$department),true);
                $full_day = $attendance_array['full_day'];
                $half_day = $attendance_array['half_day'];
                $overtime = $attendance_array['overtime'];
                $overmin = $attendance_array['overmin'];
                $overtime_day = $attendance_array['overtime_day'];
                $overtime_days = $attendance_array['overtime_days'];
                $overtime_hours = $attendance_array['overtime_hours']; 
                $overtime_half_day=$attendance_array['overtime_half_day'];

                $emp_attendance=round(($full_day+$half_day/2),5);              
                $emp_att = floor($emp_attendance);
                $emp_att_diff =$emp_attendance-$emp_att; 
                $emp_absent=$total_working_days-$emp_attendance;
                $sal_per_day=$emp_salary/$total_working_days;
                $overtime_salary = ($overtime_day*$sal_per_day) + ($overtime_half_day*$sal_per_day/2) + $overtime_hours*($sal_per_day/8) + $overmin*($sal_per_day/(8*60));
                // last time change $overtime_salary = ($overtime_day*$sal_per_day) + ($half_day*$sal_per_day/2) + $overtime_hours*($sal_per_day/8) + $overmin*($sal_per_day/(8*60));
                $full_day_salary = $full_day*$sal_per_day;
                $half_day_salary = $half_day/2*$sal_per_day;
                $original_salary = $full_day_salary+$half_day_salary;

                $other+=$overtime_salary;
                // Log::info('overtime_salary'.' '.$overtime_salary);

                $epf_master=(array)json_encode(DB::table('epf_master')->where('branch_id',$emps->branch_location_id)->first());
                $master=json_decode($epf_master[0],true);
                $emp_epf = $master['epf'];
                $emp_esic = $master['esic'];
                $min_wages = $master['minimum_wages'];
                $epf=0;$esic=0;            
                $target_count=DB::table('target')->where('emp_id',$emps->id)->wheremonth('start_date','=',$month)->wheremonth('end_date','=',$month)->count();
                

                $other_incentive = DB::table('incentive')->where('month',$month)->where('emp_id',$emps->id)->value('incentive_amt');
                
                if($other_incentive=='')
                {
                    $other_incentive=0;
                }
            
                $total_incentive+=$other_incentive;
                $arrear = DB::table('arrear')->where('month',$month)->where('emp_id',$emps->id)->value('arrear_amt');
                // From here i am changing hole

                if($emps->epf_option==0 && $emps->esic_option==0 && $emp_att!=0)
                {
                    $other;
                    $salary_array = json_decode($this->salary_noepf_noesic($emp_attendance,$emp_att,$emp_att_diff,$overtime_salary,$sal_per_day,$half_day_salary),true); 
                    $attendance_based_salary=$salary_array['attendance_based_salary'];
                    $salary_of_round_attendance=$salary_array['salary_of_round_attendance'];
                    $attendance_based_basic=$attendance_based_salary;
                    $other+=$salary_of_round_attendance;
                    $other=$other;
                    $gross_salary=round($attendance_based_salary+$other);
                    $attendance=$emp_att;
                    
                        // Log::info("attendance_based_salary".$attendance_based_salary.' '.'salary_of_round_attendance'.' '.$salary_of_round_attendance);
                    // Log::info('Gross salary'.' '.$gross_salary);                                   
                }
                elseif($emps->epf_option==0 && $emps->esic_option==1 && $emp_att!=0)
                {

                    $salary_array = json_decode($this->salary_noepf_noesic($emp_attendance,$emp_att,$emp_att_diff,$overtime_salary,$sal_per_day,$half_day_salary),true); 
                    $attendance_based_salary=$salary_array['attendance_based_salary'];
                    $salary_of_round_attendance=$salary_array['salary_of_round_attendance'];
                    $attendance_based_basic=$attendance_based_salary;
                    $other+=$salary_of_round_attendance;
                    $other=$other;
                    $gross_salary=round($attendance_based_salary+$other);
                    $attendance=$emp_att;
                    // Log::info("attendance_based_salary".$attendance_based_salary.' '.'salary_of_round_attendance'.' '.$salary_of_round_attendance);
                    // Log::info('Gross salary'.' '.$gross_salary);
                       
                }
                elseif($emps->epf_option==1 &&  $emp_salary >= $emp_basic_salary && $emp_att!=0)
                {
                    
                    $salary_array = json_decode($this->salary_with_epf_esic_with_greatersal($emp_salary,$total_working_days,$emp_basic_salary,$hra,$emp_attendance,$emp_att,$emp_att_diff,$overtime_salary,$sal_per_day,$half_day_salary),true); 
                    $attendance_based_salary=$salary_array['attendance_based_salary'];
                    $salary_of_round_attendance=$salary_array['salary_of_round_attendance'];
                    $attendance_based_basic=$salary_array['attendance_based_basic'];
                    $hra=$salary_array['hra'];
                    $salary_diff=$salary_array['salary_diff'];
                    $attendance=$emp_att;
                    $other+=$salary_diff-$hra+$salary_of_round_attendance ;
                    $other=$other;
                    $gross_salary=round($hra+$attendance_based_basic+$other);
                   
                        // Log::info("attendance_based_salary".$attendance_based_salary.' '.'attendance_based_basic'.' '.$attendance_based_basic.'  '.'salary_of_round_attendance'.'  '.$salary_of_round_attendance.'  '.'hra'.' '.$hra.'  '.'salary diff'.' '.$salary_diff);
                        //    Log::info('Gross salary'.' '.$gross_salary.' '.$salary_diff);                      
                }
                    
                 elseif($emps->epf_option==1  && $emp_salary < $emp_basic_salary &&  $emp_basic_salary!=0 && $emp_att!=0)
                {                   
                    $salary_array = json_decode($this->salary_with_epf_esic_less_sal($emp_salary,$total_working_days,$emp_basic_salary,$emp_attendance,$emp_att,$emp_att_diff,$overtime_salary,$sal_per_day,$half_day_salary),true); 
                    $attendance_based_salary=$salary_array['attendance_based_salary'];
                    $salary_of_round_attendance=$salary_array['salary_of_round_attendance'];
                    $attendance_based_basic=$salary_array['attendance_based_basic'];
                    $manipulated_attendance=$salary_array['manipulated_attendance'];
                    $manipulated_att_sal_diff=$salary_array['manipulated_att_sal_diff']; 
                    $other+=$manipulated_att_sal_diff+ $salary_of_round_attendance;
                    $other=$other;
                    $gross_salary=round($attendance_based_basic+$other);  
                    $attendance=$manipulated_attendance; 
                        // Log::info('manipulated_attendance'.' '.$manipulated_attendance.' '."attendance_based_salary".$attendance_based_salary.' '.'attendance_based_basic'.' '.$attendance_based_basic.'  '.'salary_of_round_attendance'.'  '.$salary_of_round_attendance.' '.'$manipulated_att_sal_diff'.' '.$manipulated_att_sal_diff );
                        //  Log::info('Gross salary'.' '.$gross_salary);                      
                }
                if($emps->epf_option==1)
                {  
                    {   
                        $epf_array=json_decode($this->calculate_epf_deduction($attendance_based_basic,$emp_epf));
                        $epf=$epf_array->epf_amount_g;                                                
                        //  Log::info('epf_amount '.' '.$epf);                        
                    }
                }
                if($emps->esic_option==1)
                {   
                    {   
                        $esic_array=json_decode($this->calculate_esic_deduction($gross_salary,$emp_esic));
                        $esic=$esic_array->esic_amount_g;                                                
                        // Log::info('esic_amount '.' '.$esic);
                        
                    }
                }                          
              $advance = DB::table('advance')->where('emp_id',$emps->id)->get();
              $advance_count = sizeof($advance);

              if($advance_count>0)
              {
                $advance_array=json_decode($this->calculate_advance($advance));               
                $advance_deduction = $advance_array->advance_deduction;
                $advance_left=$advance_array->advance_left;
              }
                 /////////////////////End calculate advance////////////////////////////////////////////////////

            //Professional Tax//////
            // $emp_trans=DB::table('emp')->select('transfer')->where('id',$emps->id)->value('transfer');
			// 	if($emp_trans=='yes')
			// 	{   
			// 		return $tranfer_table=DB::table('transfer')->join('branch','transfer.transfer_to_branch','=','branch.branch')->where('transfer.emp_id','=',$emps->id)->select('branch.id')->value('branch.id');
			// 		$branch_id=$tranfer_table;
			// 	}
            activity()->log($emps->id);     
           
             $professional_tax_array = json_decode($this->getProfessionalTax($emps->gender,$emps->id,$branch_id, $emp_salary,$tax,$month,$gross_salary) ,true );
             $tax =$professional_tax_array['tax'];
            // Log::info('professional tax '.' '.$tax);
           // Professional Tax Ends///
            activity()->log( $tax );

           
            $tds = DB::table('tds')->where('emp_id',$emps->id)->where('month',$month)->where('year',$year)->value('tds_amt');
             if($tds=='' || $tds==0)
             {
                 $tds=0;
             } 
            //   $other_deduction+= $att_salary;
            
              $net_salary=round($gross_salary-($esic+$epf+$tds),2);
              if($net_salary-$tax>0)
              {
               $net_salary=$net_salary-$tax;
               if($net_salary-$advance_deduction>0)
               {
                   $net_salary=$net_salary-$advance_deduction;
               }
               else{
                   $advance_remark='Not able to pay';
               }
              }
              else{
               
                $tax_remark='Not able to pay';
            }

            $other_bonus = DB::table('bonus')->where('month',$month)->where('emp_id',$emps->id)->value('bonus_amt');
            $exgratia = DB::table('exgratia')->where('month',$month)->where('emp_id',$emps->id)->value('exgratia_amt');
            
            if($other_bonus=='')
            {
                $other_bonus=0;
            }
            if($exgratia=='')
            {
                $exgratia=0;
            }
            
            
            $net_salary+=$other_bonus+$exgratia;
            // $other_deduction;
            $net_payable= $net_salary -$other_deduction;
             
               $employee[$emps->id]=json_encode(array('emp_id'=>$emps->id,'emp_name'=>$emps->title." ".$emps->first_name." ".$emps->middle_name." ".$emps->last_name,'biometric_id'=>$emps->biometric_id
             ,'designation'=>$designation,'designation_id'=>$emps->designation,'branch'=>$emps->branch_location_id,'epf_option'=>$emps->epf_option,
                'esic_option'=>$emps->esic_option,'team_id'=>$team_id,'team_name'=>$team_name,'salary'=>$emp_salary,'basic_salary'=>$emp_basic_salary,'target'=>$target,'team_target'=>$team_target,'incentive_target'=>$incentive_target,'incentive'=>$total_incentive,'aging_bonus'=>$aging_bonus,'epf'=>$epf,'esic'=>$esic,'tds'=>$tds,'hra'=>$hra,'other'=>$other,'present_days'=>$emp_attendance,'overtime_day'=>$overtime_days,'attendance'=>$attendance, 'absent_days'=>$emp_absent,'total_working_days'=>$total_working_days,'net_salary'=>$net_salary,
                'gross_salary'=>$gross_salary,'advance_id'=>$advance_id,'advance_left'=>$advance_left,'advance'=>$advance_deduction,'tax'=>$tax,'tax_remark'=>$tax_remark,'advance_remark'=>$advance_remark,'attendance_based_salary'=>$attendance_based_basic,'other_deduction'=> $other_deduction,'net_payable'=> $net_payable,'month'=>$month,'full_day'=>$full_day,'half_day'=>$half_day,'overtime_hours'=>$overtime_hours,'overmin'=>$overmin,'arrear'=>$arrear,'bonus'=>$other_bonus,'exgratia'=>$exgratia));
           
          }
          $flag=true;
          activity()->log('Sending Salary Details To payslip page');
          return view('payslip-withdata',compact('employee','flag'));

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
          return redirect('dashboard')->with('alert-danger',"Only admin and HR can access payslip");
      }
        
  }  
  public function generate_payslip(Request $request)
    {
        try
        {
            if(Session::get('username')!='')
            { 
        $i=0; $inserted=$toinsert=0; $insert = $payslip_latest = false; 
        foreach($request->input('emp_id') as $emp_id)
        { 
            /*if(($request->other_ded[$i]!=0) || ($request->other_add[$i]!=0))
            {     $toinsert++;   */      
              $insert=DB::table('payslip_master')->insert([
             'emp_id'=>$emp_id,
             'designation_id'=>$request->designation_id[$i],
             'actual_attendance'=>$request->present_days[$i],
             'attendance'=>$request->attendance[$i],
             'over_time'=>$request->overtime_day[$i].' Days '.$request->overtime_hours[$i].' Hours '.$request->overmin[$i].' mins',
             'salary'=>$request->salary[$i],
             'basic'=>$request->basic_salary[$i],
             'hra'=>$request->hra[$i],
             'other'=>$request->other[$i],
             'incentive'=>$request->incentive[$i],
             'incentive_target'=>$request->incentive_target[$i],
             'arrear'=>$request->arrear[$i],
             'gross_salary'=>$request->gross_salary[$i],
             'epf'=>$request->epf[$i],
             'esic'=>$request->esic[$i],
             'tds'=>$request->tds[$i],
             'exgratia'=>$request->exgratia[$i],
             'bonus'=>$request->bonus[$i],
             'advance'=>$request->advance[$i],
             'professional_tax'=>$request->tax[$i],
             'net_salary'=>$request->net_salary[$i],
             'other_deduction'=>$request->other_ded[$i],
             'other_addition'=>$request->other_add[$i],
             'remark'=>$request->remark[$i],
             'attendance_based_sal'=>$request->attendance_based_sal[$i],
             'net_payable'=>$request->net_payable[$i],
             'month'=>$request->month[$i],
             'created_at'=>now()
             ]);
              if($insert)
              { $inserted++;
                if($insert)
                {
                $master_id=DB::table('payslip_master')->max('id');
                $payslip_latest=DB::table('payslip_latest')->insert(['emp_id'=>$emp_id,'month'=>$request->month[$i],'master_id'=>$master_id,'generated_by'=>Session::get('user_id'),'created_at'=>now()
                ]);
                }
                else{
                    break;
                }
                if($insert && $payslip_latest)
                {
                  activity()->log('Salary Details Uploaded Successfully');
                   if($request->advance[$i]>0)
                   {
                       $advance=DB::table('advance_repayment')->insert([
                           'advance_id'=>$request->advance_id[$i],
                           'deduction_date'=>now(),
                           'deduction_amount'=>$request->advance[$i],
                           'advance_left'=>$request->advance_left[$i],
                           'created_at'=>date("Y-m-d")
                       ]);
                   } 
                }
                else
                {
                  activity()->log('Salary Details Upload Process Failed');
                  break;
                }

                  /*$master_id=DB::table('payslip_master')->max('id');
                  $payslip_latest=DB::table('payslip_latest')
                  ->where('emp_id',$emp_id)
                  ->where('month',$request->month[$i])
                  ->update(['master_id' => $master_id,'generated_by'=>Session::get('user_id'),'updated_at'=>now()]);*/
              }
           /* }*/

             $i++;
          }
          if($inserted==$i)
						{
						activity()->log('payslip generated Successfully');
						$request->session()->flash('alert-success', 'payslip  generated Successfully!');
                        return redirect()->back();
						}
					else
						{
						activity()->log('payslip cannot be generated');
						$request->session()->flash('alert-danger', 'payslip cannot be generated! 24334');
                        return redirect()->back();
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
        return redirect()->back()->with('failure',"Database Query Error! [".$e->getMessage()." ]");
    } 
    catch(Exception $e)
       {
           activity()->log($e->getMessage());
        return redirect()->back()->with('alert-danger',$e->getMessage());
       }
    }

    public function get_delete_payslip(Request $request)
    {
      if(Session::get('username')!='' && (session('role')=='admin' || session('role')=='hr' || session('role')=='accountant' ))
      {
        try
        {
          $branches = DB::table('branch')->get();
          return view('deletepayslip',compact('branches'));
        }
        catch(QueryException $e)
        {
          activity()->log($e->getMessage());
        return Redirect::to('deletepayslip')->with('alert-danger',"Database Query Error! [".$e->getMessage()." ]");
        }
        catch(Exception $e)
        {
          activity()->log($e->getMessage());
        return Redirect::to('deletepayslip')->with('alert-danger',$e->getMessage());
        } 
      }
      else
      {
        if(Session::get('username')=='')
          return redirect('/')->with('status',"Please login First");
        else
          return redirect('dashboard')->with('alert-danger',"Only admin , HR and accountant can add Bank");
      }
    }
    
     public function deletepayslip(Request $request)
    {
        if($request->input('zone')=='' )
        {
            return Redirect::to('deletepayslip')->with('alert-warning',"Select Branch First");
        }
        if(DB::table('payslip_master')->count()=='' || DB::table('payslip_master')->count()=='')
        {
             return Redirect::to('deletepayslip')->with('alert-info',"There is no data to delete");
        }
        if(Session::get('username')!='' && (session('role')=='admin' || session('role')=='hr' || session('role')=='accountant' ))
        {
          try
          {
               $month=date('n');
             $branch = $request->input('zone');
             $employee =DB::table('emp')->join('payslip_master','emp.id', '=' , 'payslip_master.emp_id')->select('payslip_master.id','payslip_master.emp_id')
         ->where('emp.branch_location_id','=',$branch)->whereMonth('payslip_master.created_at','=',$month)->get();
               foreach($employee as $emp)
               {
                  DB::table('payslip_master')->where('id', '=', $emp->id)->delete();                    
               }
               
                $payslip_latest =DB::table('emp')->join('payslip_latest','emp.id', '=' , 'payslip_latest.emp_id')->select('payslip_latest.id','payslip_latest.emp_id')
         ->where('emp.branch_location_id','=',$branch)->whereMonth('payslip_latest.created_at','=',$month)->get();
               foreach($payslip_latest as $latest)
               {
                  DB::table('payslip_latest')->where('emp_id', '=', $latest->emp_id)->delete();
               }
                   
               return Redirect::to('deletepayslip')->with('alert-success',"Payslip deleted sucessfully");
          }
          catch(QueryException $e)
          {
            activity()->log($e->getMessage());
          return Redirect::to('deletepayslip')->with('alert-danger',"Database Query Error! [".$e->getMessage()." ]");
          }
          catch(Exception $e)
          {
            activity()->log($e->getMessage());
          return Redirect::to('deletepayslip')->with('alert-danger',$e->getMessage());
          } 
        }
        else
        {
          if(Session::get('username')=='')
                return redirect('/')->with('status',"Please login First");
            else
            return redirect('dashboard')->with('alert-danger',"Only admin , HR and accountant can add Bank");
        }
    }
}
