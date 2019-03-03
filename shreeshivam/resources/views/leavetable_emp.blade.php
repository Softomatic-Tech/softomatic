                            <table class="table table-striped" id="myleavetable_emp">
                                <thead>
                                    <tr class="bg-orange">
                                        <th>#</th>
                                        <th>Reason</th>
										 <th>From Date</th>
                                        <th>To Date</th>
                                        <th>status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
								<?php 
								$i=1;
								?>
								@foreach($leaves_list as $leave)
								   <tr>
									 {{ csrf_field() }}
									  <th scope="row">{{ $i++ }}</th>
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
                                        <td class="">{{ $leave->status }}</td>
                                        <td> 
                                            @if($leave->status=='Rejected') 
                                            <a href="javascript:void(0);" class="btn btn-warning showreason">Reason</a>
                                            <input type="hidden" name="myreason_value" class="myreason_value" value= " {{ $leave->rejection_reason }} " >
                                            @endif
                                        </td>                              
								   </tr>
                                 @endforeach
                                </tbody>
                            </table>