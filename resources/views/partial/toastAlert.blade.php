{{-- <link rel="stylesheet" type="text/css" href="{{asset('css/app.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/toastify.min.css')}}">
<script type="text/javascript" src="{{asset('js/toastify.js')}}"></script>
<script src="{{asset('js/sweetalert.min.js')}}"></script> --}}

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        @if ($errors->any())
        @foreach ($errors->all() as $error)
        Toastify({text: "{{ $error }}", close: true, backgroundColor: 'red', duration: 15000}).showToast();
        @endforeach
        @endif
        @if (session('status'))
        Toastify({text: "{{ session('status') }}", close: true, backgroundColor: 'blue'}).showToast();
        @endif
        @if (session('alert'))
        Toastify({text: "{{ session('alert') }}", close: true, backgroundColor: 'purple'}).showToast();
        @endif
        @if (session('success'))
        Toastify({text: "{{ session('success') }}", close: true, backgroundColor: 'green'}).showToast();
        @endif
        @if (session('warning'))
        Toastify({text: "{{ session('warning') }}", close: true, backgroundColor: 'orange'}).showToast();
        @endif
        @if (session('error'))
        Toastify({text: "{{ session('error') }}", close: true, backgroundColor: 'red', duration: 15000}).showToast();
        @endif
    });
</script>
