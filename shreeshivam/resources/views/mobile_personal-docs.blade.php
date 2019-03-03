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
					<h3>My Documents</h3></div>
				 <div class="flash-message" id="success-alert">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                    @if(Session::has('alert-' . $msg))
                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                    @endif
                    @endforeach
                </div>

                 <div class=" container row clearfix myrow">
                    <div class="col-sm-3"><a href="mobile_upload_doc">Upload</a></div> <div class="col-sm-3"><a href="doc_viewer">View Documents</a></div>
			</div> 
	 </div>
</div>
		</div>
		<style type="text/css">
			.capture_div
			{
				border:2px solid #dedede;
				border-radius: 5px;
				padding-top: 12px;
			}
		</style>
@endsection
@section('jquery')	



<script type="text/javascript">
	$(document).ready(function(){
	
    });
</script>
@endsection