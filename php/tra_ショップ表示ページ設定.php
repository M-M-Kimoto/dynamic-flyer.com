<?php


//define('URL_ROOT', `../../`);
class TraShopPageInfo_Ctrl
{

  /*
  エラーチェック
  */
  function check($PI_ary){

    // 型確認
    $PO_ary = array('status'=>true, 'msg'=>array());
    $today = new DateTime('now');
    
    /* 表示開始日時 */
    $chkDateTime_表示開始日時 = chk_change_Datetime('表示開始日時',$PI_ary, $PO_ary) ;
    if($chkDateTime_表示開始日時 == 結果['問題あり']){
      $PO_ary['status'] = 結果['問題あり'];
      $PO_ary['msg']['表示開始日時'] = エラーメッセージ['chk_change_Datetime'];
    }else{
      $PO_ary = check_primaryItem('表示開始日時',$PI_ary, 19, $PO_ary);
      
      // 運営上の問題として、開始が３ヶ月後以上先は入れさせない
      $overLimitDay = addMonth($today, 6);
      if(chk_over(strtotime($PI_ary['表示開始日時']), strtotime($overLimitDay->format('Y-m-d'))) == false){
        $PO_ary['status'] = 結果['問題あり'];
        $PO_ary['msg']['表示開始日時'] = エラーメッセージ['chk_over'];
      }
    }

    /* 表示終了日時 */
    $chkDateTime_表示終了日時 = chk_change_Datetime('表示終了日時',$PI_ary, $PO_ary) ;
    if($chkDateTime_表示終了日時 == 結果['問題あり']){
      $PO_ary['status'] = 結果['問題あり'];
      $PO_ary['msg']['表示終了日時'] = エラーメッセージ['chk_change_Datetime'];
    }else{
      $PO_ary = check_primaryItem('表示終了日時',$PI_ary, 19, $PO_ary);

      if($chkDateTime_表示開始日時 == 結果['問題なし']){
        $datetime_表示開始日時 = new datetime ($PI_ary['表示開始日時']);
        // 表示開始日から半年を上限とする
        $overLimitDay = addMonth($datetime_表示開始日時, 6);
        if(chk_over(strtotime($PI_ary['表示終了日時']), strtotime($overLimitDay->format('Y-m-d'))) == false){
          $PO_ary['status'] = 結果['問題あり'];
          $PO_ary['msg']['表示終了日時'] = エラーメッセージ['chk_over'];
        }
        // 表示開始日時より終了日時が下回っている
        if(chk_less(strtotime($PI_ary['表示終了日時']), strtotime($PI_ary['表示開始日時'])) == false){
          $PO_ary['status'] = 結果['問題あり'];
          $PO_ary['msg']['表示終了日時'] = エラーメッセージ['chk_less'];
        }

      }

    }
      
    /* レベル */
    $PO_ary = check_primaryItem('レベル',$PI_ary, 1, $PO_ary);
    if(chk_while($PI_ary['レベル'], 1, 9) == 結果['問題あり']){
      $PO_ary['status'] = 結果['問題あり'];
      $PO_ary['msg']['レベル'] = エラーメッセージ['chk_while'];
    }

    /* 名称 */
    $PO_ary = check_primaryItem('名称',$PI_ary, 100, $PO_ary);
    
    /* 表示URL */
    $PO_ary = check_primaryItem('表示ページURL',$PI_ary, 500, $PO_ary);

    return $PO_ary;
  }
  
  function select($PI_ary) {
      $sql = '';
      $sql = $sql. 'select ';
      $sql = $sql. ' `ショップID`,';
      $sql = $sql. ' `レベル`,';
      $sql = $sql. ' `名称`,';
      $sql = $sql. ' `表示開始日時`,';
      $sql = $sql. ' `表示終了日時`,';
      $sql = $sql. ' `表示ページURL`,';
      $sql = $sql. ' `最終更新日`';
      $sql = $sql. ' from `tra_ショップ表示ページ設定`';
      $sql = $sql. ' where 1 = 1';
      if (array_key_exists('ショップID', $PI_ary)) {
        $sql = $sql. ' AND `ショップID` = "'.$PI_ary['ショップID'].'"';
      }
      if (array_key_exists('レベル', $PI_ary)) {
        $sql = $sql. ' AND `レベル` = "'.$PI_ary['レベル'].'"';
      }
      $sql = $sql. ' order by `ショップID`,`レベル`';
      $sql = $sql. ';';

      return $sql;
  }

  function update($PI_ary) {
    $sql = '';
    $sql = $sql. 'update `tra_ショップ表示ページ設定`';
    $sql = $sql. ' set ';
    $sql = $sql. ' `名称`        = "'.$PI_ary['名称'].'",';
    $sql = $sql. ' `表示開始日時`  = "'.$PI_ary['表示開始日時'].'",';
    $sql = $sql. ' `表示終了日時`  = "'.$PI_ary['表示終了日時'].'",';
    $sql = $sql. ' `表示ページURL`  = "'.$PI_ary['表示ページURL'].'",';
    $sql = $sql. ' `最終更新日`  = Now()';

    $sql = $sql. ' where 1 = 1';
    if (array_key_exists('ショップID', $PI_ary)) {
      $sql = $sql. ' AND `ショップID` = "'.$PI_ary['ショップID'].'"';
    }else{
      //ショップID未指定は禁止
      return ;
    }
    if (array_key_exists('レベル', $PI_ary)) {
      $sql = $sql. ' AND `レベル` = "'.$PI_ary['レベル'].'"';
    }else{
      //レベル未指定は禁止
      return ;
    }
    $sql = $sql. ';';

    return $sql;
  }

  function insert($PI_ary) {
    $sql = '';
    $sql = $sql. 'insert into `tra_ショップ表示ページ設定`(';
    $sql = $sql. ' `ショップID`,';
    $sql = $sql. ' `レベル`,';
    $sql = $sql. ' `名称`,';
    $sql = $sql. ' `表示開始日時`,';
    $sql = $sql. ' `表示終了日時`,';
    $sql = $sql. ' `表示ページURL`,';
    $sql = $sql. ' `最終更新日`';
    $sql = $sql. ')values(';
    $sql = $sql. '  "'.$PI_ary['ショップID'].'",';
    $sql = $sql. '  "'.$PI_ary['レベル'].'",';
    $sql = $sql. '  "'.$PI_ary['名称'].'",';
    $sql = $sql. '  "'.$PI_ary['表示開始日時'].'",';
    $sql = $sql. '  "'.$PI_ary['表示終了日時'].'",';
    $sql = $sql. '  "'.$PI_ary['表示ページURL'].'",';
    $sql = $sql. ' Now()';
    $sql = $sql. ' );';

    return $sql;
  }

  function delete($PI_ary) {
    $sql = '';
    $sql = $sql. 'delete from `tra_ショップ表示ページ設定` ';
    $sql = $sql. ' where 1 = 1';
    if (array_key_exists('ショップID', $PI_ary)) {
      $sql = $sql. ' and `ショップID`           = "'.$PI_ary['ショップID'].'"';
    }else{
      return ;
    }
    if (array_key_exists('レベル', $PI_ary)) {
      $sql = $sql. ' and `レベル`           = "'.$PI_ary['レベル'].'"';
    }
    $sql = $sql. ';';

    return $sql;
  }

}



