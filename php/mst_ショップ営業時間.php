<?php

class MstSalesTime_Ctrl
{

  /*
  エラーチェック
  */
  function check($PI_ary){

    $PO_ary = array('status'=>true, 'msg'=>array());

    // 曜日ID
    $PO_ary = check_flg('日曜フラグ',$PI_ary, $PO_ary);
    $PO_ary = check_flg('月曜フラグ',$PI_ary, $PO_ary);
    $PO_ary = check_flg('火曜フラグ',$PI_ary, $PO_ary);
    $PO_ary = check_flg('水曜フラグ',$PI_ary, $PO_ary);
    $PO_ary = check_flg('木曜フラグ',$PI_ary, $PO_ary);
    $PO_ary = check_flg('金曜フラグ',$PI_ary, $PO_ary);
    $PO_ary = check_flg('土曜フラグ',$PI_ary, $PO_ary);

    if($PI_ary['日曜フラグ'] == 0 && 
       $PI_ary['月曜フラグ'] == 0 &&
       $PI_ary['火曜フラグ'] == 0 &&
       $PI_ary['水曜フラグ'] == 0 &&
       $PI_ary['木曜フラグ'] == 0 &&
       $PI_ary['金曜フラグ'] == 0 &&
       $PI_ary['土曜フラグ'] == 0 
    ){
      $PO_ary['status'] = 結果['問題あり'];
      $PO_ary['msg']['曜日フラグ'] = エラーメッセージ['chk_nothing'];
    }


    /*
    開始時分、終了時分　チェックに使う共通変数
    */
    $str_開始時分 = str_pad($_POST['開始時'], 2,0,STR_PAD_LEFT).str_pad($_POST['開始分'], 2,0,STR_PAD_LEFT);
    $str_終了時分 = str_pad($_POST['終了時'], 2,0,STR_PAD_LEFT).str_pad($_POST['終了分'], 2,0,STR_PAD_LEFT);

    // 開始時
    $PO_ary = check_primaryItem('開始時',$PI_ary, 2, $PO_ary);
    if(chk_while((int)$PI_ary['開始時'], 0, 23) == 結果['問題あり']){
      $PO_ary['status'] = 結果['問題あり'];
      $PO_ary['msg']['開始時'] = エラーメッセージ['chk_while'];
    }
    // 時の方で終了時間未満かチェックする
    if(chk_over((int)$str_開始時分, (int)$str_終了時分) == 結果['問題あり']){
      $PO_ary['status'] = 結果['問題あり'];
      $PO_ary['msg']['開始時'] = エラーメッセージ['chk_over'];
    }

    // 開始分
    $PO_ary = check_primaryItem('開始分',$PI_ary, 2, $PO_ary);
    if(chk_while((int)$PI_ary['開始分'], 0, 59) == 結果['問題あり']){
      $PO_ary['status'] = 結果['問題あり'];
      $PO_ary['msg']['開始分'] = エラーメッセージ['chk_while'];
    }

    // 終了時
    $PO_ary = check_primaryItem('終了時',$PI_ary, 2, $PO_ary);
    if(chk_while((int)$PI_ary['終了時'], 0, 29) == 結果['問題あり']){
      $PO_ary['status'] = 結果['問題あり'];
      $PO_ary['msg']['終了時'] = エラーメッセージ['chk_while'];
    }
    // 終了分
    $PO_ary = check_primaryItem('終了分',$PI_ary, 2, $PO_ary);
    if(chk_while((int)$PI_ary['終了分'], 0, 59) == 結果['問題あり']){
      $PO_ary['status'] = 結果['問題あり'];
      $PO_ary['msg']['終了分'] = エラーメッセージ['chk_while'];
    }
    return $PO_ary;
  }

  function select($PI_ary) {
      $sql = '';
      $sql = $sql. 'select ';
      $sql = $sql. ' `ショップID`,';
      $sql = $sql. ' `日曜フラグ`,';
      $sql = $sql. ' `月曜フラグ`,';
      $sql = $sql. ' `火曜フラグ`,';
      $sql = $sql. ' `水曜フラグ`,';
      $sql = $sql. ' `木曜フラグ`,';
      $sql = $sql. ' `金曜フラグ`,';
      $sql = $sql. ' `土曜フラグ`,';
      $sql = $sql. ' `開始時`,';
      $sql = $sql. ' `開始分`,';
      $sql = $sql. ' `終了時`,';
      $sql = $sql. ' `終了分`,';
      $sql = $sql. ' `更新日時`';
      $sql = $sql. ' from `mst_ショップ営業時間`';
      $sql = $sql. ' where 1 = 1';
      if (array_key_exists('ショップID', $PI_ary)) {
        $sql = $sql. ' AND `ショップID` = "'.$PI_ary['ショップID'].'"';
      }
      $sql = $sql. ' order by `ショップID`, `開始時`, `開始分`';
      $sql = $sql. ';';

      return $sql;
  }

