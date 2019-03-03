<?php

namespace App\Http\Controllers;
use App\User;
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Exception;
use \Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class IncentiveController extends Controller
{
    public function Index()
    {
        $department=DB::table('department')->select('id')->where('department_name','Sales')->value('id');
        $designation=DB::table('designation')->select('id')->where('department',$department)
        ->where('designation','Fashion Consultant')
        ->value('id');
        

        $emp=DB::table('emp')
        ->get();

         foreach($emp as $emps)
        {
            $emp_target=array();
            $target=array();
            $aging=array();
            $team_target=array(); 
            $incentive=0;
            $team_id='';
            $team_name='';
            $aging_bonus=0;
            $hra=0;
            $other=0;
            $team=DB::table('team')->get();
            foreach($team as $teams)
            {
                $team_member=explode(',',$teams->team_member);
               
                if(in_array($emps->id,$team_member))
                {
                    $team_id=$teams->id;
                    $team_name=$teams->team_name;
                    break;
                }
                else{
                    continue;
                }
               
            }
            
          $target=DB::table('target')
            ->where('emp_id',$emps->id)
            ->wheremonth('start_date','=',date('m'))
            ->wheremonth('end_date','=',date('m'))
            ->get();
        $target_count=DB::table('target')
        ->where('emp_id',$emps->id)
        ->wheremonth('start_date','=',date('m'))
        ->wheremonth('end_date','=',date('m'))
        ->count();
        

         if($target_count!=0)
           {
            
            $i=0;
           
                foreach($target as $targets)
                {
                 
                   
                  $total_emp_sale=DB::table('target_sales')
                  ->where('emp_id',$targets->emp_id)
                  ->where('target_id',$targets->id)
                  ->groupby('target_id')
                  ->sum('actual_sale');
                
                  $total_team_target=DB::table('target')
                            ->where('start_date',$targets->start_date)
                            ->where('end_date',$targets->end_date)
                            ->where('team_id',$targets->team_id)
                            ->groupby('start_date')
                            ->groupby('team_id')
                            ->sum('target');
                           
                  $target_id=DB::table('target')
                  ->select('id')
                  ->where('start_date',$targets->start_date)
                  ->where('end_date',$targets->end_date)
                  ->where('team_id',$targets->team_id)
                  ->get();
                 
                  $sum_team_actual_sale=0; 
                   foreach($target_id as $target_ids)
                 {
                     $team_actual_sale=DB::table('target_sales')
                     ->where('target_id',$target_ids->id)
                     ->groupby('target_id')
                     ->sum('actual_sale') ;
                    $sum_team_actual_sale+=$team_actual_sale;
                    
                 }
                
                 
                 if($sum_team_actual_sale>=$total_team_target)
                 {
                     $team_achieved=1;
                     
                     if($total_emp_sale>=$targets->target)
                     {
                     $incentive=$total_emp_sale/1000;
                     }
                     else
                     {
                        
                        $incentive=0;
                     } 
                 }
                 else
                 {
                    $team_achieved=0;
                    $incentive=0;
                 }
                  
                        $emp_target[$i] =(array)json_encode(array('week'.$i=>array(
                       "target" =>$targets->target,
                        "actual_sale" => $total_emp_sale,
                        "incentive"=>$incentive)));
                        $team_target[$i]=(array)json_encode(array('week'.$i=>array("total_team_target"=>$total_team_target,"team_sale"=>$sum_team_actual_sale,"target_achieved"=>$team_achieved)))  ;   

                        
                         $i++;
                        
                }
                         $aging1=DB::table('sales')
                         ->where('sales_person_id',$emps->genesis_id)
                         ->wheremonth('bill_date',date('m'))
                         ->groupby('aging')
                         ->get( array('aging AS aging_title',
                            DB::raw( 'SUM(net_amount) AS net_amount' ),
                            DB::raw( 'SUM(items) AS items' ),
                        ));
                
                     

                    
                     
                  
                        $target=(array)json_encode(array('target'=>$emp_target));
                        $team_target=(array)json_encode(array('team_target'=>$team_target));
                        $aging=(array)json_encode(array("aging"=>$aging1));    
                       
                        $i=0;

                       
                        
               
            } 
            
// Salary Calculation--------------------------------------------------------------------------------

            $salary=DB::table('salary')
            ->select('salary')
             ->where('id',$emps->salary_id)
            ->value('salary');
           $sal=json_decode($salary,true);
          
            $emp_salary=$sal['emp_salary']['salary'];
            $emp_basic_salary = $sal['emp_salary']['basic'];
           
            $total_days=date('t');
            $total_working_days=27;
            $emp_attendance=DB::table('daily_report')->wheremonth('date',date('m'))->where('emp_id',$emps->biometric_id)->where('total_working_hour','!=','00.00.00')->count();
            $emp_absent=$total_working_days-$emp_attendance;
            $sal_per_day=$emp_salary/$total_working_days;
            $original_salary=$sal_per_day*$emp_attendance;
            $epf_master=(array)json_encode(DB::table('epf_master')->where('branch_id',$emps->branch_location_id)->first());
            $master=json_decode($epf_master[0],true);
            $emp_epf = $master['epf'];
            $emp_esic = $master['esic'];
            $min_wages = $master['minimum_wages'];
            $epf=0;$esic=0;

//Aging calculation--------------------------------------------------------------------------------
            if(!empty($aging))
            {
                $aging= json_decode($aging[0],true);
                $aging = $aging['aging'];
                foreach($aging as $age)
                {
                    $aging_per = DB::table('aging_master')->where('aging_title',$age['aging_title'])->where('branch_id',$emps->branch_location_id)->value('aging_per');
                    $aging_bonus+=($age['net_amount']*$aging_per)/100;
                }                     
            }
//End Aging Calculation--------------------------------------------------------------------------------------
            
            if($emp_salary<$min_wages)
            {
                $attendance= ($original_salary/$min_wages)*$emp_attendance;
                if($emps->epf_option==1)
                {
                    $epf= ($original_salary*$emp_epf)/100;

                }
                
                $hra=0;
                $other=0;
                
              $gross_salary=$original_salary+$hra+$other+$incentive+$aging_bonus;
            }
            else 
            {
                if($emps->epf_option==1)
                {
                    $epf= ($sal['emp_salary']['basic']*$emp_epf)/100;
                }
                
                $ex_hra=($sal['emp_salary']['basic']*40)/100;
                
                if($original_salary<($ex_hra+$sal['emp_salary']['basic']))
                {
                    $hra=0;
                    $other = $original_salary-$sal['emp_salary']['basic'];
                }
                else{
                    $hra=$ex_hra;
                    $other=$original_salary-($sal['emp_salary']['basic']+$hra);
                }
                $gross_salary=$sal['emp_salary']['basic']+$hra+$other+$incentive+$aging_bonus;
            }
            
            if($emps->esic_option==1)
            {
                $esic=($gross_salary*$emp_esic)/100;
            }
            
             $net_salary=$gross_salary-($esic+$epf);


            //end  Salary Calculation--------------------------------------------------------------------------------

           
            

            
           $employee[$emps->genesis_id]=json_encode(array('department'=>$department
            ,'designation'=>$designation,'branch'=>$emps->branch_location_id,'epf_option'=>$emps->epf_option,
            'esic_option'=>$emps->esic_option,'team_id'=>$team_id,'team_name'=>$team_name,'salary'=>$emp_salary,'basic_salary'=>$emp_basic_salary,'target'=>$target,'team_target'=>$team_target,'incentive'=>$incentive,'aging_bonus'=>$aging_bonus,'epf'=>$emp_epf,'esic'=>$emp_esic,'hra'=>$hra,'other'=>$other));
   
        }
        return $employee;
        }
       
    }

