@extends('layouts.form-app')

@section('content')
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Bank</h2>
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
                               Add Bank
                             </h2>
                          
                        </div>
					<form action="bank/submit"  method="POST">
					{{ csrf_field() }}
					<!--body Start-->	
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-sm-2" align="right">
								 <label> Branch</label>
								</div>
							   <div class="col-sm-4">
                                   <div class="form-line">
                                    <select class="form-control show-tick" placeholder="Shreeshivam Branch" id="branch_location_id" name="branch_location_id">
                                        <option>Select Branch</option>
                                        @foreach($branches as $branch)
                                            <option value="{{$branch->id}}">{{$branch->branch}}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger">{{ $errors->first('branch_location_id') }}</span>
                                        </div>
                                  </div>
                            </div>

							<div class="row clearfix">
                                <div class="col-sm-2" align="right">
								    <label>Bank Name</label>
								</div>
							    <div class="col-sm-4">
                                    <div class="form-line">
                                          <!--  <input type="text" name="bank_name" id="bank_name" class="form-control" placeholder="Bank Name" /> -->
                                          <select class="form-control select2" placeholder="Shreeshivam Bank" id="bank_name" name="bank_name">
                                            <option></option>
                                            @foreach($bank_list as $bank)
                                                <option value="{{$bank->id}}">{{$bank->bank_name}}</option>
                                            @endforeach
                                         </select>
                                           <span class="text-danger">{{ $errors->first('bank_name') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-sm-2" align="right">
                                    <label>Bank Branch</label>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-line">
                                           <input type="text" name="bank_branch" id="bank_branch" class="form-control" placeholder="Bank Branch" />
                                           <span class="text-danger">{{ $errors->first('bank_branch') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-sm-2" align="right">
                                    <label>IFSC Code</label>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-line">
                                           <input type="text" name="bank_ifsc_code" id="bank_ifsc_code" class="form-control" placeholder="IFSC code" />
                                           <span class="text-danger">{{ $errors->first('bank_ifsc_code') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-sm-2" align="right">
                                    <label>Account Number</label>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-line">
                                           <input type="text" name="bank_acc_no" id="bank_acc_no" class="form-control" placeholder="Account Holder" />
                                           <span class="text-danger">{{ $errors->first('bank_acc_no') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-sm-2" align="right">
                                    <label>Account Holder Name</label>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-line">
                                           <input type="text" name="bank_acc_holder_name" id="bank_acc_holder_name" class="form-control" placeholder="Account Holder Name" />
                                           <span class="text-danger">{{ $errors->first('bank_acc_holder_name') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-sm-2" align="right">
                                    <label>status</label>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-line">
                                        <select class="form-control show-tick" placeholder="status" id="status" name="status">
                                            <option>Select status</option>
                                            <option value="current">Current</option>
                                            <option value="other">Other</option>
                                        </select>
                                        <span class="text-danger">{{ $errors->first('status') }}</span>
                                    </div>
                                </div>
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
                               Bank List
                            </h2>
                          </div>
                        <div class="body table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr class="bg-orange">
                                        <th>#</th>
                                        <th>Branch</th>
                                        <th>Bank Name</th>
										<th>Bank Branch</th>
										<th>Bank IFSC Code</th>
                                        <th>Bank Acc Number</th>
                                        <th>Bank Acc Holder Name</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                      </tr>
                                </thead>
                                <tbody>
								<?php 
								$i=1; 
								?>
								@foreach($banks as $bank)
                                  
								   <tr>
									 {{ csrf_field() }}
									  <th scope="bank">{{ $i++ }}</th>
                                        <td>{{ $bank->branch }}</td>
                                        <td>{{ $bank->bank_name }}</td>
										<td>{{ $bank->bank_branch }}</td>
									    <td>{{ $bank->bank_ifsc_code }}</td>
                                        <td>{{ $bank->bank_acc_no }}</td>
                                        <td>{{ $bank->bank_acc_holder_name }}</td>
                                        <td>{!! ucfirst($bank->status) !!}</td>
                                        <td> <button type="button" id="update_bank" 
										class="btn bg-teal waves-effect edit-modal" data-toggle="modal" 
										data-target="#updateModal" data-id="{{$bank->id}}"
										data-bank_name="{{$bank->bank_list_id}}" data-bank_branch="{{$bank->bank_branch}}" data-branch="{{$bank->branch_location_id}}" data-bank_acc_no="{{$bank->bank_acc_no}}" data-bank_ifsc_code="{{$bank->bank_ifsc_code}}" data-bank_acc_holder_name="{{$bank->bank_acc_holder_name}}" data-status="{{$bank->status}}">
                                    <i class="material-icons">create</i>
                                </button>
								<!-- <button type="delete" data-id="{{$bank->id}}" data-target="#deleteModal" data-toggle="modal" class="btn bg-red waves-effect delete-modal">
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
    </section>
	<div class="modal fade" id="updateModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-pink">
                            <h4 class="modal-title" id="uModalLabel">Edit Bank</h4>
                        </div>
						
                        <div class="modal-body">
						<form action="bank/update"  method="POST">
						{{ csrf_field() }}
						<div class="form-group clearfix">
						<div class="col-sm-4" align="right">
								 <label> Branch</label>
								</div>
                        <div class="col-sm-8">
                            <div class="form-line">
								    <input type="hidden" id="fid" name="id"  class="form-control" placeholder="Branch Name" required/>
                                    <select class="form-control show-tick" placeholder="Shreeshivam Branch" id="modal_location_id" name="branch_location_id">
                                        <option>Select Branch</option>
                                        @foreach($branches as $branch)
                                            <option value="{{$branch->id}}">{{$branch->branch}}</option>
                                        @endforeach
                                    </select>
									
                            </div>
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <div class="col-sm-4" align="right">
                                 <label> Bank Name</label>
                                </div>
                        <div class="col-sm-8">
                            <div class="form-line">
                                <!-- <input type="text" name="bank_name" id="modal_name" class="form-control"/> -->
                                <select class="form-control select2" placeholder="Shreeshivam Bank" id="modal_name" name="bank_name">
                                    <option>Select Bank</option>
                                    @foreach($bank_list as $bank)
                                        <option value="{{$bank->id}}">{{$bank->bank_name}}</option>
                                    @endforeach
                                </select>
                                   
                            </div>
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <div class="col-sm-4" align="right">
                                 <label> Bank Branch</label>
                                </div>
                        <div class="col-sm-8">
                            <div class="form-line">
                                <input type="text" name="bank_branch" id="modal_branch" class="form-control"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <div class="col-sm-4" align="right">
                                 <label> IFSC Code</label>
                                </div>
                        <div class="col-sm-8">
                            <div class="form-line">
                                <input type="text" name="bank_ifsc_code" id="modal_ifsc_code" class="form-control"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <div class="col-sm-4" align="right">
                                 <label> Account Number</label>
                                </div>
                        <div class="col-sm-8">
                            <div class="form-line">
                                <input type="text" name="bank_acc_no" id="modal_acc_no" class="form-control"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <div class="col-sm-4" align="right">
                                 <label>Account Holder Name</label>
                                </div>
                        <div class="col-sm-8">
                            <div class="form-line">
                                <input type="text" name="bank_acc_holder_name" id="modal_acc_holder_name" class="form-control"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <div class="col-sm-4" align="right">
                                 <label>Status</label>
                                </div>
                        <div class="col-sm-8">
                            <div class="form-line">
                                <select class="form-control show-tick" placeholder="status" id="modal_status" name="status">
                                    <option>Select status</option>
                                    <option value="current">Current</option>
                                    <option value="other">Other</option>
                                </select>
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
                            <h4 class="modal-title" id="defaultModalLabel">Delete Branch</h4>
                        </div>
						<form action="branch/delete"  method="POST">
                        <div class="modal-body">						
						{{ csrf_field() }}
						<input type="hidden" id="id" name="dept_id"  class="form-control" placeholder="branch Name" required/>
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
@endsection
@section('jquery')
<script>
$(document).ready(function() {
        $("#bank_name").select2({
         placeholder: "Select Bank",
         allowClear: true
        });
        /*$("#modal_name").select2({
         placeholder: "Select Bank",
         allowClear: true
        });*/
	$(document).on('click', '.edit-modal', function(){  
        $('#fid').val($(this).data('id'));
        $("#modal_location_id").children('[value="'+$(this).data('branch')+'"]').attr('selected', true);
        $("#modal_name").children('[value="'+$(this).data('bank_name')+'"]').attr('selected', true);
        /*$('#modal_name').val($(this).data('bank_name'));*/
        $('#modal_branch').val($(this).data('bank_branch'));
        $('#modal_acc_no').val($(this).data('bank_acc_no'));
        $('#modal_ifsc_code').val($(this).data('bank_ifsc_code'));
        $('#modal_acc_holder_name').val($(this).data('bank_acc_holder_name'));
        $('#modal_status').val($(this).data('status'));
        $('#updateModal').modal('show');
    });
    
 });
 
</script >


@endsection