@extends('layouts.form-app')

@section('content')

<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Team</h2>
            </div>
<div class="flash-message" id="success-alert">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
      @if(Session::has('alert-' . $msg))
		<p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
      @endif
    @endforeach
	
  </div> <!-- end .flash-message -->
            <!-- Content here -->
              <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Create Team
                             </h2>
                          
                        </div>
					<form action="create-team/submit"  method="POST">
					{{ csrf_field() }}
					<!--body Start-->	
                        <div class="body">
						<div class="row clearfix">
                                <div class="col-sm-2" align="right">
								 <label> Branch</label>
								</div>
							   <div class="col-sm-4">
                                   <div class="form-line">
                                           <select class="form-control show-tick" placeholder="branch" name="branch_id" id="branch_id">
											<option value="">---Select Branch---</option>
											@foreach($branch as $branches)
											<option value="{{$branches->id}}">{{$branches->branch}}</option>
											@endforeach
											</select>
											<span class="text-danger">{{ $errors->first('branch') }}</span>
                                        </div>
                                  </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-2" align="right">
								 <label> Team</label>
								</div>
							   <div class="col-sm-4">
                                   <div class="form-line">
                                          <input type="text" class="form-control" name="team_name" id="team_name" placeholder="Team Name">
                                          <span class="text-danger">{{ $errors->first('team_name') }}</span>
                                        </div>
                                  </div>
                            </div>
							 <div class="row clearfix">
                                <div class="col-sm-2" align="right">
								 <label> Division</label>
								</div>
							   <div class="col-sm-4">
                                   <div class="form-line">
                                      <!-- <input type="text" class="form-control" name="division" id="division"> -->
                                       <select class="form-control select2 division" placeholder="Division" name="division" id="division">
											<option value="">---Select Division---</option>
											@foreach($divisions as $division)
											<option value="{{$division->id}}">{{$division->division}}</option>
											@endforeach
										</select>
										<span class="text-danger">{{ $errors->first('division') }}</span>
                                   </div>
                                  </div>
                            </div>
							 <div class="row clearfix">
                                <div class="col-sm-2" align="right">
								 <label> Section</label>
								</div>
							   <div class="col-sm-4">
                                   <div class="form-line">
                                           <!-- <input type="text" class="form-control" name="section" id="section"> -->
                                            <select class="form-control select2 section" placeholder="section" name="section" id="section">
												<option value="">---Select section---</option>
												@foreach($sections as $section)
												<option value="{{$section->id}}">{{$section->section}}</option>
												@endforeach
											</select>
											<span class="text-danger">{{ $errors->first('section') }}</span>
                                        </div>
                                  </div>
                            </div>
							<div class="row clearfix">
                                <div class="col-sm-2" align="right">
								 <label>Sub-Section</label>
								</div>
							   <div class="col-sm-4">
                                   <div class="form-line">
                                           <!-- <input type="text" class="form-control" name="subsection" id="subsection"> -->
                                            <select class="form-control select2 subsection" placeholder="subsection" name="subsection" id="subsection">
												<option value="">---Select Sub Section---</option>
												@foreach($subsections as $subsection)
												<option value="{{$subsection->id}}">{{$subsection->sub_section}}</option>
												@endforeach
											</select>
											<span class="text-danger">{{ $errors->first('subsection') }}</span>
                                        </div>
                                  </div>
                            </div>
							 <div class="row clearfix">
                                <div class="col-sm-2" align="right">
								 <label>Team Leader</label>
								</div>
							   <div class="col-sm-4">
                                   <div class="form-line">
                                           <select  autocomplete="none"  class="form-control select2 team_leader" placeholder="Team"  name="team_leader" id="team_leader">
											<!-- <option value="">---Select Team Leader---</option>
											@foreach($team_leader as $team_leaders)
									        @if(!in_array($team_leaders->id,$uniq_team_lead))
											<option value="{{$team_leaders->id}}">{{$team_leaders->title}} {{$team_leaders->first_name}} {{$team_leaders->middle_name}} {{$team_leaders->last_name}}</option>
											@endif
											@endforeach -->
											
											</select>
											<span class="text-danger">{{ $errors->first('team_leader') }}</span>
                                        </div>
                                  </div>
                            </div>
							
							<div class="row clearfix">
                                <div class="col-sm-2" align="right">
								 <label>Team Member</label>
								</div>
								
							<div class="col-sm-4">
                               <div class="form-line" >
		                             <select autocomplete="none" type="text" name="team_member[]" id="team_member" class="form-control team_member select2" placeholder="Name" value="{{ old('team_member') }}" multiple="multiple">
										
					            		<!-- @foreach($team_member as $team_members)
										@if (!in_array($team_members->id,$uniq_team_mem))
													<option value="{{$team_members->id}}">{{$team_members->title}} {{$team_members->first_name}} {{$team_members->middle_name}} {{$team_members->last_name}}</option>
													@endif
													@endforeach -->
													
					            	</select>
					            	<span class="text-danger">{{ $errors->first('team_member') }}</span>
							</div>
							<div>
							
							
							</div>
                            </div>
						  </div>
							
							<div class="row clearfix">
                                <div class="col-sm-2" align="right">
								 <label>Status</label>
								</div>
							<div class="col-sm-4">
                               <div class="form-line">
                             <select autocomplete="none" type="text" name="status" id="status" class="form-control status" placeholder="status" value="{{ old('status') }}" >
			            		<option value="active">Active</option>
								<option value="inactive">Inactive</option>			
			            	</select>
			            	<span class="text-danger">{{ $errors->first('status') }}</span>
							</div>
                            </div>
						  </div>
						
						  <div class="row clearfix">
                               <div class="col-sm-4 col-sm-push-3">
                                    <div class="form-group">
										<button type="submit" name="submit" class="btn bg-green waves-effect"><i class="material-icons">save</i><span class="icon-name">Save</span></button>
                                    </div>
                                </div>
                            </div>
						  </div>
                   	<!--body End-->	
					</form>
				   </div>
                </div>
			</div>
			 <div class="row clearfix">
			       <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Team List
                            </h2>
                          </div>
                        <div class="body table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr class="bg-orange">
                                        <th>#</th>
                                        <th>Team Name</th>
										<th>Branch</th>
										<th>Division</th>
										<th>Section</th>
										<th>Sub Section</th>
										<th>Team Leader</th>
										<th>Team Member</th>
										<th>Created At</th>
										<th>Status</th>
                                        <th>Action</th>
                                      </tr>
                                </thead>
                                <tbody>
								<?php 
								$i=1;
								$j=0;
								?>
								
                                  @foreach($team_data as $teams_data) 
								   <tr>
									 {{ csrf_field() }}
									
									   <th scope="row">{{$i++}}</th>
                                        <td>{{$teams_data->team_name}}</td>
										<td>{{$teams_data->branch}}</td>
										<td>{{$teams_data->division}}</td>
										<td>{{$teams_data->section}}</td>
										<td>{{$teams_data->sub_section}}</td>
										<td>{{$teams_data->title}} {{$teams_data->first_name}} {{$teams_data->middle_name}} {{$teams_data->last_name}}</td>
										<td>{{$team_member_name[$j]}}</td>
										<td>{{$teams_data->created_at}}</td>
										<td>{{$teams_data->status}}</td>
										<td> <button type="button" id="{{$teams_data->id}}" 
										class="btn bg-teal waves-effect edit-modal" data-toggle="modal" 
										data-target="#updateModal" data-id="{{$teams_data->id}}" data-team_name="{{$teams_data->team_name}}"  data-branch="{{$teams_data->branch_id}}"  data-division="{{$teams_data->division_id}}"   data-section="{{$teams_data->section_id}}" data-sub_section="{{$teams_data->sub_sectionid}}" data-status="{{$teams_data->status}}" data-team_leader="{{$teams_data->team_leader}}"
										data-team_member="{{$teams_data->team_member}}"
										>
                                    <i class="material-icons">create</i>
                                </button>
							<!--	<button type="delete" data-id="" data-target="#deleteModal" data-toggle="modal" class="btn bg-red waves-effect delete-modal">
                                    <i class="material-icons">delete</i>
                                </button>-->
                                    </td>   
                              
								   </tr>
								   <?php
								   $j++;
								   ?>
                                 @endforeach                                 
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
				
        </div>
		</div>
    </section>
	<div class="modal fade" id="updateModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-pink">
                            <h4 class="modal-title" id="uModalLabel">Edit team</h4>
                        </div>
						
                        <div class="modal-body">
						<form action="team/update"  method="POST">
						{{ csrf_field() }}
						<div class="row clearfix">
						<div class="col-sm-3" align="right">
								 <label>Branch</label>
								</div>
                               <div class="col-sm-8">
                                    <div class="form-line">
										    <input type="hidden" id="fid" name="id"  class="form-control" placeholder="team Name"/>
                                             <select class="form-control show-tick" placeholder="branch" name="branch_id" id="modal_branch_id">
											<option value="">---Select Branch---</option>
											@foreach($branch as $branches)
											<option value="{{$branches->id}}">{{$branches->branch}}</option>
											@endforeach
											</select>
                                        </div>
                                   
                                </div>
                            </div> <br>
							<div class="row clearfix">
						<div class="col-sm-3" align="right">
								 <label>Team Name</label>
								</div>
                               <div class="col-sm-8">
                                    <div class="form-line">
										   <input type="text" class="form-control" name="team_name" id="modal_team_name">
											
                                        </div>
                                 </div>
                            </div><br> 
							<div class="row clearfix">
						<div class="col-sm-3" align="right">
								 <label>Division</label>
								</div>
                               <div class="col-sm-8">
                                    <div class="form-line">
										   <!-- <input type="text" class="form-control" name="division" id="modal_division"> -->
											<select autocomplete="none" type="text" name="division" id="modal_division" class="form-control division" placeholder="Division">
												<option value="">---Select Division---</option>
												@foreach($divisions as $division)
												<option value="{{$division->id}}">{{$division->division}}</option>
												@endforeach
											</select>
                                        </div>
                                 </div>
                            </div> <br>
							<div class="row clearfix">
						<div class="col-sm-3" align="right">
								 <label>Section</label>
								</div>
                               <div class="col-sm-8">
                                    <div class="form-line">
										   <!-- <input type="text" class="form-control" name="section" id="modal_section"> -->
											<select autocomplete="none" type="text" name="section" id="modal_section" class="form-control section" placeholder="Section">
												<option value="">---Select section---</option>
												@foreach($sections as $section)
												<option value="{{$section->id}}">{{$section->section}}</option>
												@endforeach
											</select>
                                        </div>
                                 </div>
                            </div><br>
						<div class="row clearfix">
						<div class="col-sm-3" align="right">
								 <label>Sub Section</label>
								</div>
                               <div class="col-sm-8">
                                    <div class="form-line">
										   <!-- <input type="text" class="form-control" name="subsection" id="modal_subsection"> -->
											<select autocomplete="none" type="text" name="subsection" id="modal_subsection" class="form-control subsection">
												<option value="">---Select Sub Section---</option>
												@foreach($subsections as $subsection)
												<option value="{{$subsection->id}}">{{$subsection->sub_section}}</option>
												@endforeach
											</select>
                                        </div>
                                 </div>
                            </div><br> 
							<div id="select">
						
							</div>
						<div class="row clearfix">
						<div class="col-sm-3" align="right">
								 <label>Status</label>
								</div>
                               <div class="col-sm-8">
                                    <div class="form-line">
										    <select autocomplete="none" type="text" name="status" id="modal_status" class="form-control status" placeholder="status" value="{{ old('status') }}" >
												<option value="active">Active</option>
												<option value="inactive">Inactive</option>			
											</select>
										 </div>
                                 </div>
                            </div><br> 							
                       </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn  bg-deep-orange waves-effect">Save Changes</button>
                            <button type="button" class="btn bg-blue-grey  waves-effect" data-dismiss="modal">Close</button>
                        </div>
						</form>
                    </div>
                </div>
            </div>
			
			<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-pink">
                            <h4 class="modal-title" id="defaultModalLabel">Delete team</h4>
                        </div>
						<form action="team/delete"  method="POST">
                        <div class="modal-body">						
						{{ csrf_field() }}
						<input type="hidden" id="id" name="dept_id"  class="form-control" placeholder="team Name"/>
						       <h5> Are you sure you want to delete this?</h5>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn  bg-deep-orange waves-effect">Yes</button>
                            <button type="button" class="btn bg-blue-grey  waves-effect" data-dismiss="modal">No</button>
                        </div>
						</form>
                    </div>
                </div>
            </div>
            <style type="text/css">
            	.select2
            	{
            		width: 100%!important;
            	}
            </style>
