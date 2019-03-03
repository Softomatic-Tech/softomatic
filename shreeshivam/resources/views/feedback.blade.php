@extends('layouts.form-app')

@section('content')
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Feedback</h2>
            </div>
<div class="flash-message" id="success-alert">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
      @if(Session::has('alert-' . $msg))
		<p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
      @endif
    @endforeach
  </div> <!-- end .flash-message -->
            <!-- Content here -->
            @if(session('role')=='admin')
              <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Feedback
                             </h2>
                          
                        </div>
					
                        <div class="body table-responsive">
                          <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                            <thead>
                                <tr class="bg-pink">
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>User name</th>
                                    <!-- <th>User email</th> -->
                                    <th>Feedback Type</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody><?php $count=0;?>
                                @foreach($feedback as $feed)
                                @if($feed->type=='complain')
                                <tr style="background:#eeb790;">
                                @else
                                <tr>
                                @endif
                                    <td>{{++$count}}</td>
                                    <td>{{$feed->created_at}}</td>
                                    <td>{{$feed->first_name}} {{$feed->middle_name}} {{$feed->last_name}}</td>
                                    <!-- <td>{{$feed->user_email}}</td> -->
                                    <td>{!! ucfirst($feed->type) !!}</td>
                                    <td>{{$feed->description}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                          </table>
						  </div>
                   	<!--body End-->	
					
				   </div>
                </div>
			</div>
            @else
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Give Feedback
                             </h2>
                          
                        </div>
                    
                        <div class="body">
                            <form action="save_feedback"  method="POST">
                            {{ csrf_field() }}
                            <div class="row clearfix">
                                <div class="col-sm-2">
                                    <label> Feedback Type</label>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-line">
                                        <select class="form-control select2" id="feedback_type" name="feedback_type" required="">
                                            <option value="">Select Feedback Type</option>
                                            <option value="suggestion">Suggestion</option>
                                            <option value="appreciation">Appreciation</option>
                                            <option value="complain">Complain</option>
                                        </select>
                                        <span class="text-danger">{{ $errors->first('feedback_type') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-2">
                                    <label>Description</label>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-line">
                                        <textarea class="form-control" id="description" name="description" placeholder="Description" required=""></textarea>
                                        <span class="text-danger">{{ $errors->first('description') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                               <div class="col-sm-6" align="right">
                                    <div class="form-group">
                                        <button type="submit" name="submit" class="btn bg-green waves-effect"><i class="material-icons">save</i><span class="icon-name">submit</span></button>
                                    </div>
                                </div>
                            </div>
                            </form>
                          </div>
                    <!--body End--> 
                    
                   </div>
                </div>
            </div>
            @endif
		</div>
    </section>
@endsection
@section('jquery')
<link href="plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
    
    <script src="plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/buttons.flash.min.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/buttons.html5.min.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/buttons.print.min.js"></script>
    <script src="js/pages/tables/jquery-datatable.js"></script>
@endsection
