<?php

class TraUserFavorite_Ctrl
{

  /*
  エラーチェック
  */
  function check($PI_ary){

    $PO_ary = array('status'=>true, 'msg'=>array());
    return $PO_ary;
  }

  function insert($PI_ary){

    $sql = "";
    $sql = $sql. 'insert into `tra_ユーザお気に入り`(';
    $sql = $sql. ' `ユーザID`,';
    $sql = $sql. ' `ショップID`,';
    $sql = $sql. ' `登録日時`';
    $sql = $sql. ')values(';
    $sql = $sql. '    "'.$PI_ary['ユーザID'].'",';
    $sql = $sql. '    "'.$PI_ary['ショップID'].'",';
    $sql = $sql. '    Now()';
    $sql = $sql. ');';

    return $sql;


    return ;

  }
  function insert_his($PI_ary){

    $sql = "";
    $sql = $sql. 'insert into `tra_履歴_ユーザお気に入り`(';
    $sql = $sql. ' `ユーザID`,';
    $sql = $sql. ' `ショップID`,';
    $sql = $sql. ' `登録日時`,';
    $sql = $sql. ' `処理区分`';
    $sql = $sql. ')values(';
    $sql = $sql. '    "'.$PI_ary['ユーザID'].'",';
    $sql = $sql. '    "'.$PI_ary['ショップID'].'",';
    $sql = $sql. '    Now(),';
    $sql = $sql. '    "'.$PI_ary['処理区分'].'"';
    $sql = $sql. ');';

    return $sql;


    return ;

  }

  function delete($PI_ary) {
    $sql = '';
    $sql = $sql. 'delete from `tra_ユーザお気に入り` ';
    $sql = $sql. ' where 1 = 1';
    $sql = $sql. ' and `ユーザID`   = "'.$PI_ary['ユーザID'].'"';
    $sql = $sql. ' and `ショップID` = "'.$PI_ary['ショップID'].'"';
    $sql = $sql. ';';

    return $sql;
  }

}



