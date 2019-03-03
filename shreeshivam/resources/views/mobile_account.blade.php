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
					<h3>Account</h3></div>
				 <div class="flash-message" id="success-alert">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                    @if(Session::has('alert-' . $msg))
                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                    @endif
                    @endforeach
                </div> 
			<div class="container-fluid">	
			<div class="form-group row ">
			<div class="col-xs-5" align="left">
			 <label for="username">Username</label>
			</div>
				<div class="col-xs-12">
				 @foreach($user as $users) 
					 <input class="form-control" id="ex1" value=" {{$users->email}}" type="text">
					   @endforeach
				</div>
			</div>
			</div>
          <form method="post" action="mobile-account/submit" id="form">	
               {{ csrf_field() }}			
			<div class="form-group row ">
			<div class="col-xs-5"  align="left">
			 <label for="username">New Password </label>
			</div>
				<div class="col-xs-12">
				<input type="hidden"  name="uid" value="{{$users->id}}" >
                                       <input type="password" id="pass"  name="password"  class="form-control" placeholder="New Password" required/>
				</div>
			</div>	
          <div class="form-group row ">
			<div class="col-xs-5"  align="left">
			 <label for="username">Confirm Password </label>
			</div>
				<div class="col-xs-12">
					  <input type="password" id="conpass" name="conpass"  class="form-control" placeholder="Confirm New Password" required/><br>
                 </div>
				<div class="col-xs-12">
				 <span><p class="bg-danger" id="errmsg" style="display:none;"> Password not matched </p> </span>
			</div></div>
			<div class="form-group row ">
			<div class="col-xs-5"  align="left">
			  <a  id="sub" class="btn btn-success">Update</a> 
			</div>
				
			</div>	
		</form>	
			</div> 
	 </div>
</div>
		</div>
		
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
   
});
</script>		
@endsection