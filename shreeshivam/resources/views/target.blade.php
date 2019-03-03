 @extends('layouts.form-app')

 @section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Target</h2>
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
                               Assign Target
                             </h2>
                          
                        </div>
					<form action="target/submit"  method="POST">
					{{ csrf_field() }}
					<!--body Start-->	
                        <div class="body">
                        	<div class="row clearfix ">
                                <div class="form-group">
            					<div class="col-md-4 col-sm-6 col-xs-12">
            						<label for="doj">Start Date</label>
            					</div>
            					<div class="col-md-4 col-sm-6 col-xs-12">
	            					<div class="form-line">
	            						<input autocomplete="none" type="text" name="from" id="from" class="form-control datepicker" placeholder="Start Date" value="{{old('from')}}" required="">
	            						<span class="text-danger">{{ $errors->first('from') }}</span>
	            					</div>
	            				</div>
            				</div>
                        </div>
            				<div class="row clearfix ">
                                <div class="form-group">
            					<div class="col-md-4 col-sm-6 col-xs-12">
            						<label for="doj">End Date</label>
            					</div>
            					<div class="col-md-4 col-sm-6 col-xs-12">
	            					<div class="form-line">
	            						<input autocomplete="none" type="text" name="to" id="to_date" class="form-control datepicker" placeholder="End Date" value="{{old('to')}}" required="">
	            						<span class="text-danger">{{ $errors->first('to') }}</span>
	            					</div>
	            				</div>
	            			</div>
                        </div>

                            <div class="row clearfix">
                                <div class="col-md-4">
								 <label>Select Team</label>
								</div>
							   <div class="col-md-4">
                                   <div class="form-line">
                                           <select class="form-control team_name" placeholder="Team" required name="team_name" id="team_id">
											<option value="">---Select Team---</option>
											
											</select>
                                        </div>
                                  </div>
                            </div>
                            <div class="team_details">
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
                               Target List
                            </h2>
                          </div>
                        <div class="body table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr class="bg-orange">
                                        <th>#</th>
                                        <th>Team Name</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
										 <th>Target</th>
                                        <th>Action</th>
                                      </tr>
                                </thead>
                                <tbody>
								<?php 
								$i=1;
								?>
								
                                  @foreach($target as $target1)
								   <tr>
									 {{ csrf_field() }}
									  <th scope="row"></th>
                                        <td>{{$target1->team_name}}</td>
										 <td>{{date('d-m-Y',strtotime($target1->start_date))}}</td>
										 <td>{{date('d-m-Y',strtotime($target1->end_date))}}</td>
										 <td>{{$target1->total_target}}</td>
                                        <td> <button type="button" id="" 
										class="btn bg-teal waves-effect edit-modal" data-toggle="modal" 
										data-target="#editmodal" data-id="{{$target1->id}}" data-teamid="{{$target1->team_id}}" data-start="{{$target1->start_date}}" data-end="{{$target1->end_date}}">
                                    <i class="material-icons">create</i>
                                </button>
								<!-- <button type="delete" data-id="" data-target="#deleteModal" data-toggle="modal" class="btn bg-red waves-effect delete-modal">
                                    <i class="material-icons">delete</i>
                                </button> -->
                                    </td>   
                                
								   </tr>
                               @endforeach
                                 
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
				
				 <div class="modal fade" id="editmodal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-pink">
                            <h4 class="modal-title" id="defaultModalLabel">Update Target</h4>
                        </div>
                        <form action="target/update"  method="POST">
						 <div class="modal-body">
                        	{{ csrf_field() }}

                        	<div class="row clearfix ">
                                <div class="form-group">
            					<div class="col-md-6 col-sm-6 col-xs-12">
            						<label for="doj">Start Date</label>
            					</div>
            					<div class="col-md-6 col-sm-6 col-xs-12">
	            					<div class="form-line">
	            						<input autocomplete="none" type="text" name="modal_from" id="modal_from" class="form-control datepicker" placeholder="Start Date" value="{{old('modal_from')}}" required="">

	            						<input type="hidden" name="old_modal_from" id="old_modal_from" class="datepicker" value="">

	            						<span class="text-danger">{{ $errors->first('modal_from') }}</span>
	            					</div>
	            				</div>
            				</div>
                        </div><br>
            				<div class="row clearfix ">
                                <div class="form-group">
            					<div class="col-md-6 col-sm-6 col-xs-12">
            						<label for="doj">End Date</label>
            					</div>
            					<div class="col-md-6 col-sm-6 col-xs-12">
	            					<div class="form-line">
	            						<input autocomplete="none" type="text" name="modal_to" id="modal_to" class="form-control datepicker" placeholder="End Date" value="{{old('modal_to')}}" required="">

	            						<input type="hidden" name="old_modal_to" id="old_modal_to" class="datepicker" value="">

	            						<span class="text-danger">{{ $errors->first('modal_to') }}</span>
	            					</div>
	            				</div>
	            			</div>
                        </div><br>

                        	<div class="modal_team_details">
                        		
                        	</div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn  bg-deep-orange waves-effect update">Update</button>
                            <button type="button" class="btn bg-blue-grey  waves-effect" data-dismiss="modal">Dismiss</button>
                        </div>
						</form>
                    </div>
                </div>
            </div>       

        </div>
		</div>
    </section>  

   <style type="text/css">
   	.datepicker{z-index:5151 !important;}
   </style>
