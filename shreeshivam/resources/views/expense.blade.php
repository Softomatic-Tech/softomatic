@extends('layouts.form-app')

@section('content')
<meta http-equiv="content-type" content="text/html; charset=UTF8">
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
                <h2>EXPENSE</h2>
            </div>
            {{-- {{ $expenses->expense_title }} --}}
             <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>Expense</h2>
                          
                        </div>
                        {{-- <svg class="bar-chart"></svg> --}}
                        <svg class="line-chart"> </svg>
                    </div>
                </div>
             </div>
			 
   
@endsection
@section('jquery')
<script src="https://d3js.org/d3.v5.min.js" charset="utf-8"></script>

<script>
var api = 'https://api.coindesk.com/v1/bpi/historical/close.json?start=2017-12-31&end=2018-04-01';

document.addEventListener("DOMContentLoaded", function(event) {
   fetch(api)
     .then(function(response) { return response.json(); })
     .then(function(data) { 
         //DO SOMETHING WITH DATA  
     })
});

    
.then( function(data) { 
         var parsedData = parseData(data);
 })

function parseData(data) {
   var arr = [];
   for (var i in data.bpi) {
      arr.push(
         {
            date: new Date(i), //date
            value: +data.bpi[i] //convert string to number
         });
   }
   return arr;
}

.then(function(data) { 
   var parsedData = parseData(data);
   drawChart(parsedData);
})

function drawChart(data) {
   var svgWidth = 600, svgHeight = 400;
   var margin = { top: 20, right: 20, bottom: 30, left: 50 };
   var width = svgWidth - margin.left - margin.right;
   var height = svgHeight - margin.top - margin.bottom;
   var svg = d3.select('svg')
     .attr("width", svgWidth)
     .attr("height", svgHeight);

var g = svg.append("g")
   .attr("transform", 
      "translate(" + margin.left + "," + margin.top + ")"
   );
}
   var x = d3.scaleTime().rangeRound([0, width]);
   var y = d3.scaleLinear().rangeRound([height, 0]);

   var line = d3.line()
   .x(function(d) { return x(d.date)})
   .y(function(d) { return y(d.value)})
   x.domain(d3.extent(data, function(d) { return d.date }));
   y.domain(d3.extent(data, function(d) { return d.value }));


   g.append("g")
   .attr("transform", "translate(0," + height + ")")
   .call(d3.axisBottom(x))
   .select(".domain")
   .remove();

   g.append("g")
   .call(d3.axisLeft(y))
   .append("text")
   .attr("fill", "#000")
   .attr("transform", "rotate(-90)")
   .attr("y", 6)
   .attr("dy", "0.71em")
   .attr("text-anchor", "end")
   .text("Price ($)");

    g.append("path")
    .datum(data)
    .attr("fill", "none")
    .attr("stroke", "steelblue")
    .attr("stroke-linejoin", "round")
    .attr("stroke-linecap", "round")
    .attr("stroke-width", 1.5)
    .attr("d", line);
// bar chart

    // var dataset=[100,140,220,80,45,90];
    // var svgWidth=600 , svgHeight=300 , barPadding=5;
    // var barWidth =svgWidth/dataset.lenght ;

    //  var svg=d3.select('svg')
    //     .attr("width" , svgWidth)
    //     .attr("height" , svgHeight);

    // var barChart=svg.selectAll("rect")    
    //     .data(dataset)
    //     .enter()
    //     .append("rect")
    //     .attr("y", function (d){
    //         return svgHeight - d
    //     })
    //     .attr("height" , function(d){
    //         return d;
    //     })
    //     .attr("width" ,100 - barPadding)
    //     .attr("transform" , function(d , i ){
    //         var translate =[ 100 * i , 0];
    //         return "translate("+ translate +")"; 
    //     });

// bar chart end     

    // d3.select('h1').style('color','blue')
    // .attr('class','heading')
    // .text('updated h1 tag');

    // var dataset=[1,2,3,4,5,6];
    // d3.select('h2')
    // .selectAll('p')
    // .data(dataset)
    // .enter()
    // .append('p')
    // .text( function(d) { return d ; });
</script>
@endsection