@extends('layouts.mobile_app')
@section('content')
	
<div class="body-back">
	<div class="masthead pdng-stn1">
		<div class="phone-box wrap push" id="home">
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
					<h3>Personal Detail</h3></div>
				 <div class="flash-message" id="success-alert">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                    @if(Session::has('alert-' . $msg))
                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                    @endif
                    @endforeach
                </div> 
				@if($flag==0)
				<form method="post" action="personal-detail/submit" id="form">	
               {{ csrf_field() }}	
			<div class="">	
			<div class="form-group row ">
					<div class="col-xs-5" align="left">
					<label for="username">Father's Name</label>
					</div>
					<div class="col-xs-12">
					
						<input class="form-control" name="father_name" value="" type="text" placeholder="Father Name">
						
					</div>
			</div>
			<div class="form-group row ">
				<div class="col-xs-5"  align="left">
				<label for="username">Father's DOB</label>
				</div>
				<div class="col-xs-12">
				<input class="form-control datepicker" name="father_dob" value="" type="text" placeholder="Father's DOB">
				</div>
			</div>
			<div class="form-group row ">
				<div class="col-xs-5"  align="left">
				<label for="username">Father's Adhaar No</label>
				</div>
				<div class="col-xs-12">
				<input class="form-control" name="father_aadhar" value="" type="number" placeholder="Father's Adhaar No">
				</div>
				</div>	
				<div class="form-group row ">
			<div class="col-xs-6"  align="left">
			 <label for="username">Father's Current Place</label>
			</div>
				<div class="col-xs-12">
				<input class="form-control" name="father_cur_place" value="" type="text" placeholder="Father's Current Place">
				</div>
			</div>		
			<div class="form-group row ">
			<div class="col-xs-5"  align="left">
			 <label for="username">Mother's Name</label>
			</div>
				<div class="col-xs-12">
				<input class="form-control" name="mother_name" value="" type="text" placeholder="Mother Name">
				</div>
			</div>	
			<div class="form-group row ">
			<div class="col-xs-5"  align="left">
			 <label for="username">Mother's DOB</label>
			</div>
				<div class="col-xs-12">
				<input class="form-control datepicker" name="mother_dob" value="" type="text" placeholder="Mother's DOB">
                 </div>
				
			</div>
			<div class="form-group row ">
				<div class="col-xs-5"  align="left">
				<label for="username">Mother's Adhaar No</label>
				</div>
				<div class="col-xs-12">
				<input class="form-control" name="mother_aadhar" value="" type="number" placeholder="Mother's Adhaar No">
				</div>
				</div>	
				<div class="form-group row ">
			<div class="col-xs-6"  align="left">
			 <label for="username">Mother's Current Place</label>
			</div>
				<div class="col-xs-12">
				<input class="form-control" name="mother_cur_place" value="" type="text" placeholder="Mother's Current Place">
				</div>
			</div>		
			<div class="form-group row ">
			<div class="col-xs-5"  align="left">
			 <label for="username">Married</label>
			</div>
				<div class="col-xs-12">
				<label class="radio-inline">
				<input type="radio"  name="if_married"  onclick="show_spouse_div();" value="yes" checked>Yes
				</label>
				<label class="radio-inline">
				<input type="radio" name="if_married"   onclick="hide_spouse_div();" value="no">No
				</label>
                 </div>
				
			</div>
			<div id="spouse_div">
				<div class="form-group row ">
						<div class="col-xs-6"  align="left">
							<label for="username">Spouse Name</label>
						</div>
						<div class="col-xs-12">
							<input class="form-control" name="spouse_name" value="" type="text" placeholder="Spouse Name">
						</div>
				</div>
				<div class="form-group row ">
					<div class="col-xs-6"  align="left">
						<label for="username">Spouse Gender</label>
					</div>
					<div class="col-xs-12">
					<select  name="spouse_gender" id="spouse_gender"   class="form-control"  required>
								<option>Select Gender</option>
								<option value="male">Male</option>
								<option value="female">Female</option>
						</select>
					</div>
				</div>
				<div class="form-group row ">
					<div class="col-xs-6"  align="left">
						<label for="username">Spouse DOB</label>
					</div>
					<div class="col-xs-12">
						<input class="form-control datepicker" name="spouse_dob" value="" type="text" placeholder="Spouse DOB">
					</div>
				</div>
				<div class="form-group row ">
					<div class="col-xs-6"  align="left">
						<label for="username">Spouse Adhaar No</label>
					</div>
					<div class="col-xs-12">
						<input class="form-control" name="spouse_aadhar" value="" type="number" placeholder="Spouse Adhaar No">
					</div>
				</div>
				<div class="form-group row ">
					<div class="col-xs-6"  align="left">
						<label for="username">Spouse Current Place</label>
					</div>
					<div class="col-xs-12">
						<input class="form-control" name="spouse_cur_place" value="" type="text" placeholder="Spouse Current Place">
					</div>
				</div>
				<div class="form-group row ">
			<div class="col-xs-5"  align="left">
			 <label for="username">children</label>
			</div>
				<div class="col-xs-12">
				<label class="radio-inline">
				<input type="radio" name="if_children"  onclick="show_child_div();" value="yes" checked>Yes
				</label>
				<label class="radio-inline">
				<input type="radio" name="if_children"  onclick="hide_child_div();" value="no">No
				</label>
                 </div>
				
			</div>
			<div class="form-group row">
					<div class="col-xs-6"  align="left">
						<label for="username">No Of Children</label>
					</div>
					<div class="col-xs-10">
					<input type="hidden" class="form-control" name="child_no"  id="child_no" value="0">
					<select class="form-control" name="no_of_children" id="no_of_children">
				            							<option value="">Select No Of Children</option>
				            							<!-- @if(old('no_of_children') == '0')
      														<option value="0" selected>None</option>
														@else
      														<option value="0">None</option>
														@endif -->
				            							@if(old('no_of_children') == '1')
      														<option value="1" selected>1</option>
														@else
      														<option value="1">1</option>
														@endif
														@if(old('no_of_children') == '2')
      														<option value="2" selected>2</option>
														@else
      														<option value="2">2</option>
														@endif
														@if(old('no_of_children') == '3')
      														<option value="3" selected>3</option>
														@else
      														<option value="3">3</option>
														@endif
														@if(old('no_of_children') == '4')
      														<option value="4" selected>4</option>
														@else
      														<option value="4">4</option>
														@endif
														@if(old('no_of_children') == '5')
      														<option value="5" selected>5</option>
														@else
      														<option value="5">5</option>
														@endif
														@if(old('no_of_children') == '6')
      														<option value="6" selected>6</option>
														@else
      														<option value="6">6</option>
														@endif
														@if(old('no_of_children') == '7')
      														<option value="7" selected>7</option>
														@else
      														<option value="7">7</option>
														@endif
														@if(old('no_of_children') == '8')
      														<option value="8" selected>8</option>
														@else
      														<option value="8">8</option>
														@endif
				            						</select>
					</div>
				</div>
				<div class="row form-group" id="child_div">
			     	<div id="children_detail" name="children_detail">
                       
					</div>                       
			</div>
		<div class="form-group row">	            					
		<div class="col-xs-6">
		  
			
			
		</div>
		</div>
		</div>
				<div class="form-group row ">
					<div class="col-xs-5"  align="left">
					<a  id="sub" class="btn btn-success">Save</a> 
				
					
				</div>	
			</form>	
			 
	 </div>
