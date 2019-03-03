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
                            Account Setting
                            </h4>
                        
                        </div>
                        <form  method="POST" action="ware_acc/submit" enctype="multipart/form-data">
                            	@csrf
                        <div class="body">
                            <div class="row clearfix">
                             <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                        <label for="username">Username</label>
                                      
                                            <input type="text" name="username" class="form-control" value="{{ $username }}" required>
                                           <span class="text-danger">{{ $errors->first('username') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                          
                            <div class="row clearfix">
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                        <label for="password">Password</label>
                                        <input type="hidden" name="uid" class="form-control" value="{{$userid}}">
                                            <input type="password" name="password" class="form-control" value="{{ old('password') }}" placeholder="Password" required>
                                             <span class="text-danger">{{ $errors->first('password') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                        <label for="confirm_password">Confirm Password</label>
                                            <input type="password" name="conpass" class="form-control" value="{{ old('conpass') }}" placeholder="Confirm Password" required>
                                            <span class="text-danger">{{ $errors->first('confirm_password') }}</span>
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

</script>
 @endsection