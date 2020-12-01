<?php

class TraNotice_Ctrl
{

  /*
  エラーチェック
  */
  function check($PI_ary){

    // 型確認
    $PO_ary = array('status'=>true, 'msg'=>array());
    $today = new DateTime('now');
    
    /* 開始日時 */
    $chkDateTime_開始日時 = chk_change_Datetime('開始日時',$PI_ary, $PO_ary) ;
    if($chkDateTime_開始日時 == 結果['問題あり']){
      $PO_ary['status'] = 結果['問題あり'];
      $PO_ary['msg']['開始日時'] = エラーメッセージ['chk_change_Datetime'];
    }else{
      $PO_ary = check_primaryItem('開始日時',$PI_ary, 19, $PO_ary);
      
      // 運営上の問題として、開始が３ヶ月後以上先は入れさせない
      $overLimitDay = addMonth($today, 6);
      if(chk_over(strtotime($PI_ary['開始日時']), strtotime($overLimitDay->format('Y-m-d'))) == false){
        $PO_ary['status'] = 結果['問題あり'];
        $PO_ary['msg']['開始日時'] = エラーメッセージ['chk_over'];
      }
    }

    /* 終了日時 */
    $chkDateTime_終了日時 = chk_change_Datetime('終了日時',$PI_ary, $PO_ary) ;
    if($chkDateTime_終了日時 == 結果['問題あり']){
      $PO_ary['status'] = 結果['問題あり'];
      $PO_ary['msg']['終了日時'] = エラーメッセージ['chk_change_Datetime'];
    }else{
      $PO_ary = check_primaryItem('終了日時',$PI_ary, 19, $PO_ary);

      if($chkDateTime_開始日時 == 結果['問題なし']){
        $datetime_開始日時 = new datetime ($PI_ary['開始日時']);
        // 開始日から1日を上限とする
        /* 
        $PI_ary['終了日時']は'Y-m-d H:i:m'形式のため、+2未満とすることで翌日までを許可する
        */
        $overLimitDay = $datetime_開始日時->modify('+1 month');
        if(chk_over(strtotime($PI_ary['終了日時']), strtotime($overLimitDay->format('Y-m-d'))) == false){
          $PO_ary['status'] = 結果['問題あり'];
          $PO_ary['msg']['終了日時'] = エラーメッセージ['chk_over'];
        }
        // 開始日時より終了日時が下回っている
        if(chk_less(strtotime($PI_ary['終了日時']), strtotime($PI_ary['開始日時'])) == false){
          $PO_ary['status'] = 結果['問題あり'];
          $PO_ary['msg']['終了日時'] = エラーメッセージ['chk_less'];
        }
      }
    }
      

    /* メッセージ */
    /* 表示領域の関係でサイズは80 */
    $PO_ary = check_primaryItem('メッセージ',$PI_ary, 80, $PO_ary);
    if(3 < substr_count($PI_ary['メッセージ'],"\n")){
      $PO_ary['status'] = 結果['問題あり'];
      $PO_ary['msg']['メッセージ'] = エラーメッセージ['chk_returnCode_overCount'];
    }

    /* 表示ページURL */
    $PO_ary = check_primaryItem('表示ページURL',$PI_ary, 500, $PO_ary);


    return $PO_ary;
  }

  function select($PI_ary){
    $sql = '';
    $sql = $sql. 'select ';
    $sql = $sql. ' `ショップID`,';
    $sql = $sql. ' `開始日時`,';
    $sql = $sql. ' `終了日時`,';
    $sql = $sql. ' `通知区分コード`,';
    $sql = $sql. ' `メッセージ`,';
    $sql = $sql. ' `表示ページURL`,';
    $sql = $sql. ' `最終更新日`';
    $sql = $sql. ' from `tra_通知`';
    $sql = $sql. ' where 1 = 1';
    if (array_key_exists('ショップID', $PI_ary)) {
      $sql = $sql. ' and `ショップID`           = "'.$PI_ary['ショップID'].'"';
    }else{
      return ;
    }
    if (array_key_exists('開始日時', $PI_ary)) {
      $sql = $sql. ' and `開始日時`           <= "'.$PI_ary['開始日時'].'"';
    }
    if (array_key_exists('終了日時', $PI_ary)) {
      $sql = $sql. ' and "'.$PI_ary['終了日時'].'" <= `終了日時`';
    }
    $sql = $sql. 'order by `ショップID`, `開始日時` ';
    $sql = $sql. ';';
  
    return $sql;

  }

