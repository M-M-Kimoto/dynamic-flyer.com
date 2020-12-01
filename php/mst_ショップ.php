<?php

class MstShop_Ctrl
{

  /*
  Mst_ユーザ のエラーチェック
  */
  function check($PI_ary){

    // 初期処理
    $PO_ary = array('status'=>結果['問題なし'], 'msg'=>array());
  
    // ショップID
    $PO_ary = check_primaryItem('ID',$PI_ary, 50, $PO_ary);

    //パスワード
    check_primaryItem('パスワード', $PI_ary, 50, $PO_ary);
    if (preg_match('/\A(?=.*?[a-z])(?=.*?\d)(?=.*?[!-\/:-@[-`{-~])[!-~]{8,50}+\z/i',  $PI_ary['パスワード'] ) == false) {
      //パターンに一致しない
      $PO_ary['status'] = 結果['問題あり'];
      $PO_ary['msg']['パスワード'] = '半角英数字記号をそれぞれ1種類以上含む8文字以上50文字以下で入力してください。';
    }

    // 正式名称
    $PO_ary = check_primaryItem('正式名称',$PI_ary, 100, $PO_ary);

    // 略称
    $PO_ary = check_primaryItem('略称',$PI_ary, 100, $PO_ary);

    // 電話番号
    $PO_ary = check_nomalItem_match('電話番号',$PI_ary, 20, '/^[0-9-]+$/',$PO_ary);

    // FAX番号
    $PO_ary = check_nomalItem_match('FAX番号',$PI_ary, 20, '/^[0-9-]+$/', $PO_ary);

    // メールアドレス
    $PO_ary = check_nomalItem('メールアドレス',$PI_ary, 100, $PO_ary);

    // 郵便番号
    $PO_ary = check_nomalItem_match('郵便番号',$PI_ary, 8, '/^[0-9-]+$/', $PO_ary);

    // 都道府県
    $PO_ary = check_primaryItem('都道府県',$PI_ary, 20, $PO_ary);

    // 市区町村
    $PO_ary = check_primaryItem('市区町村',$PI_ary, 20, $PO_ary);

    // 町名番地
    $PO_ary = check_nomalItem('町名番地',$PI_ary, 20, $PO_ary);

    // 建物等
    $PO_ary = check_nomalItem('建物等',$PI_ary, 200, $PO_ary);

    // タグ
    $PO_ary = check_nomalItem('タグ',$PI_ary, 500, $PO_ary);

    // ショップ種類コード
    $PO_ary = check_nomalItem('ショップ種類コード',$PI_ary, 10, $PO_ary);

    // 喫煙可
    $PO_ary = check_flg('喫煙可',$PI_ary, $PO_ary);

    // 駐車場有
    $PO_ary = check_flg('駐車場有',$PI_ary, $PO_ary);

    // メッセージ
    /* 表示領域の関係で80 */
    $PO_ary = check_nomalItem('メッセージ',$PI_ary, 80, $PO_ary);
    if(3 < substr_count($PI_ary['メッセージ'],"\n")){
      $PO_ary['status'] = 結果['問題あり'];
      $PO_ary['msg']['メッセージ'] = エラーメッセージ['chk_returnCode_overCount'];
    }

    return $PO_ary;

  }

  
  function select($PI_ary) {
      $sql = '';
      $sql = $sql. 'select ';
      $sql = $sql. '`ID`,';
      $sql = $sql. '`パスワード`,';
      $sql = $sql. '`正式名称`,';
      $sql = $sql. '`略称`,';
      $sql = $sql. '`電話番号`,';
      $sql = $sql. '`FAX番号`,';
      $sql = $sql. '`メールアドレス`,';
      $sql = $sql. '`郵便番号`,';
      $sql = $sql. '`都道府県`,';
      $sql = $sql. '`市区町村`,';
      $sql = $sql. '`町名番地`,';
      $sql = $sql. '`建物等`,';
      $sql = $sql. '`ショップ種類コード`,';
      $sql = $sql. '`喫煙可`,';
      $sql = $sql. '`駐車場有`,';
      $sql = $sql. '`メッセージ`,';
      $sql = $sql. '`タグ`';
      $sql = $sql. ' from `mst_ショップ`';
      $sql = $sql. ' where 1 = 1';
      if (array_key_exists('ID', $PI_ary)) {
        $sql = $sql. ' AND `ID` = "'.$PI_ary['ID'].'"';
      }else{
        return ;
      }
      if (array_key_exists('パスワード', $PI_ary)) {
        $sql = $sql. ' AND `パスワード` = "'.$PI_ary['パスワード'].'"';
      }
      $sql = $sql. ' order by `ID`';
      $sql = $sql. ';';

      return $sql;
  }

