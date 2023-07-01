@extends('layouts.quiz')


@section('content')

<!-- ランダムだと同じ問題が複数回出題されてしまう。 
もし全ての問題が出力されたら、終了の表示をさせる-->

<div class="card-header">
    <div class="row h5 my-1">
        <div class="col-md-4 text-start">
            <a href="/home" style="text-decoration:none;">ホーム</a>
            <!-- <h5 style="display:inline;" id="list"><a href="/home">一覧へ</a></h5> -->
        </div>
        <div class="col-md-4 text-center">
            すべて出題
        </div>
        <div class="col-md-3 text-end px-0">
            <form method="post" action="{{route('update_checklist')}}"  id="checklist">
                @csrf
                <div type="hidden" name="agree" id="agree" value="checked" onchange="checkForm(this.form)"></div>
                <input type="hidden" name="japanese" id="japanese_check">
                <input type="hidden" name="phrase" id="phrase_check">
                <input type="hidden" name="memo" id="memo_check">
                <input type="hidden" name="phrase_id" id="phrase_id_check">
                <input type="hidden" name="checklist" id="checklist_check">
            </form>
        </div>
        <div class="col-md-1 text-end">
            <a href="javascript:;" style="text-decoration:none;" onclick="Display_JS('answer')" id = "answer">答え</a>
        </div>
    </div>
</div>


<div class="card-body text-center h5">
    @if(isset($phrases[0]['id']))

        <!-- 結果ページ -->
        <div style="display:inline-flex; position:relative; width:50%; height:50%;">
            <canvas id="mychart-pie" style="display:none;"></canvas>
        </div>
        <div class="row">
            <div class="col text-end" id="again"></div>
            <div class="col text-start" id="UnKnownAgain"></div>
        </div>
        <div id="all_correct" style="margin: 0 20%;"></div>


        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <div>
            <div class="my-5" id="japanese">
                <p scope="row" style="display:inline-flex" class="japanese">{{$phrases[$randoms[0]]['japanese']}}</p>
            </div>
            <div class="my-5" id="phrase">
                <p scope="row" style="display:inline-flex" class="english">{{$phrases[$randoms[0]]['phrase']}}</p>
            </div>
            <!-- 次へボタン -->
            <div class="px-3 my-0 text-end" style="margin:10%;">
                <a href="javascript:;"  style="text-decoration:none;" onclick="Display_JS('next')" id="next"></a>
            </div> 

            <div id="memo-card" style="display:none">
                <div class="card shadow-sm card-body" style="margin:0 10%;">
                    <div id="memo-name" class="text-start"></div>
                    <div id="memo">
                        <p scope="row" style="display:inline-flex" class="memo">{{$phrase_checked[$randoms_checked[0]]['memo']}}</p>
                    </div>
                </div>
            </div>
        </div>
    


        <div class="row">
            <div class="col-md-6 text-center px-0">
                <a href="javascript:;" style="text-decoration:none;"onclick="Display_JS('Known')" id="Known">
                    <div class="know-md" style="padding:5em; border:1px solid #ccc;">
                        わかる
                    </div>
                </a>
            </div>
            <div class="col-md-6 text-center px-0">
                <a href="javascript:;"  style="text-decoration:none;" onclick="Display_JS('UnKnown')" id="UnKnown">
                <div class="unknow-md" style="padding:5em; border:1px solid #ccc;">
                    わからない
                </div>
                </a>
            </div> 
        </div>
    @else
        <div class="card-body text-center h5">このカテゴリーにフレーズは登録されていません。</div>
    @endif
</div>

@endsection

