@extends('layouts.app')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>EMPLOYEE LIST</h2>

                @if(session('success'))
				    <div class="msg alert alert-success">
				   		<h3><center>{{session('success')}}</center></h3>
				    </div> 
				@endif

				@if(session('failure'))
				    <div class="msg alert alert-danger">
				   		<h4>{!! session('failure') !!}</h4>
				    </div> 
				@endif
            </div>

            <div class="row clearfix">
			       <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Employee List
                            </h2>
                          </div>
                        <div class="body table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable myemptable">
                                <thead>
                                    <tr class="bg-orange">
                                        <th>Emp ID</th>
                                        <th>Employee Name</th>
                                        <th>Branch</th>
										 <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                      </tr>
                                </thead>
                                <tbody>
								<?php 
								$i=1;
								?>
								@foreach($emps as $emp)
                                  
								   <tr>
									 {{ csrf_field() }}
									  <th scope="row">{{ $emp->id }}</th>
                                        <td>{{ $emp->title }} {{ $emp->first_name }} {{$emp->middle_name}} {{$emp->last_name}}</td>
                                        <td>{{ $emp->branch }}</td>
										 <td>{{ $emp->email }}</td>
										 <td>{{ $emp->mobile }}</td>
                                         <td>{{ ucfirst(trans($emp->status)) }}</td>
                                        <td>
										<button type="button" id="" 
												class="btn bg-amber waves-effect edit-modal" data-toggle="modal" 
												data-target="#updateModal" data-id="{{$emp->id}}"
												data-name="">
											<i class="material-icons">open_with</i>
										</button>
			                                <a type="button" target="_blank" href="edit-employee-{{ $emp->id }}" class="btn bg-teal waves-effect">
			                                    <i class="material-icons">create</i>
			                                </a>
											<!-- <button type="button" data-id="{{$emp->id}}" data-target="#deleteModal" data-toggle="modal" class="btn bg-red waves-effect delete-modal">
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
				
        </div>
            
        </div>

        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-pink">
                            <h4 class="modal-title" id="defaultModalLabel">Delete Employee</h4>
                        </div>
						<form action="employee/delete"  method="POST">
                        <div class="modal-body">						
						{{ csrf_field() }}
						<input type="hidden" id="emp_id" name="emp_id"  class="form-control"/>
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
    </section>
