@extends('layouts.form-app')

@section('content')
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Reports</h2>
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
                              Reports
                             </h2>
                          
                        </div>
                        <div class="body table-responsive">
                          @if(Session::has('flag'))
                            @if(session('flag')==0)
                              <p class="alert alert-danger">Please generate and submit the <a href="create-payslip">payslip</a> to view reports <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                            @else
                            <table class="table table-bordered table-striped table-hover js-exportable dataTable" id="mainTable">

                               {!!  Session::get('table') !!}
                            
                            </table>
                            @endif
                          @else
                          <p><a href="report">Go Back</a></p>
                          @endif
                        </div>
                        
                    </div>
                </div>
            </div>
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
     
