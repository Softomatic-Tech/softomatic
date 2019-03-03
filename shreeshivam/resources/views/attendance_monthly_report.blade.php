@extends('layouts.form-app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>ATTENDANCE REPORT</h2>
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
                                MONTHLY ATTENDANCE REPORT
                            </h2>
                        </div>
                        <div class="body">
                        	 <form id="form_search" method="POST" action="get_monthly_attendance" enctype="multipart/form-data">
                        		<div class="row clearfix">
                        			<div class="form-group">
		                            	<div class="col-md-3 col-sm-6 col-xs-12">
			            					<div class="">
			            						<select autocomplete="none" type="text" name="mymonth" id="mymonth" class="form-control mymonth select2" placeholder="Month" value="{{ old('mymonth') }}">
			            							<option></option>
			            							<option value="01">January</option>
			            							<option value="02">February</option>
			            							<option value="03">March</option>
			            							<option value="04">April</option>
			            							<option value="05">May</option>
			            							<option value="06">June</option>
			            							<option value="07">July</option>
			            							<option value="08">August</option>
			            							<option value="09">September</option>
			            							<option value="10">October</option>
			            							<option value="11">November</option>
			            							<option value="12">December</option>
			            						</select>
			            						<span class="text-danger">{{ $errors->first('month') }}</span>
			            					</div>
			            				</div>
			            				<div class="col-md-3 col-sm-6 col-xs-12">
			                               <div class="">
			                                    <div class="form-group">
			                                    <select class="form-control select2 branch" id="branch" name="branch">
			                                    <option value="">Select Branch</option>
			                                    @foreach($branch as $branches)
			                                    <option value="{{$branches->id}}">{{$branches->branch}}</option>
			                                    @endforeach
			                                    </select>
			                                       
			                                    </div>
			                                </div>			                                
			                            </div>
			            				<div class="col-md-3 col-sm-6 col-xs-12">
			            					<div class="">
			            						<select name="emp_name" id="emp_name" class="form-control emp_name select2" placeholder="Search Employee Name" value="{{ old('emp_name') }}">
			            							<option></option>
			            							@foreach($emp_name as $emp)
			            							<option value="{{$emp->id}}">{{$emp->title}} {{$emp->first_name}} {{$emp->middle_name}} {{$emp->last_name}}</option>
			            							@endforeach
			            						</select>
			            						<span class="text-danger">{{ $errors->first('emp_name') }}</span>
			            					</div>
			            				</div>
			            				<div class="col-md-3 col-sm-6 col-xs-12">
			            					<div class="">
			            						<input autocomplete="none" type="button" name="show" id="show" class="form-control btn btn-xs btn-warning" placeholder="Show" value="SHOW REPORT" >
			            					</div>
			            				</div>
			            				
		            				</div>
		            			</div>
                        	</form>
                        </div>
                    </div>

                    <div class="card attendance_card">
                        <div class="header">
                            <h2>
                                MONTHLY ATTENDANCE REPORT
                            </h2>
                        </div>
                        <div class="body" id="my_table_body">
                        	
                        </div>
                    </div>

                </div>
            </div>
            
        </div>

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
			$('.attendance_card').hide()
			$('.select2').select2();

	    $(".mymonth").select2({
	     placeholder: "Select Month",
	     allowClear: true
	    /* multiple: true*/
	    });
	    $(".emp_name").select2({
	     placeholder: "Select Employee",
	     allowClear: true
	    });
	    $(".branch").select2({
	     placeholder: "Select Branch",
	     allowClear: true
	    });
	   

	     $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

			$('#show').click(function(){
				if($('#mymonth').val()=='')
				{
					$('#mymonth').closest('div').find('.text-danger').html('Please Select Month');
					$('#mymonth').focus();
				}
				else
				{
					$.ajax({
	                type:'POST',
	                datatype: 'text',
	                url:'get_monthly_attendance',
	                data:{month:$('#mymonth').val(),emp_id:$('#emp_name').val(),'branch':$('#branch').val()},
	                success:function(data){
	                	$('.attendance_card').show();
	                    $('#my_table_body').html(data);   
	                }
	                });
				}
			});

			$('#my_table_body').on("click",".myadd1",function(e){ 
        		$(this).closest('tr').find('.myinoutmodal').modal('show');
        	});
		});
	</script>
@endsection