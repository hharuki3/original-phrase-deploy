@extends('layouts.groups')


@section('content')


<div class="bg-paper py-4">
    <div class="container" style="max-width: 540px">
        <!-- if(!$group[user]) -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(!empty($group_user_phrases))
            @foreach($group_user_phrases as $group_user_phrase)
            <div class="card-body">
                <div class="form-group form-control-lg my-3">
                    <div class="row justify-content-center">
                        <p class="text-center" style="width:50%;font-size:20px" >{{$group_user_phrase['japanese']}}</p>
                    </div>
                    <div class="row justify-content-center">
                        <p class="text-center" style="width:50%;font-size:20px" >{{$group_user_phrase['phrase']}}</p>
                    </div>
                    <div class="row justify-content-center">
                        <p class="text-center" style="width:50%;font-size:20px" >{{$group_user_phrase['memo']}}</p>
                    </div>
                </div>
            </div>  
            <div class="border-top p-4 mt-2">
            @endforeach

        @else
            <h4>例</h4>

            <p>あなたの登録したフレーズを簡単に共有できます</p>
            <div class="row">
                <div class="col-sm-8">
                    <h5>友達を招待しよう</h5>
                    <p class="pt-2 mb-0">
                        あなたの投稿したフレーズを簡単に共有できます
                    </p>
                </div>
                <div class="col-sm-4 my-2">
                    <a class="btn btn-block bg-paper btn-outline-teal1 text-teal1" href="{{ route('invite') }}">
                        <i class="fas fa-plus mr-1"></i><b>招待する</b>
                    </a>
                </div>
            </div>

            <div class="border-top border-bottom p-4 mt-2">
                <div class="row">
                    <div class="col-md-6">
                        @foreach($login_users as $login_user)
                        <p class="text-start">{{$login_user['name']}}</p>
                        @endforeach
                        <!-- AppServiceProvider.phpで選択したgroupに所属しているユーザーを取得 -->
                        <!-- $usersにログインユーザーが含まれている場合に表示 （ここは後回し）-->
                        
                    </div>
                    <div class="col-md-6">
                        <a href="/" class="text-end">グループを退会</a>
                    </div>
                </div>  
            </div>

            <div class="card mt-2 p-4 shadow-sm">
                <div class="card-body py-2">
                    <p class="card-title text-secondary small mb-1">メンバー一覧</p>
                    <!-- if(!$groups)  まだ誰も参加していません-->
                    <!-- foreach($groups as $group)-->
                    <div class="row">
                        @foreach($users as $user)
                        @if($query_group)
                            <div class="col-md-6 text-start">
                                <!-- メンバー1 > $group[name] -->
                                <p class="card-text">{{$user['name']}}</p>
                            </div>
                            <div class="col-md-6 text-end">
                                @php
                                    $baseUrl = URL::to('/');
                                    $currentUrl = request()->fullUrl();
                                    $targetUrl = str_replace($baseUrl, '', $currentUrl);
                                @endphp
                                <!-- このページにphraseを表示させる or phraseページを作成し、そこに遷移させる -->
                                <a href="/group/?user={{ $user['id'] }}">投稿を見る</a>
                            </div>

                        @else
                            <div class="text-start">
                                <p class="card-text">所属しているグループを選択してください。</p>
                            </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>


@endsection