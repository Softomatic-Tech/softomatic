@extends('layouts.app')
@section('content')
<style>
.modal-dialog {
    width: 900px;
    margin: 30px auto;
}
hr {
    margin-top: 10px;
    margin-bottom: 10px;
}
</style>
<meta name="csrf-token" content="{{ csrf_token() }}">
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
                               Department List
                            </h2>
                          </div>
                        <div class="body table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr class="bg-orange">
                                        <th>#</th>
                                        <th>Employee ID</th>
										 <th>Father's Name</th>
                                         <th>Mother's Name</th>
                                         <th>Spouse Name</th>
                                         <th>Children Name</th>
                                         <th>Status</th>
                                         <th>Action</th>
                                      </tr>
                                </thead>
                                <tbody>
								<?php 
								$i=1;
								?>
								@foreach($personal_detail_temp as $row)
                                  <?php
                                 
                                  $father=json_decode($row->father,true);
                                  $mother=json_decode($row->mother,true);
                                  $spouse=json_decode($row->spouse,true);
                                  $children=json_decode($row->children,true);
                                // echo $children[0]['name'];
                                  ?>
								   <tr>
									 {{ csrf_field() }}
                                        <td scope="row">{{ $i++ }}</td>
                                        <td>{{ $row->first_name }} {{ $row->middle_name }} {{ $row->last_name }}</td>
                                        <td>{{ $father['father_name'] }}</td>
                                        <td>{{ $mother['mother_name'] }}</td>
                                        <td>{{ $spouse['spouse_name'] }}</td>
                                       
                                        <td>
                                            @foreach($children as $child)
                                            {{$child['child_name']}}, 
                                            @endforeach
                                        </td>
                                        @if($row->status=='reject')
                                        <td><h4><span class="label label-danger">{{ $row->status }}</span><h4></td>
                                        @elseif($row->status=='pending')
                                        <td><h4><span class="label label-warning">{{ $row->status }}</span><h4></td>
                                        @else
                                        <td><h4><span class="label label-success">{{ $row->status }}</span><h4></td>
                                        @endif
                                        <td>
                                        <button type="button" id="" 
												class="btn bg-amber waves-effect view-modal" data-toggle="modal" 
												data-target="#detailModal" data-status="{{$row->status}}"  data-emp_id="{{ $row->emp_id}}" data-id="{{ $row->id}}"
												data-name="">
											<i class="material-icons">open_with</i>
										</button>
                                        </td>
                                    </tr>
                                 @endforeach       
                                 <?php 
								$i=1;
								?>
								@foreach($personal_detail as $row1)
                                  <?php
                                 
                                  $father=json_decode($row1->father,true);
                                  $mother=json_decode($row1->mother,true);
                                  $spouse=json_decode($row1->spouse,true);
                                  $children=json_decode($row1->children,true);
                                // echo $children[0]['name'];
                                  ?>
								   <tr>
									 {{ csrf_field() }}
                                        <td scope="row1">{{ $i++ }}</td>
                                        <td>{{ $row1->first_name }} {{ $row1->middle_name }} {{ $row1->last_name }}</td>
                                        <td>{{ $father['father_name'] }}</td>
                                        <td>{{ $mother['mother_name'] }}</td>
                                        <td>{{ $spouse['spouse_name'] }}</td>
                                       
                                        <td>
                                            @foreach($children as $child)
                                            {{$child['child_name']}}, 
                                            @endforeach
                                        </td>
                                        <td><h4><span class="label label-success">{{ $row1->status }}</span><h4></td>
                                       
                                        <td>
                                        <button type="button" id="" 
												class="btn bg-amber waves-effect view-modal-acc" data-toggle="modal" 
												data-target="#detailModal" data-status="{{$row1->status}}" data-emp_id="{{ $row1->emp_id}}" data-id="{{ $row1->id}}">
											<i class="material-icons">open_with</i>
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
	
			<div class="modal fade" id="detailModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                <div class="modal-content"id="view">
                    </div>
                </div>
            </div>
@endsection
@section('jquery')
<script>
$(document).ready(function() {
	
     $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
		$("#success-alert").slideUp(500);
		});
    
    
 });
 
</script>
 <script type="text/javascript">
	
    $(document).ready(function(){  
    $.ajaxSetup({
         headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                   }
                  });
     $('.view-modal').click(function(e){
        var emp_id = $(this).data('emp_id');
        var id = $(this).data('id');
        var status = $(this).data('status');
       
      $.ajax({
          url: 'get_per_details',
          type:'POST',
          data: {'emp_id':emp_id,'id':id,'status':status},
          dataType: 'text',
          success: function(data)
          { 
         
           $('#view').html(data);
          }
          
        });    
      });
       });
       $(document).ready(function(){  
    $.ajaxSetup({
         headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                   }
                  });
     $('.view-modal-acc').click(function(e){
        var emp_id = $(this).data('emp_id');
        var id = $(this).data('id');
        var status = $(this).data('status');
      
      $.ajax({
          url: 'get_per_details',
          type:'POST',
          data: {'emp_id':emp_id,'id':id,'status':status},
          dataType: 'text',
          success: function(data)
          { 
         
           $('#view').html(data);
          }
          
        });    
      });
       });
     $(document).on('click','.accept',function(){
        document.getElementById('rejectdiv').style.display ='none'; 
        var id = $('.family_id').val();
       $.ajax({
          url: 'update_per_details',
          type:'POST',
          data: {'id':id},
          dataType: 'text',
          success: function(data)
          { 
            $('#detailModal').modal('toggle');
            location.reload();
         }
          
        });    
});
$(document).on('click','.final_reject',function(){
   
        var id = $('.family_id').val();
        var remark=$('.remark').val();
        $.ajax({
          url: 'reject_per_details',
          type:'POST',
          data: {'id':id,'remark':remark},
          dataType: 'text',
          success: function(data)
          { 
           
            $('#detailModal').modal('toggle');
            location.reload();
         }
          
        });    
});
$(document).on('click','.reject',function(){
    document.getElementById('rejectdiv').style.display ='block'; 
});
        </script>

@endsection