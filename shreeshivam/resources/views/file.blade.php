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
          					<form action="upload_file" method="POST" enctype="multipart/form-data">
          					{{ csrf_field() }}
					<!--body Start-->	
                        <div class="body">
                          <div class="row clearfix">
                                <div class="col-sm-2" align="right">
                                 <label>File Type</label>
                                </div>
                                 <div class="col-sm-4">
                                   <div class="form-line">
                                      <input type="text" name="file_type" class="form-control" required/>
                                      <span class="text-danger">{{ $errors->first('file_type') }}</span>
                                  </div>
                    
                                  </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-2" align="right">
                								 <label> Upload File</label>
                								</div>
                							   <div class="col-sm-4">
                                   <div class="form-line">
                                      <input type="file" name="file" class="form-control btn-md bg-teal waves-effect" required/>
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
                                          <option value="{{$branches->id}}">{{$branches->branch}}</option>
                                          @endforeach
                                        </select>
                                        <span class="text-danger">{{ $errors->first('branch') }}</span>
                                     </div> 

                                     <div class="col-sm-3">
                                        <select class="form-control select2 department" name="department" id="department" placeholder="Gender" >
                                          <option></option>
                                          @foreach($department  as $dept )
                                          <option value="{{$dept->id}}">{{$dept->department_name}}</option>
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
                        <div class="body table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr class="bg-orange">
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>File</th>
                                        <th>Branch</th>
                                        <th>Department</th>
                                        <th>Employee Name</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                      </tr>
                                </thead>
                                <tbody>
                <?php 
                $i=1;
                ?>
                @foreach($document as $docs)
                                  
                   <tr>
                   {{ csrf_field() }}
                    <td scope="row">{{ $i++ }}</td>
                    <td>{{ $docs->heading }}</td>
                    <td>{{ $docs->file }}</td>
                    <td>{{ $docs->branch}}</td>
                    <td>{{  $docs->department_name}}</td>
                    <td>{{$docs->title}}  {{$docs->first_name}} {{$docs->middle_name}} {{$docs->last_name}}</td>
                    <td>{{ $docs->created_at }}</td>
                    <td><input type="hidden" class="doc_id" value="{{$docs->id}}">
                        <button type="delete" class="btn bg-red waves-effect deactivate">
                        Deactivate  
                        </button>
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

   

    $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

    $(document).on('click','.deactivate',function(e){
        e.preventDefault();
        var id = $(this).closest('td').find('.doc_id').val();
        
        $.ajax({
        type:'POST',
        datatype: 'text',
        url:'file_deactivate',
        data:{id:id},
        success:function(data){
            if(data=='1')
              window.location.reload();
        }
        });
        
    });

    $('.department').change(function(){
       
            $.ajax({
                type:'POST',
                datatype: 'text',
                url:'getdepartment',
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

  });
</script>
@endsection