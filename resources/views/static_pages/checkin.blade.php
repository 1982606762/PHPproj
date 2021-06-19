@extends('layouts.default')
@section('content')
<div class="container">
    <div class="container-fluid">
        <div class="back-box">
                <form method="POST" class="form-horizontal" action="{{route('CheckinReserve')}}">
                    @csrf
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">Invitation Code</div>
                            <input type="text" class="form-control" name="ivtcd" placeholder="Input the 6-digit Invitation Code">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">Password</div>
                            <input type="password" class="form-control" name="pwd" placeholder="Input your account password">
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="submit" id="reserve" value="Check in" class="btn btn-primary">
                    </div>
                </form>
        </div>
    </div>
</div>
@endsection
