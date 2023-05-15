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
    <div id="again"></div>
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
            <a href="javascript:;" style="text-decoration:none;" onclick="Display_JS('next')" id="next">
                ▶️▶️
            </a>
        </div>
    </div> 

    <div class="row">
        <div class="col-md-6 text-center px-0">
            <a href="javascript:;" style="text-decoration:none;"onclick="Display_JS('Known')" id="Known">
                <div style="margin-left:10em;padding:5em; border:1px solid #ccc;">
                    わかる
                </div>
            </a>
        </div>
        <div class="col-md-6 text-center px-0">
            <a href="javascript:;"  style="text-decoration:none;" onclick="Display_JS('UnKnown')" id="UnKnown">
            <div style="margin-right:10em;padding:5em; border:1px solid #ccc;">
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
        //console.log(param);
        let JSPhrases = @json($phrases);
        const next = document.querySelector('#next');

        next.addEventListener('click', () => {
            num = num + 1;
            console.log(param[num]);
            if (num < param.length) {
                document.getElementById("japanese").innerHTML = `<p>${JSPhrases[param[num]]['japanese']}</p>`;
                document.getElementById("phrase").innerHTML = ``;
                document.getElementById("memo").innerHTML = ``;
                
            } else {
                document.getElementById("answer").innerHTML = '';
                document.getElementById("japanese").innerHTML = '';
                document.getElementById("Known").innerHTML = '';
                document.getElementById("UnKnown").innerHTML = '';
                document.getElementById("next").innerHTML = '';
                const again = document.getElementById('again');
                const button = document.createElement('button');
                button.textContent = 'もう一度';
                button.className = "btn btn-primary my-5"
                button.style.fontSize = "1rem"
                button.addEventListener('click', function() {
                window.location.href = 'quiz_all';
                });
                again.appendChild(button);
                // document.body.appendChild(button); 
            }
            Eelements.forEach(element => element.style.display = 'none');
            Melements.forEach(element => element.style.display = 'none');
            document.getElementById("agree").innerHTML = '';

        });

        function Display_JS(quiz){
            if(quiz == "answer" || quiz == "Known" || quiz == "UnKnown"){

                const checklist = `${JSPhrases[param[num]]['checklist']}`; 
                console.log(checklist);
                if(checklist =="checked"){
                    //すでにcheckされている状態にしておく。
                    document.getElementById("agree").innerHTML = '<input type="checkbox" name="agree" id="agree" value="checked"  onchange="checkForm(this.form)" checked>'
                    console.log("チェックされているよ。");
                }
                else{
                    document.getElementById("agree").innerHTML = '<input type="checkbox" name="agree" id="agree" value="checked"  onchange="checkForm(this.form)">'

                }
                document.getElementById("japanese").innerHTML = `<p>${JSPhrases[param[num]]['japanese']}</p>`;
                document.getElementById("phrase").innerHTML = `<p>${JSPhrases[param[num]]['phrase']}</p>`;
                document.getElementById("memo").innerHTML = `<p>${JSPhrases[param[num]]['memo']}</p>`;
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

<!-- チェックボックスを押すと、phraseのテーブルにあるis_existカラムにチェックがつく。
    んで、チェックがついたphraseだけ出題させる。
    テーブルに登録していく必要があるということは、postでcontroller側に値を飛ばして、そこでカラムに追加する作業が必要になる。
    postで飛ばすということは、「次」ボタンを押したら画面遷移しなければならないということになる。
    そうなるとせっかくjsで画面遷移なく次の問題に移行できたのに意味がなくなる。  
    可能ならカラムを使わずに、いや値を保持するにはデータベースに値を登録しなければならない？
    そうなると、postで送信が条件になるから、コードを書き換える必要性が出てくる。面倒臭い -->



<!-- jsの読み込みはyieldでcontentを先に読み込んだとしても
    section('javascript')はcontentの後に記述した方が良い。 -->


<!-- ElementCountの名前を適切な名前に変更
    チェックリスト機能はphraseテーブルにis_set?, is_exist?のカラムを設けて、true or falseでチェックしてるかどうかを判断
    後もう一つくらいあった気がするけどなんだったかな。
    `〇〇` この点々のなかで${}で記述したJSコードを有効にできる。
    laravelのタブを自動調整してくれる拡張機能を入れる
     -->





