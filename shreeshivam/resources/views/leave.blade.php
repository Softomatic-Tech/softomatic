@extends('layouts.form-app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>LEAVE</h2>
                 <div class="flash-message" id="success-alert">
		            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
				      @if(Session::has('alert-' . $msg))
						<p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
				      @endif
				    @endforeach
				</div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                	@if(Session::get('role')!='admin')
                    <div class="card">
                        <div class="header">
                            <h2>APPLY FOR LEAVE</h2>

                           
                        </div>
                        <div class="body">
                        	{!! Form::open(['url' => 'leave/submit', 'files' => true]) !!}
                        		@csrf
                        		<!-- {{Form::select('Reason', ['Sick' => 'Sick', 'Occasion' => 'Occasion'], null, ['placeholder' => 'Select a reason for leave...'])}} -->
                        		<div class="row clearfix">  
                    				<div class="col-md-8 col-md-offset-2">
                    					<div class="row clearfix ">
                                            <div class="form-group">
		                        			<div class="col-md-4 col-sm-6 col-xs-12">
		                                		<label for="reason">Select Reason for leave</label>
		                                	</div>
		                                	<div class="col-md-8 col-sm-6 col-xs-12">  
		                        				<div class="form-line">                 			
				                        			<select class="form-control show-tick" name="reason" id="reason" required="">
				                        				<option value="">Select Reason for leave</option>
				                        				<option value="pl" <?php if(old('reason')=='pl') echo "selected"; ?> >Personal Leave</option>
				                        				<option value="ml" <?php if(old('reason')=='ml') echo "selected"; ?>>Sick Leave</option>
				                        				<option value="cl" <?php if(old('reason')=='cl') echo "selected"; ?>>Casual Leave</option>
                                                        <option value="lwp" <?php if(old('reason')=='lwp') echo "selected"; ?>>Leave Without Pay</option>
				                        				<option value="Other" <?php if(old('reason')=='Other') echo "selected"; ?>>Other</option>
				                        			</select>
				                        			<span class="text-danger">{{ $errors->first('doj') }}</span>
				                        		</div>
			                        		</div>
                                        </div>
			                        	</div>
                                        <div class="row clearfix ">
			                        	<div class="form-group" id="other_reason_div">
			            					<div class="col-md-4 col-sm-6 col-xs-12">
			            						<label for="other_reason">Other Reason</label>
			            					</div>
			            					<div class="col-md-8 col-sm-6 col-xs-12">
				            					<div class="form-line">
				            						<textarea autocomplete="none" name="other_reason" id="other_reason" class="form-control" placeholder="Other Reason For Leave">{{old('other_reason')}}</textarea>
				            						<span class="text-danger">{{ $errors->first('other_reason') }}</span>
				            					</div>
				            				</div>
				            			</div>
                                    </div>
			                        	<div class="row clearfix ">
                                            <div class="form-group">
			            					<div class="col-md-4 col-sm-6 col-xs-12">
			            						<label for="doj">From Date</label>
			            					</div>
			            					<div class="col-md-8 col-sm-6 col-xs-12">
				            					<div class="form-line">
				            						<input autocomplete="none" type="text" name="from" id="from" class="form-control datepicker" placeholder="From Date" value="{{old('from')}}" required="">
				            						<span class="text-danger">{{ $errors->first('from') }}</span>
				            					</div>
				            				</div>
			            				</div>
                                    </div>
			            				<div class="row clearfix ">
                                            <div class="form-group">
			            					<div class="col-md-4 col-sm-6 col-xs-12">
			            						<label for="doj">To Date</label>
			            					</div>
			            					<div class="col-md-8 col-sm-6 col-xs-12">
				            					<div class="form-line">
				            						<input autocomplete="none" type="text" name="to" id="to" class="form-control datepicker" placeholder="To Date" value="{{old('to')}}" required="">
				            						<span class="text-danger">{{ $errors->first('to') }}</span>
				            					</div>
				            				</div>
				            			</div>
                                    </div>
				            			<div class="row clearfix ">
                                            <div class="form-group">
					            			<div class="col-md-4 col-md-offset-4">
					            				<button autocomplete="none" type="submit" name="submit" class="btn btn-success btn-lg col-md-4">Apply</button>
				            				</div>
				            			</div>
                                        </div>				            			
		                        	</div>
                        		</div>
                        	{!! Form::close() !!}
                        </div>
                    </div>

                    @if(Session::get('role')!='hr')
                    <div class="card">
                        <div class="header">
                            <h2>LEAVE APPLICATIONS</h2>
                        </div>
                        <div class="body table-responsive">
                            @include('leavetable_emp')

                        </div>
                    </div>
                    @endif

                    @endif

                    @if((Session::get('role')=='admin') || Session::get('role')=='hr')
                    <div class="flash-message" id="msgalert">
                        <p class="alert alert-danger"><span id="mymsg"></span> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                    </div>
                    <div class="card">
                        <div class="header">
                            <h2>LEAVE APPLICATIONS</h2>
                        </div>
                        <div class="body table-responsive"> 
                        @include('leavetable')                       	
                                                                         
                        </div>
                    </div>
                    @endif
                </div>
            </div>            
        </div>
    </section>

    <div class="modal fade" id="mydescmodal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-pink">
                            <h4 class="modal-title" id="defaultModalLabel">Rejection Reason</h4>
                        </div>
                        
                       <!--  <form action="" id="modalform"  method="POST"> -->
                             {{ csrf_field() }}
                        <div class="modal-body" id="mybody">        
                        <textarea id="myreason" name="reason" class="form-control" placeholder="Enter Rejection reason" required></textarea>
                        <input type="hidden" name="action" id="myaction2" value="" required>          
                        <input type="hidden" id="id" name="id"  class="form-control" placeholder="" required/>
                        
                              <!--  <h5> Are you sure you want to delete this?</h5> -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn  bg-deep-orange waves-effect" id="yes">Submit</button>
                            <button type="button" class="btn bg-blue-grey  waves-effect" data-dismiss="modal">Dismiss</button>
                        </div>
                        <!-- </form> -->
                    </div>
                </div>
            </div>

            <div class="modal fade" id="my_reason_modal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-pink">
                            <h4 class="modal-title" id="defaultModalLabel1">Rejection Reason</h4>
                        </div>
                        
                        
                        <div class="modal-body" id="my_reason_value">        
                        
                        </div>
                        <div class="modal-footer">                           
                            <button type="button" class="btn bg-blue-grey  waves-effect" data-dismiss="modal">Dismiss</button>
                        </div>
                       
                    </div>
                </div>
            </div>
            <style type="text/css">
                .accept,.reject,.showreason
                {
                    margin:2.5px;
                }
            </style>
    @endsection

    @section('jquery')
    @if((Session::get('role')!='admin') && (Session::get('role')!='hr'))
    <script type="text/javascript">
        var autoLoad = setInterval(
                function ()
                {
                    $('#myleavetable_emp').load('leavetable_emp', function() {
                    });

                }, 30000);
    </script>
    @endif
    <script type="text/javascript">
    	$(document).ready(function(){
            $('#msgalert').hide();
             $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

    		$('.datepicker').datepicker().on('changeDate', function (ev) {
			    $('.dropdown-menu').hide();
			});
			if($('#reason').val()=='Other')
			{
				$('#other_reason_div').show();
			}
			else
			{
    			$('#other_reason_div').hide();
    		}
    		$('#reason').change(function(){
    			if($(this).val()=='Other')
    			{
    				$('#other_reason_div').show();
    			}
    			else
    			{
    				$('#other_reason_div').hide();
    			}
    		});

    		/*$("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
				$("#success-alert").slideUp(500);
			});*/

           $('#myleavetable').on("click",".reject",function(e){
                e.preventDefault();
                var id = $(this).closest('tr').find('.myid').val();
                var emp_id = $(this).closest('tr').find('.myempid').val();
                if(emp_id=="<?php echo session('user_id') ?>")
                { 
                    $('#mymsg').html('you can take action on your own application')
                    $('#msgalert').focus().show();
                }
                else
                {
                    $('#msgalert').hide();
                    $('#id').val(id);
                    $('#myaction2').val('Rejected');
                    $('#mydescmodal').modal('show');               
                }

            });

            $('#yes').click(function(e){ //alert();
                e.preventDefault();
                // $('#myaction').val('accepted');
                // $("#theForm").submit();
                var id = $('#id').val();
                var action = $('#myaction2').val();
                var reason = $('#myreason').val();
                if(reason=="")
                {
                    $('#myreason').focus();
                }
                else{
                $.ajax({
                type:'POST',
                datatype: 'json',
                url:'leave/action',
                data:{id:id,action: action, reason:reason},
                success:function(data){
                    $('#myreason').val('');
                    $('#mydescmodal').modal('hide');
                    refreshTable();
                }
                });
            }

          });

           $('#myleavetable').on("click",".accept",function(e){
                e.preventDefault();
                $('#myreason').val('');
                var emp_id = $(this).closest('tr').find('.myempid').val();
                if(emp_id=="<?php echo session('user_id') ?>")
                { 
                    $('#mymsg').html('you can take action on your own application')
                    $('#msgalert').focus().show();
                }
                else
                {
                    $('#msgalert').hide();
                    var id = $(this).closest('tr').find('.myid').val();
                    var action = $(this).closest('span').find('.action').val();
                    $.ajax({
                    type:'POST',
                    datatype: 'json',
                    url:'leave/action',
                    data:{id:id,action: action},
                    success:function(data){
                        refreshTable();
                    }
                    });
                }
            });

             $('#myleavetable').on("click",".showreason",function(e){
                var reason = $(this).closest('td').find('.myreason_value').val();
                $('#my_reason_value').html(reason);
                $('#my_reason_modal').modal('show');
            });

             $('#myleavetable_emp').on("click",".showreason",function(e){
                var reason = $(this).closest('td').find('.myreason_value').val();
                $('#my_reason_value').html(reason);
                $('#my_reason_modal').modal('show');
            });
            
    	});

        function refreshTable()
          {
            //$('#joblisttable').fadeOut();
            $('#myleavetable').load('leavetable', function() {
            //$('#joblisttable').fadeIn();
            });
          }
    </script>
    @endsection