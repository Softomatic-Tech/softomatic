@extends('layouts.ware-app')
@section('content')
<style>
            #trans_signature{
                width: 100%;
                height: 100px;
                border: 1px solid black;
            }
            
            #gate_signature{
                width: 100%;
                height: 100px;
                border: 1px solid black;
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
							Vendor Master
                            </h4>
                       
                        </div>
                        <form  method="POST" action="vendor/submit" enctype="multipart/form-data">
                            	@csrf
                        <div class="body">
                            <div class="row clearfix">
                             	<div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                        <label for="username">Vendor's Name</label>
                                           <input type="text" name="vendor" placeholder="Vendor's Name" class="form-control"  required>
                                           <span class="text-danger">{{ $errors->first('vendor') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                        <label for="vendor_id">Vendor's ID</label>
                                           <input type="text" name="vendor_id" placeholder="Vendor's ID" class="form-control"  required>
                                           <span class="text-danger">{{ $errors->first('vondor_id') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                        <label for="gst_id_no">GST Identification No</label>
                                           <input type="text" name="gst_id_no" placeholder="GST Identification No" class="form-control" value="{{old('gst_id_no')}}" required>
                                           <span class="text-danger">{{ $errors->first('gst_id_no') }}</span>
                                        </div>
                                    </div>
                                </div>
								
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                        <label for="city_name">City Name</label>
                                           <input type="text" name="city_name" placeholder="City Name" class="form-control" value="{{old('city_name')}}" required>
                                           <span class="text-danger">{{ $errors->first('city_name') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="password">Mobile No</label>
                                    		<input type="text" name="mobile_no" class="form-control" value="{{ old('mobile_no') }}" placeholder="Mobile No" required>
                                             <span class="text-danger">{{ $errors->first('mobile_no') }}</span>
                                        </div>
                                    </div>
                                </div>
								<div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                        <label for="confirm_password">Email</label>
                                            <input type="text" name="email" class="form-control" value="{{ old('email') }}" placeholder="Email" required>
                                            <span class="text-danger">{{ $errors->first('email') }}</span>
                                        </div>
                                    </div>
                                </div>
							
    							<div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                        	<label for="confirm_password">Address</label>
                                      		<textarea name="address" id="" class="form-control" rows="5"></textarea>
                                        	<span class="text-danger">{{ $errors->first('address') }}</span>
                                        </div>
                                    </div>
                            	</div>

                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="logistics_applicable">Logistics Applicable</label>
                                            <select name="logistics_applicable" id="" class="form-control">
                                                <option value="yes">Y</option>
                                                <option value="No">N</option>
                                            </select>
                                                <span class="text-danger">{{ $errors->first('logistics_applicable') }}</span>
                                        </div>
                                    </div>
                                </div>

								<div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="password">Bank Name</label>
                                    		<input type="text" name="bank_name" class="form-control" value="{{ old('bank_name') }}" placeholder="Bank Name" required>
                                             <span class="text-danger">{{ $errors->first('bank_name') }}</span>
                                        </div>
                                    </div>
                                </div>

								<div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="password">Account No</label>
                                    		<input type="text" name="acc_no" class="form-control" value="{{ old('acc_no') }}" placeholder="Account No" required>
                                             <span class="text-danger">{{ $errors->first('acc_no') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="pan_no">PAN No</label>
                                            <input type="text" name="pan_no" class="form-control" value="{{ old('pan_no') }}" placeholder="PAN No" required>
                                             <span class="text-danger">{{ $errors->first('pan_no') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="gst_category">GST Category</label>
                                            <input type="text" name="gst_category" class="form-control" value="{{ old('gst_category') }}" placeholder="GST Category" required>
                                            <span class="text-danger">{{ $errors->first('gst_category') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="gst_state_name">GST State Name</label>
                                            <input type="text" name="gst_state_name" class="form-control" value="{{ old('gst_state_name') }}" placeholder="GST State Name" required>
                                            <span class="text-danger">{{ $errors->first('gst_state_name') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label for="gst_state_code">GST State Code</label>
                                            <input type="text" name="gst_state_code" class="form-control" value="{{ old('gst_state_code') }}" placeholder="GST State Code" required>
                                            <span class="text-danger">{{ $errors->first('gst_state_code') }}</span>
                                        </div>
                                    </div>
                                </div>

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
						<div class="header">
                            <h2>
                               Department List
                            </h2>
                          </div>
                        <div class="body table-responsive">
                            <table class="table table-striped">
    <thead>
        <th> Vendor</th><th>Mobile No</th><th></th>
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
                </div>
                    </div>
                  
					
            </div>
            <!-- #END# Multi Column -->
        </div>
   @endsection
 @section('jquery')	
<script>

</script>
 @endsection