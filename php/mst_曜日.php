<?php


//define('URL_ROOT', `../../`);
class MstWeek_Ctrl
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
      $sql = $sql. ' from `mst_曜日`';
      $sql = $sql. ' where 1 = 1';
      $sql = $sql. ' order by `ID`';
      $sql = $sql. ';';

      return $sql;
  }

}



