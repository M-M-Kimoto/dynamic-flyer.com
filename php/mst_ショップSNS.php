<?php


//define('URL_ROOT', `../../`);
class MstShopSNS_Ctrl
{

  /*
  エラーチェック
  */
  function check($PI_ary){

    // 初期処理
    $PO_ary = array('status'=>結果['問題なし'], 'msg'=>array());

    
    // SNSID
    $PO_ary = check_primaryItem('SNSID',$PI_ary, 2, $PO_ary);

    // URL
    $PO_ary = check_primaryItem('URL',$PI_ary, 100, $PO_ary);

    // ショップID
    $PO_ary = check_primaryItem('ショップID',$PI_ary, 10, $PO_ary);

    return $PO_ary;
  }

  function select_Join_MstSNS($PI_ary){

    $sql = '';
    $sql = $sql . ' select ';
    $sql = $sql . '     `shopSNS`.`ショップID`,';
    $sql = $sql . '     `shopSNS`.`SNSID`,';
    $sql = $sql . '     `SNS`.`名称`,';
    $sql = $sql . '     `shopSNS`.`URL`,';
    $sql = $sql . '     `SNS`.`imgパス`';
    $sql = $sql . ' from `mst_ショップSNS` as `shopSNS`';
    $sql = $sql . ' inner join  `mst_SNS` as `SNS` on (1 = 1';
    $sql = $sql . '     and `shopSNS`.`SNSID` = `SNS`.`ID`';
    $sql = $sql . ' )';
    $sql = $sql . ' where 1 = 1';
    if (array_key_exists('ショップID', $PI_ary)) {
      $sql = $sql . ' and `shopSNS`.`ショップID` = "'.$PI_ary['ショップID'].'"';
    }
    if (array_key_exists('SNSID', $PI_ary)) {
      $sql = $sql . ' and `shopSNS`.`SNSID` = "'.$PI_ary['SNSID'].'"';
    }
    $sql = $sql. ' order by `shopSNS`.`ショップID`, `SNS`.`ID`';
    $sql = $sql. ';';

    return $sql;
  }

  function select($PI_ary) {
      $sql = '';
      $sql = $sql. 'select ';
      $sql = $sql. ' `ショップID`,';
      $sql = $sql. ' `SNSID`,';
      $sql = $sql. ' `URL`,';
      $sql = $sql. ' `更新日`';
      $sql = $sql. ' from `mst_ショップSNS`';
      $sql = $sql. ' where 1 = 1';
      if (array_key_exists('ショップID', $PI_ary)) {
        $sql = $sql. ' AND `ショップID` = "'.$PI_ary['ショップID'].'"';
      }
      $sql = $sql. ' order by `ショップID`, `SNSID`';
      $sql = $sql. ';';

      return $sql;
  }

  function update($PI_ary) {

    $sql = '';
    $sql = $sql. 'update `mst_ショップSNS` ';
    $sql = $sql. ' set';
    $sql = $sql. '     `更新日時` = Now()';
    if (array_key_exists('URL', $PI_ary)) {
      $sql = $sql. '  ,`URL`           = "'.$PI_ary['URL'].'"';
    }
    $sql = $sql. ' where 1 = 1';
    if (array_key_exists('ショップID', $PI_ary)) {
      $sql = $sql. ' AND `ショップID` = "'.$PI_ary['ショップID'].'"';
    }else{
      return ;
    }
    if (array_key_exists('SNSID', $PI_ary)) {
      $sql = $sql. ' AND `SNSID` = "'.$PI_ary['SNSID'].'"';
    }else{
      return ;
    }
    $sql = $sql. ';';

    return $sql;
  }

  function insert($PI_ary) {
    // 連番などは自動登録のため、insert時に指定しない
    $sql = "";
    $sql = $sql. 'insert into `mst_ショップSNS`(';
    $sql = $sql. ' `ショップID`,';
    $sql = $sql. ' `SNSID`,';
    $sql = $sql. ' `URL`,';
    $sql = $sql. ' `更新日時`';
    $sql = $sql. ")VALUES(";
    $sql = $sql. '  "'.$PI_ary['ショップID'].'"';
    $sql = $sql. ' ,"'.$PI_ary['SNSID'].'"';
    $sql = $sql. ' ,"'.$PI_ary['URL'].'"';
    $sql = $sql. ' ,Now()';
    $sql = $sql. ' );';

    return $sql;
  }

  function delete($PI_ary){

    $sql = '';
    $sql = $sql. 'delete from `mst_ショップSNS` ';
    $sql = $sql. ' where 1 = 1';
    if (array_key_exists('ショップID', $PI_ary)) {
      $sql = $sql. ' AND `ショップID` = "'.$PI_ary['ショップID'].'"';
    }else{
      return ;
    }
    if (array_key_exists('SNSID', $PI_ary)) {
      $sql = $sql. ' AND `SNSID` = "'.$PI_ary['SNSID'].'"';
    }else{
      return ;
    }
    $sql = $sql. ';';

    return $sql;
  }

}


?>
