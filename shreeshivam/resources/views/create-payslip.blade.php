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
					Salary
        </h2>
			</div>
         <form action=""  method="POST">
					{{ csrf_field() }}
            <div class="body">
            <div class="row clearfix">                    
                    <div class="col-sm-3">
                      <div class="form-line">        
                        <select class="form-control show-tick" name="month" id="month" placeholder="Month" required >
                          <option  value="">---Select Month---</option>
                          <option value="01">Jan</option>
                          <option value="02">Feb</option>
                          <option value="03">Mar</option>
                          <option value="04">Apr</option>
                          <option value="05">May</option>
                          <option value="06">Jun</option>
                          <option value="07">Jul</option>
                          <option value="08">Aug</option>
                          <option value="09">Sep</option>
                          <option value="10">Oct</option>
                          <option value="11">Nov</option>
                          <option value="12">Dec</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-3">
                      <div class="form-line"> 
                        <select class="form-control show-tick select2" id="year" name="year">
                          <option value="">--Select Year--</option>
                          @for($i=2010;$i<=date("Y");$i++)
                                <option value="{{$i}}" selected="{{date("Y")}}">{{$i}}</option>
                          @endfor     
                        </select> 
                      </div>
                    </div>
                  <div class="col-sm-3" >
                    <div class="form-line">                        
                      <select class="form-control show-tick select2" placeholder="Shreeshivam Branch" id="branch_location_id" name="branch">
                      <option value="">---Select Branch---</option>
                      @foreach($branch as $branches) 				           	
                        <option value="{{$branches->id}}"  > {{$branches->branch}}</option>
                      @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-2" >
                    <div class="form-line">
                      <button type="button" id="salary_find" name="submit" class="btn bg-green waves-effect"><i class="material-icons">save</i><span class="icon-name">GENERATE</span></button> 
                    </div>
                  </div>   
                </div> 
              </div>
            </div>
          </form>
			</div>
    
		</div>
	</div>
<div id="final_salary">
<center>
<div class="preloader pl-size-lg" style='display:none; margin-top: 50px;' id="loadingmessage">
    <div class="spinner-layer pl-red-grey">
        <div class="circle-clipper left">
            <div class="circle"></div>
        </div>
        <div class="circle-clipper right">
            <div class="circle"></div>
        </div>
    </div>
</div> 
</center>
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
        $('.msg').hide();
        if($('#month').val()=='')
        {
          $('#month').focus();
        }
        else
        {
          if($('#branch_location_id').val()=='')
          {
            $('#branch_location_id').focus();
          }
          else
          {
            $('#loadingmessage').show(); 
            $.ajax({
                type:'POST',
                url:'payslip-withdata',
                data:{id:$('#branch_location_id').val(),month:$('#month').val(),year:$('#year').val()},
                success:function(data){
                   $('#final_salary').empty().html(data);
                   $('#loadingmessage').hide();
                }
            });
          }
        }
				
			});

$(function()
{

    $(document).on("keyup", ".other_ded", function(){
      var add = $(this).closest('tr').find('.other_add').val();
      var sub = $(this).val();

      if(add=='')
        add=0;
      if(sub=='')
        sub=0;

      var total=parseFloat($(this).closest('tr').find('.net_payable_hide').val())+parseFloat(add)-parseFloat(sub);
      total=parseFloat(total);
     
      $(this).closest('tr').find('.net_payable').val(total);
      /*$(this).closest('tr').find('.flag').val(1);*/
   });
  });
$(function()
{
   $(document).on("keyup", ".other_add", function(){
    var add = $(this).val();
      var sub = $(this).closest('tr').find('.other_ded').val();

      if(add=='')
        add=0;
      if(sub=='')
        sub=0;

      var total=parseFloat($(this).closest('tr').find('.net_payable_hide').val())-parseFloat(sub)+parseFloat(add);
      total=parseFloat(total);
   
      $(this).closest('tr').find('.net_payable').val(total);
      /*$(this).closest('tr').find('.flag').val(1);*/
   });
});

</script>

@endsection