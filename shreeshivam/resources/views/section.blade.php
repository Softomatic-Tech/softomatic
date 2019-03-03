@extends('layouts.form-app')

@section('content')
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Section</h2>
            </div>
<div class="flash-message" id="success-alert">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
      @if(Session::has('alert-' . $msg))
		<p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
      @endif
    @endforeach
  </div> <!-- end .flash-message -->
      

                <div class="row clearfix">
                <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Create Section
                             </h2>
                          
                        </div>
                    
                   
                    <!--body Start-->   
                        <div class="body">
                            <div class="row clearfix">
                            <form action="section/submit"  method="POST">   
                             {{ csrf_field() }}     

                             <div class="row">                      
                                <div class="col-lg-2" align="right">
                                 <label>Division</label>
                                </div>
                                <div class="col-lg-4">
                                   <div class="form-line">
                                            <select name="division" id="division" class="form-control" placeholder="Division Name" required>
                                                <option></option>
                                                @foreach($division as $div)
                                                <option value="{{$div->id}}">{{$div->division}}</option>
                                                @endforeach
                                                <!-- <option value="active">Active</option>
                                                <option value="inactive">Inactive</option> -->
                                            </select>
                                        </div>
                                </div>
                            </div>

                             <div class="row">                      
                                <div class="col-lg-2" align="right">
                                 <label>Name</label>
                                </div>
                                <div class="col-lg-4">
                                   <div class="form-line">
                                              <input type="text" name="section" class="form-control" placeholder="Section Name" required/>
                                        </div>
                                </div>
                            </div>
                            <div class="row">                      
                                <div class="col-lg-2" align="right">
                                 <label>Status</label>
                                </div>
                                <div class="col-lg-4">
                                   <div class="form-line">
                                            <select name="status" id="status" class="form-control" p required>
                                                <option></option>
                                                <option value="active">Active</option>
                                                <option value="inactive">Inactive</option>
                                            </select>
                                        </div>
                                </div>
                            </div>

                                <div class="col-lg-4 col-lg-offset-4">
                                    <div class="form-group">
                                        <button type="submit" name="submit" class="btn btn-sm bg-green waves-effect"><i class="material-icons">save</i><span class="icon-name">Save</span></button>
                                    </div>
                                </div>  

                                                  
                        </form>
                        <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                           <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable myemptable">
                                    <thead>
                                        <tr class="bg-orange">
                                            <th>#</td>
                                            <th>Division</th>
                                            <th>Section</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody> <?php $count=0; ?>
                                        @foreach($section as $sec) <?php $count++; ?>
                                        <tr>
                                            <td>{{$count}}</td>
                                            <td>{{$sec->division}}</td>
                                            <td>{{$sec->section}}</td>
                                            <td>{!!ucfirst($sec->status)!!}</td>
                                            <td><a type="button" target="_blank" data-id="{{$sec->id}}" data-name="{{$sec->section}}"
                                            data-div="{{$sec->division_id}}" data-status="{{$sec->status}}" class="btn bg-teal waves-effect edit_sec">
                                                <i class="material-icons">create</i>
                                            </a></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    </div>
                    </div>
                    <!--body End--> 
                    
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
                <h4 class="modal-title" id="uModalLabel">Edit Section</h4>
            </div>
            <form action="section/update"  method="POST">
            <div class="modal-body">                        
            {{ csrf_field() }}
            <div class="row clearfix">
                <div class="form-group">
                <div class="col-sm-4" align="right">
                         <label>Division</label>
                        </div>
                <div class="col-sm-8">
                    <div class="form-line">
                        <select name="division" id="modal_division" style="width: 100%;" class="form-control select2" placeholder="Status" required>
                            <option></option>
                            @foreach($division as $div)
                            <option value="{{$div->id}}">{{$div->division}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            </div>

            <div class="row clearfix">
                <div class="form-group">
                <div class="col-sm-4" align="right">
                     <label>Section</label>
                </div>
                    <div class="col-sm-8">
                        <div class="form-line">
                            <input type="hidden" id="did" name="id"  class="form-control" placeholder="Name" required/>
                            <input type="text" id="modal_section" name="section"  class="form-control" placeholder="Division Name" required/><br>
                        </div> 
                    </div>
                </div>
            </div> 
            <div class="row clearfix">
                <div class="form-group">
                <div class="col-sm-4" align="right">
                         <label>Status</label>
                        </div>
                <div class="col-sm-8">
                    <div class="form-line">
                        <select name="status" id="modal_status" style="width: 100%;" class="form-control" placeholder="Status" required>
                            <option></option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
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

<style type="text/css">
    .table-responsive
    {
        min-height: .01%;
        overflow-x: initial;
    }
</style>

@endsection
@section('jquery')
 <link href="plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
    <script src="plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
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
<script>
$(document).ready(function() {

    $("#status").select2({
         placeholder: "Select Status",
         allowClear: true
        });
    $("#division").select2({
         placeholder: "Select Division",
         allowClear: true
        });

     $("#modal_status").select2({
         placeholder: "Select Status",
         allowClear: true
        });
    $("#modal_division").select2({
         placeholder: "Select Division",
         allowClear: true
        });

	$(document).on('click', '.edit_sec', function(){
        $('#did').val($(this).data('id'));
        $("#modal_division").children('[value="'+$(this).data('div')+'"]').attr('selected', true);
        $('#modal_section').val($(this).data('name'));
        $("#modal_status").children('[value="'+$(this).data('status')+'"]').attr('selected', true);
        
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