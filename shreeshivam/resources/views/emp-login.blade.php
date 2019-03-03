 <?php date_default_timezone_set('Asia/Kolkata'); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
        <title>Shree Shivam</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta charset utf="8">
        <!--font-awsome-css-->
          <!--  <link rel="stylesheet" href="plugins/mobile/css/font-awesome.min.css">  -->
		<!--bootstrap-->
			<link href="plugins/mobile/css/bootstrap.min.css" rel="stylesheet" type="text/css">
		<!--custom css-->
			<link href="plugins/mobile/css/style.css" rel="stylesheet" type="text/css"/>
			<!-- <link rel="stylesheet" href="plugins/mobile/css/percircle.css"> -->
        <!--component-css-->
			<script src="plugins/mobile/js/jquery-2.1.4.min.js"></script>
			<script src="plugins/mobile/js/bootstrap.min.js"></script>
			
           <script src="plugins/mobile/js/bigSlide.js"></script>  
            <script>
				$(document).ready(function() {
				$('.menu-link').bigSlide();
				});
            </script>
            <style>
                .alert-warning {
                    color: #000; 
                    background-color: #fff;
                    border-color: #fff;
                    border-radius: 0px;
            }
            </style>
    </head>
<body class="body_l">
<div class="login">
    <div class="sing-up-icon text-center"></div>
        @if(count($errors)>0)
            <div class="msg alert alert-warning col-md-12" style="width: 100%;"> 
                <h3>
                    <center>
                    @foreach($errors->all() as $error)
                    {{$error}}
                    @endforeach
                    </center>
                </h3>
            </div>
        @endif
        @if(session('status'))
            <div class="msg alert alert-warning col-md-12" style="width: 100%;"> 
            <h4>
                <center>
                {{session('status')}}
                </center>
            </h4>
            </div>
        @endif
			<form method="POST" id="sign_in">
                 {{ csrf_field() }}
				<div class="styled-input">
					<input type="email" name="username" required />
						<label>Email</label>
							<span></span> </div>
						<div class="styled-input">
							<input type="password" name="password" required />
								 <label>Password</label>
						<span></span> </div>
									
				<div class="send">
					<input type="submit" value="Login" >
				</div>
            </form>
		</div>
    </div>
</body>
</html>