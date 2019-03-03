@extends('layouts.app')

@section('content')
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Add Incentive</h2>
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
                              Upload Incentive
                             </h2>
                          
                        </div>
          					<form action="uploadcsv" method="POST" enctype="multipart/form-data">
          					{{ csrf_field() }}
					<!--body Start-->	
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-sm-2" align="right">
                								 <label> Upload File</label>
                								</div>
                							   <div class="col-sm-4">
                                   <div class="form-line">
                                      <input type="file" name="csvfile" class="form-control btn-md bg-cyan waves-effect" required/>
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
		</div>
    </section>
	
			
@endsection
@section('jquery')
<script>
<script>
$(document).ready(function() {
	
     $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
		$("#success-alert").slideUp(500);
		});
    
    
 });
 
</script >

</script>
@endsection