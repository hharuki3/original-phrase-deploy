@extends('layouts.register')

@section('content')

    <!-- route('store')と書くと、/storeと同義 -->

    <form action="{{route('store')}}" method="post">
        @csrf
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">カテゴリー</div>
                    <div class="card-body">
                        <div>
                            <div class="text-center form-group">
                                <input class="form-control" type="text" name="new_category" placeholder="カテゴリーを追加">
                            </div>
                            <!-- リダイレクトすると入力欄がリセットされる。 -->
                            @error('new_category')
                                <div style="color:red;">{{$message}}</div>
                            @enderror
                            @foreach($categories as $category)
                            <div class="my-3 mt-4">
                                <div class="d-flex justify-content-between">
                                    <dvi class="px-3">
                                        <input type="checkbox" name="categories[]" id="{{$category['id']}}" value="{{$category['id']}}">
                                        <label for="{{$category['id']}}">{{$category['name']}}</label>
                                    </dvi>
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
                                    <input class="form-control text-center" style="width:50%;font-size:20px" type="text" name="japanese" placeholder="日本語文を追加" >
                                </div>
                                @error('japanese')
                                    <div style="color:red;">{{$message}}</div>
                                @enderror
                            </div>

                            <div class="form-group text-center form-control-lg my-3">
                                <div class="row justify-content-center">
                                    <label for="english" class="col-form-label">英語文</label>
                                </div>
                                <div class="row justify-content-center">
                                    <input class="form-control text-center" style="width:50%;font-size:20px" type="text" name="phrase" placeholder="フレーズを追加" >
                                </div>
                                @error('phrase')
                                    <div style="color:red;">{{$message}}</div>
                                @enderror
                                
                            </div> 
                            
                            <div class="form-group text-center form-control-lg my-3">
                                <div class="row justify-content-center">
                                    <label for="memo" class="col-form-label">メモ</label>
                                </div>
                                <div class="row justify-content-center">
                                    <textarea class="form-control text-center" style="font-size:20px" name="memo" rows="3" placeholder="一言メモ" ></textarea>
                                </div>
                                @error('memo')
                                    <div style="color:red;">{{$message}}</div>
                                @enderror
                            </div>
                            
                            <div class="row mt-5">
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-light btn-lg mx-1">保存</button>
                                    <a href="../" class="btn btn-light" style="font-size:20px">戻る</a>
                                </div>
                            </div>
                    </div> 
                </div> 
            </div> 
        </div>
    </form>
@endsection

