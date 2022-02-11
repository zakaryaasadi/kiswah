<!DOCTYPE html>
<html lang="en">
<head>
   @include('partial.head')
    <link rel="stylesheet" href="{{asset('css/plugins.css')}}">
    <link href="{{asset('css/styles.css')}}" rel="stylesheet">
    <link href="{{asset('css/extra.css')}}" rel="stylesheet">
    @yield('links')
</head>
<body>
<div class="main-wrapper">
   @yield('content')


</div>
@yield('script')
</body>
</html>



