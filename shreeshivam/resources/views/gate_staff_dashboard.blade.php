@extends('layouts.ware-app')
@section('content')
<style>
            #trans_signature{
                width: 100%;
                height: 100px;
                border: 1px solid #ccc;
                padding: 6px 12px;
             
            }
            
            #gate_signature{
                width: 100%;
                height: 100px; 
                border: 1px solid #ccc;
            }
        </style>
<meta name="csrf-token" content="{{ csrf_token() }}">
<section class="content" style="margin: 60px 20px 0px 20px;">
        <div class="">
           <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h4>
                             Parcel Detail Form

                            </h4>
                        
                        </div>
                        <form  method="POST" action="get_form/submit" enctype="multipart/form-data">
                            	@csrf
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="date" class="form-control  datepicker" value="{{ old('date') }}" required>
                                            <label class="form-label">Date</label>
                                            <span class="text-danger">{{ $errors->first('date') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="party_name" class="form-control" value="{{ old('party_name') }}" required>
                                            <label class="form-label">Party Name</label>
                                            <span class="text-danger">{{ $errors->first('party_name') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                        <select  name="state" id="state"   class="form-control state" required>
                                            <option value="">--Select State---</option>
                                            @foreach($state as $states)
                                            <option value="{{$states->id}}">{{$states->state}}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger">{{ $errors->first('state') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                        <select  name="city" id="city"   class="form-control city"  required>
                                            <option value="">--Select State First ---</option>
                                        </select>
                                        <span class="text-danger">{{ $errors->first('city') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="transport_name" class="form-control" value="{{ old('transport_name') }}" required>
                                            <label class="form-label">Transport Name</label>
                                            <span class="text-danger">{{ $errors->first('transport_name') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="packet_no" class="form-control" value="{{ old('packet_no') }}" required>
                                            <label class="form-label">Packet No</label>
                                            <span class="text-danger">{{ $errors->first('packet_no') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                        <input type="text" class="form-control" name="parcel_no" value="{{ old('parcel_no') }}" required>
                                        <label class="form-label">Parcel No</label>   
                                        <span class="text-danger">{{ $errors->first('parcel_no') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" class="form-control bill_no" name="bill_no" value="{{ old('bill_no') }}" required>
                                            <label class="form-label">Bill No</label>
                                            <span class="text-danger">{{ $errors->first('bill_no') }}</span>
                                        </div>
                                    </div>
                                </div>
                             </div>   
                             <div class="row clearfix">
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                        <input type="number" class="form-control" name="quantity" value="{{ old('quantity') }}" required>
                                            <label class="form-label">Quantity</label> 
                                            <span class="text-danger">{{ $errors->first('quantity') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                            
                                <div class="col-md-4 col-sm-6">
                                <input type="hidden" class="form-control" id="flag" value="0">
                                    <div class="form-group form-float">
                                        <div class="form-line" >
                                       <label>Transporter's Signature</label> <br>
                                         <div id="trans_signature">
                                        </div><br>
                                        <textarea name="transporter_sign" id="tsvalue" cols="30" rows="10" style="display:none"></textarea>
                                    
                                                                 </div>
                                     
                                    </div>
                                </div>
                                </div>
                                <div class="row clearfix">
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line" >
                                       <label>Gate Staff's Signature</label> <br>
                                       <div id="gate_signature">
                                        </div><br>
                                        <textarea name="gate_staff_sign" id="gsvalue" cols="30" rows="10" style="display:none"></textarea>
                                        <button type="button" name="submit" id='click'>OK</button> 
                                        </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                        <button autocomplete="none" type="submit" id="submit" name="submit" class="btn btn-success btn-lg">Submit</button> 
                                        </div>
                                    </div>
                                </div>
                             </div>   
                        </div>
                    </div>
                    </form>
                </div>
            </div>
            <!-- #END# Multi Column -->
        </div>
   @endsection
   @section('jquery')	
   <script>
$( function() {
    $( ".datepicker" ).datepicker();
  } );
</script>
<script>
$(document).ready(function(){
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        $('.state').change(function(){
            var state=$(this).val();
            $.ajax({
                        type:'POST',
                        datatype: 'text',
                        url:'getcity',
                        data:{state:state},
                        success:function(data){
                        
                        $('.city').html(data);
                        
                        }
                        });
                    });
        var $sigdiv = $("#trans_signature").jSignature({'UndoButton':true});
        var $sigdiv1 = $("#gate_signature").jSignature({'UndoButton':true});
            $('#click').click(function(){
                // Get response of type image
                $('#flag').val(1)
                var data = $sigdiv.jSignature('getData', 'image');
                var data1 = $sigdiv1.jSignature('getData', 'image');
                $('#tsvalue').val(data);
                $('#gsvalue').val(data1); 
                });
                $('#submit').click(function(){
                  
                    var flag=$('#flag').val();
                    if(flag!=1)
                    {
                        alert('please click on ok button');
                        return false;
                    }
                    else
                    {

                        return true;


                    }
                    
                });      

 });            
</script>


   <script src="plugins/jsignature/js/jSignature.min.js"></script>
    <script src="plugins/jsignature/js/modernizr.js"></script>
   @endsection