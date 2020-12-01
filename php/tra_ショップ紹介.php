<?php


//define('URL_ROOT', `../../`);
class TraShopRecom_Ctrl
{

  /*
  エラーチェック
  */
  function check($PI_ary){

    // 初期処理
    $PO_ary = array('status'=>結果['問題なし'], 'msg'=>array());
    $today = new DateTime('now');
    
    /* 掲載開始日時 */
    $chkDateTime_開始日時 = chk_change_Datetime('掲載開始日時',$PI_ary, $PO_ary) ;
    if($chkDateTime_開始日時 == 結果['問題あり']){
      $PO_ary['status'] = 結果['問題あり'];
      $PO_ary['msg']['掲載開始日時'] = エラーメッセージ['chk_change_Datetime'];
    }else{
      $PO_ary = check_primaryItem('掲載開始日時',$PI_ary, 19, $PO_ary);
      
      // 運営上の問題として、開始が１ヶ月後以上先は入れさせない
      $overLimitDay = addMonth($today, 1);
      if(chk_over(strtotime($PI_ary['掲載開始日時']), strtotime($overLimitDay->format('Y-m-d'))) == false){
        $PO_ary['status'] = 結果['問題あり'];
        $PO_ary['msg']['掲載開始日時'] = エラーメッセージ['chk_over'];
      }
    }

    /* 掲載終了日時 */
    $chkDateTime_終了日時 = chk_change_Datetime('掲載終了日時',$PI_ary, $PO_ary) ;
    if($chkDateTime_終了日時 == 結果['問題あり']){
      $PO_ary['status'] = 結果['問題あり'];
      $PO_ary['msg']['掲載終了日時'] = エラーメッセージ['chk_change_Datetime'];
    }else{
      $PO_ary = check_primaryItem('掲載終了日時',$PI_ary, 19, $PO_ary);

      if($chkDateTime_開始日時 == 結果['問題なし']){
        $datetime_開始日時 = new datetime ($PI_ary['掲載開始日時']);
        // 開始日から1月を上限とする
        /* 
        $PI_ary['終了日時']は'Y-m-d H:i:m'形式のため、+2未満とすることで翌日までを許可する
        */
        $overLimitDay = $datetime_開始日時->modify('+1 month');
        if(chk_over(strtotime($PI_ary['掲載終了日時']), strtotime($overLimitDay->format('Y-m-d'))) == false){
          $PO_ary['status'] = 結果['問題あり'];
          $PO_ary['msg']['掲載終了日時'] = エラーメッセージ['chk_over'];
        }
        // 開始日時より終了日時が下回っている
        if(chk_less(strtotime($PI_ary['掲載終了日時']), strtotime($PI_ary['掲載開始日時'])) == false){
          $PO_ary['status'] = 結果['問題あり'];
          $PO_ary['msg']['掲載終了日時'] = エラーメッセージ['chk_less'];
        }
      }
    }
     
    // 区分
    $PO_ary = check_primaryItem('区分',$PI_ary, 1, $PO_ary);

    // 商品名
    $PO_ary = check_primaryItem('商品名',$PI_ary, 50, $PO_ary);

    // キャッチコピー
    $PO_ary = check_nomalItem('キャッチコピー',$PI_ary, 50, $PO_ary);

    // 詳細
    $PO_ary = check_nomalItem('詳細',$PI_ary, 80, $PO_ary);
    /*
    if(3 < substr_count($PI_ary['詳細'],"\n")){
      $PO_ary['status'] = 結果['問題あり'];
      $PO_ary['msg']['メッセージ'] = エラーメッセージ['chk_returnCode_overCount'];
    }
    */

    // 販売開始日時
    $chkDateTime_開始日時 = chk_change_Datetime('販売開始日時',$PI_ary, $PO_ary) ;
    if($chkDateTime_開始日時 == 結果['問題あり']){
      $PO_ary['status'] = 結果['問題あり'];
      $PO_ary['msg']['販売開始日時'] = エラーメッセージ['chk_change_Datetime'];
    }else{
      $PO_ary = check_nomalItem('販売開始日時',$PI_ary, 19, $PO_ary);
      
      // 運営上の問題として、開始が3ヶ月後以上先は入れさせない
      $overLimitDay = addMonth($today, 3);
      if(chk_over(strtotime($PI_ary['販売開始日時']), strtotime($overLimitDay->format('Y-m-d'))) == false){
        $PO_ary['status'] = 結果['問題あり'];
        $PO_ary['msg']['販売開始日時'] = エラーメッセージ['chk_over'];
      }
    }
    /* 販売終了日時 */
    if($PI_ary['販売終了日時'] != ""){
      $chkDateTime_終了日時 = chk_change_Datetime('販売終了日時',$PI_ary, $PO_ary) ;
      if($chkDateTime_終了日時 == 結果['問題あり']){
        $PO_ary['status'] = 結果['問題あり'];
        $PO_ary['msg']['販売終了日時'] = エラーメッセージ['chk_change_Datetime'];
      }else{
        $PO_ary = check_nomalItem('販売終了日時',$PI_ary, 19, $PO_ary);

        if($chkDateTime_開始日時 == 結果['問題なし']){
          $datetime_開始日時 = new datetime ($PI_ary['販売開始日時']);
          // 開始日から６ヶ月を上限とする
          /* 
          $PI_ary['終了日時']は'Y-m-d H:i:m'形式のため、+2未満とすることで翌日までを許可する
          */
          $overLimitDay = $datetime_開始日時->modify('+6 month');
          if(chk_over(strtotime($PI_ary['販売終了日時']), strtotime($overLimitDay->format('Y-m-d'))) == false){
            $PO_ary['status'] = 結果['問題あり'];
            $PO_ary['msg']['販売終了日時'] = エラーメッセージ['chk_over'];
          }
          // 開始日時より終了日時が下回っている
          if(chk_less(strtotime($PI_ary['販売終了日時']), strtotime($PI_ary['販売開始日時'])) == false){
            $PO_ary['status'] = 結果['問題あり'];
            $PO_ary['msg']['販売終了日時'] = エラーメッセージ['chk_less'];
          }
        }
      }
    }

    // 値段
    $PO_ary = check_nomalItem_match('値段',$PI_ary, 8, '/^[0-9-]+$/', $PO_ary);

    // 数量制限区分
    $PO_ary = check_primaryItem('数量制限区分',$PI_ary, 1, $PO_ary);
    if($PI_ary['数量制限区分'] == 0){
      if($PI_ary['販売数量'] != ""){
        $PO_ary['status'] = 結果['問題あり'];
        $PO_ary['msg']['販売数量'] = エラーメッセージ['chk_over'];
      }
      if($PI_ary['残数'] != ""){
        $PO_ary['status'] = 結果['問題あり'];
        $PO_ary['msg']['残数'] = エラーメッセージ['chk_over'];
      }

    }else{
      // 販売数量
      $PO_ary = check_primaryItem_match('販売数量',$PI_ary, 8, '/^[0-9-]+$/',  $PO_ary);
  
      // 残数
      $PO_ary = check_nomalItem_match('残数',$PI_ary, 8, '/^[0-9-]+$/',  $PO_ary);
  
    }
    
    /* リンクURL */
    $PO_ary = check_nomalItem('リンクURL',$PI_ary, 500, $PO_ary);

    // タグ
    $PO_ary = check_nomalItem('タグ',$PI_ary, 500, $PO_ary);

    return $PO_ary;
  }
  
