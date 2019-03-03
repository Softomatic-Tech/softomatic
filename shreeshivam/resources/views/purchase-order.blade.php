@extends('layouts.ware-app')
@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
<section class="content" style="margin: 60px 20px 0px 20px;">
        <div class="">
           <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h4>
							    Purchase Order
                            </h4>
                        </div>
                        <form  method="POST" action="purchase/submit" enctype="multipart/form-data">
                            	@csrf
                        <div class="body">
                            <div class="row clearfix">
                             	<div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                        <label for="username">Vendor</label>
                                           <select type="text" name="vendor" id="vendor_name" placeholder="Vendor's Name" class="form-control select2"  required>
                                            <option>---Select---</option>
                                            @foreach($vendor as $ven)
                                            <option value="{{$ven->id}}">{{$ven->vendor}}<!-- {{$ven->id}}_{{$ven->vendor_id}}_{{$ven->city_name}}_{{$ven->mobile_no}} --></option>
                                            @endforeach
                                           </select>
                                           <span class="text-danger">{{ $errors->first('vendor_name') }}</span>
                                        </div>
                                    </div>
                                </div>
								
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="password">Owner Name</label>
                                    		<input type="text" name="owner_name" class="form-control" value="{{ old('mobile_no') }}" placeholder="Mobile No" required>
                                             <span class="text-danger">{{ $errors->first('mobile_no') }}</span>
                                        </div>
                                    </div>
                                </div>
								<div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                        <label for="confirm_password">Office Address</label>
                                            <input type="text" name="office_address" class="form-control" value="{{ old('email') }}" placeholder="Email" required>
                                            <span class="text-danger">{{ $errors->first('email') }}</span>
                                        </div>
                                    </div>
                                </div>
							
							<div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                        <div class="form-line">
                                        	<label for="confirm_password">GST No.</label>
                                            <input type="text" name="gst_no" class="form-control" value="{{ old('bank_name') }}" placeholder="Bank Name" required>
                                            	<span class="text-danger">{{ $errors->first('address') }}</span>
                                        </div>
                                    </div>
                            	</div>
								<div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="password">Warehouse Address</label>
                                    		<input type="text" name="warehouse_address" class="form-control" value="{{ old('bank_name') }}" placeholder="Bank Name" required>
                                             <span class="text-danger">{{ $errors->first('bank_name') }}</span>
                                        </div>
                                    </div>
                                </div>
								
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="password">Transport Name</label>
                                    		<input type="text" name="transport_name" class="form-control" value="{{ old('transport_name') }}" placeholder="" required>
                                             <span class="text-danger">{{ $errors->first('transport_name') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="password">Transport Phone No.</label>
                                    		<input type="text" name="transport_phone_no" class="form-control" value="{{ old('transport_phone_no') }}" placeholder="" required>
                                             <span class="text-danger">{{ $errors->first('transport_phone_no') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="password">Vendor Warehouse Manager Name</label>
                                    		<input type="text" name="vender_warehouse_manager" class="form-control" value="{{ old('vender_warehouse_manager') }}" placeholder="Vendor Warehouse Manager Name" required>
                                             <span class="text-danger">{{ $errors->first('vender_warehouse_manager') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="password">Vendor Warehouse Manager Phone No.</label>
                                    		<input type="text" name="ware_manager_no" class="form-control" value="{{ old('ware_manager_no') }}" placeholder="Vendor Warehouse Manager Phone No." required>
                                             <span class="text-danger">{{ $errors->first('ware_manager_no') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="password">Vendor Account Excutive Name</label>
                                    		<input type="text" name="vendor_account_exe_name" class="form-control" value="{{ old('vendor_account_exe_name') }}" placeholder="" required>
                                             <span class="text-danger">{{ $errors->first('vendor_account_exe_name') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="password">Vendor Account Excutive Phone No.</label>
                                    		<input type="text" name="vendor_acc_exe_phone" class="form-control" value="{{ old('vendor_acc_exe_phone') }}" placeholder="" required>
                                             <span class="text-danger">{{ $errors->first('vendor_acc_exe_phone') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="password">Margin/Markup</label>
                                    		<input type="text" name="margin" class="form-control" value="{{ old('margin') }}" placeholder="Margin/Markup" required>
                                             <span class="text-danger">{{ $errors->first('margin') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="password">Payment Teams</label>
                        <input type="text" name="payment_team" class="form-control" value="{{ old('payment_team') }}" placeholder="Payment Teams" required>
                                             <span class="text-danger">{{ $errors->first('payment_team') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="password">CD</label>
                                    		<input type="text" name="cd" class="form-control" value="{{ old('cd') }}" placeholder="CD" required>
                                             <span class="text-danger">{{ $errors->first('cd') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="days">Days</label>
                                    		<input type="text" name="days" class="form-control" value="{{ old('days') }}" placeholder="Days" required>
                                             <span class="text-danger">{{ $errors->first('days') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="password">Stock Correction</label>
                                    		<select name="stock_correction" id="" class="form-control">
                                            <option value="yes">Y</option>
                                            <option value="No">N</option>
                                            </select>
                                             <span class="text-danger">{{ $errors->first('stock_correction') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="password">B. Staff</label>
                                    		<select name="b_staff" id="" class="form-control">
                                            <option value="yes">Y</option>
                                            <option value="No">N</option>
                                            </select>
                                             <span class="text-danger">{{ $errors->first('b_staff') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="password">Terms of Trade</label>
                                    		<select name="terms_of_trade" id="" class="form-control">
                                            <option value="BNS">BNS</option>
                                            <option value="SOR">SOR</option>
                                            <option value="Inhouse">Inhouse</option>
                                            <option value="SIS">SIS</option>
                                            </select>
                                             <span class="text-danger">{{ $errors->first('terms_of_trade') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="password">Date of Order</label>
                                    		<input type="text" name="date_of_order" class="form-control">
                                             <span class="text-danger">{{ $errors->first('date_of_order') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="password">Order By</label>
                                    		<input type="text" name="order_by" class="form-control">
                                             <span class="text-danger">{{ $errors->first('order_by') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="password">Time Line of Delivery</label>
                                    		<input type="text" name="time_line_of_delivery" class="form-control">
                                             <span class="text-danger">{{ $errors->first('time_line_of_delivery') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="password">Stock Date</label>
                                    		<input type="text" name="stock_date" class="form-control">
                                             <span class="text-danger">{{ $errors->first('stock_date') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="password">End Date</label>
                                    		<input type="text" name="end_date" class="form-control">
                                             <span class="text-danger">{{ $errors->first('end_date') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="password">PT FIle</label>
                                    		<select name="pt_file" id="" class="form-control">
                                            <option value="yes">Y</option>
                                            <option value="No">N</option>
                                            </select>
                                             <span class="text-danger">{{ $errors->first('pt_file') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="password">Marketing Excutive Name</label>
                                    		<input type="text" name="marketing_exec_name" class="form-control">
                                             <span class="text-danger">{{ $errors->first('marketing_exec_name') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="password">Marketing Excutive No.</label>
                                    		<input type="text" name="marketing_exec_no" class="form-control">
                                             <span class="text-danger">{{ $errors->first('marketing_exec_no') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="password">Weight Of Parcel</label>
                                    		<input type="text" name="weight_of_parcel" class="form-control">
                                             <span class="text-danger">{{ $errors->first('weight_of_parcel') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="password">Status of Parcel Received </label>
                                    		<input type="text" name="status_parcel_received" class="form-control">
                                             <span class="text-danger">{{ $errors->first('status_parcel_received') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="password">Quantity</label>
                                    		<input type="text" name="quantity" class="form-control">
                                             <span class="text-danger">{{ $errors->first('quantity') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="password">Purchase Cost</label>
                                    		<input type="text" name="purchase_cost" class="form-control">
                                             <span class="text-danger">{{ $errors->first('purchase_cost') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="password">Size</label>
                                    		<input type="text" name="size" class="form-control">
                                             <span class="text-danger">{{ $errors->first('size') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="password">Color</label>
                                    		<input type="text" name="color" class="form-control">
                                             <span class="text-danger">{{ $errors->first('color') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="password">Product</label>
                                    		<input type="text" name="product" class="form-control">
                                             <span class="text-danger">{{ $errors->first('product') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="password">Remark</label>
                                    		<input type="text" name="remark" class="form-control">
                                             <span class="text-danger">{{ $errors->first('remark') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="container">
                                        
                                        <div class="col-md-6">
                                            <div class="text-center">
                                        <div id="camera_info"></div>
                                    <div id="camera"></div><br>
                                    <button type="button" id="take_snapshots" class="btn btn-success btn-sm">Take Snapshots</button>
                                    </div>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Image</th><th>Image Name</th>
                                                </tr>
                                            </thead>
                                            <tbody id="imagelist">
                                            
                                            </tbody>
                                        </table>
                                        </div>
                            </div> <!-- /container -->
  </body>
</html>
<style>
#camera {
 
  height: 500px;
}

</style>
      
                           <div class="col-md-4 col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                        <button  type="submit" id="submit" name="submit" class="btn btn-success btn-lg">Submit</button> 
                                        </div>
                                    </div>
                                </div>
                             </div>   
                        </div>
						</form>
						<!-- <div class="header">
                            <h2>
                               Department List
                            </h2>
                          </div> -->
                        <!-- <div class="body table-responsive">
                            <table class="table table-striped">
    <thead>
        <th> Vender</th><th>Mobile No</th><th>Email</th>
    </thead>
    
    <tbody>
        <tr data-toggle="collapse" data-target="#accordion" class="clickable">
            <td>Some Stuff</td>
            <td>Some more stuff</td>
            <td>And some more</td>
        </tr>
        <tr>
            <td colspan="3">
                <div id="accordion" class="collapse">Hidden by default</div>
            </td>
        </tr>
    </tbody>
</table>
                </div> -->
                    </div>
                  
					
            </div>
            <!-- #END# Multi Column -->
        </div>
   @endsection
 @section('jquery')	

 <script>
    var options = {
      shutter_ogg_url: "plugins/jpeg_camera/shutter.ogg",
      shutter_mp3_url: "plugins/jpeg_camera/shutter.mp3",
      swf_url: "plugins/jpeg_camera/jpeg_camera.swf",
    };
    var camera = new JpegCamera("#camera", options);
  
  $('#take_snapshots').click(function(){
    var snapshot = camera.capture();
    snapshot.show();
    
    snapshot.upload({api_url: "action.php"}).done(function(response) {
$('#imagelist').prepend("<tr><td><img src='"+response+"' width='100px' height='100px'></td><td>"+response+"</td></tr>");
}).fail(function(response) {
  alert("Upload failed with status " + response);
});
})

function done(){
    $('#snapshots').html("uploaded");
}

$(document).ready(function(){
    /*$('#vendor_name').change(function(){
        var detail = $(this).val().split('_');
        alert(detail[1]);
    });*/
});
</script>
 @endsection