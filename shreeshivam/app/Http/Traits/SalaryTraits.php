<?php 
namespace App\Http\Traits;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\File;
use App\Exception;
use \Illuminate\Database\QueryException;
ini_set('max_execution_time', 3000);
date_default_timezone_set('Asia/Kolkata');

trait SalaryTraits {

	public function getTeam($emp_genesis_id)
	{
		try
		{
			$team_id="";
			$team_name="";

			$team=DB::table('team')->get();           
		    foreach($team as $teams)
		    {
		        $team_member=explode(',',$teams->team_member);	         
		        if(in_array($emp_genesis_id,$team_member))
		        {
		            $team_id=$teams->id;
		            $team_name=$teams->team_name;
		            break;
		        }
		        else
		        {
		            continue;
		        }
		    }

		    return json_encode(array('team_id'=>$team_id,'team_name'=>$team_name));
		}
		catch(QueryException $e)
      	{
          	Log::error($e->getMessage());
          	return redirect()->back()->with('alert-danger','Database Query Exception! ['.$e->getMessage().' ]');
      	}	
      	catch(Exception $e)
      	{
          	Log::error($e->getMessage());
          	return redirect()->back()->with('alert-danger',$e->getMessage());
      	}
		
	}

	public function getMonthDetails($month)
	{
		try
		{
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

	      	return json_encode(array('holidays'=>$holidays,'total_working_days'=>$total_working_days));
	    }
		catch(QueryException $e)
      	{
          	Log::error($e->getMessage());
          	return redirect()->back()->with('alert-danger','Database Query Exception! ['.$e->getMessage().' ]');
      	}	
      	catch(Exception $e)
      	{
          	Log::error($e->getMessage());
          	return redirect()->back()->with('alert-danger',$e->getMessage());
      	}
	}

	public function getSalary($emp_salary_id)
	{
		try
		{
			$salary=DB::table('salary')
	              ->select('salary')
	               ->where('id',$emp_salary_id)
	              ->value('salary');
	      	// $sal=json_decode($salary,true);
	    
	     	return json_encode($salary);
	    }
		catch(QueryException $e)
      	{
          	Log::error($e->getMessage());
          	return redirect()->back()->with('alert-danger','Database Query Exception! ['.$e->getMessage().' ]');
      	}	
      	catch(Exception $e)
      	{
          	Log::error($e->getMessage());
          	return redirect()->back()->with('alert-danger',$e->getMessage());
      	}

	}

	public function workingHour()
	{
		try
		{
			$working_hour = DB::table('working_hour')->get();
              $wh = explode(':', $working_hour[0]->full_day_from);
              $fdf = $wh[0] + (float)($wh[1]/60);
              $wh = explode(':', $working_hour[0]->full_day_to);
              $fdt = $wh[0] + (float)($wh[1]/60);
              $wh = explode(':', $working_hour[0]->half_day_from);
              $hdf = $wh[0] + (float)($wh[1]/60);
              $wh = explode(':', $working_hour[0]->half_day_to);
              $hdt = $wh[0] + (float)($wh[1]/60);
              $full_day_from =3600*$fdf;// strtotime('09:00:00');
              $full_day_to = 3600*$fdt;//strtotime('10:00:00');
              $half_day_from =3600*$hdf; //strtotime('04:00:00');
              $half_day_to = 3600*$hdt;//strtotime('05:00:00');
              return json_encode(array('full_day_from'=>$full_day_from,'full_day_to'=>$full_day_to,'half_day_from'=>$half_day_from,'half_day_to'=>$half_day_to));
		}
		catch(QueryException $e)
      	{
          	Log::error($e->getMessage());
          	return redirect()->back()->with('alert-danger','Database Query Exception! ['.$e->getMessage().' ]');
      	}	
      	catch(Exception $e)
      	{
          	Log::error($e->getMessage());
          	return redirect()->back()->with('alert-danger',$e->getMessage());
      	}
	}

