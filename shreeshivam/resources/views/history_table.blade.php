<table class="table table-striped" id="myleavetable">
                                <thead>
                                    <tr class="bg-orange">
                                        <th>#</th>
                                        @if(Session::get('role')=='admin')
                                        <th>User Email</th>
                                        @endif
                                        <th>Upload Type</th>
                                        <th>File Location</th>
                                        <th>File Upload Status</th>
                                        <th>Process Status</th>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                $i=1;
                                ?>
                                {!! Form::open(['url' => 'notification/action', 'id' => 'theForm', 'files' => true]) !!}
                                @csrf
                                @if($histories=='[]')
                                    <tr>@if(Session::get('role')=='admin')<td colspan="7">@else<td colspan="6">@endif <center>No Data Available</center></td></tr>
                                @else
                                @foreach($histories as $history)                          
                                   <tr>
                                        <td scope="row">{{ $i++ }}<input type="hidden" name=" id" class="myid" value="{{ $history->id }}"></td>
                                    @if(Session::get('role')=='admin')
                                        <td>{{ $history->user_email }}</td>
                                    @endif
                                    <td>{{$history->upload_type}}</td>
                                        <td><a href="{{$history->file_location}}">{{ $history->file_location }}</a></td>
                                        <td>{{$history->file_upload_status}}</td>
                                        <td>{{$history->process_status}}</td>
                                        <td>{!! date("Y-m-d H:i:s",strtotime($history->start_time))!!}</td>
                                        <td>
                                        {!! date("Y-m-d H:i:s",strtotime($history->end_time))!!}</td>                       
                                   </tr>
                                   
                                 @endforeach
                                 @endif
                                 {!! Form::close() !!}
                                </tbody>
                            </table>    