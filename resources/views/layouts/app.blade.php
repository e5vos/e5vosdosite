<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="background:url({{asset('images/defaultbackgroundimage.png')}}) no-repeat center center fixed; background-size:cover;">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google-signin-client_id" content="352962433416-obdmdsbt2205mains21suujpvrfmhks4.apps.googleusercontent.com">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>window.Laravel = {csrfToken: '{{csrf_token()}}'}</script>

<title> @yield('title') </title>

    <!-- Scripts -->
    <script src="/public/js/app.js" async defer> </script>



    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="/public/css/app.css" rel="stylesheet">
    @yield('script')
</head>
<body style="background:none;">

    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm sticky-top">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{url('/images/whitedonci.png')}}" alt="E5vosdosite" height="40px" >
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="#">Előadások</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Programok</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Csapatok</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                               Kezelés <span class="caret"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="#">Programok szerkesztése</a>
                                <a class="dropdown-item" href="#">Tanári felület</a>
                            </div>

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('login')}}">{{ __('auth.login') }}</a>
                            </li>
                        @else
                            @if(Auth::user()->ejgClass==null)
                            <li class="nav-item">
                                <div class="align-bottom alert alert-danger">
                                    <a class="alert-link" style="color:red" href="{{route('user.edit',Auth::user()->id)}}"> Add meg az osztályod </a>
                                </div>
                            </li>
                            @endif
                            <li class="nav-item">
                                <a class="navbar-text" href="#">Bejelentkezve mint {{ Auth::user()->user_name }}</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>


                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('user.edit',Auth::user()->id) }}">Profil szerkesztése</a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('auth.logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-2">
            @yield('content')
        </main>
    </div>
</body>
</html>
