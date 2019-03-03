@extends('layouts.form-app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>NOTIFICATION</h2>
                 <div class="flash-message" id="success-alert">
		            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
				      @if(Session::has('alert-' . $msg))
						<p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
				      @endif
				    @endforeach
				</div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>NOTIFICATIONS</h2>
                        </div>
                        <div class="body table-responsive" id=""> 
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="mytable">
                                <thead>
                                    <tr class="bg-orange">
                                        <th>#</th>
                                        @if(Session::get('role')=='admin')
                                        <th>Generated For</th>
                                        @endif
                                        <th>Notification</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="notification_table">
                                    @include('notification_table')    
                                </tbody>                   	
                            </table>                                              
                        </div>
                    </div>
                </div>
            </div>            
        </div>
    </section>

            <style type="text/css">
                .accept,.reject,.showreason
                {
                    margin:2.5px;
                }
            </style>
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
    <script type="text/javascript">
        var autoLoad = setInterval(
                function ()
                {
                    $('#notification_table').load('notification_table', function() {
                    });

                }, 30000);
    </script>
    <script type="text/javascript">
    	$(document).ready(function(){
             $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).on('click','.dismiss',function(e){
                e.preventDefault();
                var id = $(this).closest('tr').find('.myid').val();
                
                $.ajax({
                type:'POST',
                datatype: 'text',
                url:'notification/action',
                data:{id:id},
                success:function(data){
                    if(data=='1')
                    refreshTable();
                }
                });
            });
            
    	});

        function refreshTable()
          {
            $('#notification_table').load('notification_table', function() {
            });
          }
    </script>
    @endsection
