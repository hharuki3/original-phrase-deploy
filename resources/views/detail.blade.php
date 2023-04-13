@extends('layouts.register')


@section('content')
<div class="row">
    <div class="col-md-3">
    <div class="card">
        <div class="card-header">カテゴリー</div>
            <div class="card-body">
                @foreach($categories as $category)
                <div class="my-3">
                    <div class="d-flex justify-content-between">
                        <div class="px-3">
                            <input type="checkbox" name="categories[]" id="{{$category['id']}}" value="{{$category['id']}}"
                            {{in_array($category['id'], $include_categories) ? 'checked' : ''}} >
                            <label for="{{$category['id']}}">{{$category['name']}}</label>
                        </div>
                        <!-- <input type="submit" name="categories[]" value="{{$category['id']}}" form="parent"> -->
                        <div class="text-center px-3">
                            <button  name="category_id" value="{{$category['id']}}" form="parent" class="btn btn-light"  onclick="deleteHandle(event);">削除</button>
                        </div>

                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">フレーズ詳細</div>
            <div class="card-body">

                <div class="form-group text-center from-control-lg my-3">
                    <div class="row justify-content-center">
                        <label for="japanese" class="col-form-label">日本語文</label>
                    </div>
                    <div class="row justify-content-center">
                    <input class="form-control text-center" style="width:50%;font-size:20px" type="text" name="japanese" value="{{$edit_phrase[0]['japanese']}}" id="japanese" disabled>
                    </div>
                </div>


                <div class="form-group text-center from-control-lg my-3">
                    <div class="row justify-content-center">
                        <label for="japanese" class="col-form-label">英語文</label>
                    </div>
                    <div class="row justify-content-center">
                        <input class="form-control text-center" style="width:50%;font-size:20px" type="text" name="phrase" value="{{$edit_phrase[0]['phrase']}}" id="english" disabled>
                    </div>
                </div>


                <div class="form-group text-center from-control-lg my-3">
                    <div class="row justify-content-center">
                        <label for="japanese" class="col-form-label">メモ</label>
                    </div>
                    <div class="row justify-content-center">
                        <textarea class="form-control text-center" style="font-size:20px" name="memo" rows="3" id="memo" disabled>{{$edit_phrase[0]['memo']}}</textarea>
                    </div>
                </div>


                <div class="row mt-5 align-items-center">
                    <div class="col-md-2">
                        <a href="../edit/{{$edit_phrase[0]['id']}}" class="btn btn-light btn-lg">編集</a>
                    </div>
                    <div class="col-md-10 d-flex justify-content-end align-items-center">
                        <a href="../" class="btn btn-light mx-3" style="font-size:20px">戻る</a>
                        <form action="{{route('destroy')}}" method="post" class="mb-0">
                            @csrf
                            <input type="hidden" name="phrase_id" value="{{ $edit_phrase[0]['id'] }}" >
                            <button type="submit" class="btn btn-light btn-lg">削除</button>
                        </form>
                    </div>
                </div>


            </div> 
        </div>
        @endsection
    </div>
</div>

