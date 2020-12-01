
function MsgChange(inputText_Msg, div_previewText, label_previewMsg){
  // inputに値を入れる
  let val = inputText_Msg.value.trimEnd();
  label_previewMsg.innerText = val;

    if(val == ""){
        div_previewText.style.display = "none";
    }else{
        div_previewText.style.display = "block";
  }

}

function OnFileSelect(obj, img_previewImg){
  // 選択ファイル
  var fileList = obj.files;
  var file = fileList[ 0 ];

  // ファイルの読み込み(Data URI Schemeの取得)
  // FileReaderを生成
  var fileReader = new FileReader();
  fileReader.readAsDataURL( file );

  // 読み込み完了時の処理を追加
  fileReader.onload = function() {
    img_previewImg.src = this.result;
  }
}