  function insert($PI_ary) {

    $sql = '';
    $sql = $sql. 'insert into `mst_ショップ` (';
    $sql = $sql. ' `ID`,';
    $sql = $sql. ' `パスワード`,';
    $sql = $sql. ' `正式名称`,';
    $sql = $sql. ' `略称`,';
    $sql = $sql. ' `電話番号`,';
    $sql = $sql. ' `FAX番号`,';
    $sql = $sql. ' `メールアドレス`,';
    $sql = $sql. ' `郵便番号`,';
    $sql = $sql. ' `都道府県`,';
    $sql = $sql. ' `市区町村`,';
    $sql = $sql. ' `町名番地`,';
    $sql = $sql. ' `建物等`,';
    $sql = $sql. ' `ショップ種類コード`,';
    $sql = $sql. ' `喫煙可`,';
    $sql = $sql. ' `駐車場有`,';
    $sql = $sql. ' `メッセージ`,';
    $sql = $sql. ' `タグ`';
    $sql = $sql. ')values(';

    if (array_key_exists('ID', $PI_ary)) {
      $sql = $sql. '  "'.$PI_ary['ID'].'"';
    }else{
      return;
    }
    if (array_key_exists('パスワード', $PI_ary)) {
      $sql = $sql. ' ,"'.$PI_ary['パスワード'].'"';
    }else{
      return;
    }
    if (array_key_exists('正式名称', $PI_ary)) {
      $sql = $sql. ' ,"'.$PI_ary['正式名称'].'"';
    }else{
      $sql = $sql. ' ,""';
    }
    if (array_key_exists('略称', $PI_ary)) {
      $sql = $sql. ' ,"'.$PI_ary['略称'].'"';
    }else{
      $sql = $sql. ' ,""';
    }
    if (array_key_exists('電話番号', $PI_ary)) {
      $sql = $sql. ' ,"'.$PI_ary['電話番号'].'"';
    }else{
      $sql = $sql. ' ,""';
    }
    if (array_key_exists('FAX番号', $PI_ary)) {
      $sql = $sql. ' ,"'.$PI_ary['FAX番号'].'"';
    }else{
      $sql = $sql. ' ,""';
    }
    if (array_key_exists('メールアドレス', $PI_ary)) {
      $sql = $sql. ' ,"'.$PI_ary['メールアドレス'].'"';
    }else{
      $sql = $sql. ' ,""';
    }
    if (array_key_exists('郵便番号', $PI_ary)) {
      $sql = $sql. ' ,"'.$PI_ary['郵便番号'].'"';
    }else{
      $sql = $sql. ' ,""';
    }
    if (array_key_exists('都道府県', $PI_ary)) {
      $sql = $sql. ' ,"'.$PI_ary['都道府県'].'"';
    }else{
      $sql = $sql. ' ,""';
    }
    if (array_key_exists('市区町村', $PI_ary)) {
      $sql = $sql. ' ,"'.$PI_ary['市区町村'].'"';
    }else{
      $sql = $sql. ' ,""';
    }
    if (array_key_exists('町名番地', $PI_ary)) {
      $sql = $sql. ' ,"'.$PI_ary['町名番地'].'"';
    }else{
      $sql = $sql. ' ,""';
    }
    if (array_key_exists('建物等', $PI_ary)) {
      $sql = $sql. ' ,"'.$PI_ary['建物等'].'"';
    }else{
      $sql = $sql. ' ,""';
    }
    if (array_key_exists('ショップ種類コード', $PI_ary)) {
      $sql = $sql. ' ,"'.$PI_ary['ショップ種類コード'].'"';
    }else{
      $sql = $sql. ' ,""';
    }
    if (array_key_exists('喫煙可', $PI_ary)) {
      $sql = $sql. ' ,"'.$PI_ary['喫煙可'].'"';
    }else{
      $sql = $sql. ' ,"0"';
    }
    if (array_key_exists('駐車場有', $PI_ary)) {
      $sql = $sql. ' ,"'.$PI_ary['駐車場有'].'"';
    }else{
      $sql = $sql. ' ,"0"';
    }
    if (array_key_exists('メッセージ', $PI_ary)) {
      $sql = $sql. ' ,"'.$PI_ary['メッセージ'].'"';
    }else{
      $sql = $sql. ' ,""';
    }
    if (array_key_exists('タグ', $PI_ary)) {
      $sql = $sql. ' ,"'.$PI_ary['タグ'].'"';
    }else{
      $sql = $sql. ' ,""';
    }
    $sql = $sql. ');';

    return $sql;
}

