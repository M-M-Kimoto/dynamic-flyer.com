<?php


//define('URL_ROOT', `../../`);
class MstNoticeCode_Ctrl
{

  /*
  エラーチェック
  */
  function check($PI_ary){

    $PO_ary = array('status'=>true, 'msg'=>array());

    return $PO_ary;
  }

  function select() {
      $sql = '';
      $sql = $sql. 'select ';
      $sql = $sql. ' `ID`,';
      $sql = $sql. ' `名称` ';
      $sql = $sql. ' from `mst_通知区分コード`';
      $sql = $sql. ' where 1 = 1';
      $sql = $sql. ' order by `ID`';
      $sql = $sql. ';';

      return $sql;
  }

}



