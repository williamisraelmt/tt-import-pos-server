<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="antialiased">
<div class="page" id="app">
    <header class="navbar navbar-expand-md d-print-none">
        <div class="container-xl">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="navbar-nav flex-row  order-md-last">
                <div class="nav-item d-md-flex">
                    <user-dropdown-component menu="admin"></user-dropdown-component>
                </div>
            </div>
            <div class="collapse navbar-collapse" id="navbar-menu">
                <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
                    <ul class="navbar-nav">

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#"
                               role="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/star -->
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                           stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                           stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path
                              d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path></svg>
                    </span>
                                <span class="nav-link-title">
                      Entidades
                    </span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <h6 class="dropdown-header">Personas</h6>
                                <a class="dropdown-item" href="{{ route("customer") }}">
                                    Clientes
                                </a>
                                <a class="dropdown-item" href="{{ route("debt-collector") }}">
                                    Cobradores
                                </a>
                                <a class="dropdown-item" href="{{ route("user") }}">
                                    Usuarios
                                </a>
                                <div class="dropdown-divider"></div>
                                <h6 class="dropdown-header">Documentos</h6>
                                <a class="dropdown-item" href="{{ route("delivery-lead") }}">
                                    Conduces
                                </a>
                                <a class="dropdown-item" href="{{ route("invoice") }}">
                                    Facturas
                                </a>
                                <a class="dropdown-item" href="{{ route("payment") }}">
                                    Pagos
                                </a>
                                <div class="dropdown-divider"></div>
                                <h6 class="dropdown-header">Inventario</h6>
                                <a class="dropdown-item" href="{{ route("product") }}">
                                    Productos
                                </a>
                                <a class="dropdown-item" href="{{ route("product-brand") }}">
                                    Marcas
                                </a>
                                <a class="dropdown-item" href="{{ route("product-category") }}">
                                    Categorías
                                </a>
                                <a class="dropdown-item" href="{{ route("product-department") }}">
                                    Departamentos
                                </a>
                                <a class="dropdown-item" href="{{ route("product-model") }}">
                                    Modelos
                                </a>
                                <a class="dropdown-item" href="{{ route("product-type") }}">
                                    Tipos
                                </a>
                            </div>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" role="button"
                               id="dropdownMenuButton2" aria-expanded="true">
                    <span class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/star -->
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                           stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                           stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path
                              d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path></svg>
                    </span>
                                <span class="nav-link-title">
                                  Herramientas
                                </span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                <a class="dropdown-item" href="{{ route("commission-calculator") }}">
                                    Calcular comisiones
                                </a>
                            </div>
                        </li>

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
