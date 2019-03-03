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
					<h3>My Documents </h3>

				
        <div class="container-fluid">
           <!--  <div class="block-header"> -->
                
           <!--  </div> -->

            <div class="row clearfix">
               
                	@if(Session::get('role')!='admin')
                    
                        	{!! Form::open(['url' => 'mobile_doc_type/add', 'files' => true]) !!}
                        		@csrf
                        		
                                        <div class="form-group row">
                                                <div class="col-md-4 col-sm-6 col-xs-12" align="left">
                                                    <label for="reason">Document Type</label>
                                                </div>
		                                	<div class="col-md-8 col-sm-6 col-xs-12">  
		                        				    <div class="form-line">                 			
				                        			    <input type="text" name="doc_type" class="form-control" placeholder="Document Name">
				                        			    
				                        		    </div>
			                        		</div>
                                        </div>
			                         <div class="form-group row">
					            			<div class="col-md-4 col-md-offset-4">
					            				<button autocomplete="none" type="submit" name="submit" class="btn btn-success btn-sm col-md-4">Save</button>
				            				</div>
				            		
                        	{!! Form::close() !!}
                    
                   @endif
                   

     </div>
        @endsection