@endsection
@section('jquery')
<script>
$(document).ready(function() {

	 $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

    		$('.datepicker').datepicker().on('changeDate', function(ev){
			    $('.dropdown-menu').hide();
			});

    		$('#to_date').on('changeDate',function(){
    			if($('#from').val()=='')
    			{
    				alert('Please select Start Date')
    				$(this).val('');
    				$('#from').focus();
    			}
    			else
    			{
    			$.ajax({
    				type:'POST',
    				datatype: 'text',
    				url:'get_team_list',
	                data:{from:$('#from').val(),to:$(this).val()},
	                success:function(data){
	                	$('#team_id').empty().html(data);	                    
	                }
    			});
    			}
    		});
    		$('#team_id').change(function(){
    			if($(this).val()=='')
    			{
    				$('.team_details').empty();
    			}
    			else
    			{
	    			$.ajax({
	    				type:'POST',
	    				datatype: 'text',
	    				url:'get_team',
		                data:{team_id:$(this).val()},
		                success:function(data){
		                	$('.team_details').empty().html(data);	                    
		                }
	    			});
    			}
    		});

    		$('.team_details').on('keyup','.member_target',function(){
    			var sum=0;
    			$('.member_target').each(function(){
    				var myvar = $(this).val();
    				if($(this).val()=='') myvar=0;
    				sum+= parseInt(myvar);
    			});
    			// sum+=parseInt($('#leader_target').val());
    			$('#team_target').val(sum);
    		});

    		$('.modal_team_details').on('keyup','.modal_member_target',function(){
    			var sum=0;
    			$('.modal_member_target').each(function(){
    				var myvar = $(this).val();
    				if($(this).val()=='') myvar=0;
    				sum+= parseInt(myvar);
    			});
    			// sum+=parseInt($('#leader_target').val());
    			$('#modal_team_target').val(sum);
    		});

	$(document).on('click', '.edit-modal', function(){
		var team_id = $(this).data('teamid');
		var target_id = $(this).data('id');
		var start_date = $(this).data('start');
		var end_date = $(this).data('end');
		$('#modal_from').val(start_date);
		$('#modal_to').val(end_date);

		$('#old_modal_from').val(start_date);
		$('#old_modal_to').val(end_date);

		$.ajax({
				type:'POST',
				datatype: 'text',
				url:'get_team2',
                data:{team_id:team_id, target_id:target_id},
                success:function(data){
                	$('.modal_team_details').empty().html(data);	                    
                }
			});

        $('#n').val($(this).data('name'));
        $('#editmodal').modal('show');
    });

	$(document).on('click', '.delete-modal', function(){
		$('#id').val($(this).data('id'));
        $('#deleteModal').modal('show');
    });
  //    $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
		// $("#success-alert").slideUp(500);
		// });
}); 
   
</script>
<script>
     $(document).ready(function() {
        $(".team_name").select2({
	     placeholder: "Select Team",
	     allowClear: true,
	     multiple: false
	    });

     });
</script>

@endsection