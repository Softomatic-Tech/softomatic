@extends('layouts.mobile_app')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
		<div class="phone-box wrap push">
			<div class="parker" id="service">
				<div class="menu-notify2">
					<div class="profile-left">
						<a href="#menu" class="menu-link"><img src="images/menu.png" ></a>
					</div>
					<div class="Profile-mid">
						<img src="./images/shri_shivam_logo_m.jpg" height="22px">
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="services">
					<h3>TARGET</h3>

				
        <div class="container-fluid">
           <!--  <div class="block-header"> -->
                 <div class="flash-message" id="success-alert">
		            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
				      @if(Session::has('alert-' . $msg))
						<p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
				      @endif
				    @endforeach
				</div>
           <!--  </div> -->
           @if(stripos(Session::get('designation'),'team leader')!==false)
           <div class="row clearfix myrow">           
            <div class="col-xs-12">
           <div class="col-xs-6">
               <h4><b>Team Target : {{$team_target}}</b></h4>
           </div>
           <div class="col-xs-6">
               <h4><b>Team Sale : {{$team_actual_sale}}</b></h4>
           </div>
            </div>
        </div>

        @else
        
         <div class="row clearfix myrow">
            
            <div class="col-xs-12">
           <div class="col-xs-6">
               <h4><b>Target : {{$target}}</b></h4>
               <input type="hidden" name="mytarget" id="mytarget" value="{{$target}}">
           </div>
           <div class="col-xs-6">
               <h4><b>Sale : {{$sale}}</b></h4>
           </div>
            </div>
            </div>

            
            <div class="row clearfix myrow">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover js-basic-example dataTable myemptable">
                    <thead>
                        <tr class="bg-orange">
                            <th>#</th>
                            <th>Aging Title</th>
                             <th>Items</th>
                            <!--  <th>Aging (%)</th>
 -->                            <th>Net Amount</th>
                            <th>Bonus</th>
                          </tr>
                    </thead>
                    <tbody><?php $count=1; ?>
                        @foreach($bonus as $bon)
                        <tr>
                            <td>{{$count}}</td>
                            <td>{{$bon['aging_title']}}</td>
                            <td>{{$bon['items']}}</td>
                            <!-- <td>{{$bon['aging_per']}}</td> -->
                            <td>{{$bon['net_amount']}}</td>
                            <td>{{$bon['aging_bonus']}}</td>
                        </tr>
                        <?php $count++; ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif 

        <div class="row clearfix myrow">
            <div class="col-xs-12 row">
                <h4>INCENTIVE PREDICTOR</h4>
            </div>

            @if(stripos(Session::get('designation'),'fashion consultant')!==false)
            <div class="mydivrow">
            <h5><u>Aging Incentive</u></h5>
            <form id="predictor" method="POST" action="predict" enctype="multipart/form-data">
                <div class="form-group row">
                    <!-- <div class="col-xs-3">
                        <label>Aging</label>
                    </div> -->
                    <div class="col-xs-12">
                        <select class="form-control" name="aging_title" id="aging_title" placeholder="Aging Title" >
                            <option value=''>Select Aging Category</option>
                            @foreach($aging_master as $master)
                                <option value="{{$master->id}}">{{$master->aging_title}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <!-- <div class="col-xs-3">
                        <label>Amount</label>
                    </div> -->
                    <div class="col-xs-12">
                        <input type="number" name="amount" id="amount" placeholder="Amount" class="form-control">
                    </div>
                </div>

                <div class="form-group row" id="incentive">
                    <div class="col-xs-4">
                        <label>Incentive : </label>
                    </div>
                    <div class="col-xs-2">
                       <div id="predicted_bonus"></div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-xs-4 col-xs-offset-4">
                        <input type="button" name="submit" id="submit" placeholder="" class="form-control btn btn-warning" value="Predict">
                    </div>
                </div>
            </form>
        </div>
        @endif

        <div class="mydivrow">           
             <h5><u>Regular Incentive</u></h5>
           
            <form id="predictor" method="POST" action="predict" enctype="multipart/form-data">
                 <input type="hidden" name="team_target" id="team_target" value="{{$team_target}}">
                <div class="form-group row">
                    <!-- <div class="col-xs-3">
                        <label>Amount</label>
                    </div> -->
                    <div class="col-xs-12">
                        <input type="number" name="amount" id="regular_amount" placeholder="Amount" class="form-control">
                    </div>
                </div>

                <div class="form-group row" id="regular_incentive">
                    <div class="col-xs-4">
                        <label>Incentive : </label>
                    </div>
                    <div class="col-xs-2">
                       <div id="regular_predicted_bonus"></div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-xs-4 col-xs-offset-4">
                        <input type="button" name="submit" id="regular_submit" placeholder="" class="form-control btn btn-warning" value="Predict">
                    </div>
                </div>
            </form>
            
           

        </div>
        </div>


            <style type="text/css">
                .accept,.reject,.showreason
                {
                    margin:2.5px;
                }
                .bg-orange {
				    background-color: #FF9800 !important;
				    color: #fff;
				}
                .myrow
                {
                    border-top : 1px solid #e4e4e4;
                }
                .mydivrow
                {
                    border-bottom : 1px solid #e4e4e4;
                    /*background: #efefef;*/
                    padding: 10px 0 0 10px;
                }
                .mydivrow h5
                {
                    padding: 10px;
                }
                .myrow h4
                {
                    padding: 20px;
                }
            </style>
        </div>
    </div>

@endsection
@section('jquery')

<link href="plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
<link href="plugins/DatePickerBootstrap/css/datepicker.css" rel="stylesheet" />
<script src="plugins/DatePickerBootstrap/js/bootstrap-datepicker.js"></script>

    <script type="text/javascript">
    	$(document).ready(function(){
            $('#incentive').hide();
            $('#regular_incentive').hide();
             $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

    		$('.datepicker').datepicker().on('changeDate', function (ev) {
			    $('.dropdown-menu').hide();
			});

   //  		$("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
			// 	$("#success-alert").slideUp(500);
			// });

            $('#amount').on('focus',function(){
                $('#incentive').slideUp();
            });

            $('#regular_amount').on('focus',function(){
                $('#regular_incentive').slideUp();
            });

             $('#submit').click(function(){
                if(($('#aging_title').val()!='') && ($('#amount').val()!=''))
                {
                    $.ajax({
                    type:'POST',
                    datatype: 'json',
                    url:'predict-incentive',
                    data:{aging_title:$('#aging_title').val(),amount: $('#amount').val()},
                    success:function(data){
                        $('#predicted_bonus').empty().html(data);
                        $('#incentive').slideDown().show();
                    }
                    });
                }
                else
                {   
                    $('#incentive').slideUp().hide();
                    if($('#aging_title').val()=='')
                    {
                        $('#aging_title').focus();
                    }
                    else
                        if($('#amount').val()=='')
                    {
                        $('#amount').focus()
                    }
                }
             });

             $('#regular_submit').click(function(){
                if($('#regular_amount').val()!='')
                {
                    $.ajax({
                    type:'POST',
                    datatype: 'json',
                    url:'predict-regular',
                    data:{amount: $('#regular_amount').val(),team_target : $('#team_target').val(),target : $('#mytarget').val()},
                    success:function(data){
                        $('#regular_predicted_bonus').empty().html(data);
                        $('#regular_incentive').slideDown().show();
                    }
                    });
                }
                else
                {   
                    $('#regular_incentive').slideUp().hide();
                    
                        $('#regular_amount').focus()
                    
                }
             });
            
    	});

        function refreshTable()
          {
            //$('#joblisttable').fadeOut();
            $('#myleavetable').load('leavetable', function() {
            //$('#joblisttable').fadeIn();
            });
          }
    </script>
@endsection