</div>
		</div>
	@endif
	@if($flag==1)	
	<form method="post" action="personal-detail/update" id="form">	
               {{ csrf_field() }}	
			   @foreach($data as $row)
			   <?php
			   $father=json_decode($row->father,true);
			   $mother=json_decode($row->mother,true);
			   $spouse=json_decode($row->spouse,true);
			   $children=json_decode($row->children,true);
			   if(!empty($children))
			   {
				$size=sizeof($children);  
			   }
			  else{
				$size=0;  
			  }
			   ?>
			<div class="">	
			<div class="form-group row ">
			<input type="hidden" class="form-control" name="id" value="{{$row->id}}">
					<div class="col-xs-5" align="left">
					<label for="username">Father's Name</label>
					</div>
					<div class="col-xs-12">
					
						<input class="form-control" name="father_name" value="{{ $father['father_name'] }}" type="text" placeholder="Father Name">
						
					</div>
			</div>
			<div class="form-group row ">
				<div class="col-xs-5"  align="left">
				<label for="username">Father's DOB</label>
				</div>
				<div class="col-xs-12">
				<input class="form-control datepicker" name="father_dob" value="{{ $father['father_dob'] }}" type="text" placeholder="Father's DOB">
				</div>
			</div>
			<div class="form-group row ">
				<div class="col-xs-5"  align="left">
				<label for="username">Father's Adhaar No</label>
				</div>
				<div class="col-xs-12">
				<input class="form-control" name="father_aadhar" value="{{ $father['father_aadhar'] }}" type="number" placeholder="Father's Adhaar No">
				</div>
				</div>	
				<div class="form-group row ">
			<div class="col-xs-6"  align="left">
			 <label for="username">Father's Current Place</label>
			</div>
				<div class="col-xs-12">
				<input class="form-control" name="father_cur_place" value="{{ $father['father_place'] }}" type="text" placeholder="Father's Current Place">
				</div>
			</div>		
			<div class="form-group row ">
			<div class="col-xs-5"  align="left">
			 <label for="username">Mother's Name</label>
			</div>
				<div class="col-xs-12">
				<input class="form-control" name="mother_name" value="{{$mother['mother_name']}}" type="text" placeholder="Mother Name">
				</div>
			</div>	
			<div class="form-group row ">
			<div class="col-xs-5"  align="left">
			 <label for="username">Mother's DOB</label>
			</div>
				<div class="col-xs-12">
				<input class="form-control datepicker" name="mother_dob" value="{{$mother['mother_dob']}}" type="text" placeholder="Mother's DOB">
                 </div>
				
			</div>
			<div class="form-group row ">
				<div class="col-xs-5"  align="left">
				<label for="username">Mother's Adhaar No</label>
				</div>
				<div class="col-xs-12">
				<input class="form-control" name="mother_aadhar" value="{{$mother['mother_aadhar']}}" type="number" placeholder="Mother's Adhaar No">
				</div>
				</div>	
				<div class="form-group row ">
			<div class="col-xs-6"  align="left">
			 <label for="username">Mother's Current Place</label>
			</div>
				<div class="col-xs-12">
				<input class="form-control" name="mother_cur_place" value="{{$mother['mother_place']}}" type="text" placeholder="Mother's Current Place">
				</div>
			</div>		
			<div class="form-group row ">
			<div class="col-xs-5"  align="left">
			 <label for="username">Married</label>
			</div>
				<div class="col-xs-12">
				<label class="radio-inline">
				<input type="radio"  name="if_married"  onclick="show_spouse_div();" value="yes" checked>Yes
				</label>
				<label class="radio-inline">
				<input type="radio" name="if_married"   onclick="hide_spouse_div();" value="no">No
				</label>
                 </div>
				
			</div>
			<div id="spouse_div">
				<div class="form-group row ">
						<div class="col-xs-6"  align="left">
							<label for="username">Spouse Name</label>
						</div>
						<div class="col-xs-12">
							<input class="form-control" name="spouse_name" value="{{$spouse['spouse_name']}}" type="text" placeholder="Spouse Name">
						</div>
				</div>
				<div class="form-group row ">
					<div class="col-xs-6"  align="left">
						<label for="username">Spouse Gender</label>
					</div>
					<div class="col-xs-12">
					<select  name="spouse_gender" id="spouse_gender"   class="form-control"  required>
								<option>Select Gender</option>
								@if($spouse['spouse_gender']=='male')
								<option value="male" {{ 'selected'}}>Male</option>
								@else 
								<option value="male">Female</option>
								@endif
								@if($spouse['spouse_gender']=='female')
								<option value="female" {{'selected'}}>Female</option>
								 @else 
								 <option value="female" >Female</option>
								@endif
						</select>
					</div>
				</div>
				<div class="form-group row ">
					<div class="col-xs-6"  align="left">
						<label for="username">Spouse DOB</label>
					</div>
					<div class="col-xs-12">
						<input class="form-control datepicker" name="spouse_dob" value="{{$spouse['spouse_dob']}}" type="text" placeholder="Spouse DOB">
					</div>
				</div>
				<div class="form-group row ">
					<div class="col-xs-6"  align="left">
						<label for="username">Spouse Adhaar No</label>
					</div>
					<div class="col-xs-12">
						<input class="form-control" name="spouse_aadhar" value="{{$spouse['spouse_aadhar']}}" type="number" placeholder="Spouse Adhaar No">
					</div>
				</div>
				<div class="form-group row ">
					<div class="col-xs-6"  align="left">
						<label for="username">Spouse Current Place</label>
					</div>
					<div class="col-xs-12">
						<input class="form-control" name="spouse_cur_place" value="{{$spouse['spouse_place']}}" type="text" placeholder="Spouse Current Place">
					</div>
				</div>
				<div class="form-group row ">
			<div class="col-xs-5"  align="left">
			 <label for="username">children</label>
			</div>
				<div class="col-xs-12">
				<label class="radio-inline">
				<input type="radio" name="if_children"  onclick="show_child_div();" value="yes">Yes
				</label>
				<label class="radio-inline">
				<input type="radio" name="if_children"  onclick="hide_child_div();" value="no" checked>No
				</label>
                 </div>
			</div>
			<div id="child_div" style="display:none">
			<div class="form-group row">
					<div class="col-xs-6"  align="left">
						<label for="username">No Of Children</label>
					</div>
					<div class="col-xs-10">
					<input type="text" class="form-control" name="child_no"  id="child_no" value="{{$size}}">
					<select class="form-control" name="no_of_children" id="no_of_children">
				            							<option value="">Select No Of Children</option>
				            							<!-- @if(old('no_of_children') == '0')
      														<option value="0" selected>None</option>
														@else
      														<option value="0">None</option>
														@endif -->
				            							@if(old('no_of_children') == '1')
      														<option value="1" selected>1</option>
														@else
      														<option value="1">1</option>
														@endif
														@if(old('no_of_children') == '2')
      														<option value="2" selected>2</option>
														@else
      														<option value="2">2</option>
														@endif
														@if(old('no_of_children') == '3')
      														<option value="3" selected>3</option>
														@else
      														<option value="3">3</option>
														@endif
														@if(old('no_of_children') == '4')
      														<option value="4" selected>4</option>
														@else
      														<option value="4">4</option>
														@endif
														@if(old('no_of_children') == '5')
      														<option value="5" selected>5</option>
														@else
      														<option value="5">5</option>
														@endif
														@if(old('no_of_children') == '6')
      														<option value="6" selected>6</option>
														@else
      														<option value="6">6</option>
														@endif
														@if(old('no_of_children') == '7')
      														<option value="7" selected>7</option>
														@else
      														<option value="7">7</option>
														@endif
														@if(old('no_of_children') == '8')
      														<option value="8" selected>8</option>
														@else
      														<option value="8">8</option>
														@endif
				            						</select>
					</div>
				</div>
				<div class="row form-group">
				<?php $i=1; ?>
				<div id="children_detail" name="children_detail">
				@if($children!='')
				@foreach($children as $child)
			     
					 <div class="col-xs-10">
					 <label><u>Child-{{$i}}</u></label>	
						<input type="text" autocomplete="none" name="child[]" id="child[]" placeholder="Name" class="form-control child" value="{{$child['child_name']}}"><br>
					 	<input type="text" autocomplete="none" name="child_dob[]" id="child_dob[]" placeholder="DOB" value="{{$child['child_dob']}}" class="form-control child datepicker"><br>
						<select  name="child_gender[]" id="child_gender[]"   class="form-control"  required>
								<option>Select Gender</option>
								@if($child['child_gender']=='male')
								<option value="male" {{'selected'}}>Male</option>
								@else
								<option value="male">Male</option>
								@endif
								@if($child['child_gender']=='female')
								<option value="female" {{'selected'}}>Female</option>
								@else
								<option value="female">Female</option>
								@endif
						</select><br>
						<input type="number" autocomplete="none" name="child_aadhar[]" id="child_aadhar[]" value="{{$child['child_aadhar']}}" placeholder="Aadhar Number" class="form-control child"><br>
						<input type="text" autocomplete="none" name="child_cur_place[]" id="child_cur_place[]" value="{{$child['child_place']}}"  placeholder="child's current Place" class="form-control child"><br>
					</div>
				
					<?php $i++; ?>
				@endforeach	 
				@endif  
				</div>                    
			</div>
		<div class="form-group row">	            					
		<div class="col-xs-6">
		 
			
		</div>
		</div>
		</div>
				<div class="form-group row ">
					<div class="col-xs-5"  align="left">
					    <a  id="sub" class="btn btn-success">Save</a> 
					</div>	
				</form>	
			 
	 </div>
