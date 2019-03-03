@extends('layouts.form-app')
 
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Salary</h2>
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
				Delete Payslip
        </h2>
			</div>
        
				
            <div class="body">
              <div class="row clearfix">
                <div class="col-sm-2" align="center">
					 <label> Select Branch </label>
				</div>
							  <div class="col-sm-3" >
                                    <div class="form-line">                        
							      <select class="form-control show-tick select2" placeholder="Shreeshivam Branch" id="branch" name="branch" required>
							      <option value="">---Select Branch---</option>
					          @foreach($branches as $branch) 				           	
									    <option value="{{$branch->id}}" > {{$branch->branch}}</option>
						        @endforeach
                    </select>
                  </div>
							  </div>
                   
                    <div class="button-demo js-modal-buttons">
                         <button type="button" data-color="red" data-toggle="modal" data-target="#updateModal"  class="btn bg-red waves-effect"><i class="material-icons">delete</i><span class="icon-name">Delete Payslip</span></button>
                                       
					    <!--<button type="button" id="salary_find" name="submit" data-type="confirm" class="btn btn-danger waves-effect"><i class="material-icons">delete</i><span class="icon-name">Delete Payslip</span></button> -->
                     
                    </div> 
              </div>
            </div>
        
          
           <div class="modal fade" id="updateModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content" style="margin-top:120px !important;">
                        <form action="payslip" method="POST">
                        {{ csrf_field() }}
                        <div class="modal-header bg-red">
                            <h4 class="modal-title" id="defaultModalLabel">Delete Payslip</h4>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="zone" name="zone">
                           Are you sure you want to delete payslip of current month. It will remove all data regarding to payslip
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger waves-effect"><i class="material-icons">delete_forever</i><span class="icon-name">Delete Payslip</span></button>

                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><i class="material-icons">cancel</i><span class="icon-name">Cancel</span></button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
		</div>
		</div>
	</div>
</section>

@endsection

@section('jquery')
<script>
  $('#branch').change(function() {
  //get txtAmt value  
  var txtAmtval1 = $('#branch').val();
  //change txtInterest% value
  $('#zone').val(txtAmtval1);
  
});
</script>
@endsection