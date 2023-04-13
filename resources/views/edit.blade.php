@extends('layouts.register')

@section('javascript')
<script src="/js/confirm.js"></script>
@endsection

@section('content')
    <!-- route('store')と書くと、/storeと同義 -->

    <!-- カテゴリー削除 -->
    <form action="{{route('category_destroy')}}" method="post" id="parent">
        @csrf
    </form>

    <form action="{{route('update')}}" method="post">
        @csrf
        <input type="hidden" name="phrase_id" value="{{ $edit_phrase[0]['id'] }}">
        <input type="hidden" name="checklist" value="">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">カテゴリー</div>
                    <div class="card-body">
                        <div>
                            <div class="text-center form-group mb-4">
                                <input class="form-control" type="text" name="new_category" placeholder="新しいカテゴリーを追加">
                            </div>
                            <input type="hidden" name="categories">
                            @foreach($categories as $category)
                            <div class="my-3">
                                <div class="d-flex justify-content-between">
                                    <div class="px-3">
                                        <input type="checkbox" name="categories[]" id="{{$category['id']}}" value="{{$category['id']}}"
                                        {{in_array($category['id'], $include_categories) ? 'checked' : ''}} >
                                        <label for="{{$category['id']}}">{{$category['name']}}</label>
                                    </div>
                                    <!-- <input type="submit" name="categories[]" value="{{$category['id']}}" form="parent"> -->
                                    <div class="text-center text-nowrap px-3">
                                        <button  name="category_id" value="{{$category['id']}}" form="parent" class="btn btn-light float-end"  onclick="deleteHandle(event);">削除</button>
                                    </div>

                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">フレーズ</div>
                    <div class="card-body">
                        <div class="form-group text-center form-control-lg my-3">
                            <div class="row justify-content-center">
                                <label for="japanese" class="col-form-label">日本語文</label>
                            </div>
                            <div class="row justify-content-center">
                                <input class="form-control text-center" style="width:50%;font-size:20px" type="text" name="japanese" value="{{$edit_phrase[0]['japanese']}}" id="japanese">
                            </div>
                        </div>
                        

                        <div class="form-group text-center form-control-lg my-3">
                            <div class="row justify-content-center">
                                <label for="english" class=" col-form-label">英語文</label>
                            </div>
                            <div class="row justify-content-center">
                                <input class="form-control text-center" style="width:50%;font-size:20px" type="text" name="phrase" value="{{$edit_phrase[0]['phrase']}}" id="english">
                            </div>
                        </div>


                        <div class="form-group text-center form-control-lg my-3">
                            <div class="row justify-content-center">
                                <label for="memo" class="col-form-label">メモ</label>
                            </div>
                            <div class="row justify-content-center">
                                <textarea class="form-control text-center" style="font-size:20px" name="memo" rows="3" id="memo">{{$edit_phrase[0]['memo']}}</textarea>
                            </div>
                        </div>

                        
                        <div class="row mt-5">
                            <div class="col-md-2 mx-4">
                                <button class="btn btn-light btn-lg">更新</button>
                            </div>
                            <div class="col-md-9 d-flex justify-content-end">
                                <a href="../" class="btn btn-light mx-3" style="font-size:20px">戻る</a>
                                <input type="submit"  class="btn btn-light btn-lg" value="削除" form="delete"  onclick="deleteHandle(event);">
                            </div>
                        </div>

                            
                    </div>
                    
                </div>
            </div>
        </div>
    </form>
    
    <form action="{{route('destroy')}}" method="post" style="display:inline-flex" id="delete">
        @csrf
        <input type="hidden" name="phrase_id" value="{{ $edit_phrase[0]['id'] }}" >
    </form>
    

@endsection
