@extends('layouts.form-app')
@section('content')
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
                <h2>COLLAPSE</h2>
            </div>
            <!-- Example -->
            <div class="row clearfix">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Generate
                                <small>EPF ,ESIC & MINIMUM WAGES </small>
                            </h2>
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                   <a class="btn bg-pink waves-effect m-b-15" role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="false"
                                     aria-controls="collapseExample">
                                    Click
                                    </a>
                                    
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <div class="collapse" id="collapseExample">
                                <div class="well">
                                    <form method="POST" action="epf_master/submit">
                                    {{ csrf_field() }}
                                    <div class="row clearfix">
                                        <div class="col-sm-4 col-xs-10" align="center">
								            <label> Select Location </label>
								        </div>
							            <div class="col-sm-6" >
                                            <div class="form-line">                        
                                                <select class="form-control show-tick select2" placeholder="Shreeshivam Branch" id="branch_location_id" name="location">
                                                    <option>Select</option>
                                                     @foreach($branch as $branches) 				           	
									                    <option value="{{$branches->id}}" > {{$branches->branch}}</option>
						                             @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row clearfix">
                                        <div class="col-sm-4 col-xs-10" align="center">
								            <label> EPF </label>
								        </div>
							            <div class="col-sm-6" >
                                            <div class="form-line">                        
                                            <input type="text"  class="form-control" name="epf" placeholder="Enter value in %">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row clearfix">
                                        <div class="col-sm-4 col-xs-10" align="center">
								            <label> ESIC </label>
								        </div>
							            <div class="col-sm-6" >
                                            <div class="form-line">                        
                                            <input type="text"  class="form-control" name="esic" placeholder="Enter value in %">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row clearfix">
                                        <div class="col-sm-4 col-xs-10" align="center">
								            <label> Minimum Wages </label>
								        </div>
							            <div class="col-sm-6" >
                                            <div class="form-line">                        
                                            <input type="text"  class="form-control" name="minimum_wage" placeholder="Enter value ">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row clearfix">
                                        <div class="col-sm-4 col-xs-12" align="center">
                                        <div class="form-group">                 
                                                <button type="submit"  name="submit" class="btn bg-green waves-effect"><i class="material-icons">save</i><span class="icon-name">submit</span></button> 
                                        </div>
                                    </div>  
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="card">
                        <div class="header bg-orange">
                            <h2>
                               Lists <small>OF EPF ,ESIC & MINIMUM WAGES</small>
                            </h2>
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                   <a class="btn bg-red waves-effect m-b-15" role="button" data-toggle="collapse" href="#editcollapseExample11" aria-expanded="false"
                                     aria-controls="collapseExample">
                                    Click
                                    </a>
                                    
                                </li>
                            </ul>
                        </div>
                       
                           <div class="body table-responsive">
                            <div class="collapse" id="editcollapseExample11">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable myemptable">
                                <thead>
                                     
                                    <tr class="bg-orange">
                                        <th>#</th>
                                        <th>Zone</th>
                                        <th>Epf</th>
                                        <th>Esic</th>
                                        <th>Minimum Wages</th>
                                        <th>Action </th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php  	$i=1; ?>

                             @foreach($epf_master as $masters)
								   <tr>
                                     {{ csrf_field() }}
                                    <th scope="row">{{ $i++ }}</th>
                                    <td>{{$masters->branch}}</td>
                                    <td>{{$masters->epf}}</td>
                                    <td>{{$masters->esic}}</td>
                                    <td>{{$masters->minimum_wages}}</td>							                                     
                                    <td>
									<button type="submit" data-toggle="modal"  id="{{ $masters->id }}" data-target="#updateModal" data-id="{{$masters->id}}" 
									 data-branch="{{$masters->branch_id}}" data-epf="{{$masters->epf}}" data-esic="{{$masters->esic}}" data-minimumwages="{{$masters->minimum_wages}}"	class="btn bg-teal waves-effect edit-modal btn-xs"  >
                                    <i class="material-icons">edit</i>   
									</button>			                               		
	                            </td>                                  
                                        </tr>   
                                    @endforeach   
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="updateModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-pink">
                            <h4 class="modal-title" id="uModalLabel">Edit EPF</h4>
                        </div>
						
                        <div class="modal-body">
						<form action="epf_update/submit"  method="POST">
						{{ csrf_field() }}
						<div class="row clearfix">
						<div class="col-sm-3" align="right">
							<label> Zone</label>
						</div>
                               <div class="col-sm-8">
                                   <input type="hidden" id="fid" name="id"  class="form-control" placeholder="Department Name" required/>
                                    <div class="form-line">                        
                                                <select class="form-control show-tick select2" placeholder="Shreeshivam Branch" id="branch" name="branch">
                                                    <option>Select</option>
                                                     @foreach($branch as $branches) 				           	
									                    <option value="{{$branches->id}}" > {{$branches->branch}}</option>
						                             @endforeach
                                                </select>
                                            </div>
                                </div>
                        </div><br>
                        <div class="row clearfix">
						<div class="col-sm-3" align="right">
							<label> EPF</label>
						</div>
                               <div class="col-sm-8">
                                    <div class="form-line">
                                        <input type="text" id="epf" name="epf"  class="form-control" placeholder="Department Name" required/>
                                      
                                    </div>
                                </div>
                        </div> <br>
                        
                        <div class="row clearfix">
						<div class="col-sm-3" align="right">
							<label> ESIC</label>
						</div>
                               <div class="col-sm-8">
                                    <div class="form-line">    
                                        <input type="text" id="esic" name="esic"  class="form-control datepicker"  placeholder="Department Name" required/>
                                    </div>
                                </div>
                        </div> <br>
                        <div class="row clearfix">
						<div class="col-sm-3" align="right">
							<label> Minimum Wages </label>
						</div>
                               <div class="col-sm-8">
                                    <div class="form-line">    
                                       <input type="text" class="form-control" id="minimum" name="minimum" required>  
                                    </div>
                                </div>
                        </div> 
                        <div class="modal-footer">
                            <button type="submit" class="btn  bg-deep-orange waves-effect">Save Changes</button>
                            <button type="button" class="btn bg-blue-grey  waves-effect" data-dismiss="modal">Close</button>
                        </div>
						</form>
                    </div>
                </div>
            </div>
    	</div>
         <div class="row clearfix">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Generate
                                <small>Aging</small>
                            </h2>
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                   <a class="btn bg-pink waves-effect m-b-15" role="button" data-toggle="collapse" href="#collapseExample1" aria-expanded="false"
                                     aria-controls="collapseExample1">
                                    Click
                                    </a>
                                    
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <div class="collapse" id="collapseExample1">
                                <div class="well">
                                    <form method="POST" action="aging_master/submit">
                                    {{ csrf_field() }}
                                    <div class="row clearfix">
                                        <div class="col-sm-4 col-xs-10" align="center">
								            <label> Select Location </label>
								        </div>
							            <div class="col-sm-6" >
                                            <div class="form-line">                        
                                                <select class="form-control show-tick select2" placeholder="Shreeshivam Branch" id="branch_location_id1" name="location">
                                                    <option>Select</option>
                                                     @foreach($branch as $branches) 				           	
									                    <option value="{{$branches->id}}" > {{$branches->branch}}</option>
						                             @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                      <div class="row clearfix">
                                        <div class="col-sm-4 col-xs-10" align="center">
								            <label> Division </label>
								        </div>
							            <div class="col-sm-6" >
                                            <div class="form-line">                        
                                            <select class="form-control show-tick select2" placeholder="Shreeshivam Branch" id="" name="division">
                                                    <option>Select</option>
                                                     @foreach($division as $divisions) 				           	
									                    <option value="{{$divisions->id}}" > {{$divisions->division}}</option>
						                             @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row clearfix">
                                    <table class="table table-stripped">
                                            		<tbody id="salary_body" name="salary_body">
                                            			<tr>
                                            				<td><input type="text" autocomplete="none" name="aging_title[1]" id="salary_type[1]" class="form-control salary_type" value="@STK"></td>
                                            				<td><input autocomplete="none" type="number" name="aging_per[1]" id="salary_value[1]" class="form-control salary_value" placeholder="Enter Percentage (%)" value="{{ old('salary_value[1]') }}" ></td>
                                            				<td><button type="button" name="remove[1]" class="btn btn-danger btn-sm remove"><i class="material-icons">delete</i></button></td>
                                            			</tr>
                                            			
                                            			<tr>
                                            				<td><input type="text" autocomplete="none" name="aging_title[2]" id="salary_type[2]" class="form-control salary_type" value="AW17"></td>
                                            				<td><input autocomplete="none" type="number" name="aging_per[2]" id="salary_value[2]" class="form-control salary_value" placeholder="Enter Percentage (%)" value="{{ old('salary_value[2]') }}" ></td>
                                            				<td><button type="button" name="remove[2]" class="btn btn-danger btn-sm remove"><i class="material-icons">delete</i></button></td>
                                            			</tr>
                                            			<tr>
                                            				<td><input type="text" autocomplete="none" name="aging_title[3]" id="salary_type[3]" class="form-control salary_type" value="SS17"></td>
                                            				<td><input autocomplete="none" type="number" name="aging_per[3]" id="salary_value[3]" class="form-control salary_value" placeholder="Enter Percentage (%)" value="{{ old('salary_value[3]') }}" ></td>
                                            				<td><button type="button" name="remove[3]" class="btn btn-danger btn-sm remove"><i class="material-icons">delete</i></button></td>
                                            			</tr>                      		
                                            		</tbody>
                                                </table>
                                            <div class="row clearfix"><div class="form-group">	            					
					            					<div class="col-sm-4 col-sm-offset-1 col-xs-12">
						            					<div class="form-line">	
						            						<input type="hidden" name="row_number" id="row_number" value="3">
						            					      <button type="button" id="add_row" name="add_row" class="btn bg-teal waves-effect">Add New Row</button>
						            					</div>
						            				</div>
					            				</div>
                                            </div>
                                    </div>
                                  
                                
                                    <div class="row clearfix">
                                        <div class="col-sm-4 col-xs-12" align="center">
                                        <div class="form-group">                 
                                                <button type="submit"  name="submit" class="btn bg-green waves-effect"><i class="material-icons">save</i><span class="icon-name">submit</span></button> 
                                        </div>
                                    </div>  
                                    </div> 
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="card">
                        <div class="header bg-orange">
                            <h2>
                               Lists <small>OF AGING</small>
                            </h2>
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                   <a class="btn bg-red waves-effect m-b-15" role="button" data-toggle="collapse" href="#editcollapseExample9" aria-expanded="false"
                                     aria-controls="collapseExample">
                                    Click
                                    </a>
                                    
                                </li>
                            </ul>
                        </div>
                       
                           <div class="body table-responsive">
                                <div class="collapse" id="editcollapseExample9">
                                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable myemptable">
                                        <thead>
                                             
                                            <tr class="bg-orange">
                                                <th>#</th>
                                                <th>Zone</th>
                                                <th>Division</th>
                                                <th>Aging Title</th>
                                                <th>Aging Percent</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php  	$i=1; ?>

                                     @foreach($aging_master as $aging)
        								   <tr>
                                             {{ csrf_field() }}
                                              <th scope="row">{{ $i++ }}</th>

                                              <td>{{$aging->branch}}</td>
                                              <td>{{$aging->division}}</td>
                                              <td>{{$aging->aging_title}}</td>
                                              <td>{{$aging->aging_per}}</td>							                                    
                                              <td>
        									<button type="submit" data-toggle="modal"  id="{{ $aging->id }}" data-target="#updateModal1" data-id="{{$aging->id}}" 
        									 data-branch="{{$aging->branch_id}}" data-division="{{$aging->division_id}}" data-title="{{$aging->aging_title}}" data-per="{{$aging->aging_per}}" class="btn bg-teal waves-effect edit-modal1 btn-xs"  >
                                            <i class="material-icons">edit</i>   
        									</button>
        	
        	                            </td>   
                                       
                                            </tr>   
                                        @endforeach   
                                        </tbody>
                                    </table>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
               <div class="modal fade" id="updateModal1" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-pink">
                            <h4 class="modal-title" id="uModalLabel">Edit Aging</h4>
                        </div>
						
                        <div class="modal-body">
						<form action="aging_update/submit"  method="POST">
						{{ csrf_field() }}
						<div class="row clearfix">
						<div class="col-sm-3" align="right">
							<label> Zone</label>
						</div>
                               <div class="col-sm-8">
                                   <input type="hidden" id="fid1" name="id"  class="form-control" placeholder="Department Name" required/>
                                    <div class="form-line">                        
                                                <select class="form-control show-tick select2" placeholder="Shreeshivam Branch" id="branch1" name="branch">
                                                    <option>Select</option>
                                                     @foreach($branch as $branches) 				           	
									                    <option value="{{$branches->id}}" > {{$branches->branch}}</option>
						                             @endforeach
                                                </select>
                                            </div>
                                </div>
                        </div><br>
                        <div class="row clearfix">
						<div class="col-sm-3" align="right">
							<label> Division</label>
						</div>
                               <div class="col-sm-8">
                                    <div class="form-line">
                                        <select class="form-control show-tick select2" placeholder="Shreeshivam Division" id="division" name="division">
                                            <option>Select</option>
                                             @foreach($division as $div)                             
                                                <option value="{{$div->id}}" > {{$div->division}}</option>
                                             @endforeach
                                        </select>
                                        <!-- <input type="text" id="division" name="division"  class="form-control" placeholder="Department Name" required/> -->
                                      
                                    </div>
                                </div>
                        </div> <br>
                        
                        <div class="row clearfix">
						<div class="col-sm-3" align="right">
							<label> Aging Title</label>
						</div>
                               <div class="col-sm-8">
                                    <div class="form-line">    
                                        <input type="text" id="title" name="title"  class="form-control datepicker"  placeholder="Department Name" required/>
                                    </div>
                                </div>
                        </div> <br>
                        <div class="row clearfix">
						<div class="col-sm-3" align="right">
							<label> Aging Percentage </label>
						</div>
                               <div class="col-sm-8">
                                    <div class="form-line">    
                                       <input type="text" class="form-control" id="per" name="per" required>  
                                    </div>
                                </div>
                        </div> 
                        <div class="modal-footer">
                            <button type="submit" class="btn  bg-deep-orange waves-effect">Save Changes</button>
                            <button type="button" class="btn bg-blue-grey  waves-effect" data-dismiss="modal">Close</button>
                        </div>
						</form>
                    </div>
                </div>
            </div>
    	</div>
    </div>  
      <div class="row clearfix">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Generate
                                <small>PROFESSIONAL TAX</small>
                            </h2>
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                   <a class="btn bg-pink waves-effect m-b-15" role="button" data-toggle="collapse" href="#collapseExample2" aria-expanded="false"
                                     aria-controls="collapseExample2">
                                    Click
                                    </a>
                                    
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <div class="collapse" id="collapseExample2">
                                <div class="well">
                                    <form method="POST" action="professional/submit">
                                    {{ csrf_field() }}
                                    <div class="row clearfix">
                                        <div class="col-sm-4 col-xs-10" align="center">
								            <label> Select Location </label>
								        </div>
							            <div class="col-sm-6" >
                                            <div class="form-line">                        
                                                <select class="form-control show-tick select2" placeholder="Shreeshivam Branch" id="branch_location_id2" name="location">
                                                    <option>Select</option>
                                                     @foreach($branch as $branches) 				           	
									                    <option value="{{$branches->id}}" > {{$branches->branch}}</option>
						                             @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                     <div class="row clearfix">
                                         <div class="col-sm-5" >
                                            <div class="form-line">
                                                <input type="radio" id="monthlysal" name="calculation_base" class="with-gap" value="monthly">
                                                <label for="monthlysal"> Calculate On Monthly Salary </label>
                                            </div>
                                          </div>
                                          <div class="col-sm-5" >
                                            <div class="form-line">
                                                <input type="radio" id="yearlysal" name="calculation_base" class="with-gap" value="yearly">
                                                <label for="yearlysal"> Calculate On Yearly Salary</label>
                                            </div>
                                          </div>
                                     </div>
                                    <div class="row clearfix">                                       
							            <div class="col-sm-4" >
                                            <div class="form-line">                        
                                            <input type="text"  class="form-control" name="amt_from" placeholder="Amount From">
                                            </div>
                                        </div>
                                        <div class="col-sm-4" >
                                            <div class="form-line">                        
                                            <input type="text"  class="form-control" name="amt_to" placeholder="Amount To">
                                            </div>
                                        </div>
                                        <div class="col-sm-4" >
                                            <div class="form-line">                        
                                            <input type="text"  class="form-control" name="prof_tax" placeholder="Professional Tax">
                                            
                                            </div>
                                        </div>
                                    </div>
                                     <div class="row clearfix">
                                         <div class="col-sm-2" >
                                            <div class="form-line">
                                                <input type="radio" id="monthly" name="payment_type" class="with-gap" value="monthly">
                                                <label for="monthly"> Monthly</label>
                                            </div>
                                          </div>
                                          <div class="col-sm-2" >
                                            <div class="form-line">
                                                <input type="radio" id="yearly" name="payment_type" class="with-gap" value="yearly">
                                                <label for="yearly"> Yearly</label>
                                            </div>
                                          </div>

                                            <div class="col-md-4 formonths">
                                                <div class="form-line">                        
                                                    <input type="text"  class="form-control" id="for_11_month" name="for_11_month" placeholder="For 11 month">                                
                                                </div>
                                            </div>
                                            <div class="col-md-4 formonths">
                                                <div class="form-line">                        
                                                    <input type="text"  class="form-control" id="for_last_month" name="for_last_month" placeholder="For Last Month">                                
                                                </div>
                                            </div>


                                          <div class="col-sm-2" >
                                            <div class="form-line">
                                                <input type="checkbox" id="men" name="men" class="filled-in" value="1">
                                                <label for="men"> Men</label>
                                            </div>
                                          </div>
                                          <div class="col-sm-3" >
                                            <div class="form-line">
                                                <input type="checkbox" id="women" name="women" class="filled-in" value="1">
                                                <label for="women"> Women</label>
                                            </div>
                                          </div>
                                     </div> 
                                    <div class="row clearfix">
                                        <div class="col-sm-4 col-xs-12" align="center">
                                        <div class="form-group">                 
                                                <button type="submit"  name="submit" class="btn bg-green waves-effect"><i class="material-icons">save</i><span class="icon-name">submit</span></button> 
                                        </div>
                                    </div>  
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="card">
                        <div class="header bg-orange">
                            <h2>
                               Lists <small>OF PROFESSIONAL TAX</small>
                            </h2>
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                   <a class="btn bg-red waves-effect m-b-15" role="button" data-toggle="collapse" href="#editcollapseExample10" aria-expanded="false"
                                     aria-controls="collapseExample">
                                    Click
                                    </a>
                                    
                                </li>
                            </ul>
                        </div>
                       
                           <div class="body table-responsive">
                                <div class="collapse" id="editcollapseExample10">
                                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable myemptable">
                                        <thead>
                                             
                                            <tr class="bg-orange">
                                                <th>#</th>
                                                <th>Zone</th>
                                                <th>Type</th>
                                                <th> From</th>
                                                <th> To</th>
                                                <th>Monthly/Yearly</th>
                                                 <th> Tax</th>
                                                <th>Men</th>
                                                <th>Women </th>
                                                <th>Action </th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php  	$i=1; ?>

                                     @foreach($professional as $pro)
        								   <tr>
                                             {{ csrf_field() }}
                                            <th scope="row">{{ $i++ }}</th>
                                            <td>{{$pro->branch}}</td>
                                            <td>{{$pro->calculation_base}}</td>
                                            <td>{{$pro->amount_from}}</td>
                                            <td>{{$pro->amount_to}}</td>
                                            <td>{{$pro->professional_tax}}</td>
                                            <td>{{$pro->tax_deducted}}</td>
                                            <td>@if($pro->for_men==1)<i class="material-icons" id="mycheck1">check</i>@endif</td>
                                            <td>@if($pro->for_women==1)<i class="material-icons" id="mycheck2">check</i>@endif</td>
                                            							                                     
                                            <td>
        									<button type="submit" data-toggle="modal"  id="{{ $pro->id }}" data-id="{{$pro->id}}" 
        									 data-branch="{{$pro->branch_id}}" data-calculation_base="{{$pro->calculation_base}}" data-amount_from="{{$pro->amount_from}}" data-amount_to="{{$pro->amount_to}}" data-professional_tax="{{$pro->professional_tax}}" data-tax_deducted="{{$pro->tax_deducted}}" data-for_men="{{$pro->for_men}}" data-for_women="{{$pro->for_women}}" data-for_11_month="{{$pro->for_11_month}}" data-for_last_month="{{$pro->for_last_month}}" class="btn bg-teal waves-effect edit-modal_pf btn-xs"  >
                                            <i class="material-icons">edit</i>   
        									</button>			                               		
        	                            </td>                                  
                                            </tr>   
                                        @endforeach   
                                        </tbody>
                                    </table>
                                </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="updateModal_pf" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-pink">
                            <h4 class="modal-title" id="uModalLabel">Edit Professional Tax</h4>
                        </div>
                        
                        <div class="modal-body">
                        <form action="professional_tax_update/submit"  method="POST">
                        {{ csrf_field() }}
                        <div class="row clearfix">
                            <div class="col-sm-3" align="right">
                                <label> Select Location </label>
                            </div>
                           <div class="col-sm-8">
                               <input type="hidden" id="fid_1" name="id"  class="form-control" placeholder="" required/>
                                <div class="form-line">                        
                                    <select class="form-control show-tick select2" placeholder="Shreeshivam Branch" id="location" name="location">
                                        <option>Select Location</option>
                                         @foreach($branch as $branches)                             
                                            <option value="{{$branches->id}}" > {{$branches->branch}}</option>
                                         @endforeach
                                    </select>
                                </div>
                            </div>
                        </div><br>
                        <div class="row clearfix">
                            <div class="col-sm-5" align="right">
                                <div class="form-line">
                                    <input type="radio" id="monthlysal_1" name="calculation_base" class="with-gap" value="monthly">
                                    <label for="monthlysal_1"> Calculate On Monthly Salary </label>
                                </div>
                            </div>
                           <div class="col-sm-5">
                                <div class="form-line">                                    
                                    <input type="radio" id="yearlysal_1" name="calculation_base" class="with-gap" value="yearly">
                                    <label for="yearlysal_1"> Calculate On Yearly Salary</label>
                                  
                                </div>
                            </div>
                        </div> <br>
                        
                        <div class="row clearfix">
                            <div class="col-sm-4" >
                                <div class="form-line">                        
                                    <input type="text"  class="form-control" id="amt_from_1" name="amt_from" placeholder="Amount From">
                                </div>
                            </div>
                            <div class="col-sm-4" >
                                <div class="form-line">                        
                                    <input type="text"  class="form-control" id="amt_to_1" name="amt_to" placeholder="Amount To">
                                </div>
                            </div>
                            <div class="col-sm-4" >
                                <div class="form-line">                        
                                    <input type="text"  class="form-control" id="prof_tax_1" name="prof_tax" placeholder="Professional Tax">                                
                                </div>
                            </div>
                        </div> <br>
                        <div class="row clearfix">
                            <div class="col-sm-2" >
                                <div class="form-line">
                                    <input type="radio" id="monthly_1" name="payment_type" class="with-gap" value="monthly">
                                    <label for="monthly_1"> Monthly</label>
                                </div>
                              </div>
                              <div class="col-sm-2" >
                                <div class="form-line">
                                    <input type="radio" id="yearly_1" name="payment_type" class="with-gap" value="yearly">
                                    <label for="yearly_1"> Yearly</label>
                                </div>
                              </div>

                              <div class="col-md-4 formonths1">
                                    <div class="form-line">                        
                                        <input type="text"  class="form-control" id="for_11_month_1" name="for_11_month" placeholder="For 11 month">                                
                                    </div>
                                </div>
                                <div class="col-md-4 formonths1">
                                    <div class="form-line">                        
                                        <input type="text"  class="form-control" id="for_last_month_1" name="for_last_month" placeholder="For Last Month">                                
                                    </div>
                                </div>

                              <div class="col-sm-2" >
                                <div class="form-line">
                                    <input type="checkbox" id="men_1" name="men" class="filled-in" value="1">
                                    <label for="men_1"> Men</label>
                                </div>
                              </div>
                              <div class="col-sm-3" >
                                <div class="form-line">
                                    <input type="checkbox" id="women_1" name="women" class="filled-in" value="1">
                                    <label for="women_1"> Women</label>
                                </div>
                              </div>
                        </div> 
                        <div class="modal-footer">
                            <button type="submit" class="btn  bg-deep-orange waves-effect">Save Changes</button>
                            <button type="button" class="btn bg-blue-grey  waves-effect" data-dismiss="modal">Close</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div> 
