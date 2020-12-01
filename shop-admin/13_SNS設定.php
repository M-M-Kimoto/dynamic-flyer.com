
<?php 

  function update(){

    $エラーコード = ERR種類['エラー無し'];
    $エラーチェック結果 = array();

    $cls_ショップSNS = new MstShopSNS_Ctrl;
    $更新内容 = array();

    /* 配列へ */
      $更新内容 = array(
        'ショップID'=>$_SESSION['shop_id'],
        'SNSID'=>$_POST['SNSID'],
        'URL'=>$_POST['URL']
      );

    /*  */

    /* エラーチェック */
      $res_check = $cls_ショップSNS->check($更新内容);
      if ($res_check['status'] == false){
        $エラーコード = ERR種類['更新失敗'];
        $エラーチェック結果 = $res_check["msg"];
      }
    
    /* */

    if($エラーコード != ERR種類['エラー無し']){
        // 引っ掛かった場合
        return array('エラーコード'=>ERR種類['エラーチェック'], 'エラーチェック結果'=>$エラーチェック結果);
    }

    /* トランザクション*/
      try{
        global $cls_dbCtrl;
        $cls_dbCtrl->begin_tran();

        $return_dateUpdIns = array();
        $return_dateUpdIns = data_Update_or_Insert($cls_ショップSNS, $更新内容);
        
        if($return_dateUpdIns['status'] == false){
          $cls_dbCtrl->rollback();
          return array('エラーコード'=>ERR種類['更新失敗'], 'エラーチェック結果'=>$エラーチェック結果);
        }

        $cls_dbCtrl->commit();
        
      }catch ( Exception $ex ) {
        $cls_dbCtrl->rollback();
        return array('エラーコード'=>ERR種類['更新失敗'], 'エラーチェック結果'=>$エラーチェック結果);
      }
      // エラー無し
      return array('エラーコード'=>ERR種類['エラー無し'], 'エラーチェック結果'=>$エラーチェック結果);
    /*  */
  }

  function delete(){

    $エラーコード = ERR種類['エラー無し'];
    $エラーチェック結果 = array();
    
    $cls_ショップSNS = new MstShopSNS_Ctrl;

    /* トランザクション*/
      try{
        global $cls_dbCtrl;
        $cls_dbCtrl->begin_tran();
        
        for ($seq = 1; $seq <= レベルMAX; $seq++){

          if (isset($_POST['reg_削除'.$seq]) == false){
            continue;
          }elseif($_POST['reg_削除'.$seq] != '1'){
            continue;
          }

          $削除内容 = array(
            'ショップID'=>$_SESSION['shop_id'],
            'SNSID'=>$_POST['reg_SNSID'.$seq]
          );

          // エラー無しで更新処理実行
          $sql_del = $cls_ショップSNS->delete($削除内容);
          $res_delete = $cls_dbCtrl->delete($sql_del);
          if($res_delete['status'] == false){
            $エラーコード = ERR種類['削除失敗'];
          }
        }

        if($エラーコード != ERR種類['エラー無し']){
          $cls_dbCtrl->rollback();
          return array('エラーコード'=>$エラーコード, 'エラーチェック結果'=>$エラーチェック結果);
        }else{
          $cls_dbCtrl->commit();
        }
        
      }catch ( Exception $ex ) {
        $cls_dbCtrl->rollback();
        return array('エラーコード'=>$エラーコード, 'エラーチェック結果'=>$エラーチェック結果);
      }
    /*  */

    // エラー無し
    return array('エラーコード'=>ERR種類['エラー無し'], 'エラーチェック結果'=>$エラーチェック結果);
  }

?>


