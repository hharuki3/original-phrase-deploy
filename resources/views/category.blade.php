@extends('layouts.app')

@section('name')
    カテゴリー
@endsection


@section('item')
<div class="row">   
    <div class="col-md-2 d-flex flex-md-column justify-content-around">
        <div>
            <a href="/" class="btn btn-light my-2">
                <img width="30" src="{{asset('img/homes.png')}}" alt="">
            </a>
        </div>
        <div>
            <a href="{{route('category')}}" class="btn btn-light my-2">
                <img width="30" src="{{asset('img/category.png')}}" alt="">
            </a>
        </div>
        <div>
            @if($phrase_exists)
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
    <div class="col-md-9 px-5">
        <a href="/category" class="btn btn-light my-2">全て表示</a>
        @foreach($categories as $category)
        <div>
            <!-- herokuにデプロイする場合は、https通信となるようfull pathを指定。groupsテーブルも同様。# -->
            <!-- <a href="/category/?category=" class="btn btn-light my-2"> -->
            <a href="https://original-phrase-heroku4.herokuapp.com/category?category={{$category['id']}}" class="btn btn-light my-2">

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
        <div class="col-md-8 space-md pb-1 text-center text-md-start h5">
            <div>
                <th scope="row" style="display:inline-flex">{{$phrase['japanese']}}</th>
            </div>
            <div>
                <p scope="row" style="display:inline-flex" class="english">{{$phrase['phrase']}}</p>
            </div>
        </div>

        <div class="col-md-4 pb-5 text-center text-md-left">
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


