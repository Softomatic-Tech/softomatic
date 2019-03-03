<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use App\Exception;
use \Illuminate\Database\QueryException;
date_default_timezone_set('Asia/Kolkata');

class TargetController extends Controller
{
	public function show(Request $request)
	{
		try
		{
		$target_id = '';
		$start_date = '';
		$end_date = '';
		$target = 0;
		$team_id = '';
		$bonus = array();
		$output = '';
		$date = date('Y-m-d');
		$useremail = Session::get('useremail');
		$emp = DB::table('emp')->select('id','genesis_id','branch_location_id')->where('email',$useremail)->get();
		$emp_id = $emp[0]->id;
		$genesis_id = $emp[0]->genesis_id;
		$targets = DB::table('target')->select('id','target','start_date','end_date','team_id')->where('emp_id',$emp_id)->where('start_date','<=',$date)->where('end_date','>=',$date)->get();
		if($targets!='[]')
		{
		$target_id = $targets[0]->id;
		$start_date = $targets[0]->start_date;
		$end_date = $targets[0]->end_date;
		$target = $targets[0]->target;
		$team_id = $targets[0]->team_id;
		}

		
		$sale = DB::table('target_sales')->where('target_id',$target_id)->where('emp_id',$genesis_id)->sum('actual_sale');
		$aging=DB::table('sales')
                         ->where('sales_person_id',$genesis_id)
                         ->where('bill_date','>=',$start_date)
                         ->where('bill_date','>=',date('Y-m-d',strtotime("-1 days")))
                         ->groupby('aging')
                         ->get( array('aging AS aging_title',
                            DB::raw( 'SUM(net_amount) AS net_amount' ),
                            DB::raw( 'SUM(items) AS items' ),
                        ));
       	$i=0;
        foreach($aging as $age)
        {
        	$aging_per = DB::table('aging_master')->where('aging_title',$age->aging_title)->where('branch_id',$emp[0]->branch_location_id)->value('aging_per');
             $aging_bonus= ($age->net_amount*$aging_per)/100;

             $bonus[$i] = array('aging_title'=>$age->aging_title,'items'=>$age->items,'net_amount'=>$age->net_amount,'aging_per'=>$aging_per,'aging_bonus'=>$aging_bonus);

        	$output.='<tr><td> '.$age->aging_title.' </td><td> '.$age->items.' </td><td> '.$age->net_amount.' </td><td> '.$aging_per.' </td><td> '.$aging_bonus.' </td></tr> ';
        	$i++;
        }
        $aging_master=DB::table('aging_master')->where('branch_id',$emp[0]->branch_location_id)->get();


    	$team_target=DB::table('target')
                            ->where('start_date',$start_date)
                            ->where('end_date',$end_date)
                            ->where('team_id',$team_id)
                            ->groupby('start_date')
                            ->groupby('team_id')
                            ->sum('target');
                           
      $target_ids=DB::table('target')
      ->select('id')
      ->where('start_date',$start_date)
      ->where('end_date',$end_date)
      ->where('team_id',$team_id)
      ->get();
                 
       $team_actual_sale=0; 
       foreach($target_ids as $id)
	     {
	         $team_actual_sale=DB::table('target_sales')
	         ->where('target_id',$id->id)
	         ->groupby('target_id')
	         ->sum('actual_sale') ;
	        $team_actual_sale+=$team_actual_sale;
	        
	     }

	    if(stripos(Session::get('designation'),'fashion consultant')!==false)
		{
        return view('mobile_target',compact('bonus','target','sale','aging_master','team_target'));
    	}
    	elseif(stripos(Session::get('designation'),'team leader')!==false)
    	{
	     return view('mobile_target',compact('team_actual_sale','target','team_target'));
        }
        
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

	public function predict(Request $request)
	{
		try
		{
		$aging_id = $request->aging_title;
		$amount = $request->amount;

		$aging_per = DB::table('aging_master')->where('id',$aging_id)->value('aging_per');

		$bonus = $amount*$aging_per/100;
		return $bonus;

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

	public function predict_regular(Request $request)
	{
		try
		{
		$amount = $request->amount;
		$emp = DB::table('emp')->select('id','salary_id')->where('email',Session::get('useremail'))->get();
		$emp_id = $emp[0]->id;
		$salary_id = $emp[0]->salary_id;
		$team_target = $request->team_target;
		$target = $request->target;
		if(stripos(Session::get('designation'),'team leader')!==false)
		{
			$salary = DB::table('salary')->where('id',$salary_id)->value('salary');
			$sal = json_decode($salary,true);
			$emp_salary=$sal['emp_salary']['salary'];
			$total_days=date('t');
			$sal_per_day=$emp_salary/$total_days;
			if($team_target!=0)
			$percentage = round(($amount/$team_target),2);
			else return 0;
			$incentive= ($sal_per_day*7)*($percentage/100);
			return round($incentive,2);
		}
		elseif(stripos(Session::get('designation'),'fashion consultant')!==false)
		{
			if($target<=$amount && $team_target!=0)
			$incentive= ($amount/1000);
			else
			$incentive=0;
			return round($incentive,2);
		}
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

	public function get_target_form()
	{
		if(Session::get('username')!='' && (session('role')=='admin' || session('role')=='hr'))
        {
			try
			{
				activity()->log('Display target');
				/*$team = DB::table('team')->join('target','team.id','!=','target.team_id')->select('team.id','team.team_member','team.team_leader','team.team_name','team.branch_id')->groupBy('team.id')->get();*/
				$target = DB::table('target')->join('team','team.id','=','target.team_id')->select(DB::raw('target.id, target.team_id, team.team_name, target.start_date, target.end_date, target.created_at, SUM(target.target) as total_target'))->groupBy('start_date')->groupBy('team_id')->get();
				return view('target',compact('target'));
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

	public function get_team_list(Request $request)
	{
		if(Session::get('username')!='' && (session('role')=='admin' || session('role')=='hr'))
        {
        	try
			{

				$output='<option value="">---Select Team---</option>';
				$from = $request->from;
				$to = $request->to;
				$selected_teams = DB::table('target')->where('start_date',$from)->where('end_date',$to)->select('team_id')->groupBy('team_id')->get();
				
				$select_teams = array(); 
				for($i=0;$i<sizeof($selected_teams);$i++){ 
					$select_teams[$i] = $selected_teams[$i]->team_id;
				}
				
				// $teams=array();
				$myteam = DB::table('team')->get();
				for($i=0;$i<sizeof($myteam);$i++)
				{
					if(in_array($myteam[$i]->id,$select_teams))
					{
						continue;
					}
					else
					{
						$output.='<option value="'.$myteam[$i]->id.'">'.$myteam[$i]->team_name.'<option>';
					}
				}

				return $output;

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

	public function get_team(Request $request)
	{
		if(Session::get('username')!='' && (session('role')=='admin' || session('role')=='hr'))
        {

		try
		{
			$id = $request->team_id;
			$select = DB::table('team')->where('id',$id)->first();
			$leader_id = $select->team_leader;
			$leader_name_array = DB::table('emp')->select('title','first_name','middle_name','last_name')->where('id',$leader_id)->get();
			$leader_name = $leader_name_array[0]->title.' '.$leader_name_array[0]->first_name.' '.$leader_name_array[0]->middle_name.' '.$leader_name_array[0]->last_name;
			$members = explode(',', $select->team_member);
			$length = sizeof($members);
			//$member = array();
			
			$output = '<div class="row clearfix ">
	                                <div class="form-group">
	            					<div class="col-md-4 col-sm-6 col-xs-12">
	            						<label for="doj">'.$leader_name.' (Leader)<input type="hidden" name="member_id[]" value="'.$leader_id.'" class="leader"></label>
	            					</div>
	            					<div class="col-md-4 col-sm-6 col-xs-12">
		            					<div class="form-line">
		            						<input autocomplete="none" type="number" name="member_target[]" id="leader_target" class="form-control member_target" placeholder="Target" value="0" required="" readonly>
		            						
		            					</div>
		            				</div>
		            			</div>
	                        </div>';
			for ($i=0; $i < $length ; $i++) 
			{
				$member_name = DB::table('emp')->select('title','first_name','middle_name','last_name')->where('id',$members[$i])->get();	
				$member = $member_name[0]->title.' '.$member_name[0]->first_name.' '.$member_name[0]->middle_name.' '.$member_name[0]->last_name;
				
						$output.= '<div class="row clearfix ">
	                                <div class="form-group">
	            					<div class="col-md-4 col-sm-6 col-xs-12">
	            						<label for="doj">'.$member.'<input type="hidden" name="member_id[]" value="'.$members[$i].'" class="leader"></label>
	            					</div>
	            					<div class="col-md-4 col-sm-6 col-xs-12">
		            					<div class="form-line">
		            						<input autocomplete="none" type="number" name="member_target[]" class="form-control member_target" placeholder="Target" value="" required="">
		            					
		            					</div>
		            				</div>
		            			</div>
	                        </div>';
			}

			$output.= '<div class="row clearfix ">
	                                <div class="form-group">
	            					<div class="col-md-4 col-sm-6 col-xs-12">
	            						<label for="doj">Team Target</label>
	            					</div>
	            					<div class="col-md-4 col-sm-6 col-xs-12">
		            					<div class="form-line">
		            						<input autocomplete="none" type="number" name="team_target" id="team_target" class="form-control" placeholder="Team Target" value="" required="" readonly>
		            					
		            					</div>
		            				</div>
		            			</div>
	                        </div>';
			return $output;
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

		// $leader = DB::table('emp')->where('id',$select->team_leader)->value('name');
		// $member = explode(',',$select->team_member);
		// return $member;
		}
        else
        {
        	if(Session::get('username')=='')
                return redirect('/')->with('status',"Please login First");
            else
            return redirect('dashboard')->with('alert-danger',"Only admin and HR can add employee");
        }
	}

	public function get_team2(Request $request)
	{
		if(Session::get('username')!='' && (session('role')=='admin' || session('role')=='hr'))
        {

		try
		{
			$id = $request->team_id;
			$target_id = $request->target_id;
			$select = DB::table('team')->where('id',$id)->first();
			$from = DB::table('target')->where('id',$target_id)->value('start_date');
			$to = DB::table('target')->where('id',$target_id)->value('end_date');
			$leader_id = $select->team_leader;
			$leader_name_array = DB::table('emp')->select('title','first_name','middle_name','last_name')->where('id',$leader_id)->get();
			$leader_name = $leader_name_array[0]->title.' '.$leader_name_array[0]->first_name.' '.$leader_name_array[0]->middle_name.' '.$leader_name_array[0]->last_name;
			$members = explode(',', $select->team_member);
			$length = sizeof($members);
			//$member = array();
			
			$output= '<input type="hidden" name="team_id" value="'.$id.'">
						<div class="row clearfix ">
	                                <div class="form-group">
	            					<div class="col-md-6 col-sm-6 col-xs-12">
	            						<label for="doj">'.$leader_name.' (Leader)<input type="hidden" name="modal_member_id[]" value="'.$leader_id.'" class="leader"></label>
	            					</div>
	            					<div class="col-md-6 col-sm-6 col-xs-12">
		            					<div class="form-line">
		            						<input autocomplete="none" type="number" name="modal_member_target[]" id="modal_leader_target" class="form-control modal_member_target" placeholder="Target" value="0" required="" readonly>
		            						
		            					</div>
		            				</div>
		            			</div>
	                        </div><br>';

	        $total_target=0;
			for ($i=0; $i < $length ; $i++) 
			{	
				$member_array = DB::table('emp')->select('title','first_name','middle_name','last_name')->where('id',$members[$i])->get();
				$member = $member_array[0]->title.' '.$member_array[0]->first_name.' '.$member_array[0]->middle_name.' '.$member_array[0]->last_name;
				$genesis_id = DB::table('emp')->where('id',$members[$i])->value('genesis_id');
				$select_target = DB::table('target')->where('emp_id',$members[$i])->where('team_id',$id)->where('start_date',$from)->value('target');

				$total_target+=$select_target;
				//return $select_target;
						$output.= '<div class="row clearfix ">
	                                <div class="form-group">
	            					<div class="col-md-6 col-sm-6 col-xs-12">
	            						<label for="doj">'.$member.'<input type="hidden" name="modal_member_id[]" value="'.$members[$i].'" class="leader"></label>
	            					</div>
	            					<div class="col-md-6 col-sm-6 col-xs-12">
		            					<div class="form-line">
		            						<input autocomplete="none" type="number" name="modal_member_target[]" class="form-control modal_member_target" placeholder="Target" value="'.$select_target.'" required="">
		            					
		            					</div>
		            				</div>
		            			</div>
	                        </div><br>';
			}

			$output.= '<div class="row clearfix ">
	                                <div class="form-group">
	            					<div class="col-md-6 col-sm-6 col-xs-12">
	            						<label for="doj">Team Target</label>
	            					</div>
	            					<div class="col-md-6 col-sm-6 col-xs-12">
		            					<div class="form-line">
		            						<input autocomplete="none" type="number" name="modal_team_target" id="modal_team_target" class="form-control" placeholder="Team Target" value="'.$total_target.'" required="" readonly>
		            					
		            					</div>
		            				</div>
		            			</div>
	                        </div>';
			return $output;
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


	public function submit_target(Request $request)
	{
		if(Session::get('username')!='' && (session('role')=='admin' || session('role')=='hr'))
        {

		try
		{
			activity()->log('Assigning target to team with id '.$request->input('team_name'));
			$rules = array(
	        
	        'from'    => 'required|date|date_format:Y-m-d',
	        'to'    => 'required|date|date_format:Y-m-d', // make sure the email is an actual email
	        'team_name'    => 'required|numeric'
	        
	        );

			$validator = Validator::make(Input::all(), $rules);

	        if ($validator->fails()) {
	            activity()->log('Assign Target process failed due to Validation Error');
	            return redirect()->back()
	            ->withErrors($validator)
	            ->withInput($request->all);
	        } 
	        $member_id = $request->input('member_id');
			$member_target = $request->input('member_target');
			$target = array();
			$team = DB::table('team')->where('id',$request->input('team_name'))->value('team_name');
	        
	        $check_target = DB::table('target')->where('team_id',$request->input('team_name'))->where('start_date',$request->input('from'))->where('end_date',$request->input('to'))->get();
			if($check_target!='[]')
	        	return redirect()->back()->with('alert-danger',"Target Already Assigned to ".$team." ");

	        for($i=0;$i<sizeof($request->input('member_id'));$i++)
	        {
	       	    $target[$i] = DB::table('target')->insert([
	       		'emp_id' => $member_id[$i],
	       		'team_id' => $request->input('team_name'),
	       		'target'	=> $member_target[$i],
	       		'start_date' => $request->input('from'),
	       		'end_date' => $request->input('to'),
	       		'created_at' => Carbon::now()
	       		]);
	        }
	        if($target)
        	{
        		return redirect()->back()->with('alert-success',"Target Assigned to Team - ".$team." Successfully");
        	}
	        else
	        {
	        	return redirect()->back()->with('alert-danger',"Target Assignment to Team - ".$team." Failed!!")->withInput($request->all);
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
        else
        {
        	if(Session::get('username')=='')
                return redirect('/')->with('status',"Please login First");
            else
            return redirect('dashboard')->with('alert-danger',"Only admin and HR can add employee");
        }   
	}

	public function update_target(Request $request)
	{
		if(Session::get('username')!='' && (session('role')=='admin' || session('role')=='hr'))
        {

		try
		{
			activity()->log('Updating target for team with id '.$request->input('team_id'));
			$rules = array(
	        'modal_from'    => 'required|date|date_format:Y-m-d',
	        'modal_to'    => 'required|date|date_format:Y-m-d', // make sure the email is an actual email
	        'team_id'    => 'required|numeric'
	        
	        );

			$validator = Validator::make(Input::all(), $rules);

	        if ($validator->fails()) {
	            activity()->log('Assign Target process failed due to Validation Error');
	            return redirect()->back()
	            ->withErrors($validator)
	            ->withInput($request->all);
	        } 

	        $team_id = $request->input('team_id');

	        $start_date = $request->input('modal_from');
	        $end_date = $request->input('modal_to');

	         $old_start_date = $request->input('old_modal_from');
	        $old_end_date = $request->input('old_modal_to');

	        $member_id = $request->input('modal_member_id');
			$member_target = $request->input('modal_member_target');
			$target = array();
			$team = DB::table('team')->where('id',$request->input('team_id'))->value('team_name');
			 $member_id;
	        for($i=0;$i<sizeof($request->input('modal_member_id'));$i++)
	        {
	       	    $target[$i] = DB::table('target')->where('team_id',$team_id)->where('start_date',$old_start_date)->where('end_date',$old_end_date)->where('emp_id',$member_id[$i])->update([
	       		'target'	=> $member_target[$i],
	       		'start_date' => $request->input('modal_from'),
	       		'end_date' => $request->input('modal_to'),
	       		'updated_at' => Carbon::now()
	       		]);
	       	    
	       	    $genesis_id = DB::table('emp')->where('id',$member_id[$i])->value('genesis_id');
	       	   	$target_id = DB::table('target')->where('team_id',$team_id)->where('start_date',$request->input('modal_from'))->where('end_date',$request->input('modal_to'))->where('emp_id',$member_id[$i])->value('id');

	       	    $target_array=json_encode(array(
	       	    	'emp_id'=>$member_id[$i],
	       	    	'team_id'=>$team_id,
	       	    	'target'=>$member_target[$i],
        			'start_date' => $request->input('modal_from'),
        			'end_date' => $request->input('modal_to'),
        		));
	       		$target_backup = DB::table('target_backup')->insert([
	       			'target_id' => $target_id,
	       			'target' => $target_array,
	       			'created_at' => Carbon::now(),
	       		]);

	        }
	        if($target)
        	{

        		return redirect()->back()->with('alert-success',"Target Updated For Team - ".$team." Successfully");
        	}
	        else
	        {
	        	return redirect()->back()->with('alert-danger',"Target Updation For Team - ".$team." Failed!!")->withInput($request->all);
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
        else
        {
        	if(Session::get('username')=='')
                return redirect('/')->with('status',"Please login First");
            else
            return redirect('dashboard')->with('alert-danger',"Only admin and HR can add employee");
        }
        
        
	}
}
?>