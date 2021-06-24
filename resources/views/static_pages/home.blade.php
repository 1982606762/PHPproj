@extends('layouts.default')

@section('content')
  <div class="jumbotron">
    <h1>欢迎使用预约系统</h1>
    <p>
      <a class="btn btn-lg btn-success" href="{{route('signup')}}" role="button">注册</a>
    </p>
  </div>
@stop