<?php // 初期処理

   
  // この画面で使う定数
  define('レベルMAX', 9);

  /* 変数初期化 */
  $エラーコード = ERR種類['エラー無し'];
  $モード = モードコード['入力'];

  $MstSNS_更新情報 = array(); // sqlを作るための引数用
  $エラーチェック結果 = array(); // エラーチェックに引っかかった項目名をキーにエラーメッセージは入った連想配列

  if( isset($_POST['btn_Update']) ) {
    // エラーチェック処理に飛ばす
    $モード =  $_POST['btn_Update'];
  }elseif( isset($_POST['btn_back']) ) {
    // エラーチェック処理に飛ばす
    $モード =  $_POST['btn_back'];
  }elseif( isset($_POST['btn_delete']) ) {
    // エラーチェック処理に飛ばす
    $モード =  $_POST['btn_delete'];
  }

  if( $モード == モードコード['戻る'] ) {

    $_POST['menu-btn'] = ショップ管理機能['メニュー']['NO'];
    header("Location: index.php");
    exit ;

  }elseif ($モード == モードコード['削除']){

    $return = delete();
    $エラーコード = $return['エラーコード'];
    $エラーチェック結果 = $return['エラーチェック結果'];

  }elseif ($モード == モードコード['更新']){
    
    // 更新処理実行
    $return = update();
    $エラーコード = $return['エラーコード'];
    $エラーチェック結果 = $return['エラーチェック結果'];

  }

?>

<?php // データ取得処理


  /* 最後に必ずマスタ最新値を取得し直す */
    $cls_ショップSNS = new MstShopSNS_Ctrl;
    $MstSNS_検索情報 = array(); // sqlを作るための引数用
    $ary_SNS = array(); // ログインショップIDのレコード

    $MstSNS_検索情報= array(
      'ショップID'=>$index['ショップID']
    );

    // ショップIDを条件に検索
    $Sql_sel = $cls_ショップSNS->select_Join_MstSNS($MstSNS_検索情報);
    $res_select = $cls_dbCtrl->select($Sql_sel);
    if ($res_select["status"] == false){
      $エラーコード = ERR種類['マスタ情報取得失敗'];
    }else{
      // 取得レコードを変数へ
      $ary_SNS = $res_select["rows"];
    }
  /*  */

  /* mst_SNS処理 */
    $cls_MstSNS = new MstSNS_Ctrl;
    $MstSNS_検索条件 = array(); // sqlを作るための引数用
    $ary_MstSNS = array(); // 

    // ショップIDを条件に検索
    $sql_select = $cls_MstSNS->select($MstSNS_検索条件);
    $res_select = $cls_dbCtrl->select($sql_select);
    if ($res_select["status"] == false){
      $エラーコード = ERR種類['マスタ情報取得失敗'];

    }else{
      // 取得レコードを変数へ
      $ary_MstSNS = $res_select["rows"];
      
    }
  /*  */

  if($エラーコード == ERR種類['エラー無し']){

    // ポストした内容も消す
    $_POST = array();

  }
  clearstatcache(); 

?>

<link rel="stylesheet" href="./stylesheets/<?php echo $index['Me']['ファイル名']; ?>.css">

<?php
  // メッセージ
  $メッセージ = '';
  if ($エラーコード == ERR種類['エラー無し'] && $モード == モードコード['更新']) {
    $メッセージ = '更新しました。';
  }elseif ($エラーコード == ERR種類['エラーチェック']) {
      $メッセージ = ERRメッセージ[$エラーコード];
  }elseif ($エラーコード == ERR種類['更新失敗']) {
    $メッセージ = ERRメッセージ[$エラーコード];
  }
?>
<h4 id="top-msg"><?php echo $メッセージ; ?></h4>

<script language="javascript" type="text/javascript">

  function IDChange(obj){
    var selectID = obj.options[obj.selectedIndex].value;
    //let selectLv = document.getElementById("レベル").selected;
    document.getElementById("URL").value = '';

    if (document.getElementById( "reg_SNSID" + selectID ) == null){
      // 存在しない場合は処理をぬける
      return;
    }

    // inputに値を入れる
    document.getElementById("URL").value = document.getElementById( "reg_URL" + selectID ).value;
    
  }

</script>

