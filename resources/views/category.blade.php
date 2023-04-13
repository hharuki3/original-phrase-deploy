@extends('layouts.app')

@section('name')
    カテゴリー
@endsection


@section('item')
<div class="row">
    <a href="/">ホームへ戻る</a>
    <div class="col-md-2">
        <div>
        </div>
        <div>
            <a href="{{route('category')}}" class="btn btn-light my-2">
            <img width="30" src="{{asset('img/home.png')}}" alt="">
            </a>
        </div>
        <div>
            <a href="{{route('quiz_all')}}" class="btn btn-light my-2">
                <img width="30" src="{{asset('img/quiz.png')}}" alt="">
            </a>
        </div>
        <div>
            <a href="{{route('group')}}" class="btn btn-light my-2">
                <img width="30" src="{{asset('img/groups.png')}}" alt="">
            </a>
        </div>
    </div>
    <div class="col-md-9 px-5">
        <a href="/category" class="btn btn-light my-2">全て表示</a>
        @foreach($categories as $category)
        <div>
            <a href="/category/?category={{$category['id']}}" class="btn btn-light my-2">
                <span>{{$category['name']}}</span>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection


@section('content')
<input type="checkbox" name="checkbox" id="checkbox">
<label for="checkbox">英語文を非表示にする</label>

<div class="row mt-3">
    @foreach($phrases as $phrase)
        <div class="col-md-8 pb-3 ps-5 h5">
            <div>
                <th scope="row" style="display:inline-flex">{{$phrase['japanese']}}</th>
            </div>
            <div>
                <p scope="row" style="display:inline-flex" class="english">{{$phrase['phrase']}}</p>
            </div>
        </div>

        <div class="col-md-4 text-left">
            <a href="detail/{{$phrase['id']}}" class="btn btn-light">詳細</a>
            <a href="edit/{{$phrase['id']}}" class="btn btn-light">編集</a>
            <div style="display:inline-flex">
        
                <form action="{{route('destroy')}}" method="post"  id="delete-form-{{$phrase['id']}}">
                    @csrf
                    <input type="hidden" name="phrase_id" value="{{ $phrase['id'] }}" >
                    <button type="submit" class="btn btn-light"  onclick="deleteHandle(event,{{ $phrase['id'] }} )">削除</button>
                </form>
            </div>
        </div>
    @endforeach

</div>

@endsection


@section('javascript')
<script src="{{ asset('/js/confirm.js') }}"></script>
<script src="{{ asset('/js/display.js') }}"></script>
@endsection


