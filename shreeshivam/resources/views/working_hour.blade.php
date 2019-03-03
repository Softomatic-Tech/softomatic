@extends('layouts.form-app')
@section('content')
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Working Hour</h2>
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
                              Working Hour
                            </h2>                          
                        </div>
					<form action="working_hour/submit"  method="POST">
					{{ csrf_field() }}
					<!--body Start-->	
                        <div class="body">
                            <div class="form-group clearfix">
                                <div class="col-sm-2" align="right">
								 <label> Full day From</label>
								</div>
							    <div class="col-sm-2">
                                    <div class="form-line">
                                        <input type="text" name="full_day_from" id="full_day_from" class="form-control" placeholder="H:M:S" />
                                        <span class="text-danger">{{ $errors->first('full_day_from') }}</span>
                                    </div>
                                </div>
                            <!-- </div>

							<div class="row clearfix"> -->
                                <div class="col-sm-2" align="right">
								    <label>Full Day To</label>
								</div>
							    <div class="col-sm-2">
                                    <div class="form-line">
                                        <input type="text" name="full_day_to" id="full_day_to" class="form-control" placeholder="H:M:S" />
                                        <span class="text-danger">{{ $errors->first('full_day_to') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-sm-2" align="right">
                                    <label>Half Day From</label>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-line">
                                        <input type="text" name="half_day_from" id="half_day_from" class="form-control" placeholder="H:M:S" />
                                        <span class="text-danger">{{ $errors->first('half_day_from') }}</span>
                                    </div>
                                </div>
                            <!-- </div>

                            <div class="row clearfix"> -->
                                <div class="col-sm-2" align="right">
                                    <label>Half Day To</label>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-line">
                                        <input type="text" name="half_day_to" id="half_day_to" class="form-control" placeholder="H:M:S" />
                                        <span class="text-danger">{{ $errors->first('half_day_to') }}</span>
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
			<div class="row clearfix">
			    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Bank Hour Detail
                            </h2>
                          </div>
                        <div class="body table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr class="bg-orange">
                                        <th>#</th>
                                        <th>Full Day From</th>
                                        <th>Full Day To</th>
										<th>Half Day From</th>
										<th>Half Day To</th>                                        
                                        <th>Action</th>
                                      </tr>
                                </thead>
                                <tbody>
								<?php 
								$count=1; 
								?>
                                @foreach($working as $work)
                                <form action="working_hour/update"  method="POST">
                                    {{ csrf_field() }}
                                    <td>{{$count}}<input type="hidden" name="id" value="{{$work->id}}"></td>
                                    <td><span class="edit">{{$work->full_day_from}}</span><input type="text" name="full_day_from" class="form-control update" value="{{$work->full_day_from}}" placeholder="Full Day From"></td>
                                    <td><span class="edit">{{$work->full_day_to}}</span><input type="text" name="full_day_to" class="form-control update" value="{{$work->full_day_to}}" placeholder="Full Day To"></td>
                                    <td><span class="edit">{{$work->half_day_from}}</span><input type="text" name="half_day_from" class="form-control update" value="{{$work->half_day_from}}" placeholder="Half Day From"></td>
                                    <td><span class="edit">{{$work->half_day_to}}</span><input type="text" name="half_day_to" class="form-control update" value="{{$work->half_day_to}}" placeholder="HAlf Day To"></td>
                                    <td><button type="button" id="edit" class="btn btn-xs btn-warning edit"><i class="material-icons">create</i></button> <button type="submit" class="btn btn-xs btn-success update"><i class="material-icons">save</i></button></td>
                                    <?php $count++; ?>
                                </form>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>				
            </div>
		</div>
    </section>
@endsection
@section('jquery')
<script type="text/javascript">
$(document).ready(function(){
	$('.update').hide();
    $('.edit').show();
    $('#edit').click(function(){
        $('.edit').hide();
        $('.update').show();
    });
 }); 
</script>
@endsection