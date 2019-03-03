@extends('layouts.mobile_app')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
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
					<h3>My Documents </h3>

				
        <div class="container-fluid">
           <!--  <div class="block-header"> -->
                
           <!--  </div> -->

            <div class="row clearfix">
               
                	@if(Session::get('role')!='admin')
                    
                        	{!! Form::open(['url' => 'doc/submit', 'files' => true]) !!}
                        		@csrf
                        		
                                        <div class="form-group row">
                                                <div class="col-md-4 col-sm-5 col-xs-12" align="left">
                                                    <label for="reason">Document Type</label>
                                                    <a href="mobile_add_doc" class="btn btn-xs btn-info" >  add</a>
                                                </div>
                                             
		                                	<div class="col-md-8 col-sm-6 col-xs-12">  
		                        				    <div class="form-line">                 			
				                        			    <select class="form-control show-tick" name="file_type" id="file_type" required="">
                                                            <option value="">Select Document Type</option>
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
                                        </div>
			                        	<!-- </div> -->
                                       <!--  <div class="row clearfix "> -->
			                        	
                                    <!-- </div>
			                        	<div class="row clearfix "> -->
                                         
                                   <!--  </div>
			            				<div class="row clearfix "> -->
                                            <div class="form-group row">
                                                <div class="col-md-4 col-sm-6 col-xs-12" align="left">
                                                    <label for="doj">File</label>
                                                </div>
			            					<div class="col-md-8 col-sm-6 col-xs-12">
				            					    <div class="form-line">
                                               
                                                         <input type="file" name="file" class="form-control " required value="{{ old('file') }}"  accept="image/*" capture />
                                                         <!--<input id="capture" name="capture" type="file" accept="image/*>-->
                                                         <span class="text-danger">{{ $errors->first('file') }}</span>
				            					    </div>
				            				    </div>
				            			</div>
                                   <!--  </div>
				            			<div class="row clearfix "> -->
                                            <div class="form-group row">
					            			<div class="col-md-4 col-md-offset-4">
					            				<button autocomplete="none" type="submit" name="submit" class="btn btn-success btn-sm col-md-4">Save</button>
				            				</div>
				            			</div>
                                       <!--  </div>		 -->		            			
		                        	<!-- </div> -->
                        		<!-- </div> -->
                        	{!! Form::close() !!}
                       <!--  </div> -->
                   <!--  </div> -->
                   @endif
                    <!-- <div class="card"> -->
                        <div class="">
                            <h3>Documents</h3>
                        </div>
                        <div class="table-responsive">
                        @include('doc_table') 
                        </div>
                    <!-- </div> -->

                 

                    @if((Session::get('role')=='admin') || Session::get('role')=='hr')
                    <!-- <div class="card"> -->
                        <div class="">
                            <h3>LEAVE APPLICATIONS</h3>
                        </div>
                        <div class="table-responsive"> 
                                           	
                                                                         
                        </div>
                    <!-- </div> -->
                    @endif
                </div>
           <!--  </div>           -->  
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
                    $('#mydocstable').load('doc_table', function() {
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

    	

           $('#mydocstable').on("click",".yes",function(e){
                e.preventDefault();
                var id = $(this).closest('tr').find('.myid').val();
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
           
          });

        function refreshTable()
          {
            $('#mydocstable').load('doc_table', function() {
          });
          }

    </script>
   
@endsection