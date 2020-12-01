<?php


class MstUser_Ctrl
{

  /*
  Mst_ユーザ　のエラーチェック
  */
  function check($PI_ary){

    // 初期処理
    $PO_ary = array('status'=>結果['問題なし'], 'msg'=>array());

    /* エラーチェック */

    //ID
    $PO_ary = check_primaryItem('ID', $PI_ary, 100, $PO_ary);
    if (preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,100}+\z/i',  $PI_ary['ID'] ) == false) {
      //パターンに一致しない
      $PO_ary['status'] = 結果['問題あり'];
      $PO_ary['msg']['ID'] = '英数字を混ぜた8文字以上で入力してください。';
    }

    // パスワード
    $PO_ary = check_primaryItem('パスワード', $PI_ary, 50, $PO_ary);
    if (preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,50}+\z/i',  $PI_ary['パスワード'] ) == false) {
      //パターンに一致しない
      $PO_ary['status'] = 結果['問題あり'];
      $PO_ary['msg']['パスワード'] = '英数字を混ぜた8文字以上で入力してください。';
    }
    
    // ニックネーム
    $PO_ary = check_primaryItem('ニックネーム', $PI_ary, 50, $PO_ary);
    
    // 生年月日
    $PO_ary = check_primaryItem('生年月日', $PI_ary, 10, $PO_ary);
    
    // 性別
    $PO_ary = check_flg('男',$PI_ary, $PO_ary);
    $PO_ary = check_flg('女',$PI_ary, $PO_ary);

    // 職業
    $PO_ary = check_nomalItem('職業',$PI_ary, 20, $PO_ary);

    // 都道府県
    $PO_ary = check_primaryItem('都道府県',$PI_ary, 20, $PO_ary);

    // 市区町村
    $PO_ary = check_primaryItem('市区町村',$PI_ary, 20, $PO_ary);

    // 市区町村
    $PO_ary = check_nomalItem('町名番地',$PI_ary, 200, $PO_ary);

    // 建物等
    $PO_ary = check_nomalItem('建物等',$PI_ary, 200, $PO_ary);

    // 質問1
    $PO_ary = check_primaryItem('質問1',$PI_ary, 100, $PO_ary);
    if (array_key_exists('回答1', $PI_ary) == false){
      $PO_ary['status'] = 結果['問題あり'];
      $PO_ary['msg']['質問1'] = '回答がありません。';
    }

    // 回答1
    $PO_ary = check_primaryItem('回答1',$PI_ary, 100, $PO_ary);
    if (array_key_exists('質問1', $PI_ary) == false){
      $PO_ary['status'] = 結果['問題あり'];
      $PO_ary['msg']['回答1'] = '質問がありません。';
    }

    // 質問2
    $PO_ary = check_nomalItem('質問2',$PI_ary, 100, $PO_ary);
    if(array_key_exists('質問2', $PI_ary) == true && !$PI_ary['質問2'] == false){
      // 回答2
      $PO_ary = check_primaryItem('回答2',$PI_ary, 100, $PO_ary);
    }else{
      // 回答2
      if(array_key_exists('質問2', $PI_ary) == true && !$PI_ary['質問2'] == false){
        $PO_ary['status'] = 結果['問題あり'];
        $PO_ary['msg']['回答2'] = '質問がありません。';
      }
    }

    // 質問3
    $PO_ary = check_nomalItem('質問3',$PI_ary, 100, $PO_ary);
    if(array_key_exists('質問3', $PI_ary) == true && !$PI_ary['質問3'] == false){
      // 回答3
      $PO_ary = check_primaryItem('回答3',$PI_ary, 100, $PO_ary);
    }else{
      // 回答2
      if(array_key_exists('質問3', $PI_ary) == true && !$PI_ary['質問3'] == false){
        $PO_ary['status'] = 結果['問題あり'];
        $PO_ary['msg']['回答3'] = '質問がありません。';
      }
    }

    return $PO_ary;
  }

  function insert($PI_ary){

      $sql = "";
      $sql = $sql. "insert into `mst_ユーザ`(";
      $sql = $sql. "`ID`,";
      $sql = $sql. "`パスワード`,";
      $sql = $sql. "`ニックネーム`,";
      $sql = $sql. "`男`,";
      $sql = $sql. "`女`,";
      $sql = $sql. "`職業`,";
      $sql = $sql. "`生年月日`,";
      $sql = $sql. "`都道府県`,";
      $sql = $sql. "`市区町村`,";
      $sql = $sql. "`町名番地`,";
      $sql = $sql. "`建物等`,";
      $sql = $sql. "`初回アクセス日時`,";
      $sql = $sql. "`質問1`,";
      $sql = $sql. "`回答1`,";
      $sql = $sql. "`質問2`,";
      $sql = $sql. "`回答2`,";
      $sql = $sql. "`質問3`,";
      $sql = $sql. "`回答3`";
      $sql = $sql. ")VALUES(";
      $sql = $sql. '  "'.$PI_ary['ID'].'"';
      $sql = $sql. ' ,"'.$PI_ary['パスワード'].'"';
      $sql = $sql. ' ,"'.$PI_ary['ニックネーム'].'"';
      $sql = $sql. ' ,"'.$PI_ary['男'].'"';
      $sql = $sql. ' ,"'.$PI_ary['女'].'"';
      $sql = $sql. ' ,"'.$PI_ary['職業'].'"';
      $sql = $sql. ' ,"'.$PI_ary['生年月日'].'"';
      $sql = $sql. ' ,"'.$PI_ary['都道府県'].'"';
      $sql = $sql. ' ,"'.$PI_ary['市区町村'].'"';
      $sql = $sql. ' ,"'.$PI_ary['町名番地'].'"';
      $sql = $sql. ' ,"'.$PI_ary['建物等'].'"';
      $sql = $sql. ' ,NOW()'; // 初回アクセス日
      $sql = $sql. ' ,"'.$PI_ary['質問1'].'"';
      $sql = $sql. ' ,"'.$PI_ary['回答1'].'"';
      $sql = $sql. ' ,"'.$PI_ary['質問2'].'"';
      $sql = $sql. ' ,"'.$PI_ary['回答2'].'"';
      $sql = $sql. ' ,"'.$PI_ary['質問3'].'"';
      $sql = $sql. ' ,"'.$PI_ary['回答3'].'"';
      $sql = $sql. ' );';

      return $sql;
  }

  function select($PI_ary){

    $sql = "";
    $sql = $sql. "select ";
    $sql = $sql. " `ID`,";
    $sql = $sql. " `パスワード`,";
    $sql = $sql. " `ニックネーム`,";
    $sql = $sql. " `男`,";
    $sql = $sql. " `女`,";
    $sql = $sql. " `職業`,";
    $sql = $sql. " `生年月日`,";
    $sql = $sql. " `都道府県`,";
    $sql = $sql. " `市区町村`,";
    $sql = $sql. " `町名番地`,";
    $sql = $sql. " `建物等`,";
    $sql = $sql. " `初回アクセス日時`,";
    $sql = $sql. " `質問1`,";
    $sql = $sql. " `回答1`,";
    $sql = $sql. " `質問2`,";
    $sql = $sql. " `回答2`,";
    $sql = $sql. " `質問3`,";
    $sql = $sql. " `回答3`";
    $sql = $sql. " from `mst_ユーザ`";
    $sql = $sql. " where 1 = 1";
    if (array_key_exists('ID', $PI_ary)) {
      $sql = $sql. ' AND `ID` = "'.$PI_ary['ID'].'"';
    }
    if (array_key_exists('パスワード', $PI_ary)) {
      $sql = $sql. ' AND `パスワード` = "'.$PI_ary['パスワード'].'"';
    }
    $sql = $sql. ' order by `ID`';
    $sql = $sql. ';';

    return $sql;
  }

  function update($PI_ary){

    $sql = "";
    $sql = $sql. "update mst_ユーザ ";
    $sql = $sql. " set ";
    // パスワードは必須のため
    $sql = $sql. '  `パスワード`         = "'.$PI_ary['パスワード'].'"';

    if (array_key_exists('ニックネーム', $PI_ary)) {
      $sql = $sql. ', `ニックネーム`           = "'.$PI_ary['ニックネーム'].'"';
    }
    if (array_key_exists('男', $PI_ary)) {
      $sql = $sql. ', `男`           = "'.$PI_ary['男'].'"';
    }
    if (array_key_exists('女', $PI_ary)) {
      $sql = $sql. ', `女`           = "'.$PI_ary['女'].'"';
    }
    if (array_key_exists('職業', $PI_ary)) {
      $sql = $sql. ', `職業`           = "'.$PI_ary['職業'].'"';
    }
    if (array_key_exists('生年月日', $PI_ary)) {
      $sql = $sql. ', `生年月日`           = "'.$PI_ary['生年月日'].'"';
    }
    if (array_key_exists('都道府県', $PI_ary)) {
      $sql = $sql. ', `都道府県`           = "'.$PI_ary['都道府県'].'"';
    }
    if (array_key_exists('市区町村', $PI_ary)) {
      $sql = $sql. ', `市区町村`           = "'.$PI_ary['市区町村'].'"';
    }
    if (array_key_exists('町名番地', $PI_ary)) {
      $sql = $sql. ', `町名番地`           = "'.$PI_ary['町名番地'].'"';
    }
    if (array_key_exists('建物等', $PI_ary)) {
      $sql = $sql. ', `建物等`           = "'.$PI_ary['建物等'].'"';
    }

    if (array_key_exists('質問1', $PI_ary)) {
      $sql = $sql. ', `質問1`           = "'.$PI_ary['質問1'].'"';
    }
    if (array_key_exists('回答1', $PI_ary)) {
      $sql = $sql. ', `回答1`           = "'.$PI_ary['回答1'].'"';
    }
    if (array_key_exists('質問2', $PI_ary)) {
      $sql = $sql. ', `質問2`           = "'.$PI_ary['質問2'].'"';
    }
    if (array_key_exists('回答2', $PI_ary)) {
      $sql = $sql. ', `回答2`           = "'.$PI_ary['回答2'].'"';
    }
    if (array_key_exists('質問3', $PI_ary)) {
      $sql = $sql. ', `質問3`           = "'.$PI_ary['質問3'].'"';
    }
    if (array_key_exists('回答3', $PI_ary)) {
      $sql = $sql. ', `回答3`           = "'.$PI_ary['回答3'].'"';
    }

    $sql = $sql. " where 1 = 1";
    if (array_key_exists('ID', $PI_ary)) {
      $sql = $sql. ' AND `ID` = "'.$PI_ary['ID'].'"';
    }else{
      return;
    }
    $sql = $sql. ' order by `ID`';
    $sql = $sql. ';';

    return $sql;
  }

}