@section('javascript')

    <script src="{{ asset('/js/questions.js') }}"></script>

    <script>

        const param = @json($randoms);
        let num = 0;
        let JSPhrases = @json($phrases);
        let query_category = @json($query_category);
        const next = document.querySelector('#next');
        //「わからない」フレーズのIDを格納する配列
        let UnKnownQuestionIds = [];
        let KnownQuestionIds = [];


        next.addEventListener('click', () => {
            num = num + 1;
            if (num < param.length) {
                document.getElementById("japanese").innerHTML = `<p>${JSPhrases[param[num]]['japanese']}</p>`;
                document.getElementById("phrase").innerHTML = ``;
                document.getElementById("memo-card").style.display = "none";
                document.getElementById("memo").innerHTML = ``;
                document.getElementById("Known").style.display = "";
                document.getElementById("UnKnown").style.display = "";
                document.getElementById("answer").style.display = "";    

            } else {
                const again = document.getElementById('again');
                const button = document.createElement('button');
                const UnKnownAgain = document.getElementById('UnKnownAgain');
                const UnKnownbutton = document.createElement('button');
                


                document.getElementById("answer").innerHTML = '';
                document.getElementById("japanese").innerHTML = '';
                document.getElementById("phrase").innerHTML = '';
                document.getElementById("memo-card").style.display = 'none';
                document.getElementById("memo").innerHTML = '';
                document.getElementById("memo-name").innerHTML = '';
                document.getElementById("Known").innerHTML = '';
                document.getElementById("UnKnown").innerHTML = '';
                document.getElementById("next").innerHTML = '';


                document.getElementById("mychart-pie").style.display = "";
                var ctx = document.getElementById('mychart-pie');
                var myChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['わかる', 'わからない'],
                    datasets: [{
                    data: [KnownQuestionIds.length, UnKnownQuestionIds.length],
                    backgroundColor: ['#f88','#48f'],
                    weight: 100,
                    }],
                },
                });
                
                button.textContent = 'もう一度';
                button.className = "btn btn-primary"
                button.style = "  padding: 1rem 2rem;margin-top:3rem; border:1px solid #ccc;"
                button.style.fontSize = "1rem"
                button.addEventListener('click', function() {
                    window.location.href = 'quiz_category?category=' + query_category;
                    //heroku用URL
                    // window.location.href = 'https://original-phrase-heroku4.herokuapp.com/quiz_category?category=' + query_category;
                });
                again.appendChild(button);

                
                if(UnKnownQuestionIds.length > 0){

                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const UnKnownform = document.createElement('form');
                    UnKnownform.method = 'POST';
                    UnKnownform.action = 'quiz_unknown';
                    

                    const UnKnownInput = document.createElement('input');
                    UnKnownInput.type = 'hidden';
                    UnKnownInput.name = 'retry_phrases[]';
                    UnKnownInput.value = JSON.stringify(UnKnownQuestionIds);

                    
                    const UnKnownSubmit = document.createElement('input');
                    UnKnownSubmit.type = 'submit';
                    UnKnownSubmit.value = '分からない問題のみ出題';
                    UnKnownSubmit.className = 'btn btn-primary';
                    UnKnownSubmit.style = "  padding: 1rem 2rem;margin-top:3rem; border:1px solid #ccc;"
                    UnKnownSubmit.style.fontSize = '1rem';
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;

                    UnKnownform.appendChild(csrfInput);
                    UnKnownform.appendChild(UnKnownInput);
                    UnKnownform.appendChild(UnKnownSubmit);
                    UnKnownAgain.appendChild(UnKnownform);

                }else{

                    button.textContent = '全ての問題が合格です。お疲れ様でした!';
                    button.className = "btn btn-primary my-5 col-12";
                    button.style.fontSize = "1rem";
                    button.addEventListener('click', function() {
                        window.location.href = 'home';
                    });
                    all_correct.appendChild(button);

                }
                // document.body.appendChild(button); 
            }
            Eelements.forEach(element => element.style.display = 'none');
            Melements.forEach(element => element.style.display = 'none');
            document.getElementById("agree").innerHTML = '';
            document.getElementById("next").innerHTML = '';


        });


        


        function Display_JS(quiz){
            if(quiz == "answer" || quiz == "Known" || quiz == "UnKnown"){

                const checklist = `${JSPhrases[param[num]]['checklist']}`; 
                if(checklist =="checked"){
                    //すでにcheckされている状態にしておく。
                    document.getElementById("agree").innerHTML = '<input type="checkbox" name="agree" id="agree" value="checked"  onchange="checkForm(this.form)" checked>'
                }
                else{
                    document.getElementById("agree").innerHTML = '<input type="checkbox" name="agree" id="agree" value="checked"  onchange="checkForm(this.form)">'

                }
                document.getElementById("japanese").innerHTML = `<p>${JSPhrases[param[num]]['japanese']}</p>`;
                document.getElementById("phrase").innerHTML = `<p>${JSPhrases[param[num]]['phrase']}</p>`;
                document.getElementById("memo-card").style.display = "";
                document.getElementById("memo-name").innerHTML = '<p>【メモ】</p>';
                document.getElementById("memo").innerHTML = `<p>${JSPhrases[param[num]]['memo']}</p>`;


                if(quiz == "answer"){
                    document.getElementById("memo-card").style.display = "none";

                }

                if(quiz =="Known" || quiz =="UnKnown"){
                    document.getElementById("next").innerHTML = `<a href="javascript:;" style="text-decoration:none;" onclick="Display_JS('next')" id="next">▶️▶️</a>`
                    document.getElementById("Known").style.display = "none";
                    document.getElementById("UnKnown").style.display = "none";
                    document.getElementById("answer").style.display = "none";
                }
                
                let currentQuestionId = `${JSPhrases[param[num]]['id']}`;

                //「わからない」ボタンがクリックされた時のイベントハンドラ
                if(quiz == "UnKnown"){
                    //idの重複なし機能
                    if(!UnKnownQuestionIds.includes(currentQuestionId)){
                        UnKnownQuestionIds.push(currentQuestionId);
                    }

                    localStorage.setItem('UnKnownQuestionIds', JSON.stringify(UnKnownQuestionIds));
                }
                else if(quiz == "Known"){
                    //idの重複なし機能
                    if(!KnownQuestionIds.includes(currentQuestionId)){
                        KnownQuestionIds.push(currentQuestionId);
                    }
    
                    localStorage.setItem('KnownQuestionIds', JSON.stringify(KnownQuestionIds));
                    console.log(KnownQuestionIds);
                    console.log("KnownQuestionIds");
                };

            };      
        };

        function checkForm(form) {
            const japanese = `${JSPhrases[param[num]]['japanese']}`;
            const phrase = `${JSPhrases[param[num]]['phrase']}`;
            const memo = `${JSPhrases[param[num]]['memo']}`;
            const phrase_id = `${JSPhrases[param[num]]['id']}`;

            document.getElementById("japanese_check").value = japanese;
            document.getElementById("phrase_check").value = phrase;
            document.getElementById("memo_check").value = memo;
            document.getElementById("phrase_id_check").value = phrase_id;
            
            if (!form.agree.checked) {
                document.getElementById("checklist_check").value="";
            }else{
                document.getElementById("checklist_check").value="checked";
            }

            form.submit();
            return false;

        }
    </script>
@endsection