	public function getAttendance($month,$year,$emp_id,$full_day_from,$full_day_to,$half_day_from,$half_day_to,$full_day,$half_day,$total_working_days,$overtime_day,$overtime_half_day,$department)
	{
		try
		{
			$overtime = 0;
      $overmin=0;
			$attendance_array = DB::table('daily_report')->wheremonth('date',$month)->whereYear('date',$year)->where('emp_id',$emp_id)->where('total_working_hour','!=',date('H:i:s',strtotime('00.00.00')))->get();
                          
              for($i=0;$i<sizeof($attendance_array);$i++)
              {
                /*$attendance = strtotime($attendance_array[$i]->total_working_hour);*/
                $exp = explode(':',$attendance_array[$i]->total_working_hour);
                  $attendance = $exp[0]*3600;
                  $attendance+= $exp[1]*60;

                if($full_day==$total_working_days)
                {
                  if($attendance>=$full_day_to)
                  {
                    $overtime_day++;
                    $over = $attendance-$full_day_to;
                    $overtime+=$over/3600;
                    $overmin+=$over%3600;
                  }
                  if($attendance<$full_day_to && $attendance>$full_day_from)
                    {
                      $overtime_day++;
                    }
                  elseif($attendance<$full_day_from && $attendance>$half_day_from)
                    {
                      $overtime_half_day++;
                    }
                }
                elseif($attendance>=$full_day_to)
                {
                  $full_day++;
                  $over = $attendance-$full_day_to;
                  $overtime+=$over/3600;
                 $overmin+=$over%3600;
                }
                elseif($attendance<$full_day_to && $attendance>$full_day_from)
                {
                  $full_day++;
                }
                elseif($attendance<$full_day_from && $attendance>$half_day_from)
                {
                  $half_day++;
                }
              }
              //$emp_attendance=sizeof($attendance_array);
              
              if($overmin>60)
              {
                $min = $overmin%60;
                $overtime+=($overmin-$min)/60;
                if($min>=40)
                  {
                    $overtime++;
                    $overmin = 0;
                  }
                  else
                  {
                    if(strtolower($department) == 'sales' || strtolower($department) == 'sale')
                    {
                      if($min>=15)
                        $overmin=$min;
                     else
                        $overmin=0;
                    }
                    else
                      $overmin = 0;
                  }
              }

              /*$overtime_hours = $overtime%8;
              $overtime_days =$overtime_day + ($overtime - $overtime_hours)/8;*/
              $overtime_hours = $overtime;
              $overtime_days = $overtime_day + ($overtime_half_day/2);

              if($department=='SALES')
              {
                $x = $overmin%30;
                $overmin = $overmin-$x;
              }
              else
              {
                $x = $overtime%2;
                $overtime = $overtime-$x;
                $overmin=0;
              }

            return json_encode(array('full_day'=>$full_day,'half_day'=>$half_day,'overtime'=>$overtime,'overmin'=>$overmin,'overtime_day'=>$overtime_day,'overtime_days'=>$overtime_days,'overtime_hours'=>$overtime_hours,'overtime_half_day'=>$overtime_half_day));
		}
		catch(QueryException $e)
      	{
          	Log::error($e->getMessage());
          	return redirect()->back()->with('alert-danger','Database Query Exception! ['.$e->getMessage().' ]');
      	}	
      	catch(Exception $e)
      	{
          	Log::error($e->getMessage());
          	return redirect()->back()->with('alert-danger',$e->getMessage());
      	}

	}

