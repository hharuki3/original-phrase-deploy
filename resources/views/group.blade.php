@extends('layouts.groups')


@section('content')


<div class="bg-paper py-4">
    <div class="container" style="max-width: 600px">
        <!-- if(!$group[user]) -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(!empty($group_user_phrases))

        

        <div class="card-body">
            <div class="form-group form-control-lg my-3">
                @foreach($group_user_phrases as $key => $group_user_phrase)
                    <div class="d-flex justify-content-center">
                        <div class="text-center flex-grow-1">
                            <p class="mt-4" style="font-size:20px" id="japanese-{{$key}}">{{$group_user_phrase['japanese']}}</p>
                        </div>
                        <!-- <p class="mt-4" style="font-size:20px">♡</p> -->

                        <!-- <form action="{{route('group_favorite')}}" method="post">
                            @csrf
                            <input type="hidden" name="phrase_id" value="">
                            <div id="favorite" value>

                            </div>
                            <div>
                                <input type="checkbox" class="mt-4" name="favorite" value="{{$key}}">
                            </div>
                        </form> -->


                        
                    </div>


                    <div class="row justify-content-center">
                        <p class="text-center" style="font-size:20px" id="phrase-{{$key}}">{{$group_user_phrase['phrase']}}</p>
                    </div>
                    <div class="row justify-content-center">
                        <p class="text-center" style="font-size:20px" id="memo-{{$key}}">{{$group_user_phrase['memo']}}</p>
                    </div>
                    <div class="border-top">
                @endforeach
                <div id="display"></div>
            </div>
        </div>  

        @else
            <p>あなたの登録したフレーズを簡単に共有できます。</p>
            <div class="row">
                <div class="col-sm-8">
                    <h5>友達を招待しよう</h5>
                </div>
                <div class="col-sm-4 my-2">
                    <!-- <a class="btn btn-block bg-paper btn-outline-teal1 text-teal1" href="">
                        <i class="fas fa-plus mr-1"></i><b>招待する</b>
                    </a> -->

                    
                    @if($selected_groups)
                        <a href="invite/{{$selected_groups[0]['id']}}" class="btn btn-light">招待する</a>
                    @endif
                    
                </div>
            </div>


            <div class="border-top border-bottom p-4 mt-2">
                <div class="row">
                    <div class="col-md-6">
                        <!-- group.phpで選択したgroupに所属しているユーザーを表示 -->
                        <h4 class="text-start">{{$login_users[0]['name']}}</h4>
                    </div>
                    @if($query_group)
                    <div class="col-md-6 text-end">
                        <form action="{{route('group_destroy')}}" method="post">
                            @csrf
                            <input class="btn btn-light btn" type="submit" value="グループを退会">
                            <input type="hidden" name="login_user_id" value="{{ $login_users[0]['id'] }}">
                            <input type="hidden" name="query_group" value="{{ $query_group }}">
                        </form>
                    </div>
                    @endif
                </div>  
            </div>

            <div class="card mt-2 p-4 shadow-sm">
                <div class="card-body py-2">
                    <p class="card-title text-secondary small mb-1">メンバー一覧</p>

                    <div class="row pt-2">
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
                                <!-- userのクエリパラメータの値を用いて、AppServiceProvider.phpでデータベースから値を取得し、表示させる -->
                                <a href="/group/?user={{ $user['id'] }}">投稿を見る</a>
                                <!-- herou用URL -->
                                <!-- <a href="https://original-phrase-heroku4.herokuapp.com/group?user=">投稿を見る</a> -->
                            </div>

                        @elseif(!$query_group && !$query_user)
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

@section('javascript')
<script>
var groupDisplay = [];

window.onload = function() {
    var checkboxes = document.querySelectorAll('input[type=checkbox][name="favorite"]');
    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            var groupID = this.value;  // this should be a unique ID for each group
            var groupInfo = {
                'japanese': document.getElementById('japanese-' + groupID).innerText,
                'phrase': document.getElementById('phrase-' + groupID).innerText,
                'memo': document.getElementById('memo-' + groupID).innerText,
            };
            
            if(this.checked) {
                groupDisplay.push(groupInfo);
            } else {
                var index = groupDisplay.findIndex(function(group) {
                    return group.japanese === groupInfo.japanese &&
                           group.phrase === groupInfo.phrase &&
                           group.memo === groupInfo.memo;
                });
                
                if (index !== -1) {
                    groupDisplay.splice(index, 1);
                }
            }

            // var displayElement = document.getElementById('display');
            //     displayElement.innerHTML = groupDisplay.map(function(group) {
            //         return `<p>${group.japanese}</p>
            //                 <p>${group.phrase}</p>
            //                 <p>${group.memo}</p>`;
            //     }).join('');
        });
    });
}


var favoriteDisplay = document.getElementById('favorite');


favoriteDisplay.addEventListener('click', () => {
    // お気に入りボタンをクリックした場合、チェックしたフレーズ等のみ表示
    console.log('クリックされました。');
    var displayElement = document.getElementById('display');
        displayElement.innerHTML = groupDisplay.map(function(group) {
            return `<p>${group.japanese}</p>
                    <p>${group.phrase}</p>
                    <p>${group.memo}</p>`;
        }).join('');
    
});


</script>

@endsection

