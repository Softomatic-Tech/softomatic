@extends('layouts.app')

@section('content')
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>ACCOUNT</h2>
                 <div class="flash-message" id="success-alert">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                    @if(Session::has('alert-' . $msg))
                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                    @endif
                    @endforeach
                </div> 
            </div>
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-6 col-xs-12">
                    <div class="card">
                        <div class="header bg-orange">
                            <h2>
                                Reset <small> Your Password</small>
                            </h2>
                            
                        </div>
                        <div class="body">
                             <div class="row clearfix">
                                <div class="col-sm-4" align="">
                                    <label> Username</label>
                                </div>
                                <div class="col-sm-8 col-md-8 col-lg-8"> 
                                    @foreach($user as $users)                                      
                                        <input type="" id="am" name="amount"  class="form-control" placeholder="" value=" {{$users->email}}" required readonly>
                                    @endforeach
                                </div>
                            </div>
                        <form method="post" action="account/submit" id="form">
                            {{ csrf_field() }}
                            <div class="row clearfix">
                                <div class="col-sm-4" align="">
                                    <label> New Password </label>
                                </div>
                                <div class="col-sm-8">
                                    <div class="form-line">    
                                        <input type="hidden"  name="uid" value={{$users->id}} >
                                       <input type="password" id="pass"  name="password"  class="form-control" placeholder="New Password" required/>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-4" align="">
                                    <label> Confirm  Password</label>
                                </div>
                                <div class="col-sm-8">
                                    <div class="form-line">    
                                       <input type="password" id="conpass" name="conpass"  class="form-control" placeholder="Confirm New Password" required/><br>
                                       <span><p class="col-red" id="errmsg" style="display:none;"> Password not matched </p> </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                               <div class="col-sm-2 " align="center">
                                    <div class="form-group">
										 <a   id="sub"  class="btn bg-green waves-effect"><i class="material-icons">save</i><span class="icon-name">Update</span></a> 
                                    </div>
                                </div>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

@endsection

@section('jquery')
<link href="plugins/DatePickerBootstrap/css/datepicker.css" rel="stylesheet"/>
<script src="plugins/DatePickerBootstrap/js/bootstrap-datepicker.js"></script>

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