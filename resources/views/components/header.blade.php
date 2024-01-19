<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- site title -->
    <title>{{$title}} - {{env('APP_NAME')}}</title>

    <!-- css file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="{{asset('/assets/css/bootstrap.min.css')}}">
{{--    <link rel="stylesheet" href="{{asset('/assets/css/main.css?v=1.2.1')}}">--}}

    <script type="text/javascript" src="https://www.bugherd.com/sidebarv2.js?apikey=sg9xngv1dfaximhakxejfq" async="true"></script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    @vite(['resources/scss/main.scss', 'resources/js/app.js'])
</head>
<body>
<header class="header-main">
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="/"><img class="img-fluid" src="{{asset('/assets/images/logo.png')}}" alt="header-logo"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav align-items-md-center align-items-left mx-auto">
                    <li class="nav-item {{ $active == 'home' ? 'nav-active' : '' }}">
                        <a class="nav-link" aria-current="page" href="/">Home</a>
                    </li>
                    <li class="nav-item {{ $active == 'prices' ? 'nav-active' : '' }}">
                        <a class="nav-link" href="/prices">Prices & Services</a>
                    </li>
                    <li class="nav-item {{ $active == 'about-us' ? 'nav-active' : '' }}">
                        <a class="nav-link" href="/about-us">About Us</a>
                    </li>
                    <li class="nav-item {{ $active == 'blogs' ? 'nav-active' : '' }}">
                        <a class="nav-link" href="/blogs">Blogs</a>
                    </li>
                    <li class="nav-item {{ $active == 'faq' ? 'nav-active' : '' }}">
                        <a class="nav-link" href="/faq">FAQs</a>
                    </li>
                </ul>
                @if(request()->segment(1) != 'booking')
                    <div>
                        @auth()
                            <a class="btn btn-brand outline py-12 px-4" href="/logout">Logout</a>
                        @endauth
                        @guest()
                            <a class="btn btn-brand outline py-12 px-4" href="/login">Login</a>
                        @endguest

                        <a class="btn btn-brand  py-12 px-4 ms-4" href="#">Book Now</a>
                    </div>
                @endif

            </div>
        </div>
    </nav>
</header>