	public function getTarget($month,$emp_id,$total_incentive)
	{
		try
		{
			$incentive=0;
			$target=DB::table('target')->rightjoin('emp','emp.id','=','target.emp_id')->select('target.id','target.emp_id','target.target','target.start_date','target.end_date','target.team_id','emp.genesis_id')
                  ->where('target.emp_id',$emp_id)
                  ->wheremonth('target.start_date','=',$month)
                  ->wheremonth('target.end_date','=',$month)
                  ->get();
                  
	          foreach($target as $targets)
	          {
	            $total_emp_sale=DB::table('target_sales')
	            ->where('emp_id',$targets->genesis_id)
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
	                $team_achieved=1;
	            else
	                $team_achieved=0;

	            $count = DB::table('team')->where('id',$targets->team_id)->where('team_leader',$emp_id)->count();
	            if($count==1)
	            {  
	              if($team_achieved==1)
	              {                         
	                $fullday = DB::table('daily_report')->where('emp_id',$emp_id)->whereBetween('date',[$targets->start_date,$targets->end_date])->wheretime('total_working_hour','>=',$full_day_from)->count();
	                $halfday = DB::table('daily_report')->where('emp_id',$emp_id)->whereBetween('date',[$targets->start_date,$targets->end_date])->wheretime('total_working_hour','>=',$half_day_from)->wheretime('total_working_hour','<=',$full_day_from)->count();
	                $weekday=$fullday+$halfday/2;
	                $incentive= ($sal_per_day*$weekday)*($tl_target_week_."".$i/100);
	              }
	            }
	            else
	            {
	              if($team_achieved==1)
	              {
	                if($total_emp_sale>=$targets->target)
	                {
	                  $incentive=$total_emp_sale*$target_week_."".$i/100;
	                }
	              }
	                $incentive+=$total_emp_sale*$regular_week_."".$i/100;
	            }                   

	            ///////////////////////////////////////////////////////////////////////////////
	            $emp_target[$i] =(array)json_encode(array('week'.$i=>array(
	           "target" =>$targets->target,
	            "actual_sale" => $total_emp_sale,
	            "incentive"=>$incentive)));

	            $team_target[$i]=(array)json_encode(array('week'.$i=>array("total_team_target"=>$total_team_target,"team_sale"=>$sum_team_actual_sale,"target_achieved"=>$team_achieved)))  ;   
	             
	                   $i++;  
	            $total_incentive+=$incentive;   
	          }

	         return json_encode(array('incentive'=>$incentive,'total_incentive'=>$total_incentive,'team_target'=>$team_target,'team_achieved'=>$team_achieved,'emp_target'=>$emp_target));
		}
		catch(QueryException $e)
      	{
          	Log::error($e->getMessage());
          	return redirect()->back()->with('alert-danger','Database Query Exception! ['.$e->getMessage().' ]');
      	}	
      	catch(Exception $e)
      	{
          	Log::error($e->getMessage());
          	return redirect()->back()->with('alert-danger',$e->getMessage());
      	}
	}

	public function salary_noepf_noesic($emp_attendance,$emp_att,$emp_att_diff,$overtime_salary,$sal_per_day,$half_day_salary){
				 $salary_of_round_attendance=0;
				 $attendance_based_salary=$sal_per_day*$emp_att;
				 if ($emp_att_diff!=0)
				 {
					 $salary_of_round_attendance=$half_day_salary;
				 }
				
				  return json_encode(array('attendance_based_salary'=>$attendance_based_salary,'salary_of_round_attendance'=>$salary_of_round_attendance));
	}
		public function salary_with_epf_esic_with_greatersal($emp_salary,$total_working_days,$emp_basic_salary,$hra,$emp_attendance,$emp_att,$emp_att_diff,$overtime_salary,$sal_per_day,$half_day_salary){
				 $salary_diff=0;
				 $salary_of_round_attendance=0;
				 $attendance_based_salary=$sal_per_day*$emp_att;
				 $attendance_based_basic=($emp_basic_salary/$total_working_days)*$emp_att;
				 $hra=0;
				 if($emp_salary >=21000){
				 $hra=$emp_basic_salary*40/100;
				}	
				if($emp_att_diff!=0)
				 {
					 $salary_of_round_attendance=$half_day_salary;
				 }			 
			  	$salary_diff=$attendance_based_salary - $attendance_based_basic;
				  return json_encode(array('attendance_based_salary'=>$attendance_based_salary,'salary_of_round_attendance'=>$salary_of_round_attendance,'salary_diff'=>$salary_diff,'hra'=>$hra,'attendance_based_basic'=>$attendance_based_basic));
	}

	public function salary_with_epf_esic_less_sal($emp_salary,$total_working_days,$emp_basic_salary,$emp_attendance,$emp_att,$emp_att_diff,$overtime_salary,$sal_per_day,$half_day_salary){
		$manipulated_attendance=0;
		$manipulated_att_sal_diff=0;		
		$salary_of_round_attendance=0;
		$attendance_based_salary=$sal_per_day*$emp_att;
		$manipulated_attendance=floor(($emp_salary/$emp_basic_salary)*$emp_att);
		$manipulated_attendance_not_round=($emp_salary/$emp_basic_salary)*$emp_att;
		$attendance_based_basic=($emp_basic_salary/$total_working_days)*$manipulated_attendance;
		$manipulated_att_sal_diff=($emp_basic_salary/$total_working_days)*$manipulated_attendance_not_round - $attendance_based_basic;
				 
		if($emp_att_diff!=0)
			{
				$salary_of_round_attendance=$half_day_salary;
			}				 
		return json_encode(array('manipulated_attendance'=>$manipulated_attendance,'manipulated_att_sal_diff'=>$manipulated_att_sal_diff,'attendance_based_salary'=>$attendance_based_salary,'salary_of_round_attendance'=>$salary_of_round_attendance,'attendance_based_basic'=>$attendance_based_basic));
	}