<div class="modal fade" id="updateModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-pink">
                            <h4 class="modal-title" id="uModalLabel">Employee profile</h4>
                        </div>
						<div class="modal-body">
			<div class="row clearfix">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					  <div id="profile_pic"></div>
					  <div class="header"><h4 id="emp_name"></h4></div>
						<hr>
				<!-- panel-group start -->		
				 <div class="panel-group" id="accordion_9" role="tablist" aria-multiselectable="true">
                    <fieldset>
					<!-- panel start -->
                        <div class="panel panel-col-orange">
						<!-- panel-heading start -->
                            <div class="panel-heading" role="tab" id="heading1">
                               <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion_9" href="#collapse1" aria-expanded="true" aria-controls="collapse1">
                                       <i class="material-icons">remove_circle</i>  Personal Details
                                    </a>

                                </h4>
                            </div>
				  <!-- panel-heading end -->
					<!-- panel-collapse start -->	
					<div id="collapse1" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading1">
                      <!-- panel-body start --> 
					   <div class="panel-body">
                           <div class="row clearfix">
							   <div class="col-md-2">
									<label for="blood_group">Blood Group:</label>
							   </div>
							   <div class="col-md-2" id="blood_group">
							   </div>
							   <div class="col-md-2">
									<label for="DOB">DOB:</label>
							   </div>
							   <div class="col-md-2" id="dob">
							   </div>
							   <div class="col-md-2">
									<label for="DOB">Email-Id:</label>
							   </div>
							   <div class="col-md-2" id="email">
							   </div>
						  </div>
						  <div class="row clearfix">
							   <div class="col-md-2">
									<label for="Mobile">Mobile:</label>
							   </div>
							   <div class="col-md-2" id="mobile">
							   </div>
							   <div class="col-md-2">
									<label for="Gender">Gender:</label>
							   </div>
							   <div class="col-md-2" id="gender">
							   </div>
							   <div class="col-md-2">
									<label for="category">Category:</label>
							   </div>
							   <div class="col-md-2" id="category">
							   </div>
						  </div>
						   	<div class="row clearfix">
						   		<div class="col-md-2">
									<label for="Marital Status">Marital Status:</label>
							   </div>
							   <div class="col-md-2" id="marital_status">
							   </div>

							   	<div class="col-md-2">
										<label for="Local Adress">Adhaar Number:</label>
							   	</div>
							   	<div class="col-md-2" id="adhaar_number">
							   	</div>
							   	<div class="col-md-2">
										<label for="Local Adress">PAN Number:</label>
							   	</div>
							   	<div class="col-md-2" id="pan_number">
							   	</div>
							</div>
							<div class="row clearfix">
								<div class="col-md-2">
									<label for="Local Adress">Local Adress:</label>
							   </div>
							   <div class="col-md-4" id="local_address">
							   </div>

							   <div class="col-md-2">
									<label for="Permanent Adress">Permanent Adress:</label>
							   </div>
							   <div class="col-md-4" id="permanent_address">
							   </div>
							</div>
							
							<div class="col-md-12">
                            	<label><h4><u>On Emergency Contact To</u></h4></label>
                            	<div class="row clearfix">
                            		<div class="form-group">
	                                	<div class="col-md-2 col-sm-6 col-xs-12">
	                                		<label for="emergency_call_person">Person Name</label>
	                                	</div>
	                                	 <div class="col-md-4" id="emergency_call_person">
			            				</div>
			            			
	                                	<div class="col-md-2 col-sm-6 col-xs-12">
	                                		<label for="emergency_call_number">Contact Number</label>
	                                	</div>
	                                	 <div class="col-md-4" id="emergency_call_number">
			            				</div>
			            			</div>
        						</div>
                            </div>
						</div> <!-- panel-body end -->
					  </div><!-- panel-collapse end -->	
				  </div><!-- panel end --> 
				</div><!-- panel-group end -->	
				  
				<!-- panel-group start -->		
				 <div class="panel-group" id="accordion_9" role="tablist" aria-multiselectable="true">
                    <fieldset>
					<!-- panel start -->
                        <div class="panel panel-col-orange">
						<!-- panel-heading start -->
                            <div class="panel-heading" role="tab" id="heading5">
                              <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion_9" href="#collapse5" aria-expanded="true" aria-controls="collapse5">
                                        <i class="material-icons">remove_circle</i>    Family Details
                                    </a>

                                </h4>
                            </div>
				  <!-- panel-heading end -->
					<!-- panel-collapse start -->	
					<div id="collapse5" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading5">
                      <!-- panel-body start --> 
					   <div class="panel-body">

					   	 <div class="row clearfix">
					   	 	<div class="col-md-12">
					   	 		<h4>Father</h4>
					   	 	</div>
							  <div class="col-md-1">
									<label for="father_name">Name:</label>
							   </div>
							   <div class="col-md-2" id="father_name">
							   </div>
							   <div class="col-md-1">
									<label for="father_dob">DOB:</label>
							   </div>
							   <div class="col-md-2" id="father_dob">
							   </div>							   
							   <div class="col-md-1">
									<label for="father_adhaar">Adhaar No:</label>
							   </div>
							   <div class="col-md-2" id="father_adhaar">
							   </div>
							   <div class="col-md-1">
									<label for="father_place">Place:</label>
							   </div>
							   <div class="col-md-2" id="father_place">
							   </div>
						  </div>

						   <div class="row clearfix">
						   		<div class="col-md-2">
									<label for="genesis_id">Genesis ID:</label>
							   </div>
							   <div class="col-md-2" id="genesis_id">
							   </div>
							   <div class="col-md-2">
									<label for="Department">Department</label>
							   </div>
							   <div class="col-md-2" id="department">
							   </div>
							   <div class="col-md-2">
									<label for="designation">Designation:</label>
							   </div>
							   <div class="col-md-2" id="designation">
							   </div>
							   						   
						  </div>
						  <div class="row clearfix">
						  	<div class="col-md-2">
									<label for="doj">Date of Joining:</label>
							   </div>
							   <div class="col-md-2" id="doj">
							   </div>	
						  		<div class="col-md-2">
									<label for="Status">Status:</label>
							   </div>
							   <div class="col-md-2" id="status">
							   </div>
							   <div class="col-md-2">
									<label for="esic_number">ESIC Number:</label>
							   </div>
							   <div class="col-md-2" id="esic_number">
							   </div>
							   
						  </div>
						  <div class="row clearfix">
						  	   <div class="col-md-2">
									<label for="epf_number">EPF Number:</label>
							   </div>
							   <div class="col-md-2" id="epf_number">
							   </div>
							   <div class="col-md-2">
									<label for="lin_number">LIN Number:</label>
							   </div>
							   <div class="col-md-2" id="lin_number">
							   </div>
							   <div class="col-md-2">
									<label for="uan_number">UAN Number:</label>
							   </div>
							   <div class="col-md-2" id="uan_number">
							   </div>							   
						  </div>
						  <div class="row clearfix">
						  	   <div class="col-md-2">
									<label for="reason_code_0">Reason for code 0:</label>
							   </div>
							   <div class="col-md-2" id="reason_code_0">
							   </div>
							   <div class="col-md-2">
									<label for="last_working_day">Last Working Day:</label>
							   </div>
							   <div class="col-md-2" id="last_working_day">
							   </div>
						  </div> 
						</div> <!-- panel-body end -->
					  </div><!-- panel-collapse end -->	
				  </div><!-- panel end --> 
				</div><!-- panel-group end -->	
				   <!-- panel-group start -->	

			<!-- panel-group start -->		
				 <div class="panel-group" id="accordion_9" role="tablist" aria-multiselectable="true">
                    <fieldset>
					<!-- panel start -->
                        <div class="panel panel-col-orange">
						<!-- panel-heading start -->
                            <div class="panel-heading" role="tab" id="heading2">
                              <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion_9" href="#collapse2" aria-expanded="true" aria-controls="collapse1">
                                        <i class="material-icons">remove_circle</i>    Company Details
                                    </a>

                                </h4>
                            </div>
				  <!-- panel-heading end -->
					<!-- panel-collapse start -->	
					<div id="collapse2" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading2">
                      <!-- panel-body start --> 
					   <div class="panel-body">

					   	 <div class="row clearfix">
							  <div class="col-md-2">
									<label for="Branch">Branch:</label>
							   </div>
							   <div class="col-md-2" id="branch">
							   </div>
							   <div class="col-md-2">
									<label for="genesis_ledger_id">Genesis Ledger ID:</label>
							   </div>
							   <div class="col-md-2" id="genesis_ledger_id">
							   </div>							   
							   <div class="col-md-2">
									<label for="biometric_id">Biometric ID:</label>
							   </div>
							   <div class="col-md-2" id="biometric_id">
							   </div>
						  </div>

						   <div class="row clearfix">
						   	<div class="col-md-2">
									<label for="genesis_id">Genesis ID:</label>
							   </div>
							   <div class="col-md-2" id="genesis_id">
							   </div>
							   <div class="col-md-2">
									<label for="Department">Department</label>
							   </div>
							   <div class="col-md-2" id="department">
							   </div>
							   <div class="col-md-2">
									<label for="designation">Designation:</label>
							   </div>
							   <div class="col-md-2" id="designation">
							   </div>
							   						   
						  </div>
						  <div class="row clearfix">
						  	<div class="col-md-2">
									<label for="doj">Date of Joining:</label>
							   </div>
							   <div class="col-md-2" id="doj">
							   </div>	
						  		<div class="col-md-2">
									<label for="Status">Status:</label>
							   </div>
							   <div class="col-md-2" id="status">
							   </div>
							   <div class="col-md-2">
									<label for="esic_number">ESIC Number:</label>
							   </div>
							   <div class="col-md-2" id="esic_number">
							   </div>
							   
						  </div>
						  <div class="row clearfix">
						  	   <div class="col-md-2">
									<label for="epf_number">EPF Number:</label>
							   </div>
							   <div class="col-md-2" id="epf_number">
							   </div>
							   <div class="col-md-2">
									<label for="lin_number">LIN Number:</label>
							   </div>
							   <div class="col-md-2" id="lin_number">
							   </div>
							   <div class="col-md-2">
									<label for="uan_number">UAN Number:</label>
							   </div>
							   <div class="col-md-2" id="uan_number">
							   </div>							   
						  </div>
						  <div class="row clearfix">
						  	   <div class="col-md-2">
									<label for="reason_code_0">Reason for code 0:</label>
							   </div>
							   <div class="col-md-2" id="reason_code_0">
							   </div>
							   <div class="col-md-2">
									<label for="last_working_day">Last Working Day:</label>
							   </div>
							   <div class="col-md-2" id="last_working_day">
							   </div>
						  </div> 
						</div> <!-- panel-body end -->
					  </div><!-- panel-collapse end -->	
				  </div><!-- panel end --> 
				</div><!-- panel-group end -->	
				   <!-- panel-group start -->	

				<div class="panel-group" id="accordion_9" role="tablist" aria-multiselectable="true">
                    <fieldset>
					    <div class="panel panel-col-orange">
					      <div class="panel-heading" role="tab" id="heading3">
                              <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion_9" href="#collapse3" aria-expanded="true" aria-controls="collapse1">
                                        <i class="material-icons">remove_circle</i>    Salary Details
                                    </a>

                                </h4>
                            </div>
				  	<div id="collapse3" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading3">
                    	 <div class="panel-body">
						   <div class="row clearfix">
							   <div class="col-md-2">
									<label for="epf_option">EPF Option:</label>
							   </div>
							   <div class="col-md-1" id="epf_option">
							   </div>
							   <div class="col-md-2">
									<label for="esic_option">Esic Option:</label>
							   </div>
							   <div class="col-md-1" id="esic_option">
							   </div>
							   <div class="col-md-2">
									<label for="salary">Salary </label>
							   </div>
							   <div class="col-md-1" id="salary">
							   </div>
							   <div class="col-md-2">
									<label for="basic">Bsic + DA :</label>
							   </div>
							   <div class="col-md-1" id="basic">
							   </div>
						  </div>
						
						 </div> 
					  </div>
				  </div>
				</div>	

				 <div class="panel-group" id="accordion_9" role="tablist" aria-multiselectable="true">
                    <fieldset>
					    <div class="panel panel-col-orange">
					        <div class="panel-heading" role="tab" id="heading4">
                              <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion_9" href="#collapse4" aria-expanded="true" aria-controls="collapse4">
                                        <i class="material-icons">remove_circle</i>    Bank Details
                                    </a>

                                </h4>
                            </div>
				  <div id="collapse4" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading4">
                       <div class="panel-body">
						   <div class="row clearfix">
							   <div class="col-md-2">
									<label for="Acc Holder Name">Acc Holder Name:</label>
							   </div>
							   <div class="col-md-2" id="acc_holder_name">
							   </div>
							   <div class="col-md-2">
									<label for="Account No">Account No:</label>
							   </div>
							   <div class="col-md-2" id="acc_no">
							   </div>
							   <div class="col-md-2">
									<label for="Ifsc Code">Ifsc Code:</label>
							   </div>
							   <div class="col-md-2" id="ifsc_code">
							   </div>
						  </div>
						  <div class="row clearfix">
							   <div class="col-md-2">
									<label for="Bank Name">Bank Name:</label>
							   </div>
							   <div class="col-md-2" id="bank_name">
							   </div>
							   <div class="col-md-2">
									<label for="Branch">Branch:</label>
							   </div>
							   <div class="col-md-2" id="bank_branch">
							   </div>
							   
						  </div>
						 </div> <!-- panel-body end -->
					  </div><!-- panel-collapse end -->	
				  </div><!-- panel end --> 
				</div><!-- panel-group end -->	
					  </div>
				  </div>
            </div>
            <div class="modal-footer">
				<button type="button" class="btn bg-blue-grey  waves-effect" data-dismiss="modal">CLOSE</button>
			</div>
		</div>

