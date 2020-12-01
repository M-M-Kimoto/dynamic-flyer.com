<?php

class TraSale_Ctrl
{

  /*
  エラーチェック
  */
  function check($PI_ary){

    $PO_ary = array('status'=>true, 'msg'=>array());

    // 日付
    $PO_ary = check_primaryItem('日付',$PI_ary, 10, $PO_ary);
    $today = new DateTime('now');
    
    if(chk_less(strtotime($PI_ary['日付']), strtotime($today->format('Y-m-d'))) == false){
      $PO_ary['status'] = 結果['問題あり'];
      $PO_ary['msg']['日付'] = エラーメッセージ['chk_less'];
    }
    /* 運営上の問題として、３ヶ月以上先の予定は入れさせない */
    $overLimitDay = addMonth($today, 3);
    if(chk_over(strtotime($PI_ary['日付']), strtotime($overLimitDay->format('Y-m-d'))) == false){
      $PO_ary['status'] = 結果['問題あり'];
      $PO_ary['msg']['日付'] = エラーメッセージ['chk_over'];
    }

    // 全日フラグ
    if(check_flg('全日フラグ',$PI_ary, $PO_ary) == false ){
      $PO_ary['status'] = 結果['問題あり'];
      $PO_ary['msg']['全日フラグ'] = エラーメッセージ['check_flg'];
    }
    if($PI_ary['全日フラグ'] == '1') {

      if($PI_ary['開始時'] != '00' || $PI_ary['開始分'] != '00'){
        $PO_ary['status'] = 結果['問題あり'];
        $PO_ary['msg']['全日フラグ'] = '全日の場合は開始時間が空である必要があります。';
      }elseif($PI_ary['終了時'] != '29' || $PI_ary['終了分'] != '59'){
        $PO_ary['status'] = 結果['問題あり'];
        $PO_ary['msg']['全日フラグ'] = '全日の場合は終了時間が空である必要があります。';
      }

      // 全日の場合は終了
      return $PO_ary;
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

  function select_fromNow($PI_ary) {
      $sql = '';
      $sql = $sql. 'select ';
      $sql = $sql. ' `ショップID`,';
      $sql = $sql. ' `日付`,';
      $sql = $sql. ' lpad(`開始時`,2,0) as `開始時`,';
      $sql = $sql. ' lpad(`開始分`,2,0) as `開始分`,';
      $sql = $sql. ' lpad(`終了時`,2,0) as `終了時`,';
      $sql = $sql. ' lpad(`終了分`,2,0) as `終了分`,';
      $sql = $sql. ' `全日フラグ`,';
      $sql = $sql. ' `更新日時`';
      $sql = $sql. ' from `vie_不定営設定_処理日以降`';
      $sql = $sql. ' where 1 = 1';
      if (array_key_exists('ショップID', $PI_ary)) {
        $sql = $sql. ' AND `ショップID` = "'.$PI_ary['ショップID'].'"';
      }
      $sql = $sql. ' order by `ショップID`, `日付`, `全日フラグ` desc, `開始時`, `開始分`';
      $sql = $sql. ';';

      return $sql;
  }

  function insert($PI_ary) {
    // 連番などは自動登録のため、insert時に指定しない
    $sql = "";
    $sql = $sql. 'insert into `tra_不定営設定`(';
    $sql = $sql. ' `ショップID`,';
    $sql = $sql. ' `日付`,';
    $sql = $sql. ' `開始時`,';
    $sql = $sql. ' `開始分`,';
    $sql = $sql. ' `終了時`,';
    $sql = $sql. ' `終了分`,';
    $sql = $sql. ' `全日フラグ`';
    $sql = $sql. ")VALUES(";
    $sql = $sql. '  "'.$PI_ary['ショップID'].'"';
    $sql = $sql. ' ,"'.$PI_ary['日付'].'"';
    $sql = $sql. ' ,"'.(int) $PI_ary['開始時'].'"';
    $sql = $sql. ' ,"'.(int) $PI_ary['開始分'].'"';
    $sql = $sql. ' ,"'.(int) $PI_ary['終了時'].'"';
    $sql = $sql. ' ,"'.(int) $PI_ary['終了分'].'"';
    $sql = $sql. ' ,"'.$PI_ary['全日フラグ'].'"';
    $sql = $sql. ' );';

    return $sql;
  }

  function update($PI_ary) {
    // 連番などは自動登録のため、insert時に指定しない
    $sql = "";
    $sql = $sql. 'update `tra_不定営設定`';
    $sql = $sql. ' set';
    $sql = $sql. '`日付`     = "'.$PI_ary['日付'].'",';
    $sql = $sql. '`終了時`   = "'.(int) $PI_ary['終了時'].'",';
    $sql = $sql. '`終了分`   = "'.(int) $PI_ary['終了分'].'",';
    $sql = $sql. '`全日フラグ` = "'.$PI_ary['全日フラグ'].'",';
    $sql = $sql. '`更新日時` = Now()';
    $sql = $sql. ' where 1 = 1';
    if (array_key_exists('ショップID', $PI_ary)) {
      $sql = $sql. ' and `ショップID`           = "'.$PI_ary['ショップID'].'"';
    }else{
      return ;
    }
    if (array_key_exists('日付', $PI_ary)) {
      $sql = $sql. ' and `日付`           = "'.$PI_ary['日付'].'"';
    }
    if (array_key_exists('開始時', $PI_ary)) {
      $sql = $sql. ' and `開始時`           = "'.(int) $PI_ary['開始時'].'"';
    }
    if (array_key_exists('開始分', $PI_ary)) {
      $sql = $sql. ' and `開始分`           = "'.(int) $PI_ary['開始分'].'"';
    }
    $sql = $sql. ';';

    return $sql;
  }

  function delete($PI_ary) {
    $sql = '';
    $sql = $sql. 'delete from `tra_不定営設定` ';
    $sql = $sql. ' where 1 = 1';
    if (array_key_exists('ショップID', $PI_ary)) {
      $sql = $sql. ' and `ショップID`           = "'.$PI_ary['ショップID'].'"';
    }else{
      return ;
    }
    if (array_key_exists('日付', $PI_ary)) {
      $sql = $sql. ' and `日付`           = "'.$PI_ary['日付'].'"';
    }
    if (array_key_exists('全日フラグ', $PI_ary)) {
      $sql = $sql. ' and `全日フラグ`           = "'.$PI_ary['全日フラグ'].'"';
    }
    if (array_key_exists('開始時', $PI_ary)) {
      $sql = $sql. ' and `開始時`           = "'.(int) $PI_ary['開始時'].'"';
    }
    if (array_key_exists('開始分', $PI_ary)) {
      $sql = $sql. ' and `開始分`           = "'.(int) $PI_ary['開始分'].'"';
    }
    if (array_key_exists('終了時', $PI_ary)) {
      $sql = $sql. ' and `終了時`           = "'.(int) $PI_ary['終了時'].'"';
    }
    if (array_key_exists('終了分', $PI_ary)) {
      $sql = $sql. ' and `終了分`           = "'.(int) $PI_ary['終了分'].'"';
    }
    $sql = $sql. ';';

    return $sql;
  }

}



