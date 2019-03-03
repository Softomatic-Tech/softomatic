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
                                SEARCH ATTENDANCE REPORT
                            </h2>
                        </div>
                        <div class="body">
                        	 <form id="form_search" method="POST" action="" enctype="multipart/form-data">
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
			            						<select autocomplete="none" type="text" name="myweek" id="myweek" class="form-control myweek select2" placeholder="Week" value="{{ old('myweek') }}" >
			            							<option></option>
			            						</select>
			            						<span class="text-danger">{{ $errors->first('myweek') }}</span>
			            					</div>
			            				</div>

			            				<div class="col-md-3 col-sm-6 col-xs-12">
			            					<div class="">
			            						<select autocomplete="none" type="text" name="mydate" id="mydate" class="form-control mydate select2" placeholder="Name" value="{{ old('mydate') }}" >
			            							<option></option>
			            						</select>
			            						<span class="text-danger">{{ $errors->first('mydate') }}</span>
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

                    <div class="card">
                        <div class="header">
                            <h2>
                                ATTENDANCE REPORT
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

			$('.select2').select2();

	    $(".mymonth").select2({
	     placeholder: "Select Month",
	     allowClear: true
	    /* multiple: true*/
	    });
	    $(".myweek").select2({
	     placeholder: "Select Week",
	     allowClear: true
	    });
	    $(".mydate").select2({
	     placeholder: "Select Date",
	     allowClear: true
	    });

	     $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


			//$('.myinouttable').hide();
			$('.mymonth').change(function(){
				$.ajax({
                type:'POST',
                datatype: 'text',
                url:'getweek',
                data:{month:$(this).val()},
                success:function(data){
                    $('.myweek').html(data);
                }
                });
			});

			$('.myweek').change(function(){
				$.ajax({
                type:'POST',
                datatype: 'text',
                url:'getdate',
                data:{month:$('.mymonth').val(),week:$(this).val()},
                success:function(data){
                    $('.mydate').html(data);
                }
                });
			});

			$('#show').click(function(){
				if($('#mymonth').val()=='')
				{
					$('#mymonth').closest('div').find('.text-danger').html('Please Select Month');
					$('#mymonth').focus();
				}
				else if($('#mydate').val()!='' && $('#myweek').val()=='')
				{
					$('#myweek').closest('div').find('.text-danger').html('Please Select Week');
					$('#myweek').focus();
				}
				else
				{
					$.ajax({
	                type:'POST',
	                datatype: 'text',
	                url:'getattendance',
	                data:{month:$('#mymonth').val(),week:$('#myweek').val(),date:$('#mydate').val()},
	                success:function(data){
	                	//alert(data);
	                    $('#my_table_body').html(data);

	                   
	                },
	                complete:function(data){
	                	
	                }
	                });
				}
			});

			$('#my_table_body').on("click",".myadd1",function(e){ /*alert('123');*/
				// $('.modal-body').html($(this).closest('tr').find('.myinouttable').html());
        		$(this).closest('tr').find('.myinoutmodal').modal('show');
        		//alert($(this).closest('tr').find('.myinouttable').html());
			});

			 
			

		});
	</script>
@endsection