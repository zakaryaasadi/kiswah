<!DOCTYPE html>
<html lang="en">
<head>
    @include('partial.head')
    @yield('links')
    @include('partial.toastAlert')

</head>
<body>
@include('partial.sidebar-mobile')
@include('partial.sidebar')
@yield('content')
<script src="{{asset('assets/js/sidenav.js')}}"></script>
@yield('scripts')
</body>
</html>
