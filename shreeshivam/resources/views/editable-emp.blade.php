
        <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                      
                        <div class="body">    
                        	<form id="update_emp_form" method="POST" action="edit-employee/submit" enctype="multipart/form-data">
                        		@csrf
                        		@foreach($emps as $emp)                            	
                            	<input disabled type="hidden" name="emp_id" value="{{ $emp->id }}">
                            	<div class="panel-group" id="accordion_9" role="tablist" aria-multiselectable="true">
                            			
				            		
                            		<fieldset>
                            			<div class="row clearfix">
	                                   	<div class="col-md-2">
				            				<div class="form-group">
				            						<a type="button" target="_blank" href="edit-employee-{{ $emp->id }}" class="btn bg-cyan waves-effect">
			                                    Edit Employee
			                                </a>
				            				</div>
				            			</div>
				            			</div>
                                    <div class="panel panel-col-cyan">
                                        <div class="panel-heading" role="tab" id="heading1">
                                            <h4 class="panel-title">
                                                <a role="button" data-toggle="collapse" data-parent="#accordion_9" href="#collapse1" aria-expanded="true" aria-controls="collapse1">
                                                    Personal Details
                                                    <i class="material-icons mycheck" id="mycheck1">check</i>
                                                </a>

                                            </h4>
                                        </div>
                                        <div id="collapse1" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading1">
                                            <div class="panel-body">
                                                <div class="col-md-6">

                                                	<div class="row clearfix"><div class="form-group">
		                                	<div class="col-md-4 col-sm-6 col-xs-12">
		                                		<label for="title">Title</label>
		                                	</div>
		                                	<div class="col-md-8 col-sm-6 col-xs-12">
				            					<div class="form-line">
				            						<select disabled name="title" id="title" class="form-control show-tick">
				            							<option></option>
				            							<!-- <option selected data-default>Select Gender</option> -->
				            							@if($emp->title == 'Mr')
      														<option value="Mr" selected>Mr</option>
														@else
      														<option value="Mr">Mr</option>
														@endif
														@if($emp->title == 'Ms')
      														<option value="Ms" selected>Ms</option>
														@else
      														<option value="Ms">Ms</option>
														@endif
														@if($emp->title == 'Mrs')
      														<option value="Mrs" selected>Mrs</option>
														@else
      														<option value="Mrs">Mrs</option>
														@endif
				            						</select>
				            						<span class="text-danger">{{ $errors->first('title') }}</span>
				            					</div>
				            				</div>
			            				</div>
			            				</div>

                                                	<div class="row clearfix">
		                                <div class="form-group">
		                                	<div class="col-md-4 col-sm-6 col-xs-12">
		                                		<label for="first_name">First Name</label>
		                                	</div>
		                                	<div class="col-md-8 col-sm-6 col-xs-12">
				            					<div class="form-line">
				            						<input disabled autocomplete="none" type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name" value="{{ $emp->first_name }}" >
				            						<span class="text-danger">{{ $errors->first('first_name') }}</span>
				            					</div>
				            				</div>
			            				</div>
			            				</div>

			            				<div class="row clearfix">
		                                <div class="form-group">
		                                	<div class="col-md-4 col-sm-6 col-xs-12">
		                                		<label for="middle_name">Middle Name</label>
		                                	</div>
		                                	<div class="col-md-8 col-sm-6 col-xs-12">
				            					<div class="form-line">
				            						<input disabled autocomplete="none" type="text" name="middle_name" id="middle_name" class="form-control" placeholder="Middle Name" value="{{ $emp->middle_name }}" >
				            						<span class="text-danger">{{ $errors->first('middle_name') }}</span>
				            					</div>
				            				</div>
			            				</div>
			            				</div>

			            				<div class="row clearfix">
		                                <div class="form-group">
		                                	<div class="col-md-4 col-sm-6 col-xs-12">
		                                		<label for="last_name">Last Name</label>
		                                	</div>
		                                	<div class="col-md-8 col-sm-6 col-xs-12">
				            					<div class="form-line">
				            						<input disabled autocomplete="none" type="text" name="last_name" id="last_name" class="form-control" placeholder="Last Name" value="{{ $emp->last_name }}" >
				            						<span class="text-danger">{{ $errors->first('last_name') }}</span>
				            					</div>
				            				</div>
			            				</div>
			            				</div>

			            				<div class="row clearfix"><div class="form-group">
			            					<div class="col-md-4 col-sm-6 col-xs-12">
		                                		<label for="blood_group">Blood Group</label>
		                                	</div>
		                                	<div class="col-md-8 col-sm-6 col-xs-12">
				            					<div class="form-line">
				            						<input disabled autocomplete="none" type="text" name="blood_group" id="blood_group" class="form-control" placeholder="Blood Group" value="{{ $emp->blood_group }}" >
				            						<span class="text-danger">{{ $errors->first('blood_group') }}</span>
				            					</div>
				            				</div>
			            				</div>
			            				</div>
			            				
			            				<div class="row clearfix"><div class="form-group">
			            					<div class="col-md-4 col-sm-6 col-xs-12">
		                                		<label for="dob">Date Of Birth</label>
		                                	</div>
		                                	<div class="col-md-8 col-sm-6 col-xs-12">
				            					<div class="form-line">
				            						<input disabled autocomplete="none" type="text" name="dob" id="dob" class="form-control datepicker" placeholder="Date Of Birth" value="{{ date('Y-m-d',strtotime($emp->dob)) }}" >
				            						<span class="text-danger">{{ $errors->first('dob') }}</span>
				            					</div>
				            				</div>
			            				</div>
			            				</div>
			            				<div class="row clearfix"><div class="form-group">
			            					<div class="col-md-4 col-sm-6 col-xs-12">
		                                		<label for="mobile">Mobile</label>
		                                	</div>
		                                	<div class="col-md-8 col-sm-6 col-xs-12">
				            					<div class="form-line">
				            						<input disabled autocomplete="none" type="number" name="mobile" id="mobile" class="form-control" placeholder="Mobile Number" value="{{ $emp->mobile }}" >
				            						<span class="text-danger">{{ $errors->first('mobile') }}</span>
				            					</div>
				            				</div>
			            				</div>
			            				</div>
			            				<div class="row clearfix"><div class="form-group">
			            					<div class="col-md-4 col-sm-6 col-xs-12">
		                                		<label for="gender">Gender</label>
		                                	</div>
		                                	<div class="col-md-8 col-sm-6 col-xs-12">
				            					<div class="form-line">
				            						<select disabled class="form-control show-tick" name="gender" id="gender" placeholder="Gender" >
				            							<!-- <option selected data-default>Select Gender</option> -->
				            							@if($emp->gender == 'female')
      														<option value="female" selected>Female</option>
														@else
      														<option value="female">Female</option>
														@endif
														@if($emp->gender == 'male')
      														<option value="male" selected>Male</option>
														@else
      														<option value="male">Male</option>
														@endif
				            						</select>
				            						<span class="text-danger">{{ $errors->first('gender') }}</span>
				            					</div>
				            				</div>
			            				</div>
			            				</div>

			            				<div class="row clearfix"><div class="form-group">
			            					<div class="col-md-4 col-sm-6 col-xs-12">
		                                		<label for="category">Category</label>
		                                	</div>
		                                	<div class="col-md-8 col-sm-6 col-xs-12">
				            					<div class="form-line">
				            						<select disabled class="form-control show-tick select2" name="category" id="category" placeholder="Category" >
				            							<option></option>
				            							<!-- <option selected data-default>Select Gender</option> -->
				            							@if($emp->category == 'GENERAL')
      														<option value="General" selected>General</option>
														@else
      														<option value="General">General</option>
														@endif
														@if($emp->category == 'OBC')
      														<option value="OBC" selected>OBC</option>
														@else
      														<option value="OBC">OBC</option>
														@endif
														@if($emp->category == 'ST/SC')
      														<option value="ST/SC" selected>ST/SC</option>
														@else
      														<option value="ST/SC">ST/SC</option>
														@endif
														@if($emp->category == 'Other')
      														<option value="Other" selected>Other</option>
														@else
      														<option value="Other">Other</option>
														@endif
				            						</select>
				            						<span class="text-danger">{{ $errors->first('category') }}</span>
				            					</div>
				            				</div>
			            				</div>
			            				</div>

			            				<div class="row clearfix"><div class="form-group">
			            					<div class="col-md-4 col-sm-6 col-xs-12">
		                                		<label for="marital_status">Marital Status</label>
		                                	</div>
		                                	<div class="col-md-8 col-sm-6 col-xs-12">
				            					<div class="form-line">
				            						
					            						<select disabled class="form-control show-tick" id="marital_status" placeholder="Marital Status"  name="marital_status">
												@if($emp->marital_status == 'married')
													<option value="married" selected>Married</option>
												@else
													<option value="married">Married</option>
												@endif

												@if($emp->marital_status == 'unmarried')
													<option value="unmarried" selected>UnMarried</option>
												@else
													<option value="unmarried">UnMarried</option>
												@endif

												@if($emp->marital_status == 'other')
													<option value="other" selected>Other</option>
												@else
													<option value="other">Other</option>
												@endif

					            						</select>
					            						<span class="text-danger">{{ $errors->first('marital_status') }}</span>
				            						
				            					</div>
				            				</div>
			            				</div>			            				
		            				</div>
		            				</div>
		            				<div class="col-md-6">

		            					<div class="row clearfix"><div class="form-group">
			            					<div class="col-md-4 col-sm-6 col-xs-12">
		                                		<label for="email">Email</label>
		                                	</div>
		                                	<div class="col-md-8 col-sm-6 col-xs-12">
				            					<div class="form-line">
				            						<input disabled autocomplete="none" type="email" name="email" id="email" class="form-control" placeholder="Email Id" value="{{ $emp->email }}" >
				            						<span class="text-danger">{{ $errors->first('email') }}</span>
				            					</div>
				            				</div>
			            				</div>
			            				</div>

		            					<div class="row clearfix"><div class="form-group">
		                                	<div class="col-md-4 col-sm-6 col-xs-12">
		                                		<label for="local_address">Adhaar Number</label>
		                                	</div>
		                                	<div class="col-md-8 col-sm-6 col-xs-12">
				            					<div class="form-line">
				            						<input disabled autocomplete="none" name="adhaar_number" id="adhaar_number" class="form-control" placeholder="Adhaar Number" value="{{ $emp->adhaar_number }}">
				            						<span class="text-danger">{{ $errors->first('adhaar_number') }}</span>
				            					</div>
				            				</div>
				            			</div>
			            				</div>

			            				<div class="row clearfix"><div class="form-group">
		                                	<div class="col-md-4 col-sm-6 col-xs-12">
		                                		<label for="local_address">PAN Number</label>
		                                	</div>
		                                	<div class="col-md-8 col-sm-6 col-xs-12">
				            					<div class="form-line">
				            						<input disabled autocomplete="none" name="pan_number" id="pan_number" class="form-control" placeholder="PAN Number" value="{{ $emp->pan_number }}">
				            						<span class="text-danger">{{ $errors->first('pan_number') }}</span>
				            					</div>
				            				</div>
				            			</div>
			            				</div>

		            					<div class="row clearfix">
		                                <div class="form-group">
		                                	<div class="col-md-4 col-sm-6 col-xs-12">
		                                		<label for="local_address">Local Address</label>
		                                	</div>
		                                	<div class="col-md-8 col-sm-6 col-xs-12">
				            					<div class="form-line">
				            						<textarea disabled autocomplete="none" rows="2" name="local_address" id="local_address" class="form-control no-resize" placeholder="Local Address" >{{ $emp->local_address }}</textarea>
				            						<span class="text-danger">{{ $errors->first('local_address') }}</span>
				            					</div>
				            				</div>
				            			</div>
			            				</div>
			            				<div class="row clearfix"><div class="form-group">
			            					<div class="col-md-12">
			            					<!-- <div class="form-line"> -->
			            						<input disabled autocomplete="none" type="checkbox" id="md_checkbox_22" class="filled-in chk-col-pink">
			            						<label for="md_checkbox_22" style="font-size: 14px"><b>Permanent Address is same as local address</b></label><!-- Block - 69/A, Camp-1, Road No. - 18, Bhilai, Post- Supela, District- Durg, Chhattisgarh.-->
			            					<!-- </div> -->
			            					</div>
			            				</div>
			            				</div>
			            				<div class="row clearfix"><div class="form-group">
			            					<div class="col-md-4 col-sm-6 col-xs-12">
		                                		<label for="permanent_address">Permanent Address</label>
		                                	</div>
		                                	<div class="col-md-8 col-sm-6 col-xs-12">
				            					<div class="form-line">
				            						 <textarea disabled autocomplete="none" rows="2" name="permanent_address" id="permanent_address" class="form-control no-resize" placeholder="Permanent Address" >{{ $emp->permanent_address }}</textarea>
				            						 <span class="text-danger">{{ $errors->first('permanent_address') }}</span>
				            					</div>
				            				</div>
			            				</div>	
			            				</div>
			            				<div class="row clearfix">	            				
			            				<div class="form-group">
			            					<div class="col-md-4 col-sm-6 col-xs-12">
		                                		<label for="photo">Photograph</label>
		                                	</div>
		                                	<div class="col-md-8 col-sm-6 col-xs-12">
				            					<div class="form-line">
				            						<!-- <span><small>Upload Photograph Here</small></span> -->
				            						<input disabled autocomplete="none" type="file" name="photo" id="photo" class="form-control" placeholder="Photograph" value="{{ $emp->photo }}" >
				            						<small><a href="{{ $emp->photo }}" target="_blank">{{ $emp->photo }}</a></small>
				            						<div id='imagePreview'></div>
				            						<span class="text-danger">{{ $errors->first('photo') }}</span>			
				            					</div>
				            				</div>
			            				</div>
			            			</div>
                                    </div>

                                    <div class="col-md-12">
                                    	<label><h4><u>On Emergency Contact To</u></h4></label>
                                    	<div class="row clearfix">
                                    		<div class="form-group">
			                                	<div class="col-md-2 col-sm-6 col-xs-12">
			                                		<label for="emergency_call_person">Person Name</label>
			                                	</div>
			                                	<div class="col-md-4 col-sm-6 col-xs-12">
					            					<div class="form-line">
					            						<input disabled autocomplete="none" name="emergency_call_person" id="emergency_call_person" class="form-control" placeholder="Person Name" value="{{ $emp->emergency_call_person }}">
					            						<span class="text-danger">{{ $errors->first('emergency_call_person') }}</span>
					            					</div>
					            				</div>
					            			
			                                	<div class="col-md-2 col-sm-6 col-xs-12">
			                                		<label for="emergency_call_number">Contact Number</label>
			                                	</div>
			                                	<div class="col-md-4 col-sm-6 col-xs-12">
					            					<div class="form-line">
					            						<input disabled autocomplete="none" type="number" name="emergency_call_number" id="emergency_call_number" class="form-control" placeholder="Contact Number" value="{{ $emp->emergency_call_number }}">
					            						<span class="text-danger">{{ $errors->first('emergency_call_number') }}</span>
					            					</div>
					            				</div>
					            			</div>
	            						</div>
                                    </div>

                                    </div>
                                </div>
                            </div>

                                    <div class="panel panel-col-cyan">
                                        <div class="panel-heading" role="tab" id="heading2">
                                            <h4 class="panel-title">
                                                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_9" href="#collapse2" aria-expanded="true"
                                                   aria-controls="collapse2">
                                                    Company Details
                                                    <i class="material-icons mycheck" id="mycheck2">check</i>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapse2" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading2">
                                            <div class="panel-body">
                                                <div class="col-md-6">
                                                <div class="row clearfix"><div class="form-group">			            					
					            						<div class="col-md-4 col-sm-6 col-xs-12">
					            							<label for="genesis_id">Genesis Id</label>
					            						</div>
					            						<div class="col-md-8 col-sm-6 col-xs-12">
					            							<div class="form-line">
					            								<input disabled autocomplete="none" type="text" name="genesis_id" id="genesis_id" class="form-control" placeholder="Genesis Id" value="{{ $emp->genesis_id }}" >
					            								<span class="text-danger">{{ $errors->first('genesis_id') }}</span>
					            							</div>
					            						</div>
						            				</div>
						            				</div>

						            				<div class="row clearfix"><div class="form-group">
						            					<div class="col-md-4 col-sm-6 col-xs-12">
						            						<label for="branch_location_id">Branch</label>
						            					</div>
						            					<div class="col-md-8 col-sm-6 col-xs-12">
							            					<div class="form-line">		
							            						 <select disabled class="form-control show-tick" placeholder="Shreeshivam Branch" id="branch_location_id" name="branch_location_id">
							            						 	<option>Select Branch</option>
						@foreach($branches as $branch)
				           		@if($emp->branch_location_id == $branch->id)
									<option value="{{$branch->id}}" selected>{{$branch->branch}}</option>	
								@else
									<option value="{{$branch->id}}">{{$branch->branch}}</option>
								@endif	
						@endforeach
							            						</select>
							            						<span class="text-danger">{{ $errors->first('branch_location_id') }}</span>
							            					</div>
							            				</div>
						            				</div>
						            				</div>


						            				<div class="row clearfix">
						            				<div class="form-group">
						            					<div class="col-md-4 col-sm-6 col-xs-12">
						            						<label for="department">Department</label>
						            					</div>
						            					<div class="col-md-8 col-sm-6 col-xs-12">
							            					<div class="form-line">		
							            						 <select disabled class="form-control show-tick" placeholder="Department" id="department" name="department">
							            						 	<option>Select Department</option>
						@foreach($depts as $dept)
				           	<!-- @if($dept->department_name!='Admin') -->
				           		@if($emp->department == $dept->id)
									<option value="{{$dept->id}}" selected>{{$dept->department_name}}</option>	
								@else
									<option value="{{$dept->id}}">{{$dept->department_name}}</option>
								@endif	
							<!-- @endif  -->
						@endforeach
							            						</select>
							            						<span class="text-danger">{{ $errors->first('department') }}</span>
							            					</div>
							            				</div>
						            				</div>
						            				</div>
						            				<div class="row clearfix">
						            				<div class="form-group">
						            					<div class="col-md-4 col-sm-6 col-xs-12">
						            						<label for="designation">Designation</label>
						            					</div>
						            					<div class="col-md-8 col-sm-6 col-xs-12">
							            					<div class="form-line">			            	
							            						<select disabled autocomplete="none" type="text" name="designation" id="designation" class="form-control" placeholder="Designation">
							            							<option>Select Designation</option>
																	@foreach($desigs as $desig)
															       		@if($emp->designation == $desig->id)
																			<option value="{{$desig->id}}" selected>{{$desig->designation}}</option>	
																		@else
																			<option value="{{$desig->id}}">{{$desig->designation}}</option>
																		@endif	
																	@endforeach
							            						</select>
							            						<span class="text-danger">{{ $errors->first('designation') }}</span>
							            					</div>
							            				</div>
						            				</div>			            				
						            			</div>

						            			<div class="row clearfix">
						            				<div class="form-group">
						            					<div class="col-md-4 col-sm-6 col-xs-12">
						            						<label for="doj">Date Of Joining</label>
						            					</div>
						            					<div class="col-md-8 col-sm-6 col-xs-12">
							            					<div class="form-line">
							            						<input disabled autocomplete="none" type="text" name="doj" id="doj" class="form-control datepicker" placeholder="Date Of Joining" value="{{ date('Y-m-d',strtotime($emp->doj)) }}" >
							            						<span class="text-danger">{{ $errors->first('doj') }}</span>
							            					</div>
							            				</div>
						            				</div>
						            			</div>

						            			<div class="row clearfix">
						            				<div class="form-group">
						            					<div class="col-md-4 col-sm-6 col-xs-12">
						            						<label for="status">Status</label>
						            					</div>
						            					<div class="col-md-8 col-sm-6 col-xs-12">
							            					<div class="form-line">			            	
							            						<select disabled class="form-control show-tick" name="status" id="status" placeholder="Status" >
							            						
							            				@if($emp->status == 'active')
							            					<option value="active" selected>Active</option>
														@else
															<option value="active">Active</option>
														@endif

														@if($emp->status == 'inactive')
															<option value="inactive" selected>Inactive</option>
														@else
															<option value="inactive">Inactive</option>
														@endif
								            					</select>
								            					<span class="text-danger">{{ $errors->first('status') }}</span>
							            					</div>
							            				</div>
						            				</div>	
						            			</div>

						            			</div>
						            			<div class="col-md-6">

						            				<div class="row clearfix"><div class="form-group">			            					
					            						<div class="col-md-4 col-sm-6 col-xs-12">
					            							<label for="emplayee_id">Biometric Id</label>
					            						</div>
					            						<div class="col-md-8 col-sm-6 col-xs-12">
					            							<div class="form-line">
					            								<input disabled autocomplete="none" type="text" name="biometric_id" id="biometric_id" class="form-control" placeholder="Biometric Id" value="{{$emp->biometric_id}}" >
					            								<span class="text-danger">{{ $errors->first('biometric_id') }}</span>
					            							</div>
					            						</div>
						            				</div>
						            				</div>

						            				<div class="row clearfix">
						            				<div class="form-group">
						            					<div class="col-md-4 col-sm-6 col-xs-12">
						            						<label for="esic_number">ESIC Number</label>
						            					</div>
						            					<div class="col-md-8 col-sm-6 col-xs-12">
							            					<div class="form-line">
							            						<input disabled autocomplete="none" type="text" name="esic_number" id="esic_number" class="form-control" placeholder="ESIC Number" value="{{ $emp->esic_number}}" >
							            						<span class="text-danger">{{ $errors->first('esic_number') }}</span>
							            					</div>
							            				</div>
						            				</div>
						            			</div>
												<div class="row clearfix">
													<div class="form-group">
						            					<div class="col-md-4 col-sm-6 col-xs-12">
						            						<label for="epf_number">EPF Number</label>
						            					</div>
						            					<div class="col-md-8 col-sm-6 col-xs-12">
							            					<div class="form-line">
							            						<input disabled autocomplete="none" type="text" name="epf_number" id="epf_number" class="form-control" placeholder="EPF Number" value="{{ $emp->epf_number }}" >
							            						<span class="text-danger">{{ $errors->first('epf_number') }}</span>
							            					</div>
							            				</div>
						            				</div>
						            			</div>

						            			<div class="row clearfix">
						            				<div class="form-group">
						            					<div class="col-md-4 col-sm-6 col-xs-12">
						            						<label for="lin_number">LIN Number</label>
						            					</div>
						            					<div class="col-md-8 col-sm-6 col-xs-12">
							            					<div class="form-line">
							            						<input disabled autocomplete="none" type="text" name="lin_number" id="lin_number" class="form-control" placeholder="LIN Number" value="{{ $emp->lin_number}}" >
							            						<span class="text-danger">{{ $errors->first('lin_number') }}</span>
							            					</div>
							            				</div>
						            				</div>
						            			</div>
						            			<div class="row clearfix">
						            				<div class="form-group">
						            					<div class="col-md-4 col-sm-6 col-xs-12">
						            						<label for="uan_number">UAN Number</label>
						            					</div>
						            					<div class="col-md-8 col-sm-6 col-xs-12">
							            					<div class="form-line">
							            						<input disabled autocomplete="none" type="text" name="uan_number" id="uan_number" class="form-control" placeholder="UAN Number" value="{{ $emp->uan_number}}" >
							            						<span class="text-danger">{{ $errors->first('uan_number') }}</span>
							            					</div>
							            				</div>
						            				</div>
						            			</div>

						            				
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    <div class="panel panel-col-cyan">
                                        <div class="panel-heading" role="tab" id="heading3">
                                            <h4 class="panel-title">
                                                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_9" href="#collapse3" aria-expanded="true"
                                                   aria-controls="collapse3">
                                                    Salary Details
                                                    <i class="material-icons mycheck" id="mycheck3">check</i>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapse3" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading3">
                                            <div class="panel-body">

                                            	<div class="row clearfix">
                                            		<div class="form-group">
                                            			<div class="col-md-2 col-sm-6 col-xs-12">
						            						<input disabled autocomplete="none" type="checkbox" name="esic_option" id="esic_option" class="filled-in chk-col-teal" placeholder="ESIC " value="{{ $emp->esic_option }}" <?php if($emp->esic_option=='1') echo "checked"; ?>>
						            						<label for="esic_option" style="font-size: 14px">ESIC</label>
						            						<span class="text-danger">{{ $errors->first('esic_option') }}</span>
							            				</div>
					            						<div class="col-md-2 col-sm-6 col-xs-12">
						            						<input disabled autocomplete="none" type="checkbox" name="epf_option" id="epf_option" class="filled-in chk-col-teal" placeholder="EPF Number" value="{{ $emp->epf_option }}" <?php if($emp->epf_option=='1') echo "checked"; ?>>	
					            							<label for="epf_option" style="font-size: 14px">EPF</label>
					            							<span class="text-danger">{{ $errors->first('epf_option') }}</span>
					            						</div>	
								            		</div>
								            		</div>
								            		<?php 
								            		$salary = (array) json_decode($salaries,true);
								            		if($salaries!='') 
                                        			{
								            		$salary = $salary['emp_salary'];
								            		?>
								            		@foreach($salary as $type=>$value)
								            		@if($type == 'salary')
								            		<div class="row clearfix">
													<div class="form-group">
						            					<div class="col-md-2 col-sm-6 col-xs-12">
						            						<label for="salary">Salary</label>
						            					</div>
						            					<div class="col-md-4 col-sm-6 col-xs-12">
							            					<div class="form-line">
							            						<input disabled autocomplete="none" type="number" name="salary" id="salary" class="form-control salary_value" placeholder="Salary" value="{{ $value }}" >
							            						<span class="text-danger">{{ $errors->first('salary') }}</span>
							            					</div>
							            				</div>
						            				</div>
						            				</div>
						            				@endif
						            				@if($type == 'basic' )
						            				<div class="row clearfix">
													<div class="form-group">
						            					<div class="col-md-2 col-sm-6 col-xs-12">
						            						<label for="basic">Basic + DA</label>
						            					</div>
						            					<div class="col-md-4 col-sm-6 col-xs-12">
							            					<div class="form-line">
							            						<input disabled autocomplete="none" type="number" name="basic" id="basic" class="form-control salary_value" placeholder="Basic + DA" value="{{ $value }}" <?php if($emp->epf_option=='0'){echo "disabled";}?>>
							            						<span class="text-danger">{{ $errors->first('basic') }}</span>

							            						<input disabled type="hidden" class="mybasic" name="mybasic" value="{{ $value }}">
							            					</div>
							            				</div>
						            				</div>
						            				</div>
						            				@endif
						            			@endforeach
						            		<?php }
						            		else{ ?>

						            			<div class="row clearfix">
													<div class="form-group">
						            					<div class="col-md-2 col-sm-6 col-xs-12">
						            						<label for="salary">Salary</label>
						            					</div>
						            					<div class="col-md-4 col-sm-6 col-xs-12">
							            					<div class="form-line">
							            						<input disabled autocomplete="none" type="number" name="salary" id="salary" class="form-control salary_value" placeholder="Salary" value="{{ old('salary') }}" >
							            						<span class="text-danger">{{ $errors->first('salary') }}</span>
							            					</div>
							            				</div>
						            				</div>
						            				</div>
						            				<div class="row clearfix">
													<div class="form-group">
						            					<div class="col-md-2 col-sm-6 col-xs-12">
						            						<label for="basic">Basic + DA</label>
						            					</div>
						            					<div class="col-md-4 col-sm-6 col-xs-12">
							            					<div class="form-line">
							            						<input disabled autocomplete="none" type="number" name="basic" id="basic" class="form-control salary_value" placeholder="Basic + DA" value="{{ old('basic') }}" <?php if($emp->epf_option=='0'){echo "disabled";}?> >
							            						<span class="text-danger">{{ $errors->first('basic') }}</span>
							            					</div>
							            				</div>
						            				</div>
						            				</div>

						            		<?php } ?>

                                            	<div class="table-responsive" style="display: none;">                                           		
                                            	<table class="table table-stripped">
                                            		<tbody id="salary_body" name="salary_body">
                                        			<?php $i=0;
                                        			$salary = (array) json_decode($salaries,true);
                                        			if($salaries!='') 
                                        			{
                                        			?>
                                            		@foreach($salary['emp_salary'] as $salary_type=>$salary_value)
                                            		<?php $i++; ?>
                                            			<tr>
                                            				<td>
                                            					<input disabled type="text" autocomplete="none" name="salary_type[<?php echo $i; ?>]" id="salary_type[<?php echo $i; ?>]" class="form-control salary_type" value="{{ $salary_type }}">
                                            				</td>
                                            				<td>
                                            					<input disabled autocomplete="none" type="number" name="salary_value[<?php echo $i; ?>]" id="salary_value[<?php echo $i; ?>]" class="form-control salary_value" placeholder="" value="{{ $salary_value }}" >
                                            				</td>
                                            				<!-- <td>
                                            					<button type="button" name="remove[<?php echo $i; ?>]" class="btn btn-danger btn-sm remove">
                                            						<i class="material-icons">delete</i>
                                            					</button>
                                            				</td> -->
                                            			</tr>
                                            		@endforeach	
                                            		<?php } ?>	
                                            		</tbody>
                                            	</table>
                                            	</div>

						            		<!-- <div class="row clearfix" style="display: none;">
                                                <div class="form-group">
					            					<div class="col-md-12 col-sm-12 col-xs-12">
						            					<div class="form-line">	
						            						<input disabled type="hidden" name="row_number" id="row_number" value="<?php echo $i; ?>">
						            					<button type="button" id="add_row" name="add_row" class="btn btn-info">Add New Row</button>          	
						            					</div>
						            				</div>
					            				</div>
					            			</div> -->
                                            </div>
                                        </div>
                                    </div>


                                    <div class="panel panel-col-cyan">
                                        <div class="panel-heading" role="tab" id="heading4">
                                            <h4 class="panel-title">
                                                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_9" href="#collapse4" aria-expanded="true"
                                                   aria-controls="collapse4">
                                                   Bank Account Details
                                                   <i class="material-icons mycheck" id="mycheck4">check</i>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapse4" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading4">
                                            <div class="panel-body">
                                                <div class="col-md-8">
                                        <div class="row clearfix">
                                   		<div class="form-group">
			                            	<div class="col-md-4 col-sm-6 col-xs-12">
				            					<label for="acc_holder_name">Account Holder Name</label>
				            				</div>
				            				<div class="col-md-8 col-sm-6 col-xs-12">
				            					<div class="form-line">
				            						<input disabled autocomplete="none" type="text" name="acc_holder_name" id="acc_holder_name" class="form-control" placeholder="Account Holder Name" value="{{ $emp->acc_holder_name }}" >
				            						<span class="text-danger">{{ $errors->first('acc_holder_name') }}</span>
				            					</div>
				            				</div>
				            			</div>
				            			</div>
						            	<div class="row clearfix">
				            			<div class="form-group">
					            			<div class="col-md-4 col-sm-6 col-xs-12">
				            					<label for="acc_no">Account Number</label>
				            				</div>
				            				<div class="col-md-8 col-sm-6 col-xs-12">  			
				            					<div class="form-line">
				            						<input disabled autocomplete="none" type="text" name="acc_no" id="acc_no" class="form-control" placeholder="Account Number" value="{{ $emp->acc_no }}" >
				            						<span class="text-danger">{{ $errors->first('acc_no') }}</span>
				            					</div>
				            				</div>
				            			</div>
				            			</div>
						            	<div class="row clearfix">
				            			<div class="form-group">
					            			<div class="col-md-4 col-sm-6 col-xs-12">
				            					<label for="ifsc_code">IFSC Code</label>
				            				</div>
				            				<div class="col-md-8 col-sm-6 col-xs-12">
				            					<div class="form-line">
				            						<input disabled autocomplete="none" type="text" name="ifsc_code" id="ifsc_code" class="form-control" placeholder="IFSC Code" value="{{ $emp->ifsc_code }}" >
				            						<span class="text-danger">{{ $errors->first('ifsc_code') }}</span>
				            					</div>
				            				</div>
				            			</div>
				            			</div>
						            	<div class="row clearfix">
				            			<div class="form-group">
					            			<div class="col-md-4 col-sm-6 col-xs-12">
				            					<label for="bank_name">Bank Name</label>
				            				</div>
				            				<div class="col-md-8 col-sm-6 col-xs-12">
				            					<div class="form-line">
				            						<input disabled autocomplete="none" type="text" name="bank_name" id="bank_name" class="form-control" placeholder="Bank Name" value="{{ $emp->bank_name }}" >
				            						<span class="text-danger">{{ $errors->first('bank_name') }}</span>
				            					</div>
				            				</div>
				            			</div>
				            			</div>
						            	<div class="row clearfix">
				            			<div class="form-group">
					            			<div class="col-md-4 col-sm-6 col-xs-12">
				            					<label for="branch">Branch</label>
				            				</div>
				            				<div class="col-md-8 col-sm-6 col-xs-12">
				            					<div class="form-line">
				            						<input disabled autocomplete="none" type="text" name="branch" id="branch" class="form-control" placeholder="Branch" value="{{ $emp->branch }}" >
				            						<span class="text-danger">{{ $errors->first('branch') }}</span>
				            					</div>
				            				</div>
				            			</div>
				            		</div>
		                            </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-col-cyan">
                                        <div class="panel-heading" role="tab" id="heading5">
                                            <h4 class="panel-title">
                                                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_9" href="#collapse5" aria-expanded="true"
                                                   aria-controls="collapse5">
                                                    Login Details
                                                    <i class="material-icons mycheck" id="mycheck5">check</i>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapse5" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading5">
                                            <div class="panel-body">
                                                <div class="col-md-8">
                                        <div class="row clearfix">
                                		<div class="form-group">
	                                		<div class="col-md-4 col-sm-6 col-xs-12">
	                                			<label for="login_email">Email</label>
	                                		</div>
	                                		<div class="col-md-8 col-sm-6 col-xs-12">
				            					<div class="form-line">
				            						<input disabled autocomplete="none" type="email" name="login_email" id="login_email" class="form-control" placeholder="Email" value="{{ $emp->email }}" disabled>
				            						<span class="text-danger">{{ $errors->first('login_email') }}</span>
				            					</div>
				            				</div>
				            			</div>
				            			</div>
				            			<div class="row clearfix">
				            			<div class="form-group">
	                                		<div class="col-md-4 col-sm-6 col-xs-12">
	                                			<label for="password">Password</label>
	                                		</div>
	                                		<div class="col-md-8 col-sm-6 col-xs-12">
				            					<div class="form-line">
				            						<input disabled autocomplete="none" type="text" name="password" id="password" class="form-control" placeholder="Password" value="{{ $emp->password }}" >
				            						<span class="text-danger">{{ $errors->first('password') }}</span>
				            					</div>
				            				</div>
				            			</div>
				            			</div>
				            			<div class="row clearfix">
				            			<div class="form-group">
			            					<div class="col-md-4 col-sm-6 col-xs-12">
			            						<label for="role">Role</label>
			            					</div>
			            					<div class="col-md-8 col-sm-6 col-xs-12">
				            					<div class="form-line">		
				            						 <select disabled class="form-control show-tick select2" name="role" id="role" placeholder="Role" >
				            						 	<option></option>
			@foreach($roles as $role)
	           	@if($role->role!='admin')
	           		@if($emp_role_id == $role->role_id)
						<option value="{{$role->role_id}}" selected>{{ucfirst(trans($role->role))}}</option>
					@else
						<option value="{{$role->role_id}}">{{ucfirst(trans($role->role))}}</option>	
					@endif
				@endif 
			@endforeach
				            						</select>
				            						<span class="text-danger">{{ $errors->first('role') }}</span>
				            					</div>
				            				</div>
			            				</div>
			            			</div>
			            				<!-- <div class="form-group">
					            			<div class="col-md-6 col-md-offset-3">
					            				<div class="form-group">
					            						<button autocomplete="none" type="submit" name="submit" class="btn btn-success">Add Employee</button>
					            				</div>
				            				</div>
				            			</div> -->
                                	</div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                    
                                </div>
				            	@endforeach
                            </form>



                        </div>
                    </div>
                </div>
            </div>


