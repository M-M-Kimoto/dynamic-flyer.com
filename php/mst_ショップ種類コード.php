<?php

class MstShopKindCode_Ctrl
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
      $sql = $sql. '`ID`,';
      $sql = $sql. '`名称`,';
      $sql = $sql. '`表示色`';
      $sql = $sql. ' from `mst_ショップ種類コード`';
      $sql = $sql. ' where 1 = 1';
      if (array_key_exists('ID', $PI_ary)) {
        $sql = $sql. ' AND `ID` = "'.$PI_ary['ID'].'"';
      }
      $sql = $sql. ' order by `ID`';
      $sql = $sql. ';';

      return $sql;
  }

}



