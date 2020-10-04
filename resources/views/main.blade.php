<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css">
</head>

<body class="antialiased">
<div class="page" id="app">
    <header class="navbar navbar-expand-md navbar-dark">
        <div class="container-xl">
            <div class="collapse navbar-collapse" id="navbar-menu">
                <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route("customer") }}">
                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md" width="24" height="24"
                             viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                             stroke-linecap="round" stroke-linejoin="round"><path stroke="none"
                                                                                  d="M0 0h24v24H0z"></path><circle
                                cx="12" cy="7" r="4"></circle><path
                                d="M5.5 21v-2a4 4 0 0 1 4 -4h5a4 4 0 0 1 4 4v2"></path></svg>
                    </span>
                                <span class="nav-link-title">
                      Clientes
                    </span>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link" href="{{ route("delivery-lead") }}">
                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md" width="24" height="24"
                             viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                             stroke-linecap="round" stroke-linejoin="round"><path stroke="none"
                                                                                  d="M0 0h24v24H0z"></path><circle
                                cx="12" cy="7" r="4"></circle><path
                                d="M5.5 21v-2a4 4 0 0 1 4 -4h5a4 4 0 0 1 4 4v2"></path></svg>
                    </span>
                                <span class="nav-link-title">
                      Conduces
                    </span>
                            </a>
                        </li>
{{--                        <li class="nav-item">--}}
{{--                            <a class="nav-link" href="{{ route("invoice") }}">--}}
{{--                    <span class="nav-link-icon d-md-none d-lg-inline-block"><svg xmlns="http://www.w3.org/2000/svg"--}}
{{--                                                                                 class="icon" width="24" height="24"--}}
{{--                                                                                 viewBox="0 0 24 24" stroke-width="2"--}}
{{--                                                                                 stroke="currentColor" fill="none"--}}
{{--                                                                                 stroke-linecap="round"--}}
{{--                                                                                 stroke-linejoin="round"><path--}}
{{--                                stroke="none" d="M0 0h24v24H0z"/><polyline points="5 12 3 12 12 3 21 12 19 12"/><path--}}
{{--                                d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"/><path--}}
{{--                                d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6"/></svg>--}}
{{--                    </span>--}}
{{--                                <span class="nav-link-title">--}}
{{--                      Facturas--}}
{{--                    </span>--}}
{{--                            </a>--}}
{{--                        </li>--}}
                    </ul>
                </div>
            </div>
        </div>
    </header>
    <div class="content">
        @yield('content')
    </div>
</div>
<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
</body>
</html>
