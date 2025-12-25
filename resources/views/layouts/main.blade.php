<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/main/img/icon.png') }}">
        <link rel="icon" href="{{ asset('assets/main/img/icon.png') }}">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <title>{{ $menuparam['menu_name'] }} {{ isset($menuparam['action']) ? ' | '.$menuparam['action'] : '' }}</title>
        <!-- Stylesheet -->
            <!-- main -->
                <link id="pagestyle" href="{{ asset('assets/main/css/font.css?v=1.1.0') }}" rel="stylesheet" />
                <link href="{{ asset('assets/soft-ui/css/soft-ui-dashboard.css?v=1.1.0') }}" rel="stylesheet" />
            <!-- ======= -->
            <!-- font awesome -->
                <link rel="stylesheet" href="{{ asset('assets/fontawesome-free-6.5.2-web/css/all.min.css') }}">
            <!-- ======= -->
            <!-- Select2 -->
                <link rel="stylesheet" href="{{ asset('assets/select2/css/select2.min.css') }}">
            <!-- ======= -->
            <!-- sweet alert -->
                <link rel="stylesheet" href="{{ asset('assets/sweetalert2/css/sweetalert2.min.css') }}">
            <!-- ======= -->
            <!-- datepicker -->
                <link rel="stylesheet" href="{{ asset('assets/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}">
            <!-- ======= -->
            <!-- datatables -->
                <link rel="stylesheet" href="{{ asset('assets/datatables/jquery.dataTables.min.css') }}">
                <link rel="stylesheet" href="{{ asset('assets/datatables-fixedcolumns/css/fixedColumns.bootstrap4.min.css') }}">
                <link rel="stylesheet" href="{{ asset('assets/datatables-fixedheader/css/fixedHeader.bootstrap4.min.css') }}">
            <!-- ======= -->
            <!-- custom css -->
                <link rel="stylesheet" href="{{ asset('css/app.css') }}">
            <!-- ======= -->
        <!-- ========== -->
    </head>
    <body class="g-sidenav-show bg-gray-100">
        <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-1 fixed-start ms-1 " id="sidenav-main">
            <div class="sidenav-header">
                <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
                <a class="navbar-brand m-0" href="{{ url('/') }}">
                    <img src="{{ asset('assets/main/img/icon.png') }}" class="navbar-brand-img h-100" alt="main_logo">
                    <span class="ms-1 font-weight-bold">Basic System</span>
                </a>
            </div>
            <hr class="horizontal dark mt-0">
            <div class="collapse navbar-collapse w-auto " id="sidenav-collapse-main" style="height: 80%;">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link {{ $menuparam['menu'] == 'dashboard' ? 'active bg-gradient-info text-light' : '' }}" href="{{ url('/') }}">
                            <i class="fa fa-home" ></i>
                            <span class="nav-link-text ms-1">Dashboard</span>
                        </a>
                    </li>
                    @if(isset($menuparam['menulist']) && !empty($menuparam['menulist']))
                        @foreach($menuparam['menulist'] as $lvl1)
                            @if(empty($lvl1['children']))
                                <li class="nav-item">
                                    <a class="nav-link {{ $menuparam['menu'] == $lvl1['kode'] ? 'active bg-gradient-info text-light' : '' }}" href="{{ url('/') }}?page={{ $lvl1['kode'] }}">
                                        <i class="fa fa-home" ></i>
                                        <span class="nav-link-text ms-1">{{ $lvl1['name'] }}</span>
                                    </a>
                                </li>
                            @else
                                <li class="nav-item mt-3">
                                    <h6 class="ps-4 text-uppercase text-xs font-weight-bolder opacity-6">{{ $lvl1['name'] }}</h6>
                                </li>
                                @foreach($lvl1['children'] as $lvl2)
                                    @if(empty($lvl2['children']))
                                        <li class="nav-item">
                                            <a class="nav-link {{ $menuparam['menu'] == $lvl2['kode'] ? 'active bg-gradient-info text-light' : '' }}" href="{{ url('/') }}?page={{ $lvl2['kode'] }}">
                                                <i class="fa {{ $lvl2['icon'] }}" ></i>
                                                <span class="nav-link-text ms-1">{{ $lvl2['name'] }}</span>
                                            </a>
                                        </li>
                                    @else
                                        <li class="nav-item mt-2 has-treeview">
                                            <a href="#" class="nav-link">
                                                <i class="fa {{ $lvl2['icon'] }}" ></i>
                                                <span class="nav-link-text ms-1">{{ $lvl2['name'] }}</span>
                                            </a>
                                            <ul class="nav navbar-nav nav-treeview">
                                                @foreach($lvl2['children'] as $lvl3)
                                                    <li class="nav-item mt-2 {{ $menuparam['menu'] == $lvl2['kode'] ? 'active bg-gradient-info text-light' : '' }}">
                                                        <a href="{{ url('/') }}?page={{ $lvl3['kode'] }}" class="nav-link">
                                                            <i class="fa {{ $lvl2['icon'] }}"></i>
                                                            <span class="nav-link-text ms-1">{{ $lvl3['name'] }}</span>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                    
                </ul>
            </div>
        </aside>
        <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
            <!-- Navbar -->
                <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
                    <div class="container-fluid py-1 px-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                                @if($menuparam['menu_parent1'] != '')
                                    <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">{{ $menuparam['menu_parent1_name'] }}</a></li>
                                @endif
                                @if($menuparam['menu_parent2'] != '')
                                    <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">{{ $menuparam['menu_parent2_name'] }}</a></li>
                                @endif
                                <li class="breadcrumb-item text-sm text-dark active" aria-current="page">{{ $menuparam['menu_name'] }}</li>
                                @if(isset($menuparam['action']) && !empty($menuparam['action']) && $menuparam['action'] != '')
                                    <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">{{ $menuparam['action'] }}</a></li>
                                @endif
                            </ol>
                            <h6 class="font-weight-bolder mb-0">{{ $menuparam['menu_name'] }}</h6>
                        </nav>
                        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                            <div class="ms-md-auto pe-md-6 d-flex align-items-center"></div>
                            <ul class="navbar-nav justify-content-end">
                                <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                                    <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                                        <div class="sidenav-toggler-inner">
                                        <i class="sidenav-toggler-line"></i>
                                        <i class="sidenav-toggler-line"></i>
                                        <i class="sidenav-toggler-line"></i>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item dropdown px-2 d-flex align-items-center">
                                    <a href="javascript:;" class="nav-link text-body p-0" id="setting-navbar" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer"></i>
                                    </a>
                                    <ul class="dropdown-menu  dropdown-menu-end px-1 py-1" aria-labelledby="setting-navbar">
                                        <li class="mb-2">
                                            <a class="dropdown-item border-radius-md" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-sign-out"></i> <span>Log out</span></a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                @csrf
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            <!-- End Navbar -->
            <div class="container-fluid py-4 h-100">
                <div class="row">
                    <div class="col col-12 col-md-12 col-sm-12 col-xs-12">
                        @include('components.userandmenupermission')
                        @include($menuparam['view'])
                        @include('modals.modal')
                    </div>
                </div>
            </div>
        </main>
        <!-- javascript -->
            <!-- soft UI -->
                <script src="{{ asset('assets/soft-ui/js/core/popper.min.js') }}"></script>
                <script src="{{ asset('assets/soft-ui/js/core/bootstrap.min.js') }}"></script>
                <script src="{{ asset('assets/soft-ui/js/plugins/perfect-scrollbar.min.js') }}"></script>
                <script src="{{ asset('assets/soft-ui/js/plugins/smooth-scrollbar.min.js') }}"></script>
                <script src="{{ asset('assets/soft-ui/js/plugins/chartjs.min.js') }}"></script>
                <script src="{{ asset('assets/soft-ui/js/soft-ui-dashboard.min.js?v=1.1.0') }}"></script>
                <script>
                    var win = navigator.platform.indexOf('Win') > -1;
                    if (win && document.querySelector('#sidenav-scrollbar')) {
                        var options = {
                            damping: '0.5'
                        }
                        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
                    }
                </script>
            <!-- jquery -->
                <script src="{{ asset('assets/jquery/jquery.min.js') }}"></script>
            <!-- ====== -->
            <!-- datepicker -->
                <script src="{{ asset('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
            <!-- ========== -->
            <!-- Select2 -->
                <script src="{{ asset('assets/select2/js/select2.min.js') }}"></script>
            <!-- ======= -->
            <!-- Datatables -->
                <script src="{{ asset('assets/datatables/jquery.dataTables.min.js') }}"></script>
                <script src="{{ asset('assets/datatables-fixedcolumns/js/dataTables.fixedColumns.min.js') }}"></script>
                <script src="{{ asset('assets/datatables-fixedheader/js/dataTables.fixedHeader.min.js') }}"></script>
                <script src="{{ asset('assets/datatables-buttons/js/dataTables.buttons.js') }}"></script>
                <script src="{{ asset('assets/datatables-buttons/js/buttons.html5.min.js') }}"></script>
                <script src="{{ asset('assets/datatables/datatable-jszip.min.js') }}"></script>
                <script src="{{ asset('assets/datatables/datatable-pdfmake.min.js') }}"></script>
            <!-- ======= -->
            <!-- Sweetallert -->
                <script src="{{ asset('assets/sweetalert2/js/sweetalert2.min.js') }}"></script>
            <!-- ======= -->
            <!-- main js -->
                <script src="{{ asset('js/main.js') }}?{{ \Carbon\Carbon::now()->addHours(7)->toDateTimeString() }}"></script>
                @if(isset($menuparam['mainjs']))
                    <script src="{{ asset( 'js/'.str_replace('.','/',$menuparam['mainjs']) ) }}.js?{{ \Carbon\Carbon::now()->addHours(7)->toDateTimeString() }}"></script>
                @endif
            <!-- ======= -->
        <!-- ========== -->
    </body>
</html>