@extends('layouts.app')

@section('content')
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>DEPARTMENT</h2>
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
                               Create Department
                             </h2>
                          
                        </div>
					<form action="department/submit"  method="POST">
					{{ csrf_field() }}
					<!--body Start-->	
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-sm-2" align="right">
								 <label> Department</label>
								</div>
							   <div class="col-sm-4">
                                   <div class="form-line">
                                            <input type="text" name="department" class="form-control" placeholder="Department Name" required/>
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
                               Department List
                            </h2>
                          </div>
                        <div class="body table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr class="bg-orange">
                                        <th>#</th>
                                        <th>DepartmentName</th>
										 <th>Created At</th>
                                        <th>Action</th>
                                      </tr>
                                </thead>
                                <tbody>
								<?php 
								$i=1;
								?>
								@foreach($department_view as $row)
                                  
								   <tr>
									 {{ csrf_field() }}
									  <th scope="row">{{ $i++ }}</th>
                                        <td>{{ $row->department_name }}</td>
										 <td>{{ $row->created_at }}</td>
                                        <td> <button type="button" id="update_department{{ $row->id }}" 
										class="btn bg-teal waves-effect edit-modal" data-toggle="modal" 
										data-target="#updateModal" data-id="{{$row->id}}"
										data-name="{{$row->department_name}}">
                                    <i class="material-icons">create</i>
                                </button>
								<!-- <button type="delete" data-id="{{$row->id}}" data-target="#deleteModal" data-toggle="modal" class="btn bg-red waves-effect delete-modal">
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
                            <h4 class="modal-title" id="uModalLabel">Edit Department</h4>
                        </div>
						
                        <div class="modal-body">
						<form action="department"  method="POST">
						{{ csrf_field() }}
						<div class="row clearfix">
						<div class="col-sm-2" align="right">
								 <label> Department</label>
								</div>
                               <div class="col-sm-8">
                                    <div class="form-line">
										    <input type="hidden" id="fid" name="id"  class="form-control" placeholder="Department Name" required/>
                                            <input type="text" id="n" name="department"  class="form-control" placeholder="Department Name" required/>
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
                            <h4 class="modal-title" id="defaultModalLabel">Delete Department</h4>
                        </div>
						<form action="department/delete"  method="POST">
                        <div class="modal-body">						
						{{ csrf_field() }}
						<input type="hidden" id="id" name="dept_id"  class="form-control" placeholder="Department Name" required/>
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
	$(document).on('click', '.edit-modal', function(){
       
		$('#fid').val($(this).data('id'));
        $('#n').val($(this).data('name'));
        $('#updateModal').modal('show');
    });
	$(document).on('click', '.delete-modal', function(){
		$('#id').val($(this).data('id'));
        $('#deleteModal').modal('show');
    });
     $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
		$("#success-alert").slideUp(500);
		});
    
    
 });
 
</script >


@endsection