</div>
		</div>
		@endforeach
	@endif
@endsection
@section('jquery')	
<script type="text/javascript">

        $(document).ready(function(){
        
        $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
		$("#success-alert").slideUp(500);
		});

      
    });
 </script>	
<script type="text/javascript">
    $(document).ready(function(){
		
    $('#sub').click(function(){
       
       var passw =$('#pass').val();
       var cpassw =$('#conpass').val();
         if(passw !=cpassw)
        {
            $('#errmsg').css('display','block');
        } 
         else
        {
             $('#errmsg').css('display','none');
             $("#form").submit();
        }
    });
	$('#no_of_children').change(function(){
		
			var rows = $('#children_detail div').length;
			
			var child_no = $('#child_no').val();
			var child = $(this).val();
			var out='';
			if(child!='')
			{
				if(child_no>0)
				{
					var out='';
					if(child>rows)
					{
						child = child-rows;
						var j=child_no;
						for(var i=1; i<=child;i++)
						{  
							j++;
							out+='<div class="col-xs-10"><label><u>child-'+j+'</u></label><input type="text" autocomplete="none" name="child[]" id="child[]" placeholder="Name" class="form-control child"><br><input type="date" autocomplete="none" name="child_dob[]" id="child_dob[]" placeholder="Name" class="form-control child"><br><select  name="child_gender[]" id="child_gender[]"   class="form-control"  required><option>Select Gender</option><option value="male">Male</option><option value="female">Female</option></select><br><input type="text" autocomplete="none" name="child_cur_place[]" id="child_cur_place[]" placeholder="child current Place" class="form-control child"><br><input type="number" autocomplete="none" name="child_aadhar[]" id="child_aadhar[]" placeholder="Aadhar Number" class="form-control child"><br></div>';
							
						} 
						$('#children_detail').append(out);
					}
					else
					{
						child = rows-child;
						for(var i=1; i<=child;i++)
						{
							$('#children_detail div:last').remove();
							/*out+='<tr><td><input type="text" name="child_name[]" class="form-control" placeholder="Child Name"></td><td><select name="child_gender[]" class="form-control select2 child_gender" placeholder="Child Gender"><option value="">Gender</option><option value="female">Female</option><option value="male">Male</option></select></td><td><input type="text" name="child_dob[]" class="form-control" placeholder="DOB"></td><td><input type="text" name="child_adhaar[]" class="form-control" placeholder="Adhaar Number"></td><td><input type="text" name="child_name[]" class="form-control" placeholder="Place Of Stay"></td></tr>';*/
						} 
						/*$('#children_tbody').append(out);*/
					}
				}
				else
				{
				   
					if(child>rows)
					{
						child = child-rows;
						var j=child_no;
						for(var i=1; i<=child;i++)
						{  
							j++;
							out+='<div class="col-xs-10"><label><u>child-'+j+'</u></label><input type="text" autocomplete="none" name="child[]" id="child[]" placeholder="Name" class="form-control child"><br><input type="date" autocomplete="none" name="child_dob[]" id="child_dob[]" placeholder="Name" class="form-control child"><br><select  name="child_gender[]" id="child_gender[]"   class="form-control"  required><option>Select Gender</option><option value="male">Male</option><option value="female">Female</option></select><br><input type="text" autocomplete="none" name="child_cur_place[]" id="child_cur_place[]" placeholder="child current Place" class="form-control child"><br><input type="number" autocomplete="none" name="child_aadhar[]" id="child_aadhar[]" placeholder="Aadhar Number" class="form-control child"><br></div>';
							
						} 
						$('#children_detail').append(out);
					}
					else
					{
						child = rows-child;
						for(var i=1; i<=child;i++)
						{
							$('#children_detail div:last').remove();
							/*out+='<tr><td><input type="text" name="child_name[]" class="form-control" placeholder="Child Name"></td><td><select name="child_gender[]" class="form-control select2 child_gender" placeholder="Child Gender"><option value="">Gender</option><option value="female">Female</option><option value="male">Male</option></select></td><td><input type="text" name="child_dob[]" class="form-control" placeholder="DOB"></td><td><input type="text" name="child_adhaar[]" class="form-control" placeholder="Adhaar Number"></td><td><input type="text" name="child_name[]" class="form-control" placeholder="Place Of Stay"></td></tr>';*/
						} 
						/*$('#children_tbody').append(out);*/
					}
				}
				
				
				$('#children_detail').show();
			}
			else
			{
				
				$('#children_detail').empty();
				$('#children_detail').hide();
			}
			
		});
});

