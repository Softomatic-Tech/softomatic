<table class="table table-striped" id="mydocstable">
                                <thead>
                                    <tr class="bg-orange">
                                        <th>#</th>
                                        <th>Uploaded By</th>
                                        <th>File</th>
                                        <th>Date</th>
                                        <th>Stutus</th> 
                                        <th>Verify</th>
                                        <th>action</th>
                                      </tr>
                                </thead>
                                <tbody>
								<?php 
								$i=1;
								?>
                               @foreach($doc as $docs)								
								{!! Form::open(['url' => 'docs/action', 'id' => 'theForm', 'files' => true]) !!}
                        		@csrf
                                <?php 
                                // $filename=explode('/',$docs->path);
                                //     $filename=$filename[2];
                                //     $path=$docs->path;
                                // ?>
								   <tr>
                                 <th scope="row">{{ $i++ }}<input type="hidden" name="id" class="myid" value="{{ $docs->id }}"></th>
									  	<td>{{ $docs->name }}</td>
									  	<td><input type="hidden" name="path" class="path" value="{{$docs->path}}"> <a href="{{$docs->path}}" target="_self">{{ $docs->document }}</a></td>
                                        <td>{{ $docs->created_at}} </td>
                                        <td>@if($docs->verify!='Pending'){{'Verified'}} @else{{'pending'}}@endif</td>
                                         <td>
                                             @if($docs->verify=='Pending' && $docs->user_email!=Session::get('useremail'))
                                        <button class="btn btn-primary yes" type="button">Yes</button>
                                        <button class="btn btn-danger no" type="button">No</button>
                                        @elseif($docs->verify!='Pending')
                                        {{ $docs->verify}}
                                        @else{{'Pending'}}
                                        @endif
                                        </td>  
                                        <td>@if($docs->verify=='no' &&  $docs->user_email==Session::get('useremail'))
                                        <button class="btn btn-warning delete" type="button">Delete</button>
                                        @endif
                                        </td>                             
								   </tr>
                                   {!! Form::close() !!}
                                  
                                 @endforeach
                                </tbody>
                            </table> 