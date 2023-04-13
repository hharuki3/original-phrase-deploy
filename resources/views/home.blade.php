
@extends('layouts.app')

@section('javascript')
<script src="/js/confirm.js"></script>
@endsection


@section('name')
    ホーム
@endsection
@section('item')


<div class="mx-2">
    <div>
        <a href="{{route('category')}}" class="btn btn-light my-2">
            <img width="30" src="{{asset('img/home.png')}}" alt="">
            <span>フレーズ一覧</span>
        </a>
    </div>
    <div>
        <a href="{{route('quiz_all')}}" class="btn btn-light my-2">
            <img width="30" src="{{asset('img/quiz.png')}}" alt="">
            <span>復習テスト</span>
        </a>

    </div>
    <div>
        <a href="{{route('group')}}" class="btn btn-light my-2">
            <img width="30" src="{{asset('img/groups.png')}}" alt="">
            <span>グループ</span>
        </a>
    </div>
</div>
@endsection
@section('content')

<div class="row">

    @if(!$phrase_exists)
    <a href="{{route('create')}}">フレーズを登録しよう！</a>
    @endif


    @foreach($phrases as $phrase)
    <div class="col-md-8 pb-3 ps-5 h5">
        <div>
            <th scope="row" style="display:inline-flex">{{$phrase['japanese']}}</th>
        </div>
            <th scope="row" style="display:inline-flex">{{$phrase['phrase']}}</th>  
    </div>

    <div class="col-md-4 text-left">
        <a href="detail/{{$phrase['id']}}" class="btn btn-light mx-1">詳細</a>
        <a href="edit/{{$phrase['id']}}" class="btn btn-light mx-1">編集</a>
        <div style="display:inline-flex">
            <form action="{{route('destroy')}}" method="post"  id="delete-form-{{$phrase['id']}}">
                @csrf
                <input type="hidden" name="phrase_id" value="{{ $phrase['id'] }}" >
                <button type="submit" class="btn btn-light mx-1"  onclick="deleteHandle(event,{{ $phrase['id'] }} )">削除</button>
            </form>
        </div>
    </div>

    @endforeach

</div>



@endsection


