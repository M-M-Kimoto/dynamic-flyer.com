<?php


//define('URL_ROOT', `../../`);
class MstShopMainPage_Ctrl
{

  /*
  エラーチェック
  */
  function check($PI_ary){

    // 初期処理
    $PO_ary = array('status'=>結果['問題なし'], 'msg'=>array());

    return $PO_ary;
  }

  function select($PI_ary) {
      $sql = '';
      $sql = $sql. 'select ';
      $sql = $sql. ' `ショップID`,';
      $sql = $sql. ' `ID`,';
      $sql = $sql. ' `画像パス`,';
      $sql = $sql. ' `画像メッセージ`,';
      $sql = $sql. ' `更新日時`';
      $sql = $sql. ' from `mst_ショップ基本ページ詳細情報`';
      $sql = $sql. ' where 1 = 1';
      if (array_key_exists('ショップID', $PI_ary)) {
        $sql = $sql. ' AND `ショップID` = "'.$PI_ary['ショップID'].'"';
      }
      if (array_key_exists('ID', $PI_ary)) {
        $sql = $sql. ' AND `ID` = "'.$PI_ary['ID'].'"';
      }
      $sql = $sql. ' order by `ショップID`';
      $sql = $sql. ';';

      return $sql;
  }

  function update($PI_ary) {

    $sql = '';
    $sql = $sql. 'update `mst_ショップ基本ページ詳細情報` ';
    $sql = $sql. ' set';
    $sql = $sql. '   `更新日時` = Now()';
    if (array_key_exists('画像パス', $PI_ary)) {
      $sql = $sql. ' ,`画像パス`           = "'.$PI_ary['画像パス'].'"';
    }
    if (array_key_exists('画像メッセージ', $PI_ary)) {
      $sql = $sql. ' ,`画像メッセージ`           = "'.$PI_ary['画像メッセージ'].'"';
    }
    $sql = $sql. ' where 1 = 1';
    if (array_key_exists('ショップID', $PI_ary)) {
      $sql = $sql. ' AND `ショップID` = "'.$PI_ary['ショップID'].'"';
    }else{
      return ;
    }
    if (array_key_exists('ID', $PI_ary)) {
      $sql = $sql. ' AND `ID` = "'.$PI_ary['ID'].'"';
    }else{
      return ;
    }
    $sql = $sql. ';';

    return $sql;
  }

  function insert($PI_ary) {
    // 連番などは自動登録のため、insert時に指定しない
    $sql = "";
    $sql = $sql. 'insert into `mst_ショップ基本ページ詳細情報`(';
    $sql = $sql. ' `ショップID`,';
    $sql = $sql. ' `ID`,';
    $sql = $sql. ' `画像パス`,';
    $sql = $sql. ' `画像メッセージ`,';
    $sql = $sql. ' `更新日時`';
    $sql = $sql. ")VALUES(";
    $sql = $sql. '  "'.$PI_ary['ショップID'].'"';
    $sql = $sql. ' ,"'.$PI_ary['ID'].'"';
    $sql = $sql. ' ,"'.$PI_ary['画像パス'].'"';
    $sql = $sql. ' ,"'.$PI_ary['画像メッセージ'].'"';
    $sql = $sql. ' ,Now()';
    $sql = $sql. ' );';

    return $sql;
  }

  function delete($PI_ary){

    $sql = '';
    $sql = $sql. 'delete from `mst_ショップ基本ページ詳細情報` ';
    $sql = $sql. ' where 1 = 1';
    if (array_key_exists('ショップID', $PI_ary)) {
      $sql = $sql. ' AND `ショップID` = "'.$PI_ary['ショップID'].'"';
    }else{
      return ;
    }
    if (array_key_exists('ID', $PI_ary)) {
      $sql = $sql. ' AND `ID` = "'.$PI_ary['ID'].'"';
    }else{
      return ;
    }
    $sql = $sql. ';';

    return $sql;
  }


}


?>
