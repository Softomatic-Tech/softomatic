@extends('layouts.form-app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Transfer master</h2>
            <div class="flash-message" id="success-alert">
	            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
			      @if(Session::has('alert-' . $msg))
					<p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
			      @endif
			    @endforeach
			</div>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>Select employee</h2>                          
                    </div>
                    <div class="body">
					 	<form id="form_search" method="POST" action="transfer/submit" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row" clearfix>
                            <div class="col-sm-3">
								<div class="">
                                <label for="title">Select Employee </label>
                                <select class="form-control select2 emp" placeholder="Shreeshivam Branch" id="emp" name="emp">
                                    <option></option>
                                    @foreach($emp as $emps)
                                        <option value="{{$emps->id}}">{{$emps->first_name.' '.$emps->middle_name.' '.$emps->last_name}}</option>
									@endforeach
								
								</select>
							</div>
                                <span class="text-danger">{{ $errors->first('emp') }}</span>
                                
                            </div>
                            <div class="col-sm-3">
                                <label for="title">Base Work location </label>			
                                <input type="text" name="branch" id="branch" class="form-control branch"  value="{{ old('branch') }}" readonly>
                                <span class="text-danger">{{ $errors->first('emp') }}</span>
                            </div>                          
                            <div class="col-sm-3">
                                <label for="title">Transfer to location  </label>
                                 <select name="transfer_to" id="" class="form-control">
									<option value="">select</option>
									@foreach($branch as $branches)
									<option value"{{$branches->id}}"> {{$branches->branch}} </option>
									@endforeach
                                </select>
                            </div>                          
                            <div class="col-sm-3">
                                <label for="title">Effective date </label>
                                <input autocomplete="none" type="text" name="effecive_date" id="dob" class="form-control datepicker" placeholder="Effective date" value="{{ old('dob') }}" >
                            </div>
						</div>
						<div class="row" clearfix>
                            <div class="col-sm-2">
								<input type="submit" class="btn btn-success btn-lg" name="submit" value="submit">
							</div>
						</div>
					</form>
                    </div>
                   
                    </div>
                </div>   
                <div class="row clearfix">
			       <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                              Transfer List
                            </h2>
                          </div>
                        <div class="body table-responsive">
								 <table class="table table-striped">
                                <thead>
                                    <tr class="bg-orange">
                                        <th>#</th>
										<th>Employee</th>
                                        <th>Base Work Location</th>
										<th>Transfer to location </th>
                                        <th>Effective date</th>
                                      </tr>
                                </thead>
                               <tbody>
								<?php 
								$i=1;
								?>
								@foreach($details as $detail)
							
								<tr>
								 <td scope="row">{{$i++}} </td>
                                 <td>{{$detail->first_name.' '.$detail->middle_name.' '.$detail->last_name}}</td>
								 <td>@if($detail->branch_location_id==1)
								        Corporate
								     @endif
								 </td>
								 <td>{{$detail->transfer_to_branch}}</td>
                                 <td>{{$detail->effective_date}} </td>   
								</tr>
									@endforeach
								
									</tbody>	
							   </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


</style>


@endsection
@section('jquery')
<script type="text/javascript">

	$('.datepicker').datepicker().on('changeDate', function (ev) {
		    $('.dropdown-menu').hide();
		});
	
	 $(".emp").select2({
	     placeholder: "Select Employee",
	     allowClear: true
	    });

	     $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
        });
			
		$('#emp').change(function(){
			if($(this).val()=='')
			{
				$(this).closest('div').find('.text-danger').html('Please Select Employee');
				$(this).focus();
			}
			else
			{
				$.ajax({
				type:'POST',
				datatype: 'text',
				url:'get_branch',
				data:{emp:$(this).val()},
				success:function(data){
					$('.branch').val(data);
				}
				});
			}				
		});			
</script>
@endsection