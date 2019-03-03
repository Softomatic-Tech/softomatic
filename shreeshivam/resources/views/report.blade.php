@extends('layouts.form-app')
 
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Reports</h2>
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
					Reports
                </h2>
			</div>
        
            <div class="body">
                <div class="row clearfix">
                    <div class="col-sm-1" align="center">
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
                    <div class="col-sm-1" align="center">
                        <label>Branch</label>
                    </div>
                        <div class="col-sm-3">
                            <div class="form-line">        
                                <select class="form-control show-tick" name="branch" id="branch" placeholder="Branch" required >
                                     <option>Select</option>
					          @foreach($branch as $branches) 				           	
									    <option value="{{$branches->id}}" > {{$branches->branch}}</option>
						        @endforeach
                                     
                                </select>
                            </div>
                    </div>
                </div>
                            
            <div class="row clearfix">
                <form action="provision/submit" method="POST">
                    {{ csrf_field() }}
                <div class="col-sm-2 col-sm-offset-1" >
                     <input type="hidden" name="month" id="month_h">
                      <input type="hidden" name="branch" id="zone">
					<button type="submit" id="" name="submit" class="btn bg-green waves-effect"><i class="material-icons">save</i><span class="icon-name">Provision Sheet</span></button> 		
                </div>
                </form> 
                <form action="current_bank/submit" method="POST">
                    {{ csrf_field() }}
                <div class="col-sm-2 " >
                    <input type="hidden" name="month1" id="month_h1">
                    <input type="hidden" name="branch1" id="zone1">
					<button type="submit" id="" name="submit" class="btn bg-green waves-effect"><i class="material-icons">save</i><span class="icon-name">Current Bank Sheet</span></button> 		
                </div>
                </form>
                <form action="other_bank/submit" method="POST">
                    {{ csrf_field() }}
                <div class="col-sm-2 " >
                    <input type="hidden" name="month1" id="month_h2">
                    <input type="hidden" name="branch1" id="zone2">
          <button type="submit" id="" name="submit" class="btn bg-green waves-effect"><i class="material-icons">save</i><span class="icon-name">Other Bank Sheet</span></button>    
                </div>
                </form>
                <form action="esic/submit" method="POST"> 
                    	{{ csrf_field() }}
                 <div class="col-sm-2 " >
                    <input type="hidden" name="month2" id="month_h3">
                    <input type="hidden" name="branch2" id="zone3">
					<button type="submit" id="" name="submit" class="btn bg-green waves-effect"><i class="material-icons">save</i><span class="icon-name">ESIC Sheet</span></button> 		
                </div>
                </form>
                <form action="epf/submit" method="POST">
                    	{{ csrf_field() }}
                 <div class="col-sm-2 " >
                    <input type="hidden" name="month3" id="month_h4">
                    <input type="hidden" name="branch3" id="zone4">
					<button type="submit" id="" name="submit" class="btn bg-green waves-effect"><i class="material-icons">save</i><span class="icon-name">EPF Sheet</span></button> 		
                </div>
                </form>
            </div>
 
            </div>
         
			</div>
			
		</div>
	</div>
<div id="final_salary">

</div>
</section>

@endsection

@section('jquery')
<script>
$('#month').change(function() {
  //get txtAmt value  
  var txtAmtval = $('#month').val();
  //change txtInterest% value
  $('#month_h').val(txtAmtval);
  $('#month_h1').val(txtAmtval);
  $('#month_h2').val(txtAmtval);
  $('#month_h3').val(txtAmtval);
  $('#month_h4').val(txtAmtval);
});

$('#branch').change(function() {
  //get txtAmt value  
  var txtAmtval1 = $('#branch').val();
  //change txtInterest% value
  $('#zone').val(txtAmtval1);
  $('#zone1').val(txtAmtval1);
  $('#zone2').val(txtAmtval1);
  $('#zone3').val(txtAmtval1);
  $('#zone4').val(txtAmtval1);
});
</script>

@endsection