  function select_01($PI_ary) {
      $sql = '';
      $sql = $sql. 'select ';
      $sql = $sql. ' `ショップID`,';
      $sql = $sql. ' date_format(`掲載開始日時`, "%Y-%m-%d") as `掲載開始日`,';
      $sql = $sql. ' date_format(`掲載開始日時`, "%H") as `掲載開始時`,';
      $sql = $sql. ' date_format(`掲載開始日時`, "%i") as `掲載開始分`,';
      $sql = $sql. ' date_format(`掲載終了日時`, "%Y-%m-%d") as `掲載終了日`,';
      $sql = $sql. ' date_format(`掲載終了日時`, "%H") as `掲載終了時`,';
      $sql = $sql. ' date_format(`掲載終了日時`, "%i") as `掲載終了分`,';
      $sql = $sql. ' `区分`,';
      $sql = $sql. ' `商品名`,';
      $sql = $sql. ' `キャッチコピー`,';
      $sql = $sql. ' `詳細`,';
      $sql = $sql. ' `値段`,';
      $sql = $sql. ' date_format(`販売開始日時`, "%Y-%m-%d") as `販売開始日`,';
      $sql = $sql. ' date_format(`販売終了日時`, "%Y-%m-%d") as `販売終了日`,';
      $sql = $sql. ' `数量制限区分`,';
      $sql = $sql. ' `販売数量`,';
      $sql = $sql. ' `残数`,';
      $sql = $sql. ' `リンクURL`,';
      $sql = $sql. ' `タグ`,';
      $sql = $sql. ' `更新日時`';
      $sql = $sql. ' from `tra_ショップ紹介`';
      $sql = $sql. ' where 1 = 1';
      if (array_key_exists('ショップID', $PI_ary)) {
        $sql = $sql. ' AND `ショップID` = "'.$PI_ary['ショップID'].'"';
      }
      $sql = $sql. ' order by `ショップID`';
      $sql = $sql. ';';

      return $sql;
  }

