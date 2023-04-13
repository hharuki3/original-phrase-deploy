    // HTML内の「checkbox」要素を取得する
    //この引数はinputタグのidと繋がっていて、ボタンが押されたらcheckboxに値が入るということ？
    //console.logで確認したところ、checkboxに「input#checkbox」という値が出力されていた。
    const checkbox = document.querySelector('#checkbox');
    // チェックボックスがクリックされたときの処理
    //<p>タグであれば非表示にできるが<th>タグだと効果がない。なぜ？
    checkbox.addEventListener('click', () => {
      // 非同期処理を開始する
      setTimeout(() => {    
        // HTML内の「english」クラスを持つ要素を取得する
        const elements = document.querySelectorAll('.english');
        if(checkbox.checked){
            elements.forEach(element => element.style.display = 'none');
    
        }else{
            elements.forEach(element => element.style.display = '');
        }
        // 取得した要素をすべて非表示にする
      }, 0);
    });