</div>
                </div>
            </div>
			
    @endsection

    @section('jquery')
    <link href="plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
    
    <script src="plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/buttons.flash.min.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/buttons.html5.min.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/buttons.print.min.js"></script>
    <script src="js/pages/tables/jquery-datatable.js"></script>
    
    <script type="text/javascript">
	
$(document).ready(function(){  
$.ajaxSetup({
     headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               }
              });
	  $(document).on('click' , '.edit-modal', function(e){
		
		   var user_id = $(this).data('id');
		 $.ajax({
      url: 'get-details',
      type:'POST',
      data: {'id':user_id},
      dataType: 'json',
      success: function(data){ 
		$.each(data, function(index, element) {		
         $('#emp_name') .text(element.title+" "+element.first_name+" "+element.middle_name+" "+element.last_name);
		 $("#profile_pic").html('<img src="' + element.photo + '" width="100px" height="100px" />');
		  $('#blood_group').text(element.blood_group);
		  $('#dob').text(element.dob);
		  $('#email').text(element.email);
		  $('#mobile').text(element.mobile);
		  $('#gender').text(element.gender);
		  $('#category').text(element.category);
		  $('#marital_status').text(element.marital_status);
		  $('#adhaar_number').html(element.adhaar_number);
		  $('#pan_number').html(element.pan_number);
		  $('#local_address').html(element.local_address);
		  $('#permanent_address') .html(element.permanent_address);
		  $('#emergency_call_number').text(element.emergency_call_number);
		  $('#emergency_call_person').text(element.emergency_call_person);
		  $('#branch').text(element.branch_location_name);
		  $('#genesis_id').text(element.genesis_id);
		  $('#genesis_ledger_id').text(element.genesis_ledger_id);
		  $('#biometric_id').text(element.biometric_id);
		  $('#esic_number').text(element.esic_number);
		  $('#epf_number').text(element.epf_number);
		  $('#lin_number').text(element.lin_number);
		  $('#uan_number').text(element.uan_number);
		  $('#reason_code_0').text(element.reason_code_0);
		  $('#last_working_day').text(element.last_working_day);
		  $('#department').text(element.department_name);
		  $('#designation').text(element.designation);
		  $('#doj') .html(element.doj);
		  $('#status') .html(element.status);
		  $('#acc_holder_name') .html(element.acc_holder_name);
		  $('#acc_no').text(element.acc_no);
		  $('#ifsc_code').text(element.ifsc_code);
		  $('#bank_name') .html(element.bank_name);
		  $('#bank_branch') .html(element.branch);
		  var sal = JSON.parse(element.salary);
		  $('#salary').html(sal.emp_salary['salary']);
		  $('#basic').html(sal.emp_salary['basic']);
		  if(element.epf_option==1)
		  	$('#epf_option').html('Yes');
		  else
		  	$('#epf_option').html('No');
		  if(element.esic_option==1)
		  	$('#esic_option').html('Yes');
		  else
		  	$('#esic_option').html('No');
        });
      }
      
    });    
  });
});
    </script>
    @endsection