  function insert($PI_ary) {
    $sql = "";
    $sql = $sql. 'insert into `mst_ショップ営業時間`(';
    $sql = $sql. '`ショップID`,';
    $sql = $sql. ' `日曜フラグ`,';
    $sql = $sql. ' `月曜フラグ`,';
    $sql = $sql. ' `火曜フラグ`,';
    $sql = $sql. ' `水曜フラグ`,';
    $sql = $sql. ' `木曜フラグ`,';
    $sql = $sql. ' `金曜フラグ`,';
    $sql = $sql. ' `土曜フラグ`,';
    $sql = $sql. ' `開始時`,';
    $sql = $sql. ' `開始分`,';
    $sql = $sql. ' `終了時`,';
    $sql = $sql. ' `終了分`';
    $sql = $sql. ")VALUES(";
    $sql = $sql. '  "'.$PI_ary['ショップID'].'"';
    $sql = $sql. ' ,"'.$PI_ary['日曜フラグ'].'"';
    $sql = $sql. ' ,"'.$PI_ary['月曜フラグ'].'"';
    $sql = $sql. ' ,"'.$PI_ary['火曜フラグ'].'"';
    $sql = $sql. ' ,"'.$PI_ary['水曜フラグ'].'"';
    $sql = $sql. ' ,"'.$PI_ary['木曜フラグ'].'"';
    $sql = $sql. ' ,"'.$PI_ary['金曜フラグ'].'"';
    $sql = $sql. ' ,"'.$PI_ary['土曜フラグ'].'"';
    $sql = $sql. ' ,"'.$PI_ary['開始時'].'"';
    $sql = $sql. ' ,"'.$PI_ary['開始分'].'"';
    $sql = $sql. ' ,"'.$PI_ary['終了時'].'"';
    $sql = $sql. ' ,"'.$PI_ary['終了分'].'"';
    $sql = $sql. ' );';

    return $sql;
  }

  function delete($PI_ary) {
    $sql = '';
    $sql = $sql. 'delete from `mst_ショップ営業時間` ';
    $sql = $sql. ' where 1 = 1';
    if (array_key_exists('ショップID', $PI_ary)) {
      $sql = $sql. ' and `ショップID`           = "'.$PI_ary['ショップID'].'"';
    }else{
      return ;
    }
    if (array_key_exists('開始時', $PI_ary)) {
      $sql = $sql. ' and `開始時`           = "'.(int) $PI_ary['開始時'].'"';
    }else{
      return ;
    }
    if (array_key_exists('開始分', $PI_ary)) {
      $sql = $sql. ' and `開始分`           = "'.(int) $PI_ary['開始分'].'"';
    }else{
      return ;
    }
    if (array_key_exists('終了時', $PI_ary)) {
      $sql = $sql. ' and `終了時`           = "'.(int) $PI_ary['終了時'].'"';
    }else{
      return ;
    }
    if (array_key_exists('終了分', $PI_ary)) {
      $sql = $sql. ' and `終了分`           = "'.(int) $PI_ary['終了分'].'"';
    }else{
      return ;
    }
    /*
    if (array_key_exists('終了時', $PI_ary)) {
      $sql = $sql. '  `終了時`           = "'.$PI_ary['終了時'].'"';
    }
    */
    $sql = $sql. ';';

    return $sql;
  }

  function update($PI_ary) {
    $sql = '';
    $sql = $sql. 'update `mst_ショップ営業時間` ';
    $sql = $sql. ' set `更新日時` = Now()';
    if (array_key_exists('日曜フラグ', $PI_ary)) {
      $sql = $sql. ' ,`日曜フラグ` = "'.(int) $PI_ary['日曜フラグ'].'"';
    }else{
      $sql = $sql. ' ,`日曜フラグ` = 0';
    }
    if (array_key_exists('月曜フラグ', $PI_ary)) {
      $sql = $sql. ' ,`月曜フラグ` = "'.(int) $PI_ary['月曜フラグ'].'"';
    }else{
      $sql = $sql. ' ,`月曜フラグ` = 0';
    }
    if (array_key_exists('火曜フラグ', $PI_ary)) {
      $sql = $sql. ' ,`火曜フラグ` = "'.(int) $PI_ary['火曜フラグ'].'"';
    }else{
      $sql = $sql. ' ,`火曜フラグ` = 0';
    }
    if (array_key_exists('水曜フラグ', $PI_ary)) {
      $sql = $sql. ' ,`水曜フラグ` = "'.(int) $PI_ary['水曜フラグ'].'"';
    }else{
      $sql = $sql. ' ,`水曜フラグ` = 0';
    }
    if (array_key_exists('木曜フラグ', $PI_ary)) {
      $sql = $sql. ' ,`木曜フラグ` = "'.(int) $PI_ary['木曜フラグ'].'"';
    }else{
      $sql = $sql. ' ,`木曜フラグ` = 0';
    }
    if (array_key_exists('金曜フラグ', $PI_ary)) {
      $sql = $sql. ' ,`金曜フラグ` = "'.(int) $PI_ary['金曜フラグ'].'"';
    }else{
      $sql = $sql. ' ,`金曜フラグ` = 0';
    }
    if (array_key_exists('土曜フラグ', $PI_ary)) {
      $sql = $sql. ' ,`土曜フラグ` = "'.(int) $PI_ary['土曜フラグ'].'"';
    }else{
      $sql = $sql. ' ,`土曜フラグ` = 0';
    }
    $sql = $sql. ' where 1 = 1';
    if (array_key_exists('ショップID', $PI_ary)) {
      $sql = $sql. ' and `ショップID`           = "'.$PI_ary['ショップID'].'"';
    }else{
      return ;
    }
    if (array_key_exists('開始時', $PI_ary)) {
      $sql = $sql. ' and `開始時`           = "'.(int) $PI_ary['開始時'].'"';
    }else{
      return ;
    }
    if (array_key_exists('開始分', $PI_ary)) {
      $sql = $sql. ' and `開始分`           = "'.(int) $PI_ary['開始分'].'"';
    }else{
      return ;
    }
    if (array_key_exists('終了時', $PI_ary)) {
      $sql = $sql. ' and `終了時`           = "'.(int) $PI_ary['終了時'].'"';
    }else{
      return ;
    }
    if (array_key_exists('終了分', $PI_ary)) {
      $sql = $sql. ' and `終了分`           = "'.(int) $PI_ary['終了分'].'"';
    }else{
      return ;
    }
    
    $sql = $sql. ';';

    return $sql;
  }

}



