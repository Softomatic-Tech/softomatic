@extends('layouts.form-app')

@section('content')
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>ATTENDANCE REPORT</h2>
            </div>
            <div class="flash-message" id="success-alert">
	            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
			      @if(Session::has('alert-' . $msg))
					<p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
			      @endif
			    @endforeach
			</div>
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                DAILY ATTENDANCE REPORT
                            </h2>
                            <!-- <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="javascript:void(0);">Action</a></li>
                                        <li><a href="javascript:void(0);">Another action</a></li>
                                        <li><a href="javascript:void(0);">Something else here</a></li>
                                    </ul>
                                </li>
                            </ul> -->
                        </div>
                        <div class="body">
                        	<div class="panel-group" id="accordion_9" role="tablist" aria-multiselectable="true">
                        		<?php 
	                        	$current_week = (array) json_decode($current_week,true);
	                        	 // echo sizeof($current_week['mycurrent']);
	                        	$i=0;
	                        	
	                        	while($i<sizeof($current_week['mycurrent']))
	                        	{	                        		
	                        		// echo $current_week['mycurrent'][$i][0]['date'].' ------- ';
	                        		// $i++; 
	                        		?>
	                        		<div class="panel panel-col-cyan">
                                    <div class="panel-heading" role="tab" id="heading1">
                                        <h4 class="panel-title">
                                            <a role="button" data-toggle="collapse" data-parent="#accordion_9" href="#collapse<?php echo $i; ?>" aria-expanded="true" aria-controls="collapse<?php echo $i; ?>"><?php if($current_week['mycurrent'][$i]!=[]){ ?> DATE - 
                                                {{date('d-m-Y',strtotime($current_week['mycurrent'][$i][0]['date']))}} <?php } ?>
                                                <!-- <i class="material-icons mycheck" id="mycheck1">check</i> -->
                                            </a>

                                        </h4>
                                    </div>
                                    <div id="collapse<?php echo $i; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?php echo $i; ?>">
                                    	<div class="table-responsive ">
			                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable myemptable">
			                                    <thead>
			                                        <tr>
			                                            <th>EMP ID</th>
			                                            <th>EMP NAME</th>
			                                            <th>INITIAL IN TIME</th>
			                                            <th>FINAL OUT TIME</th>
			                                            <th>WORKING HOUR</th>
			                                            <th>EXPAND</th>
			                                            
			                                        </tr>
			                                    </thead>
												<tbody>
													<!-- <td></td>
													<td></td> -->
			                                    	<?php
			                                    	$j=0;
			                                    	while($j<sizeof($current_week['mycurrent'][$i]))
			                                    	{ ?>
			                                    		<!-- print_r($current_week['mycurrent'][$i][$j]);
			                                    		echo '---------------------'; -->
			                                    	<tr>
			                                    		<td>{{$current_week['mycurrent'][$i][$j]['emp_id']}}</td>
			                                    		<td>{{$current_week['mycurrent'][$i][$j]['title']}}  {{$current_week['mycurrent'][$i][$j]['first_name']}} {{$current_week['mycurrent'][$i][$j]['middle_name']}} {{$current_week['mycurrent'][$i][$j]['last_name']}}</td>
			                                    		<td>{{$current_week['mycurrent'][$i][$j]['initial_in']}}</td>
			                                    		<td>{{$current_week['mycurrent'][$i][$j]['final_out']}}</td>
			                            				<td>{{$current_week['mycurrent'][$i][$j]['total_working_hour']}}</td>
			                            				<td colspan="2"><a href="javascript:void(0);" class="btn btn-xs btn-info myadd1"><i class="material-icons mycheck">add</i></a>
                    					<?php
                    					$inout = (array) json_decode($current_week['mycurrent'][$i][$j]['attendance'],true);
                    					?>
			                            <div class="table-responsive myinouttable" style="display: none;">
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
			                            				</td>
			                                    	</tr>
			                                    		<?php
			                                    		$j++;
			                                    	}
			                                    	?>
			                                    </tbody>
			                                </table>
										</div>                                    	
                                    </div>
                                </div>
	                        		<?php
	                        		$i++; 
	                        	}
	                        	?>

                        	</div>
                        	
                            <!-- <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                    <thead>
                                        <tr>
                                            <th>Emp ID</th>
                                            <th>Date</th>
                                            <th>IN 1</th>
                                            <th>OUT 1</th>
                                            <th>IN 2</th>
                                            <th>OUT 2</th>
                                            <th>IN 3</th>
                                            <th>OUT 3</th>
                                            <th>IN 4</th>
                                            <th>OUT 4</th>
                                            <th>IN 5</th>
                                            <th>OUT 5</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    	
                                    </tbody>
                                </table>
                            </div> -->
                        </div>
                    </div>
                    <div class="card">
                        <div class="header">
                            <h2>
                                WEEKLY ATTENDANCE REPORT
                            </h2>
                        </div>
                        <div class="body">
                        	<div class="panel-group" id="accordion_1" role="tablist" aria-multiselectable="true">
                        		<?php if($weeks>0)
                        		{
                        			$week_report = (array) json_decode($week_report,true);
                        			//print_r($week_report['myweeks']);
                        			$weeksize = sizeof($week_report['myweeks']);
                        			$i=0;
                        			foreach($week_report['myweeks'] as $weeks)
                        			{
                        				?>
                        				<div class="panel panel-warning">
                                            <div class="panel-heading" role="tab" id="headingOne_<?php echo $i; ?>">
                                                <h4 class="panel-title">
                                                    <a role="button" data-toggle="collapse" data-parent="#accordion_1" href="#collapseOne_<?php echo $i; ?>" aria-expanded="true" aria-controls="collapseOne_1">
                                                         WEEK {{$weeksize-$i}}
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseOne_<?php echo $i; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne_<?php echo $i; ?>">
                                                <div class="panel-body">
                                                    <?php
                                                    $days = (array) json_decode($weeks,true);
                                                    $mn = 0;   $p=0;?>
                     <div class="panel-group" id="accordionTwo_<?php echo $i ?>" role="tablist" aria-multiselectable="true">
                                                        	
                                                    	<?php
                                                    	while($p<sizeof($days['week']))
                                                    	{

                                                    	  $mydate = (array) json_decode($days['week'][$p],true); 
                                                    	  if($mydate['mydate']!=[]){
                                                    	  ?>
                                <div class="panel panel-col-cyan">
                                    <div class="panel-heading" role="tab" id="headingTwo_<?php echo $i ?><?php echo $p ?>">
                                        <h4 class="panel-title">
                                            <a role="button" data-toggle="collapse" data-parent="#accordionTwo_<?php echo $i ?>" href="#collapseTwo_<?php echo $i ?><?php echo $p ?>" aria-expanded="true" aria-controls="collapseTwo_<?php echo $p ?>">  DATE - {{date('d-m-Y',strtotime($mydate['mydate'][0]['date']))}} 
                                                
                                                <!-- <i class="material-icons mycheck" id="mycheck1">check</i> -->
                                            </a>

                                        </h4>
                                    </div>
                                     <div id="collapseTwo_<?php echo $i ?><?php echo $p ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo_<?php echo $i ?><?php echo $p ?>">
                                     	<?php 
                                     	/*print_r($mydate['mydate']);*/
                                     	 ?>
                                     		<div class="table-responsive">
			                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
			                                    <thead>
			                                        <tr>
			                                            <th>EMP ID</th>
			                                            <th>EMP NAME</th>
			                                            <th>INITIAL IN TIME</th>
			                                            <th>FINAL OUT TIME</th>
			                                            <th>WORKING HOUR</th>
			                                            <th>EXPAND</th>
			                                            
			                                        </tr>
			                                    </thead>
												<tbody>

													<?php
			                                    	$j=0;
			                                    	foreach ($mydate['mydate'] as $mynewdate) { ?>
			                                    		<!-- print_r($current_week['mycurrent'][$i][$j]);
			                                    		echo '---------------------'; -->
			                                    	<tr>
			                                    		<td>{{$mynewdate['emp_id']}}</td>
			                                    		<td>{{$mynewdate['title']}} {{$mynewdate['first_name']}} {{$mynewdate['middle_name']}} {{$mynewdate['last_name']}}</td>
			                                    		<td>{{$mynewdate['initial_in']}}</td>
			                                    		<td>{{$mynewdate['final_out']}}</td>
			                            				<td>{{$mynewdate['total_working_hour']}}</td>
			                            				<td colspan="2"><a href="javascript:void(0);" class="btn btn-xs btn-info myadd1"><i class="material-icons mycheck">add</i></a>
                    					<?php
                    					$inout = (array) json_decode($mynewdate['attendance'],true);
                    					?>
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
			                            				</td>
			                                    	</tr>
			                                    		<?php
			                                    		$j++;
			                                    	}
			                                    	?>
												</tbody>
											</table>
											</div>

                                     		<?php
                                     	 	// echo $mynewdate['emp_id'];
                                     	 /*}*/ 
                                     	?>
                                     </div>
                                                    	  
                                </div>
                                                    	  <?php }
                                                    	  $p++;
                                                    	}
	                        	
	                        	
	                        		?>                  		
	                        		
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
								
                        				<?php $i++; $mn++;
                        			}
                        			//die;
                        		}
                        		?>
                        	</div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="header">
                            <h2>
                                PREVIOUS MONTH REPORT
                            </h2>
                        </div>
                        <div class="body">
                        	<div class="panel-group" id="accordion_123" role="tablist" aria-multiselectable="true">

                        	<?php
                        	$months = (array) json_decode($months,true);
                        	//$months = (array) json_decode($months['mymonth'][0],true);
                        	//echo sizeof($months['mymonth']);
                        	$i=1;
                        	foreach($months['mymonth'] as $mymon)
                        	{ ?> 
                        		<div class="panel panel-primary">
                                    <div class="panel-heading" role="tab" id="headingThree_<?php echo $i; ?>">
										<h4 class="panel-title">
                                            <a role="button" data-toggle="collapse" data-parent="#accordion_123" href="#collapseThree_<?php echo $i; ?>" aria-expanded="true" aria-controls="collapse">
                                                Month - <?php echo date("M", strtotime("-".$i." month"));; ?>
                                            </a>

                                        </h4>
                                    </div>
                                    <div id="collapseThree_<?php echo $i; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_<?php echo $i; ?>">
                                    	<div class="panel-body">
                                    	<div class="panel-group" id="accordion_<?php echo $i ?>x" role="tablist" aria-multiselectable="true">

                                    	<?php $mymonth = (array) json_decode($mymon,true); 
                                    	$size = sizeof($mymonth['month']);
                                    		foreach($mymonth['month'] as $monweeks)
                                    			{ 
                                    	?>
                                    		<div class="panel panel-warning">
			                                    <div class="panel-heading" role="tab" id="headingThree_<?php echo $i; ?>">
													<h4 class="panel-title">
			                                            <a role="button" data-toggle="collapse" data-parent="#accordion_<?php echo $i ?>x" href="#collapseFour_<?php echo $size; ?><?php echo $i; ?>" aria-expanded="true" aria-controls="collapse">
			                                                Week - <?php echo $size; ?>
			                                            </a>

			                                        </h4>
			                                    </div>
			                                    <div id="collapseFour_<?php echo $size; ?><?php echo $i; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_<?php echo $i; ?>">
			                                    	<div class="panel-body">
			                                    		<div class="panel-group" id="accordion_<?php echo $size ?>y" role="tablist" aria-multiselectable="true">			                                    		
			                                    		<?php $mydates = (array) json_decode($monweeks,true);  $wee =0;
			                                    			foreach($mydates['week'] as $mydate){
			                                    				$mydate = (array) json_decode($mydate,true); 
			                                    				// print_r($mydate['mydate'])
			                                    				if($mydate['mydate']!=[]) {
			                                    		?>
			                                    		
			                                    			<div class="panel panel-col-cyan">
							                                    <div class="panel-heading" role="tab" id="headingFive_<?php echo $wee; ?>">
																	<h4 class="panel-title">
							                                            <a role="button" data-toggle="collapse" data-parent="#accordion_<?php echo $size ?>y" href="#collapseFive_<?php echo $wee.$size; ?>y" aria-expanded="true" aria-controls="collapse">
							                                                Date - {{date('d-m-Y',strtotime($mydate['mydate'][0]['date']))}}
							                                            </a>

							                                        </h4>
							                                    </div>
							                                    <div id="collapseFive_<?php echo $wee.$size; ?>y" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive_<?php echo $wee; ?>">
							                                    	
							                                    	<div class="table-responsive">
										                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
										                                    <thead>
										                                        <tr>
										                                            <th>EMP ID</th>
										                                            <th>EMP NAME</th>
										                                            <th>INITIAL IN TIME</th>
										                                            <th>FINAL OUT TIME</th>
										                                            <th>WORKING HOUR</th>
										                                            <th>EXPAND</th>
										                                            
										                                        </tr>
										                                    </thead>
																			<tbody> 
																				<?php
											                                    	$j=0;
											                                    	foreach ($mydate['mydate'] as $mynewdate) { ?>
											                                    		
											                                    		<?php
												                    					$inout = (array) json_decode($mynewdate['attendance'],true);
												                    					?>
											                                    	<tr>
											                                    		<td>{{$mynewdate['emp_id']}}</td>
											                                    		<td>{{$mynewdate['title']}} {{$mynewdate['first_name']}} {{$mynewdate['middle_name']}} {{$mynewdate['last_name']}}</td>
											                                    		<td>{{$mynewdate['initial_in']}}</td>
											                                    		<td>{{$mynewdate['final_out']}}</td>
											                            				<td>{{$mynewdate['total_working_hour']}}</td>
											                            				<td colspan="2"><a href="javascript:void(0);" class="btn btn-xs btn-info myadd1" ><i class="material-icons mycheck">add</i></a>
												                    					
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
											                            				</td>
											                                    	</tr>
											                                    		<?php
											                                    		$j++;
											                                    	}
											                                    	?>
																			</tbody>
																		</table>
																	</div>                                   	
							                                    </div>
							                                </div>
			                                    		
			                                    		<?php } $wee++; } ?>
			                                    		</div>
			                                    	</div>	                                    	
			                                    </div>
			                                </div>

                                    	

                                    	<?php $size--; } ?>
                                    	</div> 
                                    	</div>
                                    </div>

                                </div>

                                <?php $i++;}
                        	?>
                               
                            
                                
                        	</div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>


    	<div class="modal fade" id="myinoutmodal" tabindex="-1" role="dialog">
            <div class="modal-dialog " role="document">
                <div class="modal-content">
                    <div class="modal-header bg-pink">
                        <h4 class="modal-title" id="defaultModalLabel">DAY REPORT</h4>
                    </div>
					
                    <div class="modal-body">
					
                    </div>
                    <div class="modal-footer">
						<button type="button" class="btn bg-blue-grey  waves-effect" data-dismiss="modal">CLOSE</button>
					</div>
                </div>
            </div>
        </div>

    </section>
<style type="text/css">
    .table-responsive
    {
	    min-height: .01%;
	    overflow-x: initial;
	}
	.myinouttable
	{
		display: none;
	}
</style>
@endsection
@section('jquery')
	<link href="plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
	<script src="plugins/jquery-datatable/jquery.dataTables.js"></script>
	<script src="plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
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
		$(document).ready(function(){

			$("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
			$("#success-alert").slideUp(500);
			});

			$(document).on('click','.myadd1',function(){	
				$('.modal-body').html($(this).closest('tr').find('.myinouttable').html());
        		$('#myinoutmodal').modal('show');
			});

			
			/*$('.myadd1').click(function(){
				if($(this).closest('tr').find('div.myinouttable').is(':visible'))
					$(this).closest('tr').find('div.myinouttable').slideUp();
				else
					$(this).closest('tr').find('div.myinouttable').slideDown();
			});*/
		});
	</script>
@endsection