@endsection
@section('jquery')
<script>
$(document).ready(function() {

	 /*$(".division").select2({
					 placeholder: "Select Division",
					 allowClear: true
					});
	   $(".section").select2({
					 placeholder: "Select Section",
					 allowClear: true
					});
	   $(".subsection").select2({
					 placeholder: "Select Sub Section",
					 allowClear: true
					});*/

	$(document).on('click', '.edit-modal', function(){
       var team_id=$(this).data('id');
	   var team_leader=$(this).data('team_leader');	  
	  	$('#fid').val(team_id);
		$("#modal_branch_id").children('[value="'+$(this).data('branch')+'"]').attr('selected', true);
		$("#modal_division").children('[value="'+$(this).data('division')+'"]').attr('selected', true);
		var modal_division = $(this).data('division');
		var modal_section = $(this).data('section');
		var modal_subsection = $(this).data('sub_section');
		$.ajax({
			type:'POST',
			datatype: 'text',
			url:'getsection',
            data:{division:modal_division},
            success:function(data){ 
            	$('#modal_section').empty().append(data);   
            	$("#modal_section").children('[value="'+modal_section+'"]').attr('selected', true);     	
            }
			});
		$.ajax({
			type:'POST',
			datatype: 'text',
			url:'getsubsection',
            data:{division:modal_division,section:modal_section},
            success:function(data){ 
            	$('#modal_subsection').empty().append(data);   
            	$("#modal_subsection").children('[value="'+modal_subsection+'"]').attr('selected', true);         	
            }
			});
		
		
		$("#modal_status").children('[value="'+$(this).data('status')+'"]').attr('selected', true);
	    $('#modal_team_name').val($(this).data('team_name'));
		
		var tm=$(this).data('team_member');
		var tm1=tm.split(",");
		$.ajax({
				
				type:'POST',
				datatype: 'text',
				url:'get_select',
                data:{team_id:team_id,team_leader:team_leader,tm:tm},
                success:function(data){
					$('#select').html(data);
					$('#team_leader1').val(team_leader);   
					$('#team_member1').val(tm1);
					
					$(".modal_team_member").select2({
					 placeholder: "Select Team Member",
					 allowClear: true,
					 multiple: true
					});
		
					 $(".modal_team_leader").select2({
					 placeholder: "Select Team Leader",
					 allowClear: true,
					 multiple: false
					});
                }
			});
		$('#updateModal').modal('show');
		
 });
	$(document).on('click', '.delete-modal', function(){
		$('#id').val($(this).data('id'));
        $('#deleteModal').modal('show');
    });
     $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
		$("#success-alert").slideUp(500);
		});
	$('#branch_id').change(function(){
		$.ajax({
			type:'POST',
			datatype: 'text',
			url:'get_branch_emp',
            data:{branch_id:$(this).val()},
            success:function(data){ data = JSON.parse(data);
				$('#team_leader').html(data.team_leader);
				$('#team_member').html(data.team_member);
				
            }
			});
	});

	$('#division').change(function(){
		$.ajax({
			type:'POST',
			datatype: 'text',
			url:'getsection',
            data:{division:$(this).val()},
            success:function(data){ 
            	$('#section').empty().append(data);
            	$('#subsection').empty().append('<option value="">--Select Sub Section--</oprion>');
            }
			});
	});
	$('#section').change(function(){
		$.ajax({
			type:'POST',
			datatype: 'text',
			url:'getsubsection',
            data:{division:$('#division').val(),section:$(this).val()},
            success:function(data){ 
            	$('#subsection').empty().append(data);
            }
			});
	});
		
	$('#modal_division').change(function(){
		$.ajax({
			type:'POST',
			datatype: 'text',
			url:'getsection',
            data:{division:$(this).val()},
            success:function(data){ 
            	$('#modal_section').empty().append(data);
            	$('#modal_subsection').empty().append('<option value="">--Select Sub Section--</oprion>');
            }
			});
	});
	$('#modal_section').change(function(){
		$.ajax({
			type:'POST',
			datatype: 'text',
			url:'getsubsection',
            data:{division:$('#modal_division').val(),section:$(this).val()},
            success:function(data){ 
            	$('#modal_subsection').empty().append(data);
            }
			});
	});
}); 
   
</script>
<script>
     $(document).ready(function() {
        $(".team_member").select2({
	     placeholder: "Select Team Member",
	     allowClear: true,
	     multiple: true
	    });
		
		 $(".team_leader").select2({
	     placeholder: "Select Team Leader",
	     allowClear: true,
	     multiple: false
	    });
		
     });
</script>

@endsection