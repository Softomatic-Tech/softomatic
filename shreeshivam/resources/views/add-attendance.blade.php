@extends('layouts.form-app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Add Attendance</h2>
            </div>
  
<div class="flash-message" id="success-alert">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
      @if(Session::has('alert-' . $msg))
		<p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
      @endif
    @endforeach
    <div class="flash-message" id="myalert">
      <p class="alert alert-info"><span id="mymsg"></span> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
  </div>
  </div> <!-- end .flash-message -->
            <!-- Content here -->
              <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                              Upload Attendance
                             </h2>
                          
                        </div>
          					<form action="" id="form" method="POST" enctype="multipart/form-data">
          					{{ csrf_field() }}
					<!--body Start-->	
                        <div class="body">
                          <div class="row clearfix">
                               <div class="col-sm-4">
                                    <div class="form-group">
                                        <a href="daily_report_sample" name="button" class="btn bg-green waves-effect">Download Sample
                                    </a>
                                </div>
                            </div>
                          </div>
                          <div class="row clearfix">
                                <div class="col-sm-2" align="right">
                                 <label> Attendance Date</label>
                                </div>
                                 <div class="col-sm-4">
                                   <div class="form-line">
                                      <input type="text" autocomplete="none" name="attendance_date" id="attendance_date" class="form-control datepicker" placeholder="Attendance Date" />
                                  </div>
                    
                                  </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-sm-2" align="right">
                								 <label> Upload File</label>
                								</div>
                							   <div class="col-sm-4">
                                    <div class="form-line">
                                      <input type="file" name="csvfile" id="csvfile" class="form-control btn-md bg-cyan waves-effect" />
                                    </div>
                                  </div>
                                  <div class="mymsg"></div>
                                   <div class="preloader pl-size-xs loading">
                                    <div class="spinner-layer pl-red-grey">
                                        <div class="circle-clipper left">
                                            <div class="circle"></div>
                                        </div>
                                        <div class="circle-clipper right">
                                            <div class="circle"></div>
                                        </div>
                                    </div>
                                </div>
                                  <!-- @if(Session::has('upload_attendance'))
                                  @if(Session::get('upload_attendance')=='pending')
                                  <div class="preloader pl-size-xs">
                                    <div class="spinner-layer pl-red-grey">
                                        <div class="circle-clipper left">
                                            <div class="circle"></div>
                                        </div>
                                        <div class="circle-clipper right">
                                            <div class="circle"></div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @endif -->

                            </div>
						                <div class="row clearfix">
                               <div class="col-sm-4 col-sm-push-3">
                                    <div class="form-group">
										                    <button type="submit" name="submit" class="btn bg-green waves-effect"><i class="material-icons">save</i><span class="icon-name">Save</span></button>
                                    </div>
                                </div>
                            </div>
						            </div>
                   	<!--body End-->	
					       </form>
				   </div>
                </div>
			</div>
		
		</div>
    </section>
	
			
@endsection
@section('jquery')
<script type="text/javascript">
  $(document).ready(function(){
    $('.datepicker').datepicker().on('changeDate', function (ev) {
        $('.dropdown-menu').hide();
    });
    $('.loading').hide();
    $('#myalert').hide();
    $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
    $('#form').on('submit',function(e){
        e.preventDefault();
          if($('#csvfile').val()=='' || $('#attendance_date').val()=='')
          {
            if($('#csvfile').val()=='')
            {
              alert('Please browse file to upload')
              $('#csvfile').focus();
            }
            else
              if($('#attendance_date').val()=='')
              {
                alert('Please Select Date of Attendance')
                $('#attendance_date').focus();
              }
          }
          else
          {
          var formData = new FormData(this);
          $.ajax({
            url:'uploadcsv',
            type:'POST',
            data:formData,
            datatype:'text',
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function() {
                $('.loading').show();
            },
            success:function(data){
                     $('#mymsg').html(data);
                    $("#myalert").fadeTo(2000, 500);/*.slideUp(500, function(){
                    $("#myalert").slideUp(500);
                    });*/
                    $('.loading').hide();  
                    $('#csvfile').val(''); 
                  },
            error:function(data){
                $('.loading').hide();
            },
            complete: function() {
               $('.form-control').val('');
            },
          });
          }
      });
  });
</script>
@endsection