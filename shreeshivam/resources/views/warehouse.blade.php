<!DOCTYPE html>
<head>
	<title>Shreeshivam Login</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
<link href="plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<!--===============================================================================================-->
	<link rel="stylesheet"  href="plugins/login/css/util.css">
	<link rel="stylesheet"  href="plugins/login/css/main.css">
<!--===============================================================================================-->
</head>
<body>

	<div class="container-login100" style="background-image: url('plugins/login/images/bg-01.jpg');">
	<div class="wrap-login100 p-l-55 p-r-55 p-t-80 p-b-30">
			<form class="login100-form" method="POST" id="sign_in">
            {{ csrf_field() }}
			@if(count($errors)>0)
<div class="msg alert alert-danger col-md-12" style="width: 100%;"> 
                        <h4>
                            <center>
                            @foreach($errors->all() as $error)
                            {{$error}}
                            @endforeach
                            </center>
                        </h4>
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
                  
				<span class="login100-form-title p-b-37">
					Sign In
				</span>

				<div class="wrap-input100 validate-input m-b-20" data-validate="Enter username or email">
					<input class="input100" type="text" name="username" placeholder="Email">
					<span class="focus-input100"></span>
				</div>

				<div class="wrap-input100 validate-input m-b-25" data-validate = "Enter password">
					<input class="input100" type="password" name="password" placeholder="Password">
					<span class="focus-input100"></span>
				</div>

				<div class="container-login100-form-btn">
					<button type="submit" class="login100-form-btn">
						Sign In
					</button>
				</div>

				
			</form>

			
		</div>
	</div>
	<script src="plugins/mobile/js/jquery-2.1.4.min.js"></script>
    <script src="plugins/mobile/js/bootstrap.min.js"></script>

	

</body>
</html>