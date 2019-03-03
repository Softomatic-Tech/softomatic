@extends('layouts.form-app')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <div class="flash-message" id="success-alert">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                    @if(Session::has('alert-' . $msg))
                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                    @endif
                    @endforeach
                </div> 
                <h2>Employee Wise Sales Analysis</h2>
            </div>           
             <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <form action="employee/barchart" method="POST">
                            {{ csrf_field() }}
                        <div class="header">  
                            <div class="col-sm-2" align="center">
                                <label> <h2> Select Employee </h2></label>
                            </div>
                            <div class="col-sm-4"  style="margin-top:-15px !important;">                                                           
                            <select class="form-control team_member select2 " multiple="multiple" name="emp_id" id="emp_id" placeholder="Branch" required >
                                 <option>Select</option>
                                    @foreach($emp as $emps) 				           	
                                        <option value="{{$emps->id}}" > {{$emps->title.' '.$emps->first_name.' '.$emps->middle_name.' '.$emps->last_name.' '.$emps->genesis_ledger_id}}</option>
                                    @endforeach                          
                           </select>                      
                            </div>
                            <div class="col-sm-4"  style="margin-top:-15px !important;">                                                                                   
                                <select class="form-control months select2 month" multiple="multiple" placeholder="Select Months" id="month" name="month">
                                    <option value=""></option>
                                    <option value="01">Jan</option>
                                    <option value="02">Feb</option>
                                    <option value="03">Mar</option>
                                    <option value="04">Apr</option>
                                    <option value="05">May</option>
                                    <option value="06">Jun</option>
                                    <option value="07">Jul</option>
                                    <option value="08">Aug</option>
                                    <option value="09">Sep</option>
                                    <option value="10">Oct</option>
                                    <option value="11">Nov</option>
                                    <option value="12">Dec</option>
                                </select>                                                 
                            </div>                           
                            <div class="col-sm-2" align="center" style="margin-top: -18px !important;">
                                 <button type="button" name="submit" id="bar1" class="btn bg-green waves-effect"><i class="material-icons">save</i><span class="icon-name">Search</span></button>
                            </div>
                        </div>
                    </form>     
                        <div id="chart_div" style="width: 900px; height: 500px;">
                        </div> 
                    </div>
                </div>
             </div>
@endsection
@section('jquery')
{{-- <script src="https://d3js.org/d3.v5.min.js" charset="utf-8"></script> --}}
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>

      $(document).ready(function() {
        $(".team_member").select2({
	     placeholder: "Select Employee",
	     allowClear: true,
	     multiple: true
	    });   
         $(".months").select2({
	     placeholder: "Select Month",
	     allowClear: true,
	     multiple: true
	    }); 

     google.charts.load('current', {'packages':['corechart']});
     google.charts.setOnLoadCallback();
      });
</script>
<script>
    
 $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
   $('#bar1').click(function(){   

       temp_title='hello';     
        $.ajax({
                url:"employee/barchart",
                type:'POST',
                data:{zone:$('#emp_id').val()  ,  month:$('#month').val() },
                dataType:'JSON',
                success:function(data)
                {
                //    alert(data);
                //  $('#chart_div').html(data);
                   drawChart(data);
                },
                error:function(ts)
                 {
                      alert(ts.responseText) 
                 }
               
        });
});

function drawChart(chart_data , chart_title)
{ 
        var jsonData = chart_data;
        var data = new google.visualization.DataTable();
          var numRows = jsonData.length;
          var numCols = jsonData[0].length;
        
          // in this case the first column is of type 'string'.
          data.addColumn('string', jsonData[0][0]);
        
          // all other columns are of type 'number'.
          for (var i = 1; i < numCols; i++)
            data.addColumn('number', jsonData[0][i]);           
        
          // now add the rows.
          for (var i = 1; i < numRows; i++) 
            data.addRow(jsonData[i]);            

          // redraw the chart.
         
    var options=
    {
        title:'Employee sales comparison',
         titleTextStyle: {
            fontSize:16
        },
        hAxis:{
            title:"name",
            titleTextStyle: {
                fontSize:18,
                fontWeight:600 
            }
        },
        vAxis:{
            title:'Sales'
        },
        width: 1200 ,
        height:600 ,
        vAxis: {
       format: '#' },
      seriesType: 'bars',
      series: {5: {type: 'line'}}
    };
    var chart = new google.visualization.ComboChart(document.getElementById("chart_div"));
   
    chart.draw(data, options);
}
</script>

@endsection