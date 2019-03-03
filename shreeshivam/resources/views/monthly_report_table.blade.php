@if($emp_id!='')
<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover js-exportable dataTable" id="myattendancereport1">
        <thead>
            <tr class="bg-orange">
                <th>#</th>
                <th>Date</th>
                <th>INITIAL IN TIME</th>
                <th>FINAL OUT TIME</th>
                <th>WORKING HOUR</th>
              </tr>
        </thead>
        <tbody>
            <?php $count=1; 
            ?>

            @foreach($report as $rep)
            <?php
            ?>
            <tr>
                <td>{{$count++}}</td>
                <td>{{$rep->date}}</td>
                <td>{{$rep->initial_in}}</td>
                <td>{{$rep->final_out}}</td>
                <td>{{$rep->total_working_hour}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover js-exportable dataTable" id="myattendancereport">
        <thead>
            <tr class="bg-orange">
                <th>#</th>
                <th>NAME</th>
                <th>BIOMETRIC ID</th>
                <th>NO OF PRESENT DAYS</th>
                <th>NO OF OVERTIME DAYS</th>
                <th>TOTAL PRESENT DAYS</th>
              </tr>
        </thead>
        <tbody>
            <?php $count=1; 
            foreach($report as $rep)
            {
            ?>
            <tr>
                <td>{{$count++}}</td>
                <td>{{$rep['name']}}</td>
                <td>{{$rep['biometric_id']}}</td>
                <td>{{$rep['present']}}</td>
                <td>{{$rep['overtime']}} Days @if($rep['overtime_hours']>0) {{$rep['overtime_hours']}} Hours @endif @if($rep['overmin']>0) {{$rep['overmin']}} Mins @endif</td>
                <td>{{$rep['present'] + $rep['overtime']}}</td>
            </tr>
           <?php } ?>
        </tbody>
    </table>
</div>
@endif
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
    

