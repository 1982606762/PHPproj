<!DOCTYPE html>
<html>
  <head>
    <title>@yield('title', '预约系统')</title>
    <link rel="stylesheet" href="/css/app.css">
  </head>
  <body>
    @include('layouts._head')
    <div class="container">
      @yield('content')
    </div>
    @include('layouts._foot')
  </body>
</html>
