<?php


//define('URL_ROOT', `../../`);
class TraShopStatus_Ctrl
{

  /*
  エラーチェック
  */
  function check($PI_ary){

    $PO_ary = array('status'=>true, 'msg'=>array());

    return $PO_ary;
  }
  
  function select($PI_ary) {
      $sql = '';
      $sql = $sql. 'select ';
      $sql = $sql. ' `ショップID`,';
      $sql = $sql. ' `リンク登録可能数`,';
      $sql = $sql. ' `お気に入り機能`,';
      $sql = $sql. ' `連絡機能`,';
      $sql = $sql. ' `予約機能`,';
      $sql = $sql. ' `採用活動機能`,';
      $sql = $sql. ' `運営管理フラグ`,';
      $sql = $sql. ' `更新日時`';
      $sql = $sql. ' from `tra_ショップステータス`';
      $sql = $sql. ' where 1 = 1';
      if (array_key_exists('ショップID', $PI_ary)) {
        $sql = $sql. ' AND `ショップID` = "'.$PI_ary['ショップID'].'"';
      }
      $sql = $sql. ' order by `ショップID`';
      $sql = $sql. ';';

      return $sql;
  }

  function insert($PI_ary) {
    $sql = '';
    $sql = $sql. 'insert into `tra_ショップステータス`(';
    $sql = $sql. ' `ショップID`,';
    $sql = $sql. ' `リンク登録可能数`,';
    $sql = $sql. ' `お気に入り機能`,';
    $sql = $sql. ' `連絡機能`,';
    $sql = $sql. ' `予約機能`,';
    $sql = $sql. ' `採用活動機能`,';
    $sql = $sql. ' `運営管理フラグ`,';
    $sql = $sql. ' `登録ユーザID`,';
    $sql = $sql. ' `更新日時`';
    $sql = $sql. ')values(';
    
    if (array_key_exists('ショップID', $PI_ary)) {
      $sql = $sql. '  "'.$PI_ary['ショップID'].'"';
    }else{
      return;
    }
    if (array_key_exists('リンク登録可能数', $PI_ary)) {
      $sql = $sql. '  ,"'.$PI_ary['リンク登録可能数'].'"';
    }else{
      $sql = $sql. '  ,"3"';
    }
    if (array_key_exists('お気に入り機能', $PI_ary)) {
      $sql = $sql. '  ,"'.$PI_ary['お気に入り機能'].'"';
    }else{
      $sql = $sql. '  ,"1"';
    }
    if (array_key_exists('連絡機能', $PI_ary)) {
      $sql = $sql. '  ,"'.$PI_ary['連絡機能'].'"';
    }else{
      $sql = $sql. '  ,"0"';
    }
    if (array_key_exists('予約機能', $PI_ary)) {
      $sql = $sql. '  ,"'.$PI_ary['予約機能'].'"';
    }else{
      $sql = $sql. '  ,"0"';
    }
    if (array_key_exists('採用活動機能', $PI_ary)) {
      $sql = $sql. '  ,"'.$PI_ary['採用活動機能'].'"';
    }else{
      $sql = $sql. '  ,"0"';
    }
    if (array_key_exists('運営管理フラグ', $PI_ary)) {
      $sql = $sql. '  ,"'.$PI_ary['運営管理フラグ'].'"';
    }else{
      $sql = $sql. '  ,"1"';
    }
    if (array_key_exists('登録ユーザID', $PI_ary)) {
      $sql = $sql. '  ,"'.$PI_ary['登録ユーザID'].'"';
    }else{
      return;
    }
    $sql = $sql. '  ,Now()';
    
    $sql = $sql. ');';

    return $sql;
  }

  function update($PI_ary) {
    $sql = '';
    $sql = $sql. 'update `tra_ショップステータス` ';
    $sql = $sql. ' set';
    // パスワードは必須のため
    $sql = $sql. "  `更新日時`         = Now()";
    if (array_key_exists('リンク登録可能数', $PI_ary)) {
      $sql = $sql. ', `リンク登録可能数`           = "'.$PI_ary['リンク登録可能数'].'"';
    }
    if (array_key_exists('お気に入り機能', $PI_ary)) {
      $sql = $sql. ', `お気に入り機能`               = "'.$PI_ary['お気に入り機能'].'"';
    }
    if (array_key_exists('連絡機能', $PI_ary)) {
      $sql = $sql. ', `連絡機能`           = "'.$PI_ary['連絡機能'].'"';
    }
    if (array_key_exists('予約機能', $PI_ary)) {
      $sql = $sql. ', `予約機能`            = "'.$PI_ary['予約機能'].'"';
    }
    if (array_key_exists('採用活動機能', $PI_ary)) {
      $sql = $sql. ', `採用活動機能`     = "'.$PI_ary['採用活動機能'].'"';
    }
    if (array_key_exists('運営管理フラグ', $PI_ary)) {
      $sql = $sql. ', `運営管理フラグ`     = "'.$PI_ary['運営管理フラグ'].'"';
    }
    $sql = $sql. ' where 1 = 1';
    if (array_key_exists('ショップID', $PI_ary)) {
      $sql = $sql. ' AND `ショップID` = "'.$PI_ary['ショップID'].'"';
    }
    $sql = $sql. ';';

    return $sql;
}

}



