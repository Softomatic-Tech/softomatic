@extends('layouts.form-app')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Add Bonus</h2>
            </div>
<div class="flash-message" id="success-alert">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
      @if(Session::has('alert-' . $msg))
		<p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
      @endif
    @endforeach
  </div>
  <div class="flash-message" id="myalert">
      <p class="alert alert-info"><span id="mymsg"></span> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
  </div> <!-- end .flash-message -->
            <!-- Content here -->
              <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                              Upload Bonus
                             </h2>
                          
                        </div>
                        <form action="bonus/sample" method="POST">
                          {{ csrf_field() }}
                          <div class="body">
                          <div class="row clearfix">
                               <div class="col-sm-3">
                                    <div class="form-group">
                                    <select class="form-control select2 zone" id="zone" name="zone">
                                    <option value="">Select Branch</option>
                                    @foreach($branch as $branches)
                                    <option value="{{$branches->id}}">{{$branches->branch}}</option>
                                    @endforeach
                                    </select>
                                       
                                    </div>
                                </div>
                                <div class="col-sm-1"> 
                                <button type="submit" name="button" class="btn bg-green waves-effect">Download Sample</button></div>
                            </div>
                          </div>
                      </form>
          					<form action="upload_bonus" id="form" method="POST" enctype="multipart/form-data">
          					{{ csrf_field() }}
					<!--body Start-->	
                        <div class="body">
                          
                          <div class="row clearfix">
                                <div class="col-sm-2" align="right">
                                 <label>Month</label>
                                </div>
                                 <div class="col-sm-4">
                                   <div class="form-line">
                                <select class="form-control select2 month" id="month" name="month">
                                    <option value="">Select Month</option>
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
                                  
                            </div>

                            <div class="row clearfix">
                                <div class="col-sm-2" align="right">
                								 <label> Upload File</label>
                								</div>
                							   <div class="col-sm-4">
                                    <div class="form-line">
                                      <input type="file" name="csvfile" id="" class="form-control btn-md bg-cyan waves-effect" />
                                    </div>
                                  </div>
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
     
    /* $("#month").select2({
        placeholder: "Select Month",
        allowClear: true
       });*/
       
       $("#branch").select2({
        placeholder: "Select Branch",
        allowClear: true
       });
  });
</script>

<script type="text/javascript">
  $(document).ready(function(){ 
    $('.loading').hide();
    $('#myalert').hide();
    $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      $('#form').on('submit',function(e){ 
        e.preventDefault();
          
          if($('#csvfile').val()=='')
          {
            alert('Please browse file to upload')
            $('#csvfile').focus();
          }
          else
          {
          var formData = new FormData(this);
          $.ajax({
            url:'upload_bonus_csv',
            type:'POST',
            data:formData,
            datatype:'text',
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){

                $('.loading').show();
            },
            success:function(data){ 
                    $('#mymsg').html(data);
                    $("#myalert").fadeTo(2000, 500);
                    $('.loading').hide();  
                    $('#csvfile').val(''); 
                    $('#month').val(''); 
                  },
            error:function(data){
                 $('.loading').hide();
                
            }
          });
        }
      });
  });
</script>
@endsection