  function select($PI_ary) {
    $sql = '';
    $sql = $sql. 'select ';
    $sql = $sql. ' `ショップID`,';
    $sql = $sql. ' `掲載開始日時`,';
    $sql = $sql. ' `掲載終了日時`,';
    $sql = $sql. ' `区分`,';
    $sql = $sql. ' `商品名`,';
    $sql = $sql. ' `キャッチコピー`,';
    $sql = $sql. ' `詳細`,';
    $sql = $sql. ' `値段`,';
    $sql = $sql. ' `販売開始日時`,';
    $sql = $sql. ' `販売終了日時`,';
    $sql = $sql. ' `数量制限区分`,';
    $sql = $sql. ' `販売数量`,';
    $sql = $sql. ' `残数`,';
    $sql = $sql. ' `リンクURL`,';
    $sql = $sql. ' `タグ`,';
    $sql = $sql. ' `更新日時`';
    $sql = $sql. ' from `tra_ショップ紹介`';
    $sql = $sql. ' where 1 = 1';
    if (array_key_exists('ショップID', $PI_ary)) {
      $sql = $sql. ' AND `ショップID` = "'.$PI_ary['ショップID'].'"';
    }
    $sql = $sql. ' order by `ショップID`';
    $sql = $sql. ';';

    return $sql;
}

  function insert($PI_ary) {
    $sql = '';
    $sql = $sql. 'insert into `tra_ショップ紹介`(';
    $sql = $sql. ' `ショップID`,';
    $sql = $sql. ' `掲載開始日時`,';
    $sql = $sql. ' `掲載終了日時`,';
    $sql = $sql. ' `区分`,';
    $sql = $sql. ' `商品名`,';
    $sql = $sql. ' `キャッチコピー`,';
    $sql = $sql. ' `詳細`,';
    $sql = $sql. ' `値段`,';
    $sql = $sql. ' `販売開始日時`,';
    $sql = $sql. ' `販売終了日時`,';
    $sql = $sql. ' `数量制限区分`,';
    $sql = $sql. ' `販売数量`,';
    $sql = $sql. ' `残数`,';
    $sql = $sql. ' `リンクURL`,';
    $sql = $sql. ' `タグ`,';
    $sql = $sql. ' `更新日時`';
    $sql = $sql. ')values(';
    $sql = $sql. '   "'.$PI_ary['ショップID'].'"';
    $sql = $sql. '  ,"'.$PI_ary['掲載開始日時'].'"';
    $sql = $sql. '  ,"'.$PI_ary['掲載終了日時'].'"';
    $sql = $sql. '  ,"'.$PI_ary['区分'].'"';
    $sql = $sql. '  ,"'.$PI_ary['商品名'].'"';
    $sql = $sql. '  ,"'.$PI_ary['キャッチコピー'].'"';
    $sql = $sql. '  ,"'.$PI_ary['詳細'].'"';
    if ($PI_ary['値段'] != "") {
      $sql = $sql. '  ,'.$PI_ary['値段'].'';
    }else{
      $sql = $sql. '  ,null';
    }
    if ($PI_ary['販売開始日時'] != "") {
      $sql = $sql. '  ,"'.$PI_ary['販売開始日時'].'"';
    }else{
      $sql = $sql. '  ,null';
    }
    if ($PI_ary['販売終了日時'] != "") {
      $sql = $sql. '  ,"'.$PI_ary['販売終了日時'].'"';
    }else{
      $sql = $sql. '  ,null';
    }
    $sql = $sql. '  ,"'.$PI_ary['数量制限区分'].'"';
    if ($PI_ary['販売数量'] != "") {
      $sql = $sql. '  ,"'.$PI_ary['販売数量'].'"';
    }else{
      $sql = $sql. '  ,null';
    }
    if ($PI_ary['残数'] != "") {
      $sql = $sql. '  ,"'.$PI_ary['残数'].'"';
    }else{
      $sql = $sql. '  ,null';
    }
    $sql = $sql. '  ,"'.$PI_ary['リンクURL'].'"';
    $sql = $sql. '  ,"'.$PI_ary['タグ'].'"';
    $sql = $sql. '  ,Now()';
    $sql = $sql. ');';

    return $sql;
  }

