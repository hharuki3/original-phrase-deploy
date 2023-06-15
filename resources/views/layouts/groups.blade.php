<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Original Phrase') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Original Phrase') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                        @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('ログイン') }}</a>
                        </li>
                        @endif
                        
                        @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('登録') }}</a>
                        </li>
                        @endif
                        @else
                        <a href="{{route('create')}}" class="nav-link">{{__('投稿')}}</a>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->nickname }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('ログアウト') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
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
            <div class="row">
                <div class="col-md-3">
                    <div class="card h5">
                        <div class="card-header my-1">
                            グループ
                        </div>
                        <div class="card-body">
                        <div class="row">
                            <div class="col-md-2 d-flex flex-md-column justify-content-around">
                                <div>
                                    <a href="/" class="btn btn-light my-2">
                                        <img width="30" src="{{asset('img/homes.png')}}" alt="">
                                    </a>
                                </div>
                                <div>
                                    <a href="/" class="btn btn-light my-2">
                                    <img width="30" src="{{asset('img/category.png')}}" alt="">
                                    </a>
                                </div>
                                <div>
                                    @if ($group_exists)
                                        <a href="{{route('quiz_all')}}" class="btn btn-light my-2">
                                            <img width="30" src="{{asset('img/quizzes.png')}}" alt="">
                                        </a>
                                    @else
                                        <div class="btn btn-light my-2">
                                            <img width="30" src="{{asset('img/quizzes.png')}}" alt="">
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <a href="{{route('group')}}" class="btn btn-light my-2">
                                        <img width="30" src="{{asset('img/group.png')}}" alt="">
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-10 px-4">
                                <form action="{{route('new_invite')}}" method="post">
                                    @csrf
                                    <div class="card shadow-sm mb-3">
                                        <div class="card-body">
                                            <input class="btn btn-lignt" type="submit" value="＋グループを作成">
                                            <div class="text-center form-group">
                                                <input class="form-control" type="text" name="new_group" placeholder="グループ名を記入して下さい" required>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <p class="pt-3 text-center">参加しているグループ</p>

                                @if (!$group_exists)
                                <p>参加しているグループはありません</p>
                                @else
                                    @foreach($groups as $group)
                                    <div class="text-center">
                                        <a href="/group?group={{$group['id']}}" class="btn btn-light my-2">
                                            <!-- heroku用URL -->
                                        <!-- <a href="https://original-phrase-heroku4.herokuapp.com/group?group=" class="btn btn-light my-2"> -->
                                            <span>{{$group['name']}}</span>
                                        </a>
                                    </div>
                                    @endforeach
                                @endif
                                <!-- <a class="btn btn-block bg-white btn-outline-teal1 text-decoration-none text-teal1 mb-4"
                                    href="{{ route('home') }}">
                                    設定を編集
                                </a> -->
                                
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                
                
                <div class="col-md-7">
                    <div class="card">
                            @yield('content') 
                            @yield('javascript')
                    </div>
                </div>
            </div>
        </main>
        
    </div>
</body>
</html>







