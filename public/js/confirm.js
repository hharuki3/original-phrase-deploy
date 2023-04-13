
function deleteHandle(event, phraseId) {
    console.log(phraseId);
    event.preventDefault();
    if (window.confirm('本当に削除していいですか？')) {

        document.getElementById(`delete-form-${phraseId}`).submit();
    } else {
        alert('キャンセルしました。');
    }
}
