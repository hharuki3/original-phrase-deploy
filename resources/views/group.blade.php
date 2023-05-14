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
            <p>あなたの登録したフレーズを簡単に共有できます</p>
            <div class="row">
                <div class="col-sm-8">
                    <h5>友達を招待しよう</h5>
                </div>
                <div class="col-sm-4 my-2">
                    <!-- <a class="btn btn-block bg-paper btn-outline-teal1 text-teal1" href="">
                        <i class="fas fa-plus mr-1"></i><b>招待する</b>
                    </a> -->
                    <form action="{{route('invite')}}" method="post">
                        @csrf
                        @if(!empty($selected_groups))
                            @foreach($selected_groups as $selected_group)
                                <input type="hidden" name="group_id" value="{{$selected_group['id']}}">
                                <input class="fas fa-plus mr-1" type="submit" value="{{$selected_group['name']}}へ招待する">
                            @endforeach
                        @endif
                    </form>
                    
                </div>
            </div>


            <div class="border-top border-bottom p-4 mt-2">
                <div class="row">
                    <div class="col-md-6">
                        <!-- group.phpで選択したgroupに所属しているユーザーを表示 -->
                        <p class="text-start">{{$login_users[0]['name']}}</p>
                    </div>
                    @if($query_group)
                    <div class="col-md-6">
                        <form action="{{route('group_destroy')}}" method="post">
                            @csrf
                            <input type="hidden" name="login_user_id" value="{{ $login_users[0]['id'] }}">
                            <input type="hidden" name="query_group" value="{{ $query_group }}">
                            <input class="btn btn-light btn" type="submit" value="グループを退会">
                        </form>
                    </div>
                    @endif
                </div>  
            </div>

            <div class="card mt-2 p-4 shadow-sm">
                <div class="card-body py-2">
                    <p class="card-title text-secondary small mb-1">メンバー一覧</p>

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

                        @elseif(!$query_group && !($query_user))
                            <div class="text-start">
                                <p class="card-text">所属しているグループを選択してください。</p>
                            </div>
                        @elseif($query_user)
                            <div class="text-start">
                                <p class="card-text">投稿しているフレーズがありません。 </p>
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