  function update($PI_ary) {
    $sql = '';
    $sql = $sql. 'update `mst_ショップ` ';
    $sql = $sql. ' set';
    // パスワードは必須のため
    $sql = $sql. '  `パスワード`         = "'.$PI_ary['パスワード'].'"';
    if (array_key_exists('正式名称', $PI_ary)) {
      $sql = $sql. ', `正式名称`           = "'.$PI_ary['正式名称'].'"';
    }
    if (array_key_exists('略称', $PI_ary)) {
      $sql = $sql. ', `略称`               = "'.$PI_ary['略称'].'"';
    }
    if (array_key_exists('電話番号', $PI_ary)) {
      $sql = $sql. ', `電話番号`           = "'.$PI_ary['電話番号'].'"';
    }
    if (array_key_exists('FAX番号', $PI_ary)) {
      $sql = $sql. ', `FAX番号`            = "'.$PI_ary['FAX番号'].'"';
    }
    if (array_key_exists('メールアドレス', $PI_ary)) {
      $sql = $sql. ', `メールアドレス`     = "'.$PI_ary['メールアドレス'].'"';
    }
    if (array_key_exists('郵便番号', $PI_ary)) {
      $sql = $sql. ', `郵便番号`           = "'.$PI_ary['郵便番号'].'"';
    }
    if (array_key_exists('都道府県', $PI_ary)) {
      $sql = $sql. ', `都道府県`           = "'.$PI_ary['都道府県'].'"';
    }
    if (array_key_exists('市区町村', $PI_ary)) {
      $sql = $sql. ', `市区町村`           = "'.$PI_ary['市区町村'].'"';
    }
    if (array_key_exists('町名番地', $PI_ary)) {
      $sql = $sql. ', `町名番地`           = "'.$PI_ary['町名番地'].'"';
    }
    if (array_key_exists('建物等', $PI_ary)) {
      $sql = $sql. ', `建物等`             = "'.$PI_ary['建物等'].'"';
    }
    if (array_key_exists('ショップ種類コード', $PI_ary)) {
      $sql = $sql. ', `ショップ種類コード` = "'.$PI_ary['ショップ種類コード'].'"';
    }
    if (array_key_exists('喫煙可', $PI_ary)) {
      $sql = $sql. ', `喫煙可`             = "'.$PI_ary['喫煙可'].'"';
    }
    if (array_key_exists('駐車場有', $PI_ary)) {
      $sql = $sql. ', `駐車場有`           = "'.$PI_ary['駐車場有'].'"';
    }
    if (array_key_exists('メッセージ', $PI_ary)) {
      $sql = $sql. ', `メッセージ`               = "'.$PI_ary['メッセージ'].'"';
    }
    if (array_key_exists('タグ', $PI_ary)) {
      $sql = $sql. ', `タグ`               = "'.$PI_ary['タグ'].'"';
    }
    $sql = $sql. ', `最終更新日`         = Now()';
    $sql = $sql. ' where 1 = 1';
    if (array_key_exists('ID', $PI_ary)) {
      $sql = $sql. ' AND `ID` = "'.$PI_ary['ID'].'"';
    }
    $sql = $sql. ';';

    return $sql;
}

}



