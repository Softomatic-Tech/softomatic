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
                <h2>Zone Wise Sales Analysis</h2>
            </div>
            {{-- {{ $expenses->expense_title }} --}}
             <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <form action="zone/barchart" method="POST">
                            {{ csrf_field() }}
                        <div class="header">  
                             <div class="col-sm-2" align="center">
                                    <label> <h2> Select Branches </h2></label>
                            </div>
                            <div class="col-sm-4"  style="margin-top:-15px !important;">                                                                                   
                            <select class="form-control team_member select2 " multiple="multiple" name="zone" id="branch" placeholder="Branch" required >
                                 <option>Select</option>
                                    @foreach($branch as $branches) 				           	
                                        <option value="{{$branches->id}}" > {{$branches->branch}}</option>
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
                                 <button type="button" name="submit" id="bar" class="btn bg-green waves-effect"><i class="material-icons">save</i><span class="icon-name">Search</span></button>
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
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>

      $(document).ready(function() {
        $(".team_member").select2({
	     placeholder: "Select Branches",
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
   $('#bar').click(function(){   

       temp_title='hello';     
        $.ajax({
                url:"zone/barchart",
                type:'POST',
                data:{zone:$('#branch').val() ,  month:$('#month').val() },
                dataType:'JSON',
                success:function(data)
                {
                //    alert(data);
                   drawchart(data);
                },
                error: function(ts) { alert(ts.responseText) }
               
        });
});

function drawchart(chart_data , chart_title)
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

     var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);
    var options=
    {
        title:'Zone wise comparison of sales',
        titleTextStyle: {
            fontSize:16
        },
        hAxis:{
            title:"Branch",
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
      series: {6: {type: 'line'}}
    };

    var chart = new google.visualization.ComboChart(document.getElementById("chart_div"));
    //  google.visualization.events.addListener(chart, 'ready', function () {
    //     chart_div.innerHTML = '<img src="' + chart.getImageURI() + '">';
    //     console.log(chart_div.innerHTML);
    //     });
    chart.draw(data , options);     
}
</script>

@endsection