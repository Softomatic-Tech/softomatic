                             <table class="table table-striped" id="myleavetable">
                                <thead>
                                    <tr class="bg-orange">
                                        <th>#</th>
                                        <th>Employee Name</th>
                                        <th>Employee Email-ID</th>
                                        <th>Reason</th>
										 <th>From Date</th>
                                        <th>To Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                      </tr>
                                </thead>
                                <tbody>
								<?php 
								$i=1;
								?>
								@foreach($leaves_list as $leave)								
								{!! Form::open(['url' => 'leave/action', 'id' => 'theForm', 'files' => true]) !!}
                        		@csrf
								   <tr>
									  <th scope="row">{{ $i++ }}<input type="hidden" name="id" class="myid" value="{{ $leave->id }}"><input type="hidden" name="emp_id" class="myempid" value="{{ $leave->emp_id }}"></th>
									  	<td>{{ $leave->name }}</td>
									  	<td>{{ $leave->email }}</td>
                                        <td>@if($leave->reason=='pl')Personal Leave
                                            @elseif($leave->reason=='ml')Sick Leave
                                            @elseif($leave->reason=='cl')Casual Leave
                                            @elseif($leave->reason=='lwp')Leave Without Pay
                                            @else
                                            {{ $leave->reason }}
                                            @endif
                                        </td>
										<td>{{ $leave->from }}</td>
										<td>{{ $leave->to }}</td>
										<td>{{ $leave->status }}</td>
                                        <td>
                                             <input type="hidden" name="action" class="myaction" value="" required>
                                            @if($leave->status=='Pending')
                                        	<span>
                                                <input type="hidden" name="action1" class="action" value="Accepted">
                                        		<input type="button" class="btn btn-success accept" id="accept-{{$leave->id}}" value="Accept">
                                            </span>
                                            <span>
                                            <input type="hidden" name="action2" class="action" value="Rejected">
                                                <input type="button" class="btn btn-danger reject" id="reject-{{$leave->id}}" value="Reject">
                                            </span>
                                            @endif
                                            @if($leave->status=='Accepted')
                                            <span>
                                        	<input type="hidden" name="action2" class="action" value="Rejected">
                                        		<input type="button" class="btn btn-danger reject" id="reject-{{$leave->id}}" value="Reject">
                                            </span>
                                            @endif
                                            @if($leave->status=='Rejected') 
                                            <a href="javascript:void(0);" class="showreason btn btn-warning">Reason</a> 
                                            <input type="hidden" name="myreason_value" class="myreason_value" value="{{$leave->rejection_reason}}">
                                            @endif
                                        	
                                        </td>                              
								   </tr>
								   {!! Form::close() !!}
                                 @endforeach
                                </tbody>
                            </table> 