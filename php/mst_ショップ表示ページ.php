<?php


//define('URL_ROOT', `../../`);
class MstShopViewerPage_Ctrl
{

  /*
  エラーチェック
  */
  function check($PI_ary){

    // 初期処理
    $PO_ary = array('status'=>結果['問題なし'], 'msg'=>array());

    // ID
    $PO_ary = check_primaryItem('ID',$PI_ary, 10, $PO_ary);
    $PO_ary = check_nomalItem_match('ID',$PI_ary, 10, '/^[0-9]+$/', $PO_ary);

    // URL
    $PO_ary = check_nomalItem('URL',$PI_ary, 500, $PO_ary);
    
    // 名称
    if (array_key_exists('URL', $PI_ary) == false){

      if (array_key_exists('名称', $PI_ary) == true){
        // 配列に存在しない
        if($PI_ary['名称'] != ''){
          $PO_ary['status'] = 結果['問題あり'];
          $PO_ary['msg']['URL'] = 'URL未入力です。';
        }
      }
    }elseif($PI_ary['URL'] != ''){

      if (array_key_exists('名称', $PI_ary) == false){
        // 配列に存在しない
        $PO_ary['status'] = 結果['問題あり'];
        $PO_ary['msg']['名称'] = '名称未入力です。';
      }else{
        if($PI_ary['名称'] == ''){
          $PO_ary['status'] = 結果['問題あり'];
          $PO_ary['msg']['名称'] = '名称未入力です。';
        }
      }
    }

    return $PO_ary;
  }

  function select($PI_ary) {
      $sql = '';
      $sql = $sql. 'select ';
      $sql = $sql. ' `ショップID`,';
      $sql = $sql. ' `ID`,';
      $sql = $sql. ' `名称`,';
      $sql = $sql. ' `URL`,';
      $sql = $sql. ' `最終更新日`';
      $sql = $sql. ' from `mst_ショップ表示ページ`';
      $sql = $sql. ' where 1 = 1';
      if (array_key_exists('ショップID', $PI_ary)) {
        $sql = $sql. ' AND `ショップID` = "'.$PI_ary['ショップID'].'"';
      }
      $sql = $sql. ' order by `ショップID`, `ID`';
      $sql = $sql. ';';

      return $sql;
  }

  function update($PI_ary) {
    $sql = '';
    $sql = $sql. 'update `mst_ショップ表示ページ` ';
    $sql = $sql. ' set';
    $sql = $sql. '    `最終更新日`         = Now()';
    if (array_key_exists('名称', $PI_ary)) {
      $sql = $sql. ', `名称`           = "'.$PI_ary['名称'].'"';
    }
    if (array_key_exists('URL', $PI_ary)) {
      $sql = $sql. ', `URL`           = "'.$PI_ary['URL'].'"';
    }
    $sql = $sql. ' where 1 = 1';
    if (array_key_exists('ショップID', $PI_ary)) {
      $sql = $sql. ' AND `ショップID` = "'.$PI_ary['ショップID'].'"';
    }else{
      return ;
    }
    if (array_key_exists('ID', $PI_ary)) {
      $sql = $sql. ' AND `ID` = "'.$PI_ary['ID'].'"';
    }else{
      return ;
    }
    $sql = $sql. ';';

    return $sql;
  }

  function insert($PI_ary) {
    // 連番などは自動登録のため、insert時に指定しない
    $sql = "";
    $sql = $sql. 'insert into `mst_ショップ表示ページ`(';
    $sql = $sql. ' `ショップID`,';
    $sql = $sql. ' `ID`,';
    $sql = $sql. ' `名称`,';
    $sql = $sql. ' `URL`,';
    $sql = $sql. ' `最終更新日`';
    $sql = $sql. ")VALUES(";
    $sql = $sql. '  "'.$PI_ary['ショップID'].'"';
    $sql = $sql. ' ,"'.$PI_ary['ID'].'"';
    $sql = $sql. ' ,"'.$PI_ary['名称'].'"';
    $sql = $sql. ' ,"'.$PI_ary['URL'].'"';
    $sql = $sql. ' ,Now()';
    $sql = $sql. ' );';

    return $sql;
  }

  function delete($PI_ary) {
    $sql = '';
    $sql = $sql. ' delete from `mst_ショップ表示ページ` ';
    $sql = $sql. ' where 1 = 1';
    if (array_key_exists('ショップID', $PI_ary)) {
      $sql = $sql. ' AND `ショップID` = "'.$PI_ary['ショップID'].'"';
    }else{
      return ;
    }
    if (array_key_exists('ID', $PI_ary)) {
      $sql = $sql. ' AND `ID` = "'.$PI_ary['ID'].'"';
    }else{
      return ;
    }
    $sql = $sql. ';';

    return $sql;
  }

}


?>
