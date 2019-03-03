@extends('layouts.form-app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>HISTORY</h2>
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
                            <h2>HISTORY</h2>
                        </div>
                        <div class="body table-responsive" id="history_table"> 
                            @include('history_table')                                      
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
    <script type="text/javascript">
        var autoLoad = setInterval(
                function ()
                {
                    $('#history_table').load('history_table', function() {
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
                url:'history/action',
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
            $('#history_table').load('history_table', function() {
            });
          }
    </script>
    @endsection