  function insert($PI_ary) {

    try{
      $sql = '';
      $sql = $sql. 'insert into `tra_通知`(';
      $sql = $sql. ' `ショップID`,';
      $sql = $sql. ' `開始日時`,';
      $sql = $sql. ' `終了日時`,';
      $sql = $sql. ' `通知区分コード`,';
      $sql = $sql. ' `メッセージ`,';
      $sql = $sql. ' `表示ページURL`,';
      $sql = $sql. ' `最終更新日`';
      $sql = $sql. ')values(';
      $sql = $sql. '  "'.$PI_ary['ショップID'].'",';
      $sql = $sql. '  "'.$PI_ary['開始日時'].'",';
      $sql = $sql. '  "'.$PI_ary['終了日時'].'",';
      $sql = $sql. '  "'.$PI_ary['通知区分コード'].'",';
      $sql = $sql. '  "'.$PI_ary['メッセージ'].'",';
      $sql = $sql. '  "'.$PI_ary['表示ページURL'].'",';
      $sql = $sql. ' Now()';
      $sql = $sql. ' )';
      $sql = $sql. ';';
    }catch ( Exception $ex ) {
      return "";
    }

    return $sql;
}
function insert_his($PI_ary, $delFlg){

  $sql = "";
  $sql = $sql. 'insert into `tra_履歴_通知`(';
  $sql = $sql. ' `ショップID`,';
  $sql = $sql. ' `開始日時`,';
  $sql = $sql. ' `終了日時`,';
  $sql = $sql. ' `通知区分コード`,';
  $sql = $sql. ' `メッセージ`,';
  $sql = $sql. ' `表示ページURL`,';
  $sql = $sql. ' `最終更新日`,';
  $sql = $sql. ' `削除`';
  $sql = $sql. ')values(';
  $sql = $sql. '  "'.$PI_ary['ショップID'].'",';
  $sql = $sql. '  "'.$PI_ary['開始日時'].'",';
  $sql = $sql. '  "'.$PI_ary['終了日時'].'",';
  $sql = $sql. '  "'.$PI_ary['通知区分コード'].'",';
  $sql = $sql. '  "'.$PI_ary['メッセージ'].'",';
  $sql = $sql. '  "'.$PI_ary['表示ページURL'].'",';
  $sql = $sql. ' Now(),';
  $sql = $sql. ' "' . $delFlg . '"';
  $sql = $sql. ' )';
  $sql = $sql. ';';

  return $sql;
}


function delete($PI_ary) {
  $sql = '';
  $sql = $sql. 'delete from `tra_通知` ';
  $sql = $sql. ' where 1 = 1';
  if (array_key_exists('ショップID', $PI_ary)) {
    $sql = $sql. ' and `ショップID`           = "'.$PI_ary['ショップID'].'"';
  }else{
    return ;
  }
  if (array_key_exists('通知区分コード', $PI_ary)) {
    $sql = $sql. ' and `通知区分コード`           = "'.$PI_ary['通知区分コード'].'"';
  }
  if (array_key_exists('開始日時', $PI_ary)) {
    $sql = $sql. ' and `開始日時`           = "'.$PI_ary['開始日時'].'"';
  }
  if (array_key_exists('終了日時', $PI_ary)) {
    $sql = $sql. ' and `終了日時`           = "'.$PI_ary['終了日時'].'"';
  }
  $sql = $sql. ';';

  return $sql;
}

}



