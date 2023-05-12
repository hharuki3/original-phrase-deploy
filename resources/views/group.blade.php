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
                    <p class="text-start">山田太郎（ユーザー名）</p>
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
                    <div class="col-md-6 text-start">
                        <!-- メンバー1 > $group[name] -->
                        <p class="card-text">メンバー1</p>
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="">詳細を見る</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


@endsection