<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- <script src="{{ asset('js/nav.js') }}"></script> -->
    <!-- <script src="http://cdn.hcharts.cn/highcharts/highcharts.js"></script> -->


    <!-- jQuery -->
    <!-- <script src="{{ asset('js/jquery/jquery.min.js') }}"></script> -->
    <!-- Bootstrap Core JavaScript -->
    <!-- <script src="{{ asset('js/bootstrap/js/bootstrap.min.js') }}"></script> -->
    <!-- Metis Menu Plugin JavaScript -->
    <!-- <script src="{{ asset('js/metisMenu/metisMenu.min.js') }}"></script> -->
    <!-- Morris Charts JavaScript -->
    <!-- <script src="{{ asset('js/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('js/morrisjs/morris.min.js') }}"></script>
    <script src="{{ asset('js/morris-data.js') }}"></script>  -->
    <!-- Custom Theme JavaScript -->
    <!-- <script src="{{ asset('js/sb-admin-2.js') }}"></script> -->

    <!--Bootstrap 4 JS-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <script type='text/javascript'>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>

    <!--Bootstrap 4 CSS-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">


    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!--阿里图标库http://www.iconfont.cn-->
    <link href="{{ asset('icon/iconfont.css') }}" rel="stylesheet">

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <!-- <link href="{{ asset('css/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/metisMenu/metisMenu.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sb-admin-2.css') }}" rel="stylesheet">
    <link href="{{ asset('css/morrisjs/morris.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css"> -->

    <style>
        /*body {
            font-family: Arial,Microsoft YaHei,微软雅黑,sans-serif;
        }
        .fa-btn {
            margin-right: 6px;
        }
        .content{
            margin-top: 100px;
        }*/
        .header{
            position: fixed; 
            left: 0; 
            right: 0; 
            z-index: 999; 
            height: 50px;
            width: 100%; 
            min-width: 1000px; 
        }

        .container {
          margin-right: 0px;
          margin-left: 0px;
        }
        @media (min-width: 1200px) {
          .container {
            max-width: 100%;
          }
        }

        /*@font-face {
          font-family: 'Glyphicons Halflings';
          src: url('../js/bootstrap/fonts/glyphicons-halflings-regular.eot');
          src: url('../bootstrap/fonts/glyphicons-halflings-regular.eot?#iefix') format('embedded-opentype'), url('../js/bootstrap/fonts/glyphicons-halflings-regular.woff') format('woff'), url('../js/bootstrap/fonts/glyphicons-halflings-regular.ttf') format('truetype'), url('../js/bootstrap/fonts/glyphicons-halflings-regular.svg#glyphicons_halflingsregular') format('svg');
        }*/
        /*.glyphicon {
          position: relative;
          top: 1px;
          display: inline-block;
          font-family: 'Glyphicons Halflings';
          -webkit-font-smoothing: antialiased;
          font-style: normal;
          font-weight: normal;
          line-height: 1;
          -moz-osx-font-smoothing: grayscale;
        }*/

    </style>
</head>
<body>
    <!-- <div id='test'>
        @can('Setting')
            测试验证方法
        @endcan
    </div> -->
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel header">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                    <!-- {{trans('message.test.test')}} -->
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @else

                            
                                
                                    <!-- <i class="icon-ali-jiantouxia">test</i> -->
                                    <!-- <i class="fa fa-dashboard"></i> -->
                                    <!-- <img src="/public/img/cog.png" style="height: 15px;"> -->
                                    <!-- @yield('cog') -->
                                @can('Set interface')
                                    @section('cog')
                                    @show
                                @endcan
                               
                           

                            <li class="nav-item dropdown">
                                <a id="locale" href="#" class="nav-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ trans('message.language') }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <li >
                                        <a class="btn btn-default" onclick="setLocaleLang('zh_cn')">
                                            中文
                                        </a>
                                    </li>
                                    <li >
                                        <a class="btn btn-default" onclick="setLocaleLang('en')">
                                            English
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
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
        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <script src="{{ asset('js/nav.js') }}"></script>
</body>    

</html>
