<?php


//define('URL_ROOT', `../../`);
class MstShopPayMent_Ctrl
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
      $sql = $sql. ' `Visa`,';
      $sql = $sql. ' `JCB`,';
      $sql = $sql. ' `Mastercard`,';
      $sql = $sql. ' `American_Express`,';
      $sql = $sql. ' `Diners_Club`,';
      $sql = $sql. ' `LINE_Pay`,';
      $sql = $sql. ' `PayPay`,';
      $sql = $sql. ' `楽天ペイ`,';
      $sql = $sql. ' `d払い`,';
      $sql = $sql. ' `au_PAY`,';
      $sql = $sql. ' `メルペイ`,';
      $sql = $sql. ' `更新日時`';
      $sql = $sql. ' from `mst_ショップ支払方法`';
      $sql = $sql. ' where 1 = 1';
      if (array_key_exists('ショップID', $PI_ary)) {
        $sql = $sql. ' AND `ショップID` = "'.$PI_ary['ショップID'].'"';
      }
      $sql = $sql. ' order by `ショップID`';
      $sql = $sql. ';';

      return $sql;
  }

  function update($PI_ary) {
    $sql = '';
    $sql = $sql. 'update `mst_ショップ支払方法` ';
    $sql = $sql. ' set';
    $sql = $sql. '    `更新日時`         = Now()';
    if (array_key_exists('Visa', $PI_ary)) {
      $sql = $sql. ', `Visa` = "'.$PI_ary['Visa'].'"';
    }
    if (array_key_exists('JCB', $PI_ary)) {
      $sql = $sql. ', `JCB` = "'.$PI_ary['JCB'].'"';
    }
    if (array_key_exists('Mastercard', $PI_ary)) {
      $sql = $sql. ', `Mastercard` = "'.$PI_ary['Mastercard'].'"';
    }
    if (array_key_exists('American_Express', $PI_ary)) {
      $sql = $sql. ', `American_Express` = "'.$PI_ary['American_Express'].'"';
    }
    if (array_key_exists('Diners_Club', $PI_ary)) {
      $sql = $sql. ', `Diners_Club` = "'.$PI_ary['Diners_Club'].'"';
    }
    if (array_key_exists('LINE_Pay', $PI_ary)) {
      $sql = $sql. ', `LINE_Pay` = "'.$PI_ary['LINE_Pay'].'"';
    }
    if (array_key_exists('PayPay', $PI_ary)) {
      $sql = $sql. ', `PayPay` = "'.$PI_ary['PayPay'].'"';
    }
    if (array_key_exists('楽天ペイ', $PI_ary)) {
      $sql = $sql. ', `楽天ペイ` = "'.$PI_ary['楽天ペイ'].'"';
    }
    if (array_key_exists('d払い', $PI_ary)) {
      $sql = $sql. ', `d払い` = "'.$PI_ary['d払い'].'"';
    }
    if (array_key_exists('au_PAY', $PI_ary)) {
      $sql = $sql. ', `au_PAY` = "'.$PI_ary['au_PAY'].'"';
    }
    if (array_key_exists('メルペイ', $PI_ary)) {
      $sql = $sql. ', `メルペイ` = "'.$PI_ary['メルペイ'].'"';
    }
    $sql = $sql. ' where 1 = 1';
    if (array_key_exists('ショップID', $PI_ary)) {
      $sql = $sql. ' AND `ショップID` = "'.$PI_ary['ショップID'].'"';
    }else{
      return ;
    }
    $sql = $sql. ';';

    return $sql;
  }

  function insert($PI_ary) {
    // 連番などは自動登録のため、insert時に指定しない
    $sql = "";
    $sql = $sql. 'insert into `mst_ショップ支払方法`(';
    $sql = $sql. ' `ショップID`,';
    $sql = $sql. ' `Visa`,';
    $sql = $sql. ' `JCB`,';
    $sql = $sql. ' `Mastercard`,';
    $sql = $sql. ' `American_Express`,';
    $sql = $sql. ' `Diners_Club`,';
    $sql = $sql. ' `LINE_Pay`,';
    $sql = $sql. ' `PayPay`,';
    $sql = $sql. ' `楽天ペイ`,';
    $sql = $sql. ' `d払い`,';
    $sql = $sql. ' `au_PAY`,';
    $sql = $sql. ' `メルペイ`,';
    $sql = $sql. ' `更新日時`';
    $sql = $sql. ")VALUES(";
    $sql = $sql. '  "'.$PI_ary['ショップID'].'"';
    $sql = $sql. ' ,"'.$PI_ary['Visa'].'"';
    $sql = $sql. ' ,"'.$PI_ary['JCB'].'"';
    $sql = $sql. ' ,"'.$PI_ary['Mastercard'].'"';
    $sql = $sql. ' ,"'.$PI_ary['American_Express'].'"';
    $sql = $sql. ' ,"'.$PI_ary['Diners_Club'].'"';
    $sql = $sql. ' ,"'.$PI_ary['LINE_Pay'].'"';
    $sql = $sql. ' ,"'.$PI_ary['PayPay'].'"';
    $sql = $sql. ' ,"'.$PI_ary['楽天ペイ'].'"';
    $sql = $sql. ' ,"'.$PI_ary['d払い'].'"';
    $sql = $sql. ' ,"'.$PI_ary['au_PAY'].'"';
    $sql = $sql. ' ,"'.$PI_ary['メルペイ'].'"';
    $sql = $sql. ' ,Now()';
    $sql = $sql. ' );';

    return $sql;
  }

  function delete($PI_ary) {
    $sql = '';
    $sql = $sql. ' delete from `mst_ショップ支払方法` ';
    $sql = $sql. ' where 1 = 1';
    if (array_key_exists('ショップID', $PI_ary)) {
      $sql = $sql. ' AND `ショップID` = "'.$PI_ary['ショップID'].'"';
    }else{
      return ;
    }
    $sql = $sql. ';';

    return $sql;
  }

}


?>
