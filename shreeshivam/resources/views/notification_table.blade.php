      <?php 
	$i=1;
	?>
    {!! Form::open(['url' => 'notification/action', 'id' => 'theForm', 'files' => true]) !!}
    @csrf
    @if($notification=='[]')
        <tr>@if(Session::get('role')=='admin')<td colspan="5">@else<td colspan="4">@endif <center>No Data Available</center></td></tr>
    @else
	@foreach($notification as $notify)							
	   <tr>
		    <td scope="row">{{ $i++ }}<input type="hidden" name=" id" class="myid" value="{{ $notify->id }}"></td>
	    @if(Session::get('role')=='admin')
            <td>{{ $notify->email }}</td>
        @endif
		    <td><a href="{{$notify->link}}">{{ $notify->notification }} {{$notify->notification_status}}</a></td>
            <td>
            {!! date("Y-m-d H:i:s",strtotime($notify->created_at))!!}</td>
		    <td>
                <button type="button" name="dismiss[]" class="btn btn-danger btn-sm dismiss"><i class="material-icons">delete</i></button>
            </td>                              
	   </tr>
	   
     @endforeach
     @endif
     {!! Form::close() !!}
                             