</script>	
<script>
function show_spouse_div(){
	document.getElementById('spouse_div').style.display ='block';
}
function hide_spouse_div(){
  document.getElementById('spouse_div').style.display = 'none';
}
function show_child_div(){
	
	document.getElementById('child_div').style.display ='block';
}
function hide_child_div(){
  document.getElementById('child_div').style.display = 'none';
}

//  $('#add_row').click(function(){
// 			// alert();
// 			var row_number=parseInt($('#row_number').val());
// 			var row =row_number +1;
// 		 	var tr = '<div class="col-xs-10"><input type="text" autocomplete="none" name="child[]" id="child[]" placeholder="Name" class="form-control child"><br><input type="date" autocomplete="none" name="child_dob[]" id="child_dob[]" placeholder="Name" class="form-control child"><br><select  name="child_gender[]" id="child_gender[]"   class="form-control"  required><option>Select Gender</option><option value="male">Male</option><option value="female">Female</option></select><br><input type="text" autocomplete="none" name="child_cur_place[]" id="child_cur_place[]" placeholder="child current Place" class="form-control child"><input type="number" autocomplete="none" name="child_aadhar[]" id="child_aadhar[]" placeholder="Aadhar Number" class="form-control child"><br><button type="button" name="remove[1]" class="btn btn-danger btn-sm remove"><i class="fa fa-trash">Delete</i></button></div>';
// 			$('#children_detail').append(tr);
// 			$('#row_number').val(row);
			
// 		});
// 		$('#children_detail').on("click", "button.remove",function(){
// 			$(this).closest("div").remove();
			
// 		});
	
</script>	

<script>
$( function() {
    $( ".datepicker" ).datepicker();
  } );
</script>

@endsection