@extends('layouts.app')
<style>
.card .body .col-xs-4, .card .body .col-sm-4, .card .body .col-md-4, .card .body .col-lg-4
{
    margin-bottom:5px !important;;
}
.card .body .col-xs-8, .card .body .col-sm-8, .card .body .col-md-8, .card .body .col-lg-8
{
    margin-bottom:5px !important;;
}
.card .body .col-xs-6, .card .body .col-sm-6, .card .body .col-md-6, .card .body .col-lg-6
{
    margin-bottom:5px !important;;
}
.card .body .col-xs-12, .card .body .col-sm-12, .card .body .col-md-12, .card .body .col-lg-12
{
    margin-bottom:5px !important;
}
</style>
@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
       
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box-3 bg-purple hover-zoom-effect">
                        <div class="icon">
                            <i class="material-icons">assignment</i>
                        </div>
                        <div class="content">
                            <div class="text">All Task</div>
                            <div class="number">15</div>
                       </div>
                </div>
                </div>
  
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box-3 bg-teal hover-zoom-effect">
                        <div class="icon">
                            <i class="material-icons">assignment_late</i>
                        </div>
                        <div class="content">
                            <div class="text">Incomplete Task</div>
                            <div class="number">15</div>
                </div>
            </div>
    
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box-3 bg-orange hover-zoom-effect">
                        <div class="icon">
                            <i class="material-icons">assignment_turned_in</i>
                        </div>
                        <div class="content">
                            <div class="text">Completed Task</div>
                            <div class="number">15</div>
                </div>
            </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box-3 bg-blue hover-zoom-effect">
                        <div class="icon">
                            <i class="material-icons">people</i>
                        </div>
                        <div class="content">
                            <div class="text">Total Member</div>
                            <div class="number">15</div>
                </div>
            </div>
           
            </div>
            </div><!-------------------- row End----------------->
              <div class="row clearfix"><!-------------------- row End----------------->
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="card">
                                <div class="header">
                                    <h2>
                                     Gate Staff's All Task
                                      
                                    </h2>
                                </div>
                                <div class="body">
                                    <div class="row clearfix">
                                        <div class="col-xs-12 ol-sm-12 col-md-12 col-lg-12">
                                             <div class="panel-group" id="accordion_1" role="tablist" aria-multiselectable="true">
                                             <?php $i=1;?>
                                                @foreach($get_parcel_detail as $row)
                                                    <div class="panel panel-primary">
                                                            <div class="panel-heading" role="tab" id="headingTwo_{{$i}}">
                                                                <h4 class="panel-title">
                                                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_1" href="#collapseTwo_{{$i}}" aria-expanded="false"
                                                                    aria-controls="collapseTwo_{{$i}}">
                                                                    <i class="material-icons">add</i>Party-Name:-{{$row->party_name}}
                                                                    </a>
                                                                </h4>
                                                            </div>
                                                            <div id="collapseTwo_{{$i}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo_{{$i}}">
                                                                <div class="panel-body">
                                                                <div class="row clearfix">
                                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                                    <label> Date:-</label> <?php echo date("d-m-Y", strtotime($row->date)); ?>
                                                                    </div>
                                                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                                                    <label>Party Name:-</label> {{$row->party_name}}
                                                                    </div>
                                                                    
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                    <label> Transport Name:-</label> {{$row->transport_name}}
                                                                    </div>
                                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                    <label for=""> State:-</label> {{$row->state}}
                                                                    </div>
                                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                <label for="">City:-</label>{{$row->city}}
                                                                    </div>
                                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                    <label for=""> Packet No:-</label> {{$row->packet_no}}
                                                                    </div>
                                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                    <label for="">Parcel No:-</label>  {{$row->parcel_no}}
                                                                    </div>
                                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                    <label for="">Bill No:-</label>  {{$row->bill_no}}
                                                                    </div>
                                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                    <label for="">Quantity:-</label>  {{$row->quantity}}
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                    <label for="">Forwarded By:-</label>  {{$row->first_name}} {{$row->middle_name}} {{$row->last_name}}
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                    <label for="">Transporter's Sign:-</label><img src='data:{{$row->transporter_sign}}' height="50px"/>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                    <label for="">Gate Staff's Sign:-</label><img src='data:{{$row->gate_staff_sign}}' height="50px"/>
                                                                    </div>
                                                                </div>       
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php $i++; ?>
                                                @endforeach    
                </div>
                                        </div>
                                    </div>
                                </div>



                              
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- #END# Basic Examples -->
            </div>

            </div><!-------------------- row End----------------->

    </div><!-------------------- container fluid End----------------->
</section>        
@endsection
@section('jquery')	
@endsection