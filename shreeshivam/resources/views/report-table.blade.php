<div class="table-responsive">
	<table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="myattendancereport">
        <thead>
            <tr class="bg-orange">
                <th>#</th>
                <th>Date</th>
                <th>GENESIS ID</th>
                <th>BIOMETRIC ID</th>
                <th>EMP NAME</th>
                <th>INITIAL IN TIME</th>
                <th>FINAL OUT TIME</th>
                <th>WORKING HOUR</th>
                <th>EXPAND</th>
              </tr>
        </thead>
        <tbody>
        	<?php $count=1; ?>
        	@foreach($mydata as $datum)
        	<tr>
        		<td>{{$count++}}</td>
        		<td>{{$datum->date}}</td>
        		<td>{{$datum->genesis_id}}</td>
        		<td>{{$datum->biometric_id}}</td>
        		<td>{{$datum->first_name}} {{$datum->middle_name}} {{$datum->last_name}}</td>
        		<td>{{$datum->initial_in}}</td>
        		<td>{{$datum->final_out}}</td>
        		<td>{{$datum->total_working_hour}}</td>
        		<td><a href="javascript:void(0);" class="btn btn-xs btn-info myadd1"><i class="material-icons mycheck">add</i></a>
        			<?php
						$inout = (array) json_decode($datum->attendance,true);
					?>
					<div class="modal fade myinoutmodal" tabindex="-1" role="dialog">
		            <div class="modal-dialog " role="document">
		                <div class="modal-content">
		                    <div class="modal-header bg-pink">
		                        <h4 class="modal-title" id="defaultModalLabel">DAY REPORT</h4>
		                    </div>					
		                    <div class="modal-body">
								<div class="table-responsive myinouttable">
			                        <table class="table table-bordered table-striped table-hover">
			                        	<?php
			                                if(array_key_exists('report',$inout)) { ?>
			    						<thead>
										<tr>
										<?php
			        					
			        					foreach($inout['report'] as $key=>$value)
			                        	{ ?>
			                        		<th>{{$key}}</th>
			                        	<?php  }
			                        	?>
			                            </tr>
			    						</thead>
			    						<tbody>
										<tr>
			        					<?php
			        					// $inout = (array) json_decode($current_week['mycurrent'][$i][$j]['attendance'],true);
			        					foreach($inout['report'] as $key=>$value)
			                        	{ ?>
			                        		<td>{{$value}}</td>
			                        	<?php  }
			                        	?>
			                        	</tr>
			                    	</tbody>
			                    <?php } ?>
			                    	</table>
			                    </div>
			                    </div>
			                    <div class="modal-footer">
	                           		<button type="button" class="btn bg-blue-grey  waves-effect" data-dismiss="modal">Close</button>
		                        </div>
			                </div>
			            </div>
			        </div>
        		</td>
        	</tr>
        	@endforeach
        </tbody>
    </table>
</div>

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
