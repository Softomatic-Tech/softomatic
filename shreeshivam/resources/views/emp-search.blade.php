@extends('layouts.form-app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Employee</h2>
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
                                SEARCH EMPLOYEE
                            </h2>
                        </div>
                        <div class="body">
                        	 <form id="form_search" method="POST" action="add-employee/submit" enctype="multipart/form-data">
                        		<div class="row clearfix">
                        			<div class="form-group">
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

			            				
			            				<!-- <div class="col-md-3 col-sm-6 col-xs-12">
			            					<div class="">
			            						<input autocomplete="none" type="button" name="show" id="show" class="form-control btn btn-xs btn-warning" placeholder="Show" value="SHOW REPORT" >
			            					</div>
			            				</div> -->

		            				</div>
		            			</div>
                        	</form>
                        </div>
                    </div>

                    <div class="card">
                        <div class="header">
                            <h2>
                                EMPLOYEE DETAILS
                            </h2>
                        </div>
                        <div class="body" id="my_table_body">
                        	
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

		$("#emp_name").select2({
		     placeholder: "Search Employee Name",
		     allowClear: true
	    });

	     $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


			//$('.myinouttable').hide();
			
			$('#emp_name').change(function(){
				$.ajax({
                type:'POST',
                datatype: 'text',
                url:'get-emp-details',
                data:{id:$(this).val()},
                success:function(data){
                   $('#my_table_body').empty().html(data);
                }
                });
			});

			

			$('#my_table_body').on("click",".myadd1",function(e){ /*alert('123');*/
				// $('.modal-body').html($(this).closest('tr').find('.myinouttable').html());
        		$(this).closest('tr').find('.myinoutmodal').modal('show');
        		//alert($(this).closest('tr').find('.myinouttable').html());
			});

			 
			

		});
	</script>
@endsection