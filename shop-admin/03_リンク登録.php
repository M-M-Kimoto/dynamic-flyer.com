
<?php 

  function update(){

    $エラーコード = ERR種類['エラー無し'];
    $エラーチェック結果 = array();

    $cls_mstショップ表示ページ = new MstShopViewerPage_Ctrl();
    $更新内容 = array();

    /* 配列へ */
      $更新内容 = array(
        'ショップID'=>$_SESSION['shop_id'],
        'ID'=>$_POST['表示設定ID'],
        '名称'=>$_POST['名称'],
        'URL'=>$_POST['URL']
      );

    /*  */

    /* エラーチェック */
      $res_check = $cls_mstショップ表示ページ->check($更新内容);
      if ($res_check['status'] == false){
        $エラーコード = ERR種類['エラーチェック'];
        $ary_エラーメッセージ[$seq] = $res_check["msg"];
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
        $return_dateUpdIns = data_Update_or_Insert($cls_mstショップ表示ページ, $更新内容);
        
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
    
    $cls_mstショップ表示ページ = new MstShopViewerPage_Ctrl();

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
            'ID'=>$_POST['reg_ID'.$seq]
          );

          // エラー無しで更新処理実行
          $sql_del = $cls_mstショップ表示ページ->delete($削除内容);
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

<?php

   
  // この画面で使う定数
  define('レベルMAX', 9);

  /* 変数初期化 */
  $エラーコード = ERR種類['エラー無し'];
  $モード = モードコード['入力'];
  $登録可能数 = 3;
  $ary_エラーメッセージ = array();

  //echo $_POST['btn_confirm'];
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

  /* tra_ショップステータスの取得 */
    $cls_traショップステータス = new TraShopStatus_Ctrl();
    $traショップステータス_検索条件種類コード = array(); // sqlを作るための引数用
    $ショップステータス = array(); // ログインショップIDのmst_ショップのレコード

    // ショップ種類コードの一覧を取得
    $traショップステータス_検索条件種類コード = array(
      'ショップID'=>$index['ショップID']
    );
    $sql_select_TraShopStatus = $cls_traショップステータス->select($traショップステータス_検索条件種類コード);
    $res_select_TraShopStatus = $cls_dbCtrl->select($sql_select_TraShopStatus);
    if ($res_select_TraShopStatus["status"] == false){
      $エラーコード = ERR種類['ショップステータス取得失敗'];

    }elseif ($res_select_TraShopStatus["count"]< 1){
      
      //キーで検索しているためありえないが、
      //これをいれないと問答無用で配列の先頭をとるというのが意味不明なため
      $エラーコード = ERR種類['ショップステータス取得失敗'];

    }else{
      // 取得レコードを変数へ
      $ショップステータス = $res_select_TraShopStatus["rows"][0];
      $登録可能数 = $ショップステータス['リンク登録可能数'];
    }
  /*  */

  /* mst_ショップ表示ページの取得 */
    $cls_mstショップ表示ページ = new MstShopViewerPage_Ctrl();
    $mstショップ表示ページ_検索条件種類コード = array(); // sqlを作るための引数用
    $ary_ショップ表示ページ = array(); // ログインショップIDのmst_ショップのレコード

    // ショップ種類コードの一覧を取得
    $mstショップ表示ページ_検索条件種類コード = array(
      'ショップID'=>$index['ショップID']
    );
    $sql_select_MstShopViewerPage = $cls_mstショップ表示ページ->select($mstショップ表示ページ_検索条件種類コード);
    $res_select_MstShopViewerPage = $cls_dbCtrl->select($sql_select_MstShopViewerPage);
    if ($res_select_MstShopViewerPage["status"] == false){
      $エラーコード = ERR種類['マスタ情報取得失敗'];

    }else{
      // 取得レコードを変数へ
      $ary_ショップ表示ページ = $res_select_MstShopViewerPage["rows"];
    }
  /*  */

  if($エラーコード == ERR種類['エラー無し']){

    // ポストした内容も消す
    $_POST = array();

  }
  clearstatcache(); 

?>

<?php  echo '<link rel="stylesheet" href="./stylesheets/'.$index['Me']['ファイル名'].'.css">'; ?>

<script language="javascript" type="text/javascript">

  var window_A;
  function urlClick(){
    if (document.getElementById('URL').value == ""){
      return ;
    }
    window_A = window.open(document.getElementById('URL').value, "google");
  }

  function IDChange(obj){
    var selectID = obj.options[obj.selectedIndex].value;
    //let selectLv = document.getElementById("レベル").selected;
    document.getElementById("名称").value = '';
    document.getElementById("URL").value = '';

    if (document.getElementById( "reg_ID" + selectID ) == null){
      // 存在しない場合は処理をぬける
      return;
    }

    // inputに値を入れる
    document.getElementById("名称").value = document.getElementById( "reg_Name" + selectID ).value;
    document.getElementById("URL").value = document.getElementById( "reg_URL" + selectID ).value;
    
  }

</script>


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


<div class="content-item">

<form class="insert" action="" method="post">
  <input type="hidden" name="menu-btn" value="<?php echo $index['Me']['NO']; ?>" />

  <div class="item">
      <label class="itemLabel-primary" for="表示設定ID">設定</label>
      <select id="表示設定ID" name="表示設定ID" onChange="IDChange(this);">
        <?php
          $val = 1;
          if($エラーコード != ERR種類['エラー無し']){
            // エラーがあった場合は入力内容を引き継ぐ
            $val = $_POST['NO'];
          }
          for($idx = 1; $idx <= $登録可能数; $idx++){
            $selectNo = '';
            if($val == $idx){
              $selectNo = 'selected';
            }
        ?>
        <option value="<?php echo $idx; ?>" <?php echo $selectNo; ?> >設定：<?php echo $idx; ?></option>
        <?php } ?>
      </select>
  </div>

  <div class="item">
      <label class="itemLabel-primary" for="名称">名称</label>
      <?php
        $val = "";
        if($エラーコード != ERR種類['エラー無し']){
          $val = $_POST['名称'];
        }
      ?>
      <input type="text" id="名称" class="text" name="名称" value="<?php echo $val; ?>">
  </div>

  <div class="item">
      <label class="itemLabel-primary" for="URL">URL</label>
        <?php
          $val = "";
          if($エラーコード != ERR種類['エラー無し']){
            $val = $_POST['URL'];
          }
        ?>
        <input type="text" id="URL" class="text" name="URL" value="<?php echo $val; ?>">
      <button type="button" id="url-btn" onClick="urlClick();">URLを開く</button>
  </div>

  <div class="item" id="upd-bak-btn">
    <button id="btn_Update" name="btn_Update" value="<?php echo モードコード['更新']; ?>">更新</button>
    <button id="btn_back" name="btn_back" value="<?php echo モードコード['戻る']; ?>">戻る</button>
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
        // 隠し項目に自分のメニュー番号を
        if (count($ary_ショップ表示ページ) == 0){
          //処理日以降の不定営設定はない
          echo '<h4>有効な設定はありません。</h4>';
        }
      ?>
    <table id="reg-table">
      <tr>
        <th class="clm-SNS">No</th>
        <th class="clm-Name">名称</th>
        <th class="clm-URL">URL</th>
        <th class="clm-delBtn">削除</th>
      </tr>

      <?php
        for($seq = 1; $seq <= count($ary_ショップ表示ページ); $seq++){

          $row = $ary_ショップ表示ページ[$seq - 1];
      ?>
        <tr>
          <td class="clm-ID"><?php echo $row['ID']; ?></td>
          <td class="clm-Name"><?php echo $row['名称']; ?></td>
          <td class="clm-URL"><?php echo$row['URL']; ?></td>
          <td class="clm-delBtn">
            <input type="checkbox" class="reg_checkbox" id="reg_checkbox<?php echo $row['ID']; ?>" name="reg_削除<?php echo $row['ID']; ?>" value="1" />
          </td>
          <input type="hidden" id="reg_ID<?php echo $row['ID']; ?>" name="reg_ID<?php echo $row['ID']; ?>" value="<?php echo $row['ID']; ?>" />
          <input type="hidden" id="reg_Name<?php echo $row['ID']; ?>" name="reg_Name<?php echo $row['ID']; ?>" value="<?php echo $row['名称']; ?>" />
          <input type="hidden" id="reg_URL<?php echo $row['ID']; ?>" name="reg_URL<?php echo $row['ID']; ?>" value="<?php echo $row['URL']; ?>" />
        </tr>

      <?php
        }
      ?>
    </table>

    <div class="item">
      <button id="btn_delete" name="btn_delete" value="<?php echo モードコード['削除']; ?>">削除</button>
    </div>
  </form>
  
</div>
<script>
  IDChange(document.getElementById("表示設定ID"));
</script>
</div>