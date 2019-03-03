<style>
    table.dataTable thead > tr > th, table.dataTable thead > tr > td {
    padding-right: 14px !important;
}
</style>
      
            <div class="block-header">


                @if(session('success'))
				    <div class="msg alert alert-success">
				   		<h3><center>{{session('success')}}</center></h3>
				    </div> 
				@endif

				@if(session('failure'))
				    <div class="msg alert alert-danger">
				   		<h4>{!! session('failure') !!}</h4>
				    </div> 
				@endif
            </div>

            <div class="row clearfix">
			       <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                
                            </h2>
                          </div>
                        <div class="body table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable js-exportable " id="mainTable">
                                <thead>
                                     
                                    <tr class="bg-orange">
                                        <th>#</th>
                                        <th>NAME</th>
                                        <th>A.A</th>
                                        <th>Atn</th>
                                        <th>O.T</th>
                                        <th>SAL</th>
                                        <th>BASIC+DA</th>
                                        <th>A.B.S </th>
                                        <th>HRA</th>
                                        <th>OTHER </th>
                                        <th>INCENTIVE</th>
                                        <th>ARREAR</th>
                                        <th>GROSS SAL</th>
                                        <th>EPF</th>
                                        <th>ESIC</th>
                                        <th>TDS</th>
                                        <th>EX GRATIA</th>
                                        <th>BONUS</th>
                                        <th>ADVANCE</th>                                      
                                        <th>PROF TAX</th>
                                        <th>NET SALARY </th>
                                        <th>OTHER DEDUCT </th>
                                        <th>OTHER ADD </th>
                                        <th>REMARK</th>
                                        <th>NET PAYABLE </th>
                                       
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $salary =(array) json_decode($salary,true); ?>
                                <?php   $i=1; ?> 
                                @foreach($salary as $sal) 
                                <tr>
                                    <td>{{$i++}}</td> 
                                    <td>{{$sal['first_name']}} {{$sal['middle_name']}} {{$sal['last_name']}}</td>
                                    <td>{{$sal['actual_attendance']}}</td>
                                    <td>{{$sal['attendance']}}</td>
                                    <td>{{$sal['over_time']}}</td>
                                    <td>{{$sal['salary']}}</td>
                                    <td>{{$sal['basic']}}</td>
                                    <td>{{$sal['attendance_based_sal']}}</td>
                                    <td>{{$sal['hra']}}</td>
                                    <td>{{$sal['other']}}</td>
                                    <td>{{$sal['incentive']}}</td>
                                    <td>{{$sal['arrear']}}</td>
                                    <td>{{$sal['gross_salary']}}</td>
                                    <td>{{$sal['epf']}}</td>
                                    <td>{{$sal['esic']}}</td>
                                    <td>{{$sal['tds']}}</td>
                                    <td>{{$sal['exgratia']}}</td>
                                    <td>{{$sal['bonus']}}</td>
                                    <td>{{$sal['advance']}}</td>
                                    <td>{{$sal['professional_tax']}}</td>
                                    <td>{{$sal['net_salary']}}</td>
                                    <td>{{$sal['other_deduction']}}</td>
                                    <td>{{$sal['other_addition']}}</td>
                                    <td>{{$sal['remark']}}</td>
                                    <td>{{$sal['net_payable']}}</td>
                                     
                               
                            </tr>   
                         @endforeach 
                        </tbody>
                        
                    </table>
                </div>
               
            </div>
        </div>
				
    </div>  
    <link href="plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
    <script src="plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
    <script src="plugins/editable-table/mindmup-editabletable.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/buttons.flash.min.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/buttons.html5.min.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/buttons.print.min.js"></script>
    <script src="js/pages/tables/jquery-datatable.js"></script>     

