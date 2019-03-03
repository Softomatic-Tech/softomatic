@extends('layouts.form-app')
 
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Payslip List</h2>
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
       {{-- <button type="button" class="btn btn-default waves-effect m-r-20" data-toggle="modal" data-target="#largeModal">MODAL - LARGE SIZE</button> --}}
       <div class="modal fade" id="largeModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="largeModalLabel">EMPLOYEE PAYSLIP</h4>
                        </div>
                        <div class="modal-body" id="final_salary">
                          
                        </div>
                        <div class="modal-footer">
                            
                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                        </div>
                    </div>
                </div>
            </div>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
		<div class="card">
			<div class="header">
				<h2>
					Salary
        </h2>
			</div>
         <form action=""  method="POST">
					{{ csrf_field() }}
            <div class="body">
            <div class="row clearfix">
                                <div class="col-sm-2" align="center">
                                 <label>Month</label>
                                </div>
                               <div class="col-sm-3">
                                 <div class="form-line">        
                                    <select class="form-control show-tick" name="month" id="month" placeholder="Month" required >
                                      <option value="">---Select Month---</option>
                                      <option value="1">Jan</option>
                                      <option value="2">Feb</option>
                                      <option value="3">Mar</option>
                                      <option value="4">Apr</option>
                                      <option value="5">May</option>
                                      <option value="6">Jun</option>
                                      <option value="7">Jul</option>
                                      <option value="8">Aug</option>
                                      <option value="9">Sep</option>
                                      <option value="10">Oct</option>
                                      <option value="11">Nov</option>
                                      <option value="12">Dec</option>
                                    </select>
                                 </div>
                               </div>
                            </div>
              <div class="row clearfix">
                <div class="col-sm-2" align="center">
								 <label> Select Branch </label>
								</div>
							  <div class="col-sm-3" >
                  <div class="form-line">                        
							      <select class="form-control show-tick select2" placeholder="Shreeshivam Branch" id="branch_location_id" name="branch">
							      <option>Select</option>
					          @foreach($branch as $branches) 				           	
									    <option value="{{$branches->id}}" > {{$branches->branch}}</option>
						        @endforeach
                    </select>
                  </div>
							  </div>
                    <div class="col-sm-2 " align="center">
                      <div class="form-group">                 
							    <button type="button" id="salary_find" data-toggle="modal" name="submit" data-target="#largeModal" class="btn bg-green waves-effect"><i class="material-icons">save</i><span class="icon-name">submit</span></button> 
                      </div>
                    </div>  
              </div>
            </div>
          </form>
			</div>
			
        </div>
       

    </div>
    
<div >

</div>
</section>

@endsection

@section('jquery')
<script>
 $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
		
			$('#salary_find').click(function(){
        $('#final_salary').empty()
        if($('#branch_location_id').val()=='' || $('#month').val()=='')	
        {
          if($('#branch_location_id').val()=='')
          {
            $('#branch_location_id').focus();
          }
          else
          {
            if($('#month').val()=='')
            {
              $('#month').focus();
            }
          }
        }	
        else		
				$.ajax({
                type:'POST',
                url:'payslip-list-data',
                datatype:'json',
                data:{branch:$('#branch_location_id').val(),month:$('#month').val()},
                success:function(data){           
                   $('#final_salary').empty().html(data);
                }
                });
			});
</script>

@endsection