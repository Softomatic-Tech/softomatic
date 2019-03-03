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
					<h3>Attendance</h3></div>
				 <div class="flash-message" id="success-alert">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                    @if(Session::has('alert-' . $msg))
                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                    @endif
                    @endforeach
                </div>

                <div class="container-fluid" id="my_camera">	
					<div class="form-group row ">
						<video id="video" width="100%" height="50%" autoplay></video>
						<button id="snap">Capture</button>
					</div>
				</div>

				<div class="container-fluid" id="my_attendance_div">	
					<div class="capture_div">
						<form method="post" action="mobile_attendance/submit" id="form">	
					        {{ csrf_field() }}	
							<div class="form-group row ">
								<div class="col-xs-12">
									<canvas id="canvas" name="canvas" width="300" height="200"></canvas>
									<input type="hidden" name="image_name" id="image_name" class="form-control" value="">
									<input type="hidden" name="latitude" id="latitude" class="form-control" value="">
									<input type="hidden" name="longitude" id="longitude" class="form-control" value="">
									<img src="" name="myimage" id="myimage">
								</div>
							<!-- </div>
							<div class="form-group row "> -->
								<div class="col-xs-12">
									<button type="submit" class="btn btn-xs btn-success col-xs-6">Submit Attendance</button>
									<a class="btn btn-xs btn-danger col-xs-6" id="cancel">Cancel</a>
								</div>
							</div>
						</form>
					</div>
				</div>
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

<script>
 // Grab elements, create settings, etc.
var video = document.getElementById('video');

// Get access to the camera!
if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
    // Not adding `{ audio: true }` since we only want video now
    navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream) {
        video.src = window.URL.createObjectURL(stream);
        video.play();
    });
}


// Trigger photo take
/*var image = $('#myimage');
image.src = canvas.toDataURL("image/png");*/
</script>
<script>
var longitude = document.getElementById("longitude");
var latitude = document.getElementById("latitude");
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function showPosition(position) {
    latitude.value = position.coords.latitude; 
    longitude.value = position.coords.longitude;
});
    } else { 
        x.innerHTML = "Geolocation is not supported by this browser.";
    }
}

/*function showPosition(position) {
    x.innerHTML = "Latitude: " + position.coords.latitude + 
    "<br>Longitude: " + position.coords.longitude;
}*/
</script>

<script type="text/javascript">
	$(document).ready(function(){
		$('#my_attendance_div').hide();
  		$('#snap').click(function(){
 			getLocation();
 			var canvas = document.getElementById('canvas');
			var context = canvas.getContext('2d');
			/*var video = document.getElementById('video');		*/	
			context.drawImage(video, 0, 0, 300,200);
			var dataURL = canvas.toDataURL();
			document.getElementById('image_name').value = dataURL;
			$('#my_camera').hide();
			$('#my_attendance_div').show();
 		});
 		$('#cancel').click(function(){
 			$('#my_attendance_div').hide();
 			$('#canvas').html("");
 			$('#latitude').val("");
 			$('#longitude').val("");
 			$('#my_camera').show();
 		});
    });
</script>
@endsection