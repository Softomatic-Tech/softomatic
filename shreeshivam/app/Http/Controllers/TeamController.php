<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\Exception;
use \Illuminate\Database\QueryException;
date_default_timezone_set('Asia/Kolkata');

class TeamController extends Controller
{
	public function get_branch_emp(Request $request)
	{ 
		$leader="";
		$member="";
		$leader='<option></option>';
		$member='<option></option>';
		$branch_id = $request->branch_id;

		$team_leader_id=DB::table('designation')
							->where('designation','like','%team leader%')
							->orWhere('designation','like','%Team Leader%')
							->value('id');
			$team_leader=DB::table('emp')
						->where('designation',$team_leader_id)
						->where('branch_location_id',$branch_id)
						->get();
			$tl=DB::table('team')->select('team_leader')
			->where('status','active')
			->get();
			$tl=json_decode($tl,true);
			$k=0;
			$uniq_team_lead=array();
			foreach($tl as $teaml)
				{
				
			     array_push($uniq_team_lead,$teaml['team_leader']);
				
				}

				$leader='<option value="">---Select Team Leader---</option>';
				for($i=0;$i<sizeof($team_leader);$i++)
				{
			        if(!in_array($team_leader[$i]->id,$uniq_team_lead))
			        {
					$leader.='<option value="'.$team_leader[$i]->id.'">'.$team_leader[$i]->title.' '.$team_leader[$i]->first_name.' '.$team_leader[$i]->middle_name.' '.$team_leader[$i]->last_name.'</option>';
					}
				}

			$team_member_id=DB::table('designation')
							->where('designation','like','%Fashion Consultant%')
							->orWhere('designation','like','%fashion consultant%')
							->value('id');
			$team_member=DB::table('emp')
							->where('designation',$team_member_id)
							->where('branch_location_id',$branch_id)
							->get();
							
			$tm=DB::table('team')->select('team_member')
			->where('status','active')
			->get();
			$uniq_team_mem=array();
			$i=0;
			$j=0;
			$tm=json_decode($tm,true);
			
			foreach($tm as $team_m)
				{
				
					$team_m=explode(',',$team_m['team_member']);
					$length=sizeof($team_m);
				
			while($i<$length)
				{
					array_push($uniq_team_mem,$team_m[$i]);
					$i++;
				}
			 $i=0;
			}

			$member='<option value="">---Select Team Member---</option>';
				for($i=0;$i<sizeof($team_member);$i++)
				{
			        if(!in_array($team_member[$i]->id,$uniq_team_mem))
			        {
					$member.='<option value="'.$team_member[$i]->id.'">'.$team_member[$i]->title.' '.$team_member[$i]->first_name.' '.$team_member[$i]->middle_name.' '.$team_member[$i]->last_name.'</option>';
					}
				}

		/*$team_leader = DB::table('emp')->join('designation','designation.id','=','emp.designation')->where('emp.branch_location_id',$branch_id)->where('designation.designation','like','%team leader%')->orWhere('designation.designation','like','%Team Leader%')->select('emp.id','emp.first_name','emp.middle_name','emp.last_name','emp.title')->get();

		for($i=0;$i<sizeof($team_leader);$i++)
		{
			$leader.='<option value="'.$team_leader[$i]->id.'">'.$team_leader[$i]->title.' '.$team_leader[$i]->first_name.' '.$team_leader[$i]->middle_name.' '.$team_leader[$i]->last_name.'</option>';
		}

		$team_member = DB::table('emp')->join('designation','designation.id','=','emp.designation')->where('emp.branch_location_id',$branch_id)->where('designation.designation','like','%fashion consultant%')->orWhere('designation.designation','like','%Fashion Consultant%')->select('emp.id','emp.first_name','emp.middle_name','emp.last_name','emp.title')->get();

		for($i=0;$i<sizeof($team_member);$i++)
		{
			$member.='<option value="'.$team_member[$i]->id.'">'.$team_member[$i]->title.' '.$team_member[$i]->first_name.' '.$team_member[$i]->middle_name.' '.$team_member[$i]->last_name.'</option>';
		}*/
		return json_encode(['team_member'=>$member,'team_leader'=>$leader]);
	}
   public function index()
	{
		if(Session::get('username')!='' && (session('role')=='admin' || session('role')=='hr'))
        {
        	activity()->log('Fetching team');

	        try
	        {
			$branch = DB::table('branch')->get();
			$divisions = DB::table('division')->get();
			$sections = DB::table('section')->get();
			$subsections = DB::table('sub_section')->get();
			//Fetch Team Leader start
			$team_leader_id=DB::table('designation')
							->where('designation','like','%team leader%')
							->orWhere('designation','like','%Team Leader%')
							->value('id');
			$team_leader=DB::table('emp')
						->where('designation','=',$team_leader_id)
						->get();
			$tl=DB::table('team')->select('team_leader')
			->where('status','active')
			->get();
			$tl=json_decode($tl,true);
			$k=0;
			$uniq_team_lead=array();
			foreach($tl as $teaml)
				{
				
			     array_push($uniq_team_lead,$teaml['team_leader']);
				
				}
			//Fetch Team Leader	end
			
			
			
			
			//Fetch Team Member start
			$team_member_id=DB::table('designation')
							->where('designation','like','%Fashion Consultant%')
							->orWhere('designation','like','%fashion consultant%')
							->value('id');
			$team_member = DB::table('emp')
							->where('designation',$team_member_id)
							->get();
							
			$tm=DB::table('team')->select('team_member')
			->where('status','active')
			->get();
			$uniq_team_mem=array();
			$i=0;
			$j=0;
			$tm=json_decode($tm,true);
			
			foreach($tm as $team_m)
				{
				
					$team_m=explode(',',$team_m['team_member']);
					$length=sizeof($team_m);
				
			while($i<$length)
				{
					array_push($uniq_team_mem,$team_m[$i]);
					$i++;
				}
			 $i=0;
			}
		
			//Fetch Team Member	end////////////
			
			
			
			$team_data= DB::table('team')
						->join('branch','team.branch_id', '=' , 'branch.id')
						->join('emp','team.team_leader', '=' , 'emp.id')
						->leftjoin('division','team.division','=','division.id')
						->leftjoin('section','team.section','=','section.id')
						->leftjoin('sub_section','team.sub_section','=','sub_section.id')
						->select('team.id','team.branch_id','team.team_name','team.team_member','team.team_leader','branch.branch','division.division','section.section','sub_section.sub_section','team.division as division_id','team.section as section_id','team.sub_section as sub_sectionid','team.status','emp.title','emp.first_name','emp.middle_name','emp.last_name','team.created_at')
						->get();
			
			
			//Convert Team Member id to name
			$tm=DB::table('team')->select('team_member')->get();
			$team_member_name=array();
			$i=0;
			$j=0;
			$empname='';
			$tm=json_decode($tm,true);
			foreach($tm as $team_m)
				{
				
					$team_m=explode(',',$team_m['team_member']);
					$length=sizeof($team_m);
				
			while($i<$length)
				{
					 $a=$team_m[$i];
				$emp_name= DB::table('emp')
						   ->select('title','first_name','middle_name','last_name')	
						   ->where('id',$a)
						   ->get();
				$name = $emp_name[0]->title.' '.$emp_name[0]->first_name.' '.$emp_name[0]->middle_name.' '.$emp_name[0]->last_name;
			
				$empname=$empname.', '.$name;
				
				$i++;
				}
				$empname=ltrim($empname,',');
				
				$team_member_name[$j]=$empname;
			 $i=0;
			 $empname='';
			 $j++;
			}
			//Convert Team Member id to name end
			
			return view('create-team',compact('team','team_data','team_leader','team_member','branch','team_member_name','uniq_team_mem','uniq_team_lead','divisions','sections','subsections'));
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
	public function team_create(Request $request)
	{	
	
		try
		{
			if(Session::get('username')!='')
        	{
				$rules = array(
					'branch_id' => 'required',
					'team_name' => 'required|string',
					'division' => 'required',
					'section' => 'required',
					'subsection' => 'nullable',
					'team_leader' =>'required',
					'team_member' => 'required',
					'status' => 'required'
				);

				$validator = Validator::make(Input::all(), $rules);

				if($validator->fails())
				{
		            activity()->log('Add Team process failed due to Validation Error');
		            return redirect()->back()
		            ->withErrors($validator)
		            ->withInput($request->all);
		        } 

				activity()->log('Trying to create team');
				$branch_id = $request->input('branch_id');
				$team_name = $request->input('team_name');
				$division = $request->input('division');
				$section = $request->input('section');
				$sub_section = $request->input('subsection');
				$team_leader = $request->input('team_leader');
				$team_member = $request->input('team_member');
				$team_member = implode(',', $team_member);
				$status = $request->input('status');
				
				$team=DB::table('team')->insert(
				['branch_id'=>$branch_id,'team_name'=>$team_name,'division'=> $division,'section'=>$section,'sub_section'=> $sub_section,'team_leader'=>$team_leader,'team_member'=> $team_member,'status'=> $status,'created_at'=>carbon::now()]
				);
				
				 if($team)
				{
					
					activity()->log('Team '.$team_name.' Created Successfully');
					$request->session()->flash('alert-success', 'Team Created Successfully!');
					return Redirect::to('create-team');
				}
				else
				{
					activity()->log('Team '.$team_name.' cannot be created');
					$request->session()->flash('alert-danger', 'Team cannot be created!');
					return Redirect::to('create-team');
				}
			}
			else
			{
                return redirect('/')->with('alert-warning',"Please login First");	
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
	public function team_update(Request $request)
		{

			try
			{
				if(Session::get('username')!='')
        		{
				$id=$request->input('id');	
				$branch_id = $request->input('branch_id');
				$team_name = $request->input('team_name');
				$division = $request->input('division');
				$section = $request->input('section');
				$sub_section = $request->input('subsection');
				$team_leader = $request->input('team_leader');
				$team_member = $request->input('team_member');
				$team_member = implode(',', $team_member);
				$status = $request->input('status');
					activity()->log('Trying to update team with id '.$id.' ');
					$department = $request->input('department');
					$team = $request->input('team');

					$team=DB::table('team')
					  ->where('id', $id)
					  ->update(['branch_id'=>$branch_id,'team_name'=>$team_name,'division'=> $division,'section'=>$section,'sub_section'=> $sub_section,'team_leader'=>$team_leader,'team_member'=> $team_member,'status'=> $status,'updated_at'=>carbon::now()]);
			
					if($team)
						{
						activity()->log('team with id '.$id.' Updated Successfully');
						$request->session()->flash('alert-success', 'team Updated Successfully!');
						return Redirect::to('create-team');
						}
					else
						{
						activity()->log('team id '.$id.' cannot be updated');
						$request->session()->flash('alert-danger', 'team cannot be updated!');
						return Redirect::to('team');
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
		
		public function getSelect(Request $request)
	{
		try
		{
			
			$id = $request->team_id;
			$team_leader1 = $request->team_leader;
			$team_leader=DB::table('emp')->select('title','first_name','middle_name','last_name')
			->where('id',$team_leader1)
			->get();
			$team_leader_name = $team_leader[0]->title.' '.$team_leader[0]->first_name.' '.$team_leader[0]->middle_name.' '.$team_leader[0]->last_name;
			$tl=DB::table('team')->select('team_leader')
			->where('status','active')
			->get();
			$tl=json_decode($tl,true);
			$k=0;
			$uniq_team_lead=array();
			foreach($tl as $teaml)
				{
				
			     array_push($uniq_team_lead,$teaml['team_leader']);
				
				}
				
			
			$team_member1 = $request->tm;
			$tm=DB::table('team')->select('team_member')
			->where('status','active')
			->get();
			$uniq_team_mem=array();
			$i=0;
			$j=0;
			$tm=json_decode($tm,true);
			
			foreach($tm as $team_m)
				{
				
					$team_m=explode(',',$team_m['team_member']);
					$length=sizeof($team_m);
				
			while($i<$length)
				{
					array_push($uniq_team_mem,$team_m[$i]);
					$i++;
				}
			 $i=0;
			}
			$team_member_id=DB::table('designation')
							->where('designation','like','%Fashion Consultant%')
							->orWhere('designation','like','%fashion consultant%')
							->value('id');
			$team_member = DB::table('emp')
							->where('designation',$team_member_id)
							->get();
			$team_leader_id=DB::table('designation')
							->where('designation','like','%Team Leader%')
							->orWhere('designation','like','%team leader%')
							->value('id');
			$team_leader=DB::table('emp')
						->where('designation','=',$team_leader_id)
						->get();
						
			$output= '<div class="row clearfix">
						<div class="col-sm-3" align="right">
								 <label>Team Leader</label>
								</div>
                               <div class="col-sm-8">
                                    <div class="form-line">
									
                                           <select  autocomplete="none"  class="form-control select2 modal_team_leader" placeholder="Team"  placeholder="Name" required name="team_leader" id="team_leader1" style="width:100%" >';
										   
											 $output.='<option value="'.$team_leader1.'">'.$team_leader_name.'</option>';
										
											foreach($team_leader as $team_leaders)
											{
												
											if(!in_array($team_leaders->id,$uniq_team_lead))
											{
												 
												 $output.='<option value="'.$team_leaders->id.'">'.$team_leaders->title.' '.$team_leaders->first_name.' '.$team_leaders->middle_name.' '.$team_leaders->last_name.'</option>';
											 }	
											
											}
										   $output.='</select>
                                     </div>
                                 </div>
                            </div><br>
							<div class="row clearfix">
						<div class="col-sm-3" align="right">
								 <label>Team Member</label>
								</div>
                               <div class="col-sm-8">
                                    <div class="form-line">
										    <select autocomplete="none" name="team_member[]" id="team_member1" class="form-control modal_team_member    select2" placeholder="Name" multiple="multiple"style="width:100%"  required>';
											  $i=0;
											 $team_members1=explode(',',$team_member1);
											
											for($i=0;$i<sizeof($team_members1);$i++)
											{
												$team_member2=DB::table('emp')->where('id',$team_members1[$i])->get();  
												$team_member_name = $team_member2[0]->title.' '.$team_member2[0]->first_name.' '.$team_member2[0]->middle_name.' '.$team_member2[0]->last_name;
											 $output.='<option value="'.$team_members1[$i].'" selected>'.$team_member_name.'</option>';
										     
											}
											 
											
			            		            foreach($team_member as $team_members)
											{
												if(!in_array($team_members->id,$uniq_team_mem))
												{	
												$output.='<option value="'.$team_members->id.'">'.$team_members->title.' '.$team_members->first_name.' '.$team_members->middle_name.' '.$team_members->last_name.'</option>';
												}	
											}
			            	  $output.='</select>
											
                                        </div>
                                 </div>
                            </div> ';

	       
			return $output;
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
}
