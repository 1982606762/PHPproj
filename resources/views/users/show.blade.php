@extends('layouts.default')
@section('title', $user->name)

@section('content')
<div class="row">
  <div class="offset-md-2 col-md-8">
    <div class="col-md-12">
      <div class="offset-md-2 col-md-8">
        <section class="user_info">
          @include('shared._user_info', ['user' => $user])
        </section>
      </div>
    </div>
  </div>
</div>

<div class="container">
    <div class="container-fluid">
        <div style="width:100%;" class="center-block">
            <div class="form-inline">
                <div class="form-group">
                    <h3>The Carnival lasts for {{$GLOBALS['conf']['continue']}} days, today is Day{{$GLOBALS['conf']['now']}}.</h3>
                </div>
                <div class="form-group" style="margin-left: 140px">
                    <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">New Reservation</button>
                </div>
            </div>
        </div>
        <table class="table table-bordered table-hover text-center" style="margin-top:30px;">
            <thead>
            <tr class="active">
                <th class="text-center">Reservation Date</th>
                <th class="text-center">Invitation Code</th>
                <th class="text-center" style="width: 90px;">Status</th>
            </tr>
            </thead>
            <tbody>
            @if(empty($reservationInfo->toArray()))
                <tr>
                    <td colspan="3">No Reservation Yet.</td>
                </tr>
            @else
                @foreach ($reservationInfo as $rsv)
                    <tr>
                        <td>Day{{$rsv->reserve_date_at}}</td>
                        <td>{{$rsv->invitation}}</td>
                        @if($cuDay>$rsv->reserve_date_at)
                            <td class="danger">Passed</td>
                        @elseif($rsv->checkin==1)
                            <td class="success">Verified</td>
                        @else
                            <td><a href="{{route('delres','ivtcd='.$rsv->invitation)}}">Cancel</a></td>
                        @endif
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">
                    预定日期
				</h4>
			</div>
			<div class="container">
                <div class="container-fluid">
                    <div class="back-box">
                        @if($cuTtRsv>=3)
                            <h3>You have had 3 reservations, to reserve another day, you need to cancel an old one.</h3>
                        @elseif($ttDays==$cuDay)
                            <h3>Today is the last day of the festival, please pay attention to the next event.</h3>
                        @else
                            <form method="POST" class="form-horizontal" action="{{route('reserv')}}">
                            @csrf
                                <div class="form-group">
                                    <select name="rsv_day_at" class="form-control">
                                        @php
                                            for($i=$cuDay+1;$i<=$ttDays;$i++){
                                             echo '<option value="'.$i.'" >Day'.$i.'</option>';
                                            }
                                        @endphp
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="submit" id="reserve" value="Reserve" class="btn btn-primary">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal -->
</div>

@stop
