@extends('layouts.default')
@section('content')
<div class="container">
    <div class="container-fluid">
        <div class="back-box">
                <form method="POST" class="form-horizontal" action="{{route('CheckinReserve')}}">
                    @csrf
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">邀请码</div>
                            <input type="text" class="form-control" name="ivtcd" >
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">密码</div>
                            <input type="password" class="form-control" name="pwd">
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="submit" id="reserve" value="报到" class="btn btn-primary">
                    </div>
                </form>
        </div>
    </div>
</div>
@endsection