<style type="text/css">
	#imagePreview {
  width: 100px;
  height: 120px;
  padding: 12px;
  margin-top: 6px;
  background-position: center center;
  background-size: contain;
  background-repeat: no-repeat;
  background-clip: padding-box;
  border: 1px solid silver;
  -webkit-box-shadow: 0 0 1px 1px rgba(0, 0, 0, .3);
  display: inline-block;
}
.mycheck{
	font-weight: 700;
	color: yellow;
	float: right!important;
}
</style>

<script type="text/javascript">
	function personal_details(){
		$count=0;
				if($('#name').val()!='' && $('#blood_group').val()!='' && $('#email').val()!='' && $('#dob').val()!='' && $('#mobile').val()!='' && $('#gender').val()!='' && $('#category')!='' && $('#adhaar_number').val()!='' && $('#pan_number').val()!='' && $('#marital_status').val()!='' && $('#local_address').val()!='' && $('#permanent_address').val()!='' && $('#photo').val()!='' && $('#emergency_call_number').val()!='' && $('#emergency_call_person').val()!='')
				{
					$count++;
				}				
			if($count>0)
				{
					$('#mycheck1').show();
				}
				else
				{
					$('#mycheck1').hide();
				}
		}
		
		function company_details(){
		$count=0;
				if($('#genesis_id').val()!='' && $('#biometric_id').val()!='' && $('#employee_id').val()!='' && $('#department').val()!='' && $('#designation').val()!='' && $('#doj').val()!='' && $('#status').val()!='' && $('#esic_number').val()!='' && $('#epf_number').val()!='')
				{
					$count++;
				}				
			if($count>0)
				{
					$('#mycheck2').show();
				}
				else
				{
					$('#mycheck2').hide();
				}
		}
		function salary_details(){
		$count=0;
			$('.salary_value').each(function(){
				if($(this).val()=='')
				{
					$count++;
				}
			});	
			$('.salary_type').each(function(){
				if($(this).val()=='')
				{
					$count++;
				}
			});				
			if($count==0)
				{
					$('#mycheck3').show();
				}
				else
				{
					$('#mycheck3').hide();
				}
		}
		function bank_details(){
		$count=0;
				if($('#acc_holder_name').val()!='' && $('#acc_no').val()!='' && $('#ifsc_code').val()!='' && $('#bank_name').val()!='' && $('#branch').val()!='')
				{
					$count++;
				}				
			if($count>0)
				{
					$('#mycheck4').show();
				}
				else
				{
					$('#mycheck4').hide();
				}
		}
		function login_details(){
		$count=0;
				if($('#login_email').val()!='' && $('#password').val()!='' && $('#role').val()!='')
				{
					$count++;
				}				
			if($count>0)
				{
					$('#mycheck5').show();
				}
				else
				{
					$('#mycheck5').hide();
				}
		}
	$(document).ready(function(){
		$("#title").select2({
	     placeholder: "Select Title",
	     allowClear: true
	    });

		$('#title,#first_name,#middle_name,#last_name,#blood_group,#email,#mobile,#dob,#marital_status,#gender,#category,#adhaar_number,#pan_number,#local_address,#permanent_address,#photo,#emergency_call_number,#emergency_call_person').blur(function(){
			personal_details();
		});
		$('#heading1').click(function(){
			personal_details();
		});
		$('#employee_id,#department,#designation,#doj,#status,#genesis_id,#biometric_id,#esic_number,#epf_number').blur(function(){
			company_details();
		});
		$('#heading2').click(function(){
			company_details();
		});
		$('#salary_body').on("blur",".salary_value,.salary_type",function(){
			salary_details();			
		});
		$('#heading3').click(function(){
			salary_details();
		});
		$('#acc_holder_name,#acc_no,#ifsc_code,#bank_name,#branch').blur(function(){
			bank_details();
		});
		$('#heading4').click(function(){
			bank_details();
		});
		$('#login_email,#password,#role').blur(function(){
			login_details();
		});
		$('#heading5').click(function(){
			login_details();
		});

		// $("#wizard_with_validation").submit(function(event){ alert('123');
		// 	var form_data = $('#wizard_with_validation').serialize()
  //           $.ajax({
  //           type:'POST',
  //           url:'add-employee/submit',
  //           data:form_data,
  //           success:function(data){
  //           	alert(data);
  //             // refreshTable();
  //           }
  //           });
  //       });

		// $("#wizard_with_validation").submit(function(event){ alert('123');
    // event.preventDefault(); //prevent default action 
    // var post_url = $(this).attr("action"); //get form action url
    // var request_method = $(this).attr("method"); //get form GET/POST method
    // var form_data = $(this).serialize(); //Encode form elements for submission
    
    // $.ajax({
    //     url : post_url,
    //     type: request_method,
    //     data : form_data
    // }).done(function(response){ //
    //     $("#server-results").html(response);
    // });
// });
		
		

	});
</script>
