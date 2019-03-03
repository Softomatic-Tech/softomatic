@extends('layouts.mobile_app')
@section('content')
<style>
    	.rowcard{
	background-color: #d6d6d6 !important;
    padding: 14px 0px 34px 0px !important;
    margin: 0px 1px 0px 1px !important;
    box-shadow: 1px 4px 8px #e4e2e2 !important;
 }
 .btn{
     border-radius: 12px !important;
    height: 37px !important;
}
label {
   
    font-weight: 400 !important;
}
 }
</style>
		<div class="phone-box wrap push">
			<div class="parker" id="service">
				<div class="menu-notify2">
					<div class="profile-left">
						<a href="#menu" class="menu-link"><img src="images/menu.png" ></a>
					</div>
					<div class="Profile-mid">
						<img src="./images/shri_shivam_logo_m.jpg" height="22px">
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="services">
					<h3>LEAVE </h3>

				
        <div class="container-fluid">
           <!--  <div class="block-header"> -->
                 
           <!--  </div> -->

            <div class="row clearfix">
               
                	@if(Session::get('role')!='admin')
                    
                        	{!! Form::open(['url' => 'leave/submit', 'files' => true]) !!}
                        		@csrf
                        		
                                        <div class="form-group row rowcard">
		                        		    <div class="col-md-4 col-sm-6 col-xs-12" align="left">
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
			                        	<!-- </div> -->
                                       <!--  <div class="row clearfix "> -->
			                        	<div class="form-group row rowcard" id="other_reason_div">
			            					<div class="col-md-4 col-sm-6 col-xs-12" align="left">
			            						<label for="other_reason">Other Reason</label>
			            					</div>
			            					<div class="col-md-8 col-sm-6 col-xs-12">
				            					<div class="form-line">
				            						<textarea autocomplete="none" name="other_reason" id="other_reason" class="form-control" placeholder="Other Reason For Leave">{{old('other_reason')}}</textarea>
				            						<span class="text-danger">{{ $errors->first('other_reason') }}</span>
				            					</div>
				            				</div>
				            			</div><br>
                                    <!-- </div>
			                        	<div class="row clearfix "> -->
                                            <div class="form-group row rowcard">
			            					<div class="col-md-4 col-sm-6 col-xs-12" align="left">
			            						<label for="doj">From Date</label>
			            					</div>
			            					<div class="col-md-8 col-sm-6 col-xs-12">
				            					<div class="form-line">
				            						<input autocomplete="none" type="text" name="from" id="from" class="form-control datepicker" placeholder="From Date" value="{{old('from')}}" required="">
				            						<span class="text-danger">{{ $errors->first('from') }}</span>
				            					</div>
				            				</div>
			            				</div><br>
                                   <!--  </div>
			            				<div class="row clearfix "> -->
                                            <div class="form-group row rowcard">
			            					<div class="col-md-4 col-sm-6 col-xs-12" align="left">
			            						<label for="doj">To Date</label>
			            					</div>
			            					<div class="col-md-8 col-sm-6 col-xs-12">
				            					<div class="form-line">
				            						<input autocomplete="none" type="text" name="to" id="to" class="form-control datepicker" placeholder="To Date" value="{{old('to')}}" required="">
				            						<span class="text-danger">{{ $errors->first('to') }}</span>
				            					</div>
				            				</div>
				            			</div><br>
                                   <!--  </div>
				            			<div class="row clearfix "> -->
                                            <div class="form-group row">
					            			<div class="col-md-12 col-sm-12 col-xs-12">
					            				<button autocomplete="none" type="submit" name="submit" class="btn btn-success form-control">Apply</button>
				            				</div>
				            			</div>
                                       <!--  </div>		 -->		            			
		                        	<!-- </div> -->
                        		<!-- </div> -->
                        	{!! Form::close() !!}
                       <!--  </div> -->
                   <!--  </div> -->

                    <!-- <div class="card"> -->
                        <div class="">
                            <h3>LEAVE APPLICATIONS</h3>
                        </div>
                        <div class="table-responsive">
                            @include('leavetable_emp')
                        </div>
                    <!-- </div> -->

                    @endif

                    @if((Session::get('role')=='admin') || Session::get('role')=='hr')
                    <!-- <div class="card"> -->
                        <div class="">
                            <h3>LEAVE APPLICATIONS</h3>
                        </div>
                        <div class="table-responsive"> 
                        @include('leavetable')                       	
                                                                         
                        </div>
                    <!-- </div> -->
                    @endif
                </div>
           <!--  </div>           -->  
        </div>

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
                .bg-orange {
				    background-color: #FF9800 !important;
				    color: #fff;
				}
            </style>
        </div>
    </div>

@endsection
@section('jquery')

<link href="plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
<link href="plugins/DatePickerBootstrap/css/datepicker.css" rel="stylesheet" />
<script src="plugins/DatePickerBootstrap/js/bootstrap-datepicker.js"></script>

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

   //  		$("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
			// 	$("#success-alert").slideUp(500);
			// });

           $('#myleavetable').on("click",".reject",function(e){
                e.preventDefault();
                var id = $(this).closest('tr').find('.myid').val();
                $('#id').val(id);
                $('#myaction2').val('Rejected');
                $('#mydescmodal').modal('show');
                
        

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
                // $('#myaction').val('accepted');
                // $("#theForm").submit();
                var id = $(this).closest('tr').find('.myid').val();
                var action = $(this).closest('span').find('.action').val();
                //var reason = $(this).closest('td').find('.myreason_value').val();
                //alert(id);

                $.ajax({
                type:'POST',
                datatype: 'json',
                url:'leave/action',
                data:{id:id,action: action},
                success:function(data){
                    refreshTable();
                }
                });

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