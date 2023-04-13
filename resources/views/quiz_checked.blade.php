@extends('layouts.quiz')


@section('content')

<h5 style="display:inline;" id="list"><a href="/home">一覧へ</a></h5>
<div style="display:inline;" id="again"></div>


<h5 style="display:inline;"><a href="javascript:;" onclick="Display_JS('answer')" id = "answer">答え</a></h5>


<div>
    <div id="japanese">
        <p scope="row" style="display:inline-flex" class="japanese">{{$phrase_checked[$randoms_checked[0]]['japanese']}}</p>
    </div>
    <div id="phrase">
        <p scope="row" style="display:inline-flex" class="english">{{$phrase_checked[$randoms_checked[0]]['phrase']}}</p>
    </div>
    <div id="memo">
        <p scope="row" style="display:inline-flex" class="memo">{{$phrase_checked[$randoms_checked[0]]['memo']}}</p>
    </div>
</div>

<h5 style="display:inline;"><a href="javascript:;" onclick="Display_JS('Known')" id="Known">わかる</a></h5>
<h5 style="display:inline;"><a href="javascript:;" onclick="Display_JS('UnKnown')" id="UnKnown">わからない</a></h5> 

<!-- <div id="UnknowCheck">
    <p>チェック</p>
</div> -->


<form method="post" action="{{route('update_checklist')}}"  id="checklist">
    @csrf
    <?php session()->put(('redirect_url'), route('quiz_checked')) ?>
    <div type="hidden" name="agree" id="agree" value="checked" onchange="checkForm(this.form)"></div>
    <input type="hidden" name="japanese" id="japanese_check">
    <input type="hidden" name="phrase" id="phrase_check">
    <input type="hidden" name="memo" id="memo_check">
    <input type="hidden" name="phrase_id" id="phrase_id_check">
    <input type="hidden" name="checklist" id="checklist_check">
</form>

<h5><a href="javascript:;" onclick="Display_JS('next')" id="next">次へ</a></h5> 

@endsection

@section('javascript')

<script src="{{ asset('/js/questions.js') }}"></script>
<!-- <script src="/js/questions.js"></script> -->

<script>
    const param = @json($randoms_checked);
    let num = 0;
    let JSPhrases = @json($phrase_checked);
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
            const again = document.getElementById('again')
            const button = document.createElement('button');
            button.textContent = 'もう一度';
            button.addEventListener('click', function() {
            window.location.href = 'quiz_all';
            });
            again.appendChild(button);
        }
        Eelements.forEach(element => element.style.display = 'none');
        Melements.forEach(element => element.style.display = 'none');
        // document.getElementById("UnknowCheck").innerHTML = '';
        document.getElementById("agree").innerHTML = '';


    });

    function Display_JS(quiz){
        if(quiz == "answer" || quiz == "Known" || quiz == "UnKnown"){
            const checklist = `${JSPhrases[param[num]]['checklist']}`; 
            console.log(checklist);
            if(checklist =="checked"){
                //すでにcheckされている状態にしておく。
                // document.getElementById("UnknowCheck").innerHTML = '<input type="checkbox" name="UnknowCheck" id="UnknowCheck" checked>'
                document.getElementById("agree").innerHTML = '<input type="checkbox" name="agree" id="agree" value="checked"  onchange="checkForm(this.form)" checked>'
                console.log("チェックされているよ。");
            }
            else{
                document.getElementById("agree").innerHTML = '<input type="checkbox" name="agree" id="agree" value="checked"  onchange="checkForm(this.form)">'
                // document.getElementById("UnknowCheck").innerHTML = '<input type="checkbox" name="UnknowCheck" id="UnknowCheck">'
            }
            document.getElementById("japanese").innerHTML = `<p>${JSPhrases[param[num]]['japanese']}</p>`;
            document.getElementById("phrase").innerHTML = `<p>${JSPhrases[param[num]]['phrase']}</p>`;
            document.getElementById("memo").innerHTML = `<p>${JSPhrases[[num]]['memo']}</p>`;
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





