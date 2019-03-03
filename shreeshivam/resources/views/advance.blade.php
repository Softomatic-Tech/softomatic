@extends('layouts.form-app')

@section('content')
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Advance</h2>
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
                               Create advance
                             </h2>
                          
                        </div>
					<form action="advance/submit"  method="POST">
					{{ csrf_field() }}
					<!--body Start-->	
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-sm-2" align="right">
                                 <label>Employee</label>
                                </div>
                               <div class="col-sm-4">
                                 <div class="form-line">        
                                 <select class="form-control show-tick" placeholder="Department" required name="emp">
                                            <option value="">---Select Employee---</option>
                                         @foreach($emp as $emps)
                                            <option value="{{$emps->id}}">{{$emps->title}} {{$emps->first_name}} {{$emps->middle_name}} {{$emps->last_name}} ({{($emps->genesis_id)}})</option>   
                                         @endforeach
                                 </select>
                                 </div>
                               </div>
                            </div>
                            
                            <div class="row clearfix">
                                <div class="col-sm-2" align="right">
                                 <label>Total Advance</label>
                                </div>
                               <div class="col-sm-4">
                                 <div class="form-line">        
                                 <input type="number" name="total_advance" class="form-control" placeholder="Total Advance" required/>
                                 </div>
                               </div>
                            </div>
                            
							 <div class="row clearfix">
                                <div class="col-sm-2" align="right">
								 <label>Deduction</label>
								</div>
							   <div class="col-sm-4">
                                   <div class="form-line">
                                            <input type="number" name="deduction" class="form-control" placeholder="Deduction" required/>
                                        </div>
                                  </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-2" align="right">
								 <label>Advance Given Date</label>
								</div>
							   <div class="col-sm-4">
                                   <div class="form-line">
                                            <input autocomplete="none" type="text" name="advance_given_d" id="advance_given_d" class="form-control datepicker2" placeholder="Advance Given Date"  >
                                        </div>
                                  </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-2" align="right">
								 <label>Deduction Start Date</label>
								</div>
							   <div class="col-sm-4">
                                   <div class="form-line">
                                            <input autocomplete="none" type="text" name="deduction_start_d" id="deduction_start_d" class="form-control datepicker1" placeholder="Advance Deduction Start Date"  >
                                        </div>
                                  </div>
                            </div>
                            
							 <div class="row clearfix">
                               <div class="col-sm-12">
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
                               Advance List
                            </h2>
                          </div>
                        <div class="body table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr class="bg-orange">
                                        <th>#</th>
										<th>Employee Name</th>
                                        <th>Advance</th>
										<th>Deduction Per Month</th>
										<th>Advance Given Date</th>
										<th>Deduction Start Date</th>
                                        <!--<th>Action</th>-->
                                      </tr>
                                </thead>
                                <tbody>
								<?php 
								$i=1;
								?>
								
                             @foreach($deduction as $deductions)
								   <tr>
									 {{ csrf_field() }}
									  <th scope="row">{{$i++}}</th>
                                         <td>{{$deductions->first_name}} {{$deductions->middle_name}} {{$deductions->last_name}}</td>
										 <td>{{$deductions->advance}}</td>
										 <td>{{$deductions->deduction_per_month}}</td>
										 <td>{{$deductions->advance_given_date}}</td>
										 <td>{{$deductions->deduction_start_date}}</td>
          <!--                              <td> <button type="button" id="update_designation" -->
										<!--class="btn bg-teal waves-effect edit-modal" data-toggle="modal" -->
										<!--data-target="#updateModal" data-id=""-->
										<!--data-name="" data-dname="" >-->
          <!--                          <i class="material-icons">create</i>-->
										<!--</button>-->
										
										<!--	</td>   -->
                                
								   </tr>
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
                            <h4 class="modal-title" id="uModalLabel">Edit Advance</h4>
                        </div>
						
                        <div class="modal-body">
						<form action="designation"  method="POST">
						{{ csrf_field() }}
						<div class="row clearfix">
						<div class="col-sm-2" align="right">
								 <label> Total Advance</label>
								</div>
                               <div class="col-sm-8">
                                    <div class="form-line">
										    <input type="hidden" id="fid" name="id"  class="form-control" placeholder="Designation Name" required/>
                                            <input type="text" id="n" name="designation"  class="form-control" placeholder="Designation Name" required/>
                                        </div>
                                   
                                </div>
                            </div> <br>
							<div class="row clearfix">
						<div class="col-sm-2" align="right">
								 <label> Deduction</label>
								</div>
                               <div class="col-sm-8">
                                    <div class="form-line">
										    <select class="form-control show-tick" id="dn" placeholder="Department" required name="department">
								
											<option value=""></option>	
										
				            		</select>
                                        </div>
                                   
                                </div>
                            </div> 
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
                            <h4 class="modal-title" id="defaultModalLabel">Delete Designation</h4>
                        </div>
						
                        <div class="modal-body">
						<form action="designation/delete"  method="POST">
						{{ csrf_field() }}
						<input type="hidden" id="id" name="desig_id"  class="form-control" placeholder="Designation Name" required/>
						       <h5> Are you sure you want to delete this?</h5>                        
                        <div class="modal-footer">
                            <button type="submit" class="btn  bg-deep-orange waves-effect">Yes</button>
                            <button type="button" class="btn bg-blue-grey  waves-effect" data-dismiss="modal">No</button>
                        </div>
						</form>
                        </div>
                    </div>
                </div>
            </div>
@endsection
@section('jquery')
<script>
$('.datepicker2').datepicker().on('changeDate', function (ev) {
		    $('.dropdown-menu').hide();
		});
		
$('.datepicker1').datepicker().on('changeDate', function (ev){ 
	$('.dropdown-menu').hide();
	});
// $(document).ready(function() {
// 	$(document).on('click', '.edit-modal', function(){
       
// 		$('#fid').val($(this).data('id'));
//         $('#n').val($(this).data('name'));
// 		$('#dn').val($(this).data('dname'));
//         $('#updateModal').modal('show');
//     });
// 	$(document).on('click', '.delete-modal', function(){
       
// 		$('#id').val($(this).data('id'));
//         $('#deleteModal').modal('show');
//     });

                  
 
// 	    $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
// 		$("#success-alert").slideUp(500);
// 		});
// 	}); 

</script >


@endsection