@extends('layouts.ware-app')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<section class="content" style="margin: 60px 20px 0px 20px;">
      <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h4>
                                 Completed Task
                            </h4>
                        
                        </div>
                        <?php $i=1;?>
                     <div class="panel-group" id="accordion_1" role="tablist" aria-multiselectable="true">
                         @foreach($get_parcel_detail as $row)
                                       <div class="panel panel-primary">
                                            <div class="panel-heading" role="tab" id="headingTwo_{{$i}}">
                                                <h4 class="panel-title">
                                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_1" href="#collapseTwo_{{$i}}" aria-expanded="false"
                                                       aria-controls="collapseTwo_{{$i}}">
                                                       <i class="material-icons">add_circle</i>Party-Name:-{{$row->party_name}}
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseTwo_{{$i}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo_{{$i}}">
                                                <div class="panel-body">
                                                <div class="row clearfix">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                       <label> Date:-</label> {{$row->date}}
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <label>Party Name:-</label> {{$row->party_name}}
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <label> Transport Name:-</label> {{$row->transport_name}}
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                       <label for=""> State:-</label> {{$row->state}}
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                   <label for="">City:-</label>{{$row->city}}
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                       <label for=""> Packet No:-</label> {{$row->packet_no}}
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                       <label for="">Parcel No:-</label>  {{$row->parcel_no}}
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                       <label for="">Bill No:-</label>  {{$row->bill_no}}
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                       <label for="">Quantity:-</label>  {{$row->quantity}}
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
</section>
@endsection
@section('jquery')	
<script>
</script>
 @endsection