@endsection
 @section('jquery')
    <link href="plugins/DatePickerBootstrap/css/datepicker.css" rel="stylesheet"/>
    <script src="plugins/DatePickerBootstrap/js/bootstrap-datepicker.js"></script>
    <script type="text/javascript">

        $(document).ready(function(){
        $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
		$("#success-alert").slideUp(500);
		});

        $('.formonths').hide();
        $('.formonths1').hide();

        $(document).on('click', '.edit-modal', function(){
       
		$('#fid').val($(this).data('id'));
        $('#branch').val($(this).data('branch'));
        $('#epf').val($(this).data('epf'));
        $('#esic').val($(this).data('esic'));
        $('#minimum').val($(this).data('minimumwages'));
        $('#updateModal').modal('show');
    });

    $(document).on('click', '.edit-modal1', function(){
       
		$('#fid1').val($(this).data('id'));
       $/*('#branch1').val($(this).data('branch'));*/
       $("#branch1").children('[value="'+$(this).data('branch')+'"]').attr('selected', true);
        $('#title').val($(this).data('title'));
        $('#per').val($(this).data('per'));
        /*$('#division').val($(this).data('division'));*/
        $("#division").children('[value="'+$(this).data('division')+'"]').attr('selected', true);
        $('#updateModal1').modal('show');
    });

    $(document).on('click', '.edit-modal_pf', function(){
        /*$('#monthlysal_1').checkbox();
        $('#yearlysal_1').checkbox();
        $('#monthly_1').checkbox();
        $('#yearly_1').checkbox();
        $('#men_1').checkbox();
        $('#women_1').checkbox();*/
        $('#fid_1').val($(this).data('id'));
        if($(this).data('calculation_base')=='monthly')
            $('#monthlysal_1').prop('checked', 'checked');
        else
            $('#yearlysal_1').prop('checked', 'checked');

        if($(this).data('professional_tax')=='monthly')
        {
            $('.formonths1').hide();
            $('#for_11_month_1').val($(this).data('for_11_month'));
            $('#for_last_month_1').val($(this).data('for_last_month'));
            $('#monthly_1').prop('checked', 'checked');
        }
        else
        {
            $('.formonths1').show();
            $('#for_11_month_1').val($(this).data('for_11_month'));
            $('#for_last_month_1').val($(this).data('for_last_month'));
            $('#yearly_1').prop('checked', 'checked');
        }
        if($(this).data('for_men')=='1')
            $('#men_1').prop('checked', 'checked');
        if($(this).data('for_women')=='1')
            $('#women_1').prop('checked', 'checked');
        $('#amt_from_1').val($(this).data('amount_from'));
        $('#amt_to_1').val($(this).data('amount_to'));
        $("#location").children('[value="'+$(this).data('branch')+'"]').attr('selected', true);
        $('#prof_tax_1').val($(this).data('tax_deducted'));       
        $('#updateModal_pf').modal('show');
    });

});
    $('#add_row').click(function(){
			// alert();
			var row_number=parseInt($('#row_number').val());
			var row =row_number +1;
		 	var tr = '<tr><td><input type="text" autocomplete="none" name="aging_title['+row+']" id="salary_type['+row+']" class="form-control salary_type" value=""></td><td><input autocomplete="none" type="number" name="aging_per['+row+']" id="salary_value['+row+']" class="form-control salary_value" placeholder="" value="{{ old('salary_value[row]') }}" ></td><td><button type="button" name="remove['+row+']" class="btn btn-danger btn-sm remove"><i class="material-icons">delete</i></button></td></tr>';
			$('#salary_body').append(tr);
			$('#row_number').val(row);
			salary_details();
		});

		$('#salary_body').on("click", "button.remove",function(){
			$(this).closest("tr").remove();
			salary_details();
		});


        $(document).on('click','#yearly',function(){            
            $('.formonths').show()
        });
        $(document).on('click','#monthly',function(){            
            $('.formonths').hide()
        });

        $(document).on('click','#yearly_1',function(){            
            $('.formonths1').show()
        });
        $(document).on('click','#monthly_1',function(){            
            $('.formonths1').hide()
        });

        // $("input[name='calculation_base'][value='yearly']").click(function(){
        //     if($(this).prop("checked"))
        //     {
        //         alert(1);
        //     }
        //     else
        //     {
        //         alert(0);
        //     }
        // });
    </script>
   @endsection