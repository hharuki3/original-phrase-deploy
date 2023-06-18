@extends('layouts.quiz')


@section('content')

<!-- ランダムだと同じ問題が複数回出題されてしまう。 
もし全ての問題が出力されたら、終了の表示をさせる-->

<div class="card-header">
    <div class="row h5 my-1 d-flex">
        <div class="col-md-4 col-4 text-start ">
            <a href="/home" style="text-decoration:none;">ホーム</a>
            <!-- <h5 style="display:inline;" id="list"><a href="/home">一覧へ</a></h5> -->
        </div>
        <div class="col-md-4 col-4 text-center">
            すべて出題
        </div>
        <div class="col-md-3 col-2 text-end px-0">
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
        <div class="col-md-1 col-2 text-end">
            <a href="javascript:;" style="text-decoration:none;" onclick="Display_JS('answer')" id = "answer">答え</a>
        </div>
    </div>
</div>


<div class="card-body text-center h5">
    <div class="row d-flex">
        <div class="col-md-6 text-center px-0">
            <div id="again"></div>
        </div>
        <div class="col-md-6 text-center px-0">
            <div id="UnKnownAgain"></div>
        </div>
    </div>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div>
        <div class="my-5" id="japanese">
            <p scope="row" style="display:inline-flex" class="japanese">{{$phrases[$randoms[0]]['japanese']}}</p>
        </div>
        <div class="my-5" id="phrase">
            <p scope="row" style="display:inline-flex" class="english">{{$phrases[$randoms[0]]['phrase']}}</p>
        </div>
        <div class="my-5" id="memo">
            <p scope="row" style="display:inline-flex" class="memo">{{$phrases[$randoms[0]]['memo']}}</p>
        </div>
    </div>


    <div class="row px-0 my-0">
        <div class="col-md-10 text-end" style="margin-right:10em">
            <a href="javascript:;" style="text-decoration:none;" onclick="Display_JS('next')" id="next"></a>
        </div>
    </div> 

    <div class="row">
        <div class="col-md-6 text-center px-0">
            <a href="javascript:;" style="text-decoration:none;"onclick="Display_JS('Known')" id="Known">
                <!-- <div style="margin-left:10em;padding:5em; border:1px solid #ccc;"> -->
                <div class="know-md" style="padding:5em; border:1px solid #ccc;">
                    わかる
                </div>
            </a>
        </div>
        <div class="col-md-6 text-center px-0">
            <a href="javascript:;"  style="text-decoration:none;" onclick="Display_JS('UnKnown')" id="UnKnown">
            <div class="unknow-md" style="padding:5em; border:1px solid #ccc;">
            <!-- <div style="padding:5em; border:1px solid #ccc;"> -->
                わからない
            </div>
            </a>
        </div> 
    </div>
</div>
@endsection

@section('javascript')

    <script src="{{ asset('/js/questions.js') }}"></script>

    <script>

        const param = @json($randoms);
        let num = 0;
        let JSPhrases = @json($phrases);
        const next = document.querySelector('#next');
        //「わからない」フレーズのIDを格納する配列
        let UnKnownQuestionIds = [];

        next.addEventListener('click', () => {
            num = num + 1;
            if (num < param.length) {
                document.getElementById("japanese").innerHTML = `<p>${JSPhrases[param[num]]['japanese']}</p>`;
                document.getElementById("phrase").innerHTML = ``;
                document.getElementById("memo").innerHTML = ``;
                
            } else {
                const again = document.getElementById('again');
                const button = document.createElement('button');
                const UnKnownAgain = document.getElementById('UnKnownAgain');
                const UnKnownbutton = document.createElement('button');
                


                document.getElementById("answer").innerHTML = '';
                document.getElementById("japanese").innerHTML = '';
                document.getElementById("phrase").innerHTML = '';
                document.getElementById("memo").innerHTML = '';
                document.getElementById("Known").innerHTML = '';
                document.getElementById("UnKnown").innerHTML = '';
                document.getElementById("next").innerHTML = '';
                
                button.textContent = 'もう一度';
                button.className = "btn btn-primary know-md"
                button.style = "padding:1rem 2rem;margin-top:3rem; border:1px solid #ccc;"
                button.style.fontSize = "1rem"
                button.addEventListener('click', function() {
                window.location.href = 'quiz_all';
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
                    UnKnownSubmit.className = 'btn btn-primary unknow-md';
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
                document.getElementById("memo").innerHTML = `<p>${JSPhrases[param[num]]['memo']}</p>`;

                if(quiz =="Known" || quiz =="UnKnown"){
                    document.getElementById("next").innerHTML = `<a href="javascript:;" style="text-decoration:none;" onclick="Display_JS('next')" id="next">▶️▶️</a>`
                }

                //「わからない」ボタンがクリックされた時のイベントハンドラ
                if(quiz == "UnKnown"){
                    let currentQuestionId = `${JSPhrases[param[num]]['id']}`;
                    //idの重複なし機能
                    if(!UnKnownQuestionIds.includes(currentQuestionId)){
                        UnKnownQuestionIds.push(currentQuestionId);
                    }

                    localStorage.setItem('UnKnownQuestionIds', JSON.stringify(UnKnownQuestionIds));
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