	public function calculate_epf_deduction($attendance_based_basic,$emp_epf)
	{
		$epf_amount_g=round($attendance_based_basic*$emp_epf/100);
		return json_encode(array('epf_amount_g'=>$epf_amount_g));
	} 

		public function calculate_esic_deduction($gross_salary,$emp_esic)
	{
		$esic_amount_g=ceil($gross_salary*$emp_esic/100);
	
		return json_encode(array('esic_amount_g'=>$esic_amount_g));
	}

	public function  calculate_advance($advance){

							$advance_id = $advance[0]->id;
              $advance_amt = $advance[0]->advance;  
              $deduction_per_month = $advance[0]->deduction_per_month;
              // $advance_amt= DB::table('advance')->where('emp_id',$emps->id)->value('advance');
              $advance_repayment = DB::table('advance_repayment')->where('advance_id',$advance_id)->orderBy('id','desc')->get(); 
              if(sizeof($advance_repayment)==0)
              {
                $balance_amount = $advance_amt;
              }
              else
              {
                $balance_amount = $advance_repayment[0]->advance_left;
              }
              if($balance_amount>0)
              {
                if($deduction_per_month<=$balance_amount)
                {
                  $advance_deduction = $deduction_per_month;                  
                }
                else
                {
                  $advance_deduction = $balance_amount;
                }
                $advance_left = $balance_amount-$advance_deduction;
              }
              else
              {
                $advance_deduction = 0;
                $advance_left = 0;
							}
				return json_encode(array('advance_deduction'=>$advance_deduction,'advance_left'=>$advance_left));
		}

		public function getProfessionalTax($emps_gender ,$emps_id, $branch_id ,$emp_salary,$tax, $month,$gross_salary) {
		
			    $emp_trans=DB::table('emp')->select('transfer')->where('id',$emps_id)->value('transfer');
				if($emp_trans=='yes')
				{   
					 $tranfer_table=DB::table('transfer')->join('branch','transfer.transfer_to_branch','=','branch.branch')->where('transfer.emp_id','=',$emps_id)->select('branch.id')->value('branch.id');
					 $branch_id=$tranfer_table;
				}
      if($emps_gender=='female')
        $gender = 'for_women';
      else
        $gender = 'for_men';
     
				$professional_tax=DB::table('professional_tax')->where('branch_id',$branch_id)->where($gender,'1')->orderBy('id','asc')->get();
				foreach($professional_tax as $prof_tax)
				{ if($prof_tax->amount_to=='')
						{
							$amount_to=0;
						}
						else
						{
							$amount_to=$prof_tax->amount_to;
						}

						if($prof_tax->calculation_base=='monthly')
					{
							if($gross_salary>=$prof_tax->amount_from && ( $gross_salary<=$amount_to || $amount_to==0) && $prof_tax->tax_deducted!=0)            
							{
									if($prof_tax->professional_tax=='monthly')
									{
											$tax=$prof_tax->tax_deducted;                   
									}
									else 
									{
										if($branch_id==5){

											if($month=='02' || $month=='2')
											{
												$tax = $prof_tax->for_last_month;
											}
											else
											{
												
											 	$tax = $prof_tax->for_11_month;
											}
										}
										else{
												if($month=='03' || $month=='3')
											{
												$tax = $prof_tax->for_last_month;
											}
											else
											{
												
											 	$tax = $prof_tax->for_11_month;
											}
										}

									}
							}
							else
							{
									continue;
							}
					}
					else
					{
							if(( $emp_salary*12)>=$prof_tax->amount_from && ( $emp_salary<=$amount_to || $amount_to==0) && $prof_tax->tax_deducted!=0)            
							{
									if($prof_tax->professional_tax=='monthly')
									{
											$tax=$prof_tax->tax_deducted;                   
									}
									else 
									{
										if($month=='03' || $month=='3')
										{
											$tax = $prof_tax->for_last_month;
										}
										else
										{
											 $tax = $prof_tax->for_11_month;
										}

									}
							}
							else
							{
									continue;
							}
					}

				}
			return json_encode(array('tax'=>$tax));
		}

}