  function update($PI_ary) {
    $sql = '';
    $sql = $sql. 'update `tra_ショップ紹介` ';
    $sql = $sql. ' set';
    // パスワードは必須のため
    $sql = $sql. "  `更新日時`         = Now()";
    if (array_key_exists('掲載開始日時', $PI_ary)) {
      $sql = $sql. ', `掲載開始日時`           = "'.$PI_ary['掲載開始日時'].'"';
    }
    if (array_key_exists('掲載終了日時', $PI_ary)) {
      $sql = $sql. ', `掲載終了日時`               = "'.$PI_ary['掲載終了日時'].'"';
    }
    if (array_key_exists('区分', $PI_ary)) {
      $sql = $sql. ', `区分`           = "'.$PI_ary['区分'].'"';
    }
    if (array_key_exists('商品名', $PI_ary)) {
      $sql = $sql. ', `商品名`            = "'.$PI_ary['商品名'].'"';
    }
    if (array_key_exists('キャッチコピー', $PI_ary)) {
      $sql = $sql. ', `キャッチコピー`     = "'.$PI_ary['キャッチコピー'].'"';
    }
    if (array_key_exists('詳細', $PI_ary)) {
      $sql = $sql. ', `詳細`     = "'.$PI_ary['詳細'].'"';
    }
    if (array_key_exists('値段', $PI_ary)) {
      if ($PI_ary['値段'] != "") {
        $sql = $sql. ', `値段`     = ' .$PI_ary['値段'].'';
      }else{
        $sql = $sql. ', `値段`     =  null';
      }
    }
    if (array_key_exists('販売開始日時', $PI_ary)) {
      if ($PI_ary['販売開始日時'] != "") {
        $sql = $sql. ', `販売開始日時`     = "'.$PI_ary['販売開始日時'].'"';
      }else{
        $sql = $sql. ', `販売開始日時`     = null';
      }
    }
    if (array_key_exists('販売終了日時', $PI_ary)) {
      if ($PI_ary['販売終了日時'] != "") {
        $sql = $sql. ', `販売終了日時`     = "'.$PI_ary['販売終了日時'].'"';
      }else{
        $sql = $sql. ', `販売終了日時`     = null';
      }
    }
    if (array_key_exists('数量制限区分', $PI_ary)) {
      $sql = $sql. ', `数量制限区分`     = "'.$PI_ary['数量制限区分'].'"';
    }
    if (array_key_exists('販売数量', $PI_ary)) {
      if ($PI_ary['販売数量'] != "") {
        $sql = $sql. ', `販売数量`     = '.$PI_ary['販売数量'];
      }else{
        $sql = $sql. ', `販売数量`     = null';
      }
    }
    if (array_key_exists('残数', $PI_ary)) {
      if ($PI_ary['残数'] != "") {
        $sql = $sql. ', `残数`     = '.$PI_ary['残数'];
      }else{
        $sql = $sql. ', `残数`     = null';
      }
    }
    if (array_key_exists('リンクURL', $PI_ary)) {
      $sql = $sql. ', `リンクURL`     = "'.$PI_ary['リンクURL'].'"';
    }
    if (array_key_exists('タグ', $PI_ary)) {
      $sql = $sql. ', `タグ`     = "'.$PI_ary['タグ'].'"';
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

}