<h4>登録</h4>
<div class="content-item">

  <form class="insert" action="" method="post">
    <input type="hidden" name="menu-btn" value="<?php echo $index['Me']['NO']; ?>" />
    
    <div class="item" >
      <label class="itemLabel-primary" for="SNSID">SNS種類</label>
      <select id="SNSID" name="SNSID" onChange="IDChange(this);">
        <?php
          foreach($ary_MstSNS as $row){
            $val = "";

            if($エラーコード != ERR種類['エラー無し']){
              // エラーがあった場合は入力内容を引き継ぐ
              if($row['ID'] == $_POST['SNSID']){
                $val = "selected";
              }
            }
        ?>
          <option value="<?php echo $row['ID']; ?>" <?php echo $val; ?>><?php echo $row['名称']; ?></option>
        <?php
          }
        ?>
      </select>
      <br><label class="err">
        <?php
          if (array_key_exists('ID', $エラーチェック結果)){
            echo $エラーチェック結果['ID'];
          }
        ?>
      </label>
    </div>

    <div class="item">
        <label class="itemLabel-primary" for="URL">URL</label>
        <?php
          $val = "";
          if($エラーコード != ERR種類['エラー無し']){
            // エラーがあった場合は入力内容を引き継ぐ
            $val = $_POST['URL'];
          }
        ?>
        <input type="url" class="text" id="URL" name="URL" value="<?php echo $val?>">
        <br><label class="err">
          <?php
            if (array_key_exists('URL', $エラーチェック結果)){
              echo $エラーチェック結果['URL'];
            }
          ?>
        </label>
    </div>

    <div class="item" id="upd-bak-btn">
      <button id="btn_Update" name="btn_Update" value="<?php echo モードコード['更新']; ?>" >更新</button>
      <button id="btn_back" name="btn_back" value="<?php echo モードコード['戻る']; ?>" >戻る</button>
    </div>
  </form>

  <hr>
  <label class="title-lbl" id="sub-title-lbl">設定済み一覧</label>
  
  <?php

    // メッセージ
    $メッセージ = '';
    if ($エラーコード == ERR種類['エラー無し'] && $モード == モードコード['削除']) {
      $メッセージ = '削除しました。';
    }elseif ($エラーコード == ERR種類['削除失敗']) {
      $メッセージ = ERRメッセージ[$エラーコード];
    }elseif ($エラーコード == ERR種類['マスタ情報取得失敗']) {
      $メッセージ = ERRメッセージ[$エラーコード];
    }
  ?>
  <br><br><label id="top-msg"><?php echo $メッセージ; ?></label>

  <form class="delete" action="" method="post">
    <input type="hidden" name="menu-btn" value="<?php echo $index['Me']['NO']; ?>" />
    <?php
        if (count($ary_SNS) == 0){
          //処理日以降の不定営設定はない
          echo '<h4>有効なSNS設定はありません。</h4>';
        }
    ?>
    
    <div class="item">
      <table id="reg-table">
        <tr>
          <th class="clm-SNS">SNS</th>
          <th class="clm-URL">URL</th>
          <th class="clm-delBtn">削除</th>
        </tr>

        <?php for($seq = 1; $seq <= count($ary_SNS); $seq++){
            $row = $ary_SNS[$seq - 1];
          ?>
          <tr>
            <td class="clm-SNS"><?php echo $row['名称']; ?></td>
            <td class="clm-URL"><?php echo$row['URL']; ?></td>
            <td class="clm-delBtn">
              <input type="checkbox" class="reg_checkbox" id="reg_checkbox<?php echo $row['SNSID']; ?>" name="reg_削除<?php echo $row['SNSID']; ?>" value="1" />
            </td>
            <input type="hidden" id="reg_SNSID<?php echo $row['SNSID']; ?>" name="reg_SNSID<?php echo $row['SNSID']; ?>" value="<?php echo $row['SNSID']; ?>" />
            <input type="hidden" id="reg_URL<?php echo $row['SNSID']; ?>" name="reg_URL<?php echo $row['SNSID']; ?>" value="<?php echo $row['URL']; ?>" />
          </tr>

        <?php } ?>
      </table>
    </div>

    <div class="item">
      <button id="btn_delete" name="btn_delete" value="<?php echo モードコード['削除']; ?>">削除</button>
    </div>
  </form>
  
</div>
<script>
  if(<?php echo $エラーコード; ?> == <?php echo ERR種類['エラー無し']; ?>){
    IDChange(document.getElementById("SNSID"));
  }
</script>