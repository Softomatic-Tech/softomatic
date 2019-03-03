@extends('layouts.form-app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>ATTENDANCE</h2>
            </div>
            <div class="flash-message" id="success-alert">
	            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
			      @if(Session::has('alert-' . $msg))
					<p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
			      @endif
			    @endforeach
			</div>
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                EDIT ATTENDANCE
                            </h2>
                        </div>
                        <div class="body">
                        	 <form id="form_search" method="POST" action="" enctype="multipart/form-data">
                        	 	{{ csrf_field() }}
                        		<div class="row clearfix">
                        			<div class="form-group">
		                            	<div class="col-md-3 col-sm-6 col-xs-12">
			            					<div class="">
			            						<select class="form-control select2 branch_location_id" placeholder="Shreeshivam Branch" id="branch_location_id" name="branch_location_id">
			                                        <option></option>
			                                        @foreach($branches as $branch)
			                                            <option value="{{$branch->id}}">{{$branch->branch}}</option>
			                                        @endforeach
			                                    </select>
			                                    <span class="text-danger">{{ $errors->first('branch_location_id') }}</span>
			            					</div>
			            				</div>
			            				<div class="col-md-3 col-sm-6 col-xs-12">
			            					<div class="">
			            						<select autocomplete="none" type="text" name="emp_id" id="emp_id" class="form-control emp_id select2" placeholder="Employee" value="{{ old('emp_id') }}" >
			            							<option></option>
			            						</select>
			            						<span class="text-danger">{{ $errors->first('emp_id') }}</span>
			            					</div>
			            				</div>

			            				<div class="col-md-3 col-sm-6 col-xs-12">
			            					<div class="">
			            						<input autocomplete="none" type="text" name="mydate" id="mydate" class="form-control mydate datepicker" placeholder="Date" value="{{ old('mydate') }}" />
			            							
			            						<span class="text-danger">{{ $errors->first('mydate') }}</span>
			            					</div>
			            				</div>

			            				<div class="col-md-3 col-sm-6 col-xs-12">
			            					<div class="">
			            						<input autocomplete="none" type="button" name="edit" id="edit" class="form-control btn btn-xs btn-warning" placeholder="EDIT" value="EDIT REPORT" >
			            					</div>
			            				</div>

		            				</div>
		            			</div>
                        	</form>
                        </div>
                    </div>

                    <div class="card" id="my_body">
                        <div class="header">
                            <h2>
                                ATTENDANCE
                            </h2>
                        </div>
                        <div class="body">
                        	 <form id="form_search" method="POST" action="edit_attendance/submit" enctype="multipart/form-data">
                        	 	{{ csrf_field() }}
                        	 	<div class="row clearfix">
		                    	 	<div class="col-md-6" id="my_table_body">
				                    		
				                    </div> 
				                </div>
				                <div class="row clearfix">
		                    	 	<div class="col-md-6" align="right">
		                    	 		<input type="submit" class="btn btn-success" name="update" value="UPDATE">
		                    	 	</div>
		                    	 </div>
                        	</form>                      	
                        </div>
                    </div>

                </div>
            </div>
            
        </div>

        <!-- <div class="modal fade" class="myinoutmodal" tabindex="-1" role="dialog">
            <div class="modal-dialog " role="document">
                <div class="modal-content">
                    <div class="modal-header bg-pink">
                        <h4 class="modal-title" id="defaultModalLabel">DAY REPORT</h4>
                    </div>					
                    <div class="modal-body">
					
                    </div>
                </div>
            </div>
        </div> -->

    </section>
<style type="text/css">
    .table-responsive
    {
	    min-height: .01%;
	    overflow-x: initial;
	}
</style>
@endsection
@section('jquery')
	

	

	<script type="text/javascript">
		$(document).ready(function(){
			$('.datepicker').datepicker().on('changeDate', function (ev) {
		        $('.dropdown-menu').hide();
		    });

			$('#my_body').hide();	

	    $(".branch_location_id").select2({
	     placeholder: "Select Branch",
	     allowClear: true
	    /* multiple: true*/
	    });
	    /*$(".department").select2({
	     placeholder: "Select Department",
	     allowClear: true
	   });*/
	    $(".emp_id").select2({
	     placeholder: "Select Employee",
	     allowClear: true
	    });
	    

	     $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


			//$('.myinouttable').hide();
			$('#branch_location_id').change(function(){
				if($(this).val()=='')
				{
					$(this).closest('div').find('.text-danger').html('Please Select Branch');
					$(this).focus();
				}
				else
				{
					$.ajax({
	                type:'POST',
	                datatype: 'text',
	                url:'get_emp',
	                data:{branch:$(this).val()},
	                success:function(data){
	                    $('.emp_id').html(data);
	                }
	                });
				}				
			});

			$('#edit').click(function(){
				if($('#branch_location_id').val()=='')
				{
					$('#branch_location_id').closest('div').find('.text-danger').html('Please Select Branch');
					$('#branch_location_id').focus();
				}
				else if($('#emp_id').val()=='')
				{
					$('#emp_id').closest('div').find('.text-danger').html('Please Select Employee');
					$('#emp_id').focus();
				}
				else if($('#mydate').val()=='')
				{
					$('#mydate').closest('div').find('.text-danger').html('Please Select Date');
					/*$('#mydate').focus();*/
				}
				else
				{
					$.ajax({
	                type:'POST',
	                datatype: 'text',
	                url:'get_date_attendance',
	                data:{branch:$('#branch_location_id').val(),emp_id:$('#emp_id').val(),date:$('#mydate').val()},
	                success:function(data){ 
	                    $('#my_table_body').html(data);
	                    $('#my_body').show();

	                   
	                },
	                complete:function(data){
	                	
	                }
	                });
				}
			});

			$('#my_body').on("click",".myadd1",function(e){ /*alert('123');*/
				// $('.modal-body').html($(this).closest('tr').find('.myinouttable').html());
        		$(this).closest('tr').find('.myinoutmodal').modal('show');
        		//alert($(this).closest('tr').find('.myinouttable').html());
			});

			 
			

		});
	</script>
@endsection