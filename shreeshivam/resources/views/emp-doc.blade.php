@extends('layouts.form-app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>File</h2>
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
                              Upload File
                             </h2>
                          
                        </div>
          					<form action="upload_emp_doc" method="POST" enctype="multipart/form-data">
          					{{ csrf_field() }}
					<!--body Start-->	
                        <div class="body">
                        <div class="row clearfix">
                                <div class="col-sm-2" align="right">
                                 <label>File Type</label>
                                </div>
                                 <div class="col-sm-4">
                                   <div class="form-line">
                                     <select name="file_type" id="file_type" class="form-control select2 file_type">
                                     <option value=""></option>
                                       @foreach($file_type  as $file )
                                       @if(old('file_type') == $file->id)
                                          <option value="{{$file->id}}" selected>{{$file->name}}</option>
                                          @else
                                          <option value="{{$file->id}}">{{$file->name}}</option>
                                          @endif
                                          @endforeach
                                     </select>
                                      <span class="text-danger">{{ $errors->first('file_type') }}</span>
                                  </div>
                    
                                  </div>
                                  <div class="col-sm-1"> <button type="button" class="btn btn-xs bg-blue-grey waves-effect" data-target="#docTypeModal" data-toggle="modal">
                                    <i class="material-icons">add</i>
                                </button></div>  
                            </div>
                         
                            <div class="row clearfix">
                                <div class="col-sm-2" align="right">
                								 <label> Upload File</label>
                								</div>
                							   <div class="col-sm-4">
                                   <div class="form-line">
                                      <input type="file" name="file" class="form-control btn-md bg-teal waves-effect" value="{{ old('file') }}" required/>
                                      <span class="text-danger">{{ $errors->first('file') }}</span>
                                  </div>
										
                                  </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-2" align="right">
                                 <label>For</label>
                                </div>
                                <div class="col-sm-3">
                                 
                                     <select class="form-control select2 branch" name="branch" id="branch" placeholder="Gender" >
                                          <option></option>
                                          @foreach($branch  as $branches )
                                          @if(old('branch') ==$branches->id)
                                          <option value="{{$branches->id}}" selected>{{$branches->branch}}</option>
                                          @else
                                          <option value="{{$branches->id}}">{{$branches->branch}}</option>
                                          @endif
                                          @endforeach
                                        </select>
                                        <span class="text-danger">{{ $errors->first('branch') }}</span>
                                     </div> 

                                     <div class="col-sm-3">
                                        <select class="form-control select2 department" name="department" id="department" placeholder="Gender" >
                                          <option></option>
                                          @foreach($department  as $dept )
                                          @if(old('department') ==$dept->id)
                                          <option value="{{$dept->id}}" selected>{{$dept->department_name}}</option>
                                          @else
                                          <option value="{{$dept->id}}">{{$dept->department_name}}</option>
                                         @endif
                                          @endforeach
                                     </select>
                                        <span class="text-danger">{{ $errors->first('department') }}</span>
                                        </div>
                                        <div class="col-sm-3"> 
                                        <select class="form-control select2 employee" name="employee" id="employee" placeholder="Gender" >
                                          <option></option>
                                         </select>
                                        <span class="text-danger">{{ $errors->first('employee') }}</span>
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
                               Uploaded File List
                            </h2>
                          </div>
                        <div class="table-responsive">
                          @include('doc_table')
                    </div>
                </div>
        
        </div>
		
		</div>
        <div class="modal fade" id="docTypeModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-pink">
                            <h4 class="modal-title" id="uModalLabel">Add New Document</h4>
                        </div>
						
                        <div class="modal-body">
						<!-- <form action="designation"  method="POST"> -->
						{{ csrf_field() }}
						<div class="row clearfix">
						<div class="col-sm-2" align="right">
								 <label>Name</label>
								</div>
                               <div class="col-sm-8">
                                    <div class="form-line">
										 <input type="text" name="doc_type" class="form-control" id="doc_type">
                                        </div>
                                   
                                </div>
                            </div> 
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="add" class="btn  bg-deep-orange waves-effect add">Save</button>
                            <button type="button"  class="btn bg-blue-grey  waves-effect" data-dismiss="modal">Close</button>
                        </div>
						<!-- </form> -->
                    </div>
                </div>
            </div>
    </section>
	<style type="text/css">
   .input-group {
    margin-bottom: 0px; 
  } 
  </style>
			
@endsection
@section('jquery')
<script type="text/javascript">
  $(document).ready(function(){
     
    $("#branch").select2({
       placeholder: "Select Branch",
       allowClear: true
      });
      $("#department").select2({
       placeholder: "Select Department",
       allowClear: true
      });
      $("#employee").select2({
       placeholder: "Select Employee",
       allowClear: true
      });
      $("#file_type").select2({
       placeholder: "Select File Type",
       allowClear: true
      });
    $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
$('.department').change(function(){
       
       $.ajax({
                type:'POST',
                datatype: 'text',
                url:'getemp',
                data:{branch:$('.branch').val(),department:$(this).val()},
                success:function(data){
                   
                    $('.employee').html(data);
                }
                });
			});
     $('.branch').change(function(){
      $("#department").val('').trigger('change')
       $("#employee").val('').trigger('change')
       });

 $('#mydocstable').on("click",".yes",function(e){
    e.preventDefault();
    var id = $(this).closest('tr').find('.myid').val();
                alert(id);
                var verify='yes';
              
                $.ajax({
                type:'POST',
                datatype: 'json',
                url:'emp_doc/verify',
                data:{id:id,verify:verify},
                success:function(data){
                    alert(data);
                    refreshTable();
                }
                });
             });
 $('#mydocstable').on("click",".no",function(e){
    e.preventDefault();
    var id = $(this).closest('tr').find('.myid').val();
               
                var verify='no';
              
                $.ajax({
                type:'POST',
                datatype: 'json',
                url:'emp_doc/verify',
                data:{id:id,verify:verify},
                success:function(data){
                   refreshTable();
                }
                });
             });
             $('#mydocstable').on("click",".delete",function(e){
    e.preventDefault();
    var id = $(this).closest('tr').find('.myid').val();
    var path = $(this).closest('tr').find('.path').val();
    
                $.ajax({
                type:'POST',
                datatype: 'json',
                url:'emp_doc/delete',
                data:{id:id,path:path},
                success:function(data){
                   refreshTable();
                }
                });
             });     
$("#add").click(function(e){
    e.preventDefault();
   
  var doc_type=$('#doc_type').val();
         $.ajax({
                type:'POST',
                datatype: 'json',
                url:'doc_type/add',
                data:{doc_type:doc_type},
                success:function(data){
                    alert(data);
                  $('#file_type').html(data);
                  $('#docTypeModal').modal('hide');
                }
                });
             });               
  });
</script>
<script type="text/javascript">
        var autoLoad = setInterval(
                function ()
                {
                    $('#mydocstable').load('doc_table', function() {
                    });

                }, 30000);
    </script>

    <script type="text/javascript">
    

        function refreshTable()
          {
            $('#mydocstable').load('doc_table', function() {
          });
          }
    </script>

@endsection