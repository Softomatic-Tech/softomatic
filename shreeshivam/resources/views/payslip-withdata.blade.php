 <div class="block-header">
              
    @if($flag==false) 
				<!--  -->
				    <div class="msg alert alert-success">
				   		<h3><center>Payslip  for this month is already generated please view payslip in <u><a href="payslip-list">Payslip list</a></u></center></h3>
				    </div> 
				<!--  -->
				  
        @if(session('alert-success'))
				    <div class="msg alert alert-success">
				   		<h3><center>{{session('alert-success')}}</center></h3>
				    </div> 
				@endif
				
				@if(session('failure'))
				    <div class="msg alert alert-danger">
				   		<h4>{!! session('failure') !!}</h4>
				    </div> 
				@endif
		@endif
				
 </div>
			  @if($flag==true)
			
            <form action="payslip/submit"  method="POST">
            {{ csrf_field() }} 
            <div class="row clearfix">
			       <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                
                            </h2>
                          </div>
              
					
                        <div class="body table-responsive">
                            <!-- <table class="table table-bordered table-striped table-hover js-basic-example dataTable"> -->
                              <table class="table table-bordered table-striped table-hover dataTable">
                                <thead>
                                     
                                    <tr class="bg-orange">
                                        <th>#</th>
                                        <th>Biometric ID</th>
                                        <th>NAME</th>
                                        <th>DESIGNATION</th>
                                        <th>ACTUAL ATTENDANCE</th>
                                        <th>FULL DAY</th>
                                        <th>HALF DAY</th>
                                        <th>OVER TIME</th>
                                        <th>ATTENDANCE</th>
                                        <th>SALARY</th>
                                        <th>BASIC+DA</th>
                                        <th>A.B.S </th>
                                        <th>HRA</th>
                                        <th>OTHER </th>
                                        <th>INCENTIVE</th>
                                        <th>ARREAR</th>                                        
                                        <th>GROSS SALARY</th>                                        
                                        <th>EPF</th>
                                        <th>ESIC</th>
                                        <th>TDS</th>
                                        <th>EX GRATIA</th>
                                        <th>BONUS</th>
                                        <th>ADVANCE</th>
                                        <th>REMARK</th>  
                                        <th>PROFESSIONAL TAX</th>
                                        <th>REMARK</th>                                      
                                        <th>NET SALARY </th>
                                        <th>OTHER DEDUCTION </th>
                                        <th>OTHER ADDITION </th>
                                        <th>REMARK</th>
                                        <th>NET PAYABLE </th>
                                       <!--  <th>Action</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                               <?php $i=1; $val=0;?> 
                             
                               @foreach($employee as $emp)
                               <?php $emp =(array) json_decode($emp,true); ?>
                              <tr>
                              <td>{{$i++}}<!-- <input type="text" value="0" class="flag" name="flag[]"> --></td> 
                              <td>{{$emp['biometric_id']}}</td>
                              <td>{{$emp['emp_name']}}<input type="hidden" value="{{$emp['emp_id'] }}" name="emp_id[]">
                              <input type="hidden" value="{{$emp['incentive_target'] }}" name="incentive_target[]">
                              <input type="hidden" value="{{$emp['month'] }}" name="month[]">
                              </td> 
                              <td>{{$emp['designation']}}<input type="hidden" value="{{$emp['designation_id'] }}" name="designation_id[]"></td>
                              <td>{{$emp['present_days'] }}<input type="hidden" value="{{$emp['present_days'] }}" name="present_days[]"></td> 
                              <td>{{$emp['full_day'] }}</td>
                              <td>{{$emp['half_day'] }}</td>
                              <td>{{$emp['overtime_day'] }} Day {{$emp['overtime_hours'] }} Hours {{$emp['overmin'] }} Mins <input type="hidden" value="{{$emp['overtime_day'] }}" name="overtime_day[]"> <input type="hidden" value="{{$emp['overtime_hours'] }}" name="overtime_hours[]"><input type="hidden" value="{{$emp['overmin'] }}" name="overmin[]"></td>
                              <td>{{$emp['attendance'] }}<input type="hidden" value="{{$emp['attendance'] }}" name="attendance[]"></td>
                              <td>{{number_format($emp['salary'],2) }}<input type="hidden" value="{{$emp['salary'] }}" name="salary[]"></td> 
                              <td>{{number_format($emp['basic_salary'],2) }}<input type="hidden" value="{{$emp['basic_salary'] }}" name="basic_salary[]"></td> 
                              <td>{{number_format($emp['attendance_based_salary'],2) }}<input type="hidden" value="{{$emp['attendance_based_salary'] }}" name="attendance_based_sal[]"></td> 
                              <td>{{number_format($emp['hra'],2) }}<input type="hidden" value="{{$emp['hra'] }}" name="hra[]"></td> 
                              <td>{{number_format($emp['other'],2) }}<input type="hidden" value="{{$emp['other'] }}" name="other[]"></td> 
                              <td>{{number_format($emp['incentive']+$emp['aging_bonus'],2) }}<input type="hidden" value="{{$emp['incentive']+$emp['aging_bonus'] }}" name="incentive[]"></td> 
                              <td>{{number_format($emp['arrear'],2) }}<input type="hidden" value="{{$emp['arrear'] }}" name="arrear[]"></td>
                              <td>{{number_format($emp['gross_salary'],2) }}<input type="hidden" value="{{$emp['gross_salary'] }}" name="gross_salary[]"></td>                                
                              <td>{{number_format($emp['epf'],0) }}<input type="hidden" value="{{$emp['epf'] }}" name="epf[]"></td> 
                              <td>{{number_format($emp['esic'],0) }}<input type="hidden" value="{{$emp['esic'] }}" name="esic[]"></td> 
                              <td>{{number_format($emp['tds'],2) }}<input type="hidden" value="{{$emp['tds'] }}" name="tds[]"></td>
                              <td>{{number_format($emp['exgratia'],2) }}<input type="hidden" value="{{$emp['exgratia'] }}" name="exgratia[]"></td> 
                              <td>{{number_format($emp['bonus'],2) }}<input type="hidden" value="{{$emp['bonus'] }}" name="bonus[]"></td> 
                              <td>{{number_format($emp['advance'],2) }}<input type="hidden" value="{{$emp['advance'] }}" name="advance[]"><input type="hidden" value="{{$emp['advance_left'] }}" name="advance_left[]"><input type="hidden" value="{{$emp['advance_id'] }}" name="advance_id[]"></td>
                              <td>{{$emp['advance_remark']}}</td>
                              <td>{{number_format($emp['tax'],2) }}<input type="hidden" value="{{$emp['tax'] }}" name="tax[]"></td>
                              <td>{{$emp['tax_remark']}}</td>
                              <td>{{number_format($emp['net_salary'],2) }}<input type="hidden" value="{{$emp['net_salary'] }}" name="net_salary[]"></td> 
                              <td>                            
                              <input type="number" name="other_ded[]" value="0" class="form-control other_ded" min="0" step="0.01" style="width: 100px">  
                             </td> 
                              <td>                             
                              <input type="number" name="other_add[]" value="0" class="form-control other_add" min="0" step="0.01" style="width: 100px">
                              </td>
                              <td>                             
                              <input type="text" name="remark[]" value="" class="form-control remark" style="width: 200px">
                              </td>  
                              <td ><input type="text" readonly value="{{$emp['net_payable'] }}" name="net_payable[]" class="form-control net_payable" style="width: 100px">
                              <input type="hidden"  value="{{ $emp['net_payable'] }}"  class="net_payable_hide"></td>  
                              <!-- <td ><button type="submit" class="btn btn-xs bg-teal waves-effect edit-modal">
                                    <i class="material-icons">create</i>
                                </button></td> --> 
                            </tr>   
                        @endforeach
                   
                        <input type="hidden" value="<?php echo $i; ?>" id="id">
                        </tbody>
                        
                    </table>
                </div>
               
            </div>
        </div>
	 </div> 
     <div class="row clearfix"> 
     <div class="col-sm-2 ">
                      <div class="form-group">                 
							    <button type="submit" id="salary_find" name="submit" class="btn bg-green waves-effect"><i class="material-icons">save</i><span>FINAL SUBMIT</span></button> 
                      </div>
                    </div>    </div> 
     </form>
@endif
     <!-- <link href="plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
    <script src="plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
    
    <script src="plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/buttons.flash.min.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/buttons.html5.min.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/buttons.print.min.js"></script>
    <script src="js/pages/tables/jquery-datatable.js"></script>     --> 

