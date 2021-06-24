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
                    <h3>活动共有{{$GLOBALS['conf']['continue']}}天,今天是第{{$GLOBALS['conf']['now']}}天。</h3>
                </div>
                <div class="form-group" style="margin-left: 140px">
                    <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">新预约</button>
                </div>
            </div>
        </div>
        <table class="table table-bordered table-hover text-center" style="margin-top:30px;">
            <thead>
            <tr class="active">
                <th class="text-center">预约日期</th>
                <th class="text-center">邀请码</th>
                <th class="text-center" style="width: 90px;">状态</th>
            </tr>
            </thead>
            <tbody>
            @if(empty($reservationInfo->toArray()))
                <tr>
                    <td colspan="3">当前还没有邀请</td>
                </tr>
            @else
                @foreach ($reservationInfo as $rsv)
                    <tr>
                        <td>Day{{$rsv->reserve_date_at}}</td>
                        <td>{{$rsv->invitation}}</td>
                        @if($cuDay>$rsv->reserve_date_at)
                            <td class="danger">已过期</td>
                        @elseif($rsv->checkin==1)
                            <td class="success">已使用</td>
                        @else
                            <td><a href="{{route('delres','ivtcd='.$rsv->invitation)}}" class="cancel">取消</a></td>
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
                            <h3>您已有三个预约，要新建预约请先取消一个</h3>
                        @elseif($ttDays==$cuDay)
                            <h3>节日时间已到，请关注其他活动</h3>
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
