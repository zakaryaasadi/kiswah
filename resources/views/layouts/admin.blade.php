<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partial.head')
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.4/summernote.css" rel="stylesheet">

    @yield('links')
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav base-color sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center bg-white justify-content-center" href="/">
                <div class="sidebar-brand-icon bg-white">
                    <img src="{{asset('img/logo.png')}}" style="width: 75%" alt="Logo" />
                </div>
                {{-- <div class="sidebar-brand-text text-primary mx-3">Citi Tasker</div>--}}
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Users -->
            <li class="nav-item {{ linkActive('home') }}">
                <a class="nav-link" href="{{ route('home') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>{{ __('Home Page') }}</span></a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                {{ __('TV Management') }}
            </div>

            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ linkActive('admin/sliders') }}">
                <a class="nav-link" href="{{ url('admin/sliders') }}">
                    <i class="fas fa-fw fa-users"></i>
                    <span>{{ __('Sliders') }}</span>
                </a>
            </li>
            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ linkActive('admin/categories') }}">
                <a class="nav-link" href="{{ url('admin/categories') }}">
                    <i class="fas fa-fw fa-users"></i>
                    <span>{{ __('Categories') }}</span>
                </a>
            </li>

            <li class="nav-item {{ linkActive('admin/products') }}">
                <a class="nav-link" href="{{ url('admin/products') }}">
                    <i class="fas fa-fw fa-users-cog"></i>
                    <span>{{ __('Products') }}</span></a>
            </li>

            <li class="nav-item {{ linkActive('admin/videos') }}">
                <a class="nav-link" href="{{ url('admin/videos') }}">
                    <i class="fas fa-fw fa-users-cog"></i>
                    <span>{{ __('Videos') }}</span></a>
            </li>
            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ linkActive('admins') }}">
                <a class="nav-link" href="{{ route('admins') }}">
                    <i class="fas fa-fw fa-user-shield"></i>
                    <span>{{ __('Admins') }}</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                {{ __('Contact') }}
            </div>

            <!-- Nav Item - Profile -->
            <li class="nav-item {{ linkActive('admin/messages') }}">
                <a class="nav-link" href="{{ url('admin/messages') }}">
                    <i class="fas fa-fw fa-envelope"></i>
                    <span>{{ __('Messages') }}</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                {{ __('Settings') }}
            </div>

            <!-- Nav Item - Profile -->
            <li class="nav-item {{ linkActive('admin/teams') }}">
                <a class="nav-link" href="{{ url('admin/teams') }}">
                    <i class="fas fa-fw fa-user"></i>
                    <span>{{ __('Teams') }}</span>
                </a>
            </li>

            <!-- Nav Item - Profile -->
            <li class="nav-item {{ linkActive('profile') }}">
                <a class="nav-link" href="{{ route('profile') }}">
                    <i class="fas fa-fw fa-user"></i>
                    <span>{{ __('Profile') }}</span>
                </a>
            </li>
            <!-- Nav Item - Profile -->
            <li class="nav-item {{ linkActive('admin/about') }}">
                <a class="nav-link" href="{{ route('about.index') }}">
                    <i class="fas fa-fw fa-user"></i>
                    <span>{{ __('About Cards') }}</span>
                </a>
            </li>

            <!-- Nav Item - About -->
            <li class="nav-item {{ linkActive('settings') }}">
                <a class="nav-link" href="{{ route('settings') }}">
                    <i class="fas fa-fw fa-edit"></i>
                    <span>{{ __('Settings') }}</span>
                </a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">
            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">
                <nav class="navbar navbar-expand navbar-light topbar mb-4 static-top shadow" style="background-color: rgb(240,240,240)">

                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    {{-- @include('partial.topbar-search')--}}
                    <ul class="navbar-nav ml-auto">

                        {{-- @include('partial.search-form')--}}
                        {{-- @include('partial.dashboard-alerts')--}}
                        {{-- @include('partial.dashboard-messages')--}}

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                                <figure class="img-profile rounded-circle avatar font-weight-bold" data-initial="{{ auth()->user()->name[0] ?? 'AD' }}"></figure>
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="{{ route('profile') }}">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    {{ __('Profile') }}
                                </a>
                                <a class="dropdown-item" href="{{url('notifications')}}">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    {{ __('Notifications') }}
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    {{ __('Logout') }}
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <div class="container-fluid">
                    @yield('main-content')
                    @yield('content')
                </div>

            </div>

            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; OneHope 2020</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('Ready to Leave?') }}</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-link" type="button" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <a class="btn btn-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    {{--<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>--}}
    {{--<script src="{{ asset('vendor/bootstrap/js/bootstrap.min.js') }}"></script>--}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.4/summernote.js"></script>

    <script>
        $(document).ready(function() {
            const editor = $('.summernote');
            if (editor.length) {
                editor.summernote({
                    height: 300,
                    dialogsInBody: false,
                    callbacks: {
                        onInit: function() {
                            $('body > .note-popover').hide();
                        }
                    },
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                        ['fontname', ['fontname']],
                        ['fontsize', ['fontsize', 14]],
                        ['color', ['color']],
                        ['para', ['ol', 'ul', 'paragraph', 'height']],
                        ['table', ['table']],
                        ['insert', ['link']],
                        ['view', ['undo', 'redo', 'fullscreen', 'codeview', 'help']]
                    ]
                });

                var imageUploadDiv = $('div.note-group-select-from-files');
                if (imageUploadDiv.length) {
                    imageUploadDiv.remove();
                }
            }
        });
    </script>
    @yield('script')
</body>

</html>
