
<?php 

  function update($PI_filePath){

    $エラーコード = ERR種類['エラー無し'];
    $エラーチェック結果 = array();

    $cls_基本ページ詳細 = new MstShopMainPage_Ctrl;
    $更新内容 = array(); // sqlを作るための引数用

    /* 配列を作成 */
      $更新内容  = array(
        'ショップID'=>$_SESSION['shop_id'],
        'ID'=>$_POST['ID'],
        '画像パス'=> $PI_filePath,
        '画像メッセージ'=>$_POST['メッセージ']
      );
      /*  */
      
    /* エラーチェック */
      $res_check = $cls_基本ページ詳細->check($更新内容);
      if ($res_check['status'] == false){
        $エラーコード = ERR種類['エラーチェック'];
        $エラーチェック結果 = $res_check["msg"];
      }

    /* */

    /* トランザクション*/
      try{
        global $cls_dbCtrl;
        $cls_dbCtrl->begin_tran();

        $return_dateUpdIns = array();
        $return_dateUpdIns = data_Update_or_Insert($cls_基本ページ詳細, $更新内容);
        
        if($return_dateUpdIns['status'] == false){
          $cls_dbCtrl->rollback();
          return array('エラーコード'=>ERR種類['更新失敗'], 'エラーチェック結果'=>$エラーチェック結果);
        }

        $cls_dbCtrl->commit();
        
      }catch ( Exception $ex ) {
        $cls_dbCtrl->rollback();
        return array('エラーコード'=>ERR種類['更新失敗'], 'エラーチェック結果'=>$エラーチェック結果);
      }
    /*  */

    // エラー無し
    return array('エラーコード'=>ERR種類['エラー無し'], 'エラーチェック結果'=>$エラーチェック結果);
  }

  function delete(){

    $エラーコード = ERR種類['エラー無し'];
    $エラーチェック結果 = array();
    
    $cls_基本ページ詳細 = new MstShopMainPage_Ctrl;

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
        $sql_del = $cls_基本ページ詳細->delete($削除内容);
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
    // エラー無し
    return array('エラーコード'=>ERR種類['エラー無し'], 'エラーチェック結果'=>$エラーチェック結果);
    /*  */
  }
  
?>


<?php // 初期処理

   
  /* 初期処理 */
    // この画面で使う定数
    define('レベルMAX', 6);

    
    /* 変数初期化 */
    $エラーコード = ERR種類['エラー無し'];
    $モード = モードコード['入力'];

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
  /* */

  if( $モード == モードコード['戻る'] ) {

    $_POST['menu-btn'] = ショップ管理機能['メニュー']['NO'];
    header("Location: index.php");
    exit ;

  }elseif ($モード == モードコード['削除']){

    $return = delete();
    $エラーコード = $return['エラーコード'];
    $エラーチェック結果 = $return['エラーチェック結果'];

  }elseif ($モード == モードコード['更新']){

    $uploadFolder = ショップRoute . $_SESSION['shop_id'] . "/main/img/";
    $type = "jpg";
    $fileAfterName = "details" . sprintf('%02d', $_POST["ID"]) . "." . $type;
    
    if(isset($_FILES)&& isset($_FILES['画像ファイル']) && is_uploaded_file($_FILES['画像ファイル']['tmp_name'])){
        
      // 拡張子を取得
      $type = pathinfo($_FILES['画像ファイル']['name'], PATHINFO_EXTENSION);
      
    }

    /* 更新処理 */
      $return = update("https://" . $_SERVER["HTTP_HOST"] . substr($uploadFolder,2) . $fileAfterName);
      $エラーコード = $return['エラーコード'];
      $エラーチェック結果 = $return['エラーチェック結果'];

      /* 正常処理完了でファイルアップロード処理開始 */
      if($エラーコード == ERR種類['エラー無し']){

        if(isset($_FILES)&& isset($_FILES['画像ファイル']) && is_uploaded_file($_FILES['画像ファイル']['tmp_name'])){
          
          // 画像アップロード処理クラスを呼ぶ
          $cls_imgUpload = new imgUpload();
          $result = $cls_imgUpload->details($_FILES['画像ファイル']['tmp_name'], $type, $uploadFolder, $fileAfterName);
          if($result == false){
            $エラーコード = ERR種類['ファイルアップロード失敗'];
          }
          
          clearstatcache();
                 
        }
      }
    /*  */
  }

?>

<?php // データ取得処理

  /* 最後に必ずマスタ最新値を取得し直す */
    $cls_基本ページ詳細 = new MstShopMainPage_Ctrl;
    $Mst基本ページ詳細_検索情報 = array(); // sqlを作るための引数用
    $ary_Mst基本情報ページ詳細 = array(); // ログインショップIDのレコード

    $Mst基本ページ詳細_検索情報= array(
      'ショップID'=>$index['ショップID']
    );

    // ショップIDを条件に検索
    $Sql_sel = $cls_基本ページ詳細->select($Mst基本ページ詳細_検索情報);
    $res_select = $cls_dbCtrl->select($Sql_sel);
    if ($res_select["status"] == false){
      $エラーコード = ERR種類['マスタ情報取得失敗'];
    }else{
      // 取得レコードを変数へ
      $ary_Mst基本情報ページ詳細 = $res_select["rows"];
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
    document.getElementById("msg").value = '';
    document.getElementById("file-img").value = "";
    document.getElementById( "preview-img" ).src = '';
    document.getElementById( "preview-text" ).innerText= '';
    document.getElementById("previewText").style.display = "none";

    if (document.getElementById( "reg_ID" + selectID ) == null){
      // 存在しない場合は処理をぬける
      return;
    }

    // inputに値を入れる
    document.getElementById("msg").value = document.getElementById( "reg_msg" + selectID ).value;
    document.getElementById("preview-text").innerText = document.getElementById( "msg" ).value;
    document.getElementById("preview-img").src = document.getElementById( "reg_imgPath" + selectID ).value;

    if(document.getElementById("msg").value != ""){
      document.getElementById("previewText").style.display = "block";
    }
  }

</script>
<script type="text/javascript" src="<?php echo Route; ?>/js/previewImg.js"></script>


<h4>登録</h4>
<div class="content-item">

  <form class="insert" action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="menu-btn" value="<?php echo $index['Me']['NO']; ?>" />

    <div class="item" >
      <label class="itemLabel-primary" for="ID">No</label>
      <select id="ID" name="ID" onChange="IDChange(this);">
        <?php
          for ($idx = 1; $idx <= レベルMAX; $idx++){
            $val = "";
            if($エラーコード != ERR種類['エラー無し']){
              // エラーがあった場合は入力内容を引き継ぐ
              if($row['ID'] == $_POST['ID']){
                $val = "selected";
              }
            }
        ?>
          <option value="<?php echo $idx; ?>" <?php echo $val; ?>><?php echo $idx; ?></option>
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
      <label class="itemLabel-primary" for="画像ファイル">画像ファイル</label>
      <?php
        $val = "";
        if($エラーコード != ERR種類['エラー無し']){
          // エラーがあった場合は入力内容を引き継ぐ
          $val = $_POST['画像ファイル'];
        }
      ?>
      <input type="file" class="file" id="file-img" name="画像ファイル" accept="image/*" 
      onchange="OnFileSelect(this,document.getElementById('preview-img'));">
      <br>
      <label class="err">
        <?php
          if (array_key_exists('画像ファイル', $エラーチェック結果)){
            echo $エラーチェック結果['画像ファイル'];
          }
        ?>
      </label>
    </div>

    <div class="item">
        <label class="itemLabel-nomal" for="msg">メッセージ</label>
        <?php
          $val = "";
          if($エラーコード != ERR種類['エラー無し']){
            // エラーがあった場合は入力内容を引き継ぐ
            $val = $_POST['メッセージ'];
          }
        ?>
        <input type="text" class="text" id="msg" name="メッセージ" value="<?php echo $val; ?>" 
        onchange="MsgChange(this, document.getElementById('previewText'), document.getElementById('preview-text'));">
        <br><label class="err">
          <?php
            if (array_key_exists('メッセージ', $エラーチェック結果)){
              echo $エラーチェック結果['メッセージ'];
            }
          ?>
        </label>
    </div>

    <div class="item" id="preview">
      <label class="itemLabel-noedit" for="imgFile">イメージ</label>
      <div class="ImgMsg" id="previewImg">
        <img id="preview-img" />
        <div class="msg" id="previewText" style="display: none;">
          <p id="preview-text"></p>
        </div>
      </div>
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
        if (count($ary_Mst基本情報ページ詳細) == 0){
          //処理日以降の不定営設定はない
          echo '<h4>有効なSNS設定はありません。</h4>';
        }
      ?>
    
    <div class="item">
      <table id="reg-table">
        <tr>
          <th class="clm-ID">No</th>
          <th class="clm-msg">イメージ</th>
          <th class="clm-delBtn">削除</th>
        </tr>

        <?php
          for($seq = 1; $seq <= count($ary_Mst基本情報ページ詳細); $seq++){

            $row = $ary_Mst基本情報ページ詳細[$seq - 1];
        ?>
          <tr>
            <td class="clm-ID"><?php echo $row['ID']; ?></td>

            <td class="clm-msg" id="preview">
              
              <div class="ImgMsg">
                <img src="<?php echo $row['画像パス']; ?>">
                <?php  
                  $msgDisplay = "";
                  if($row['画像メッセージ'] == ""){
                    $msgDisplay = 'style="display:none;"';
                  }
                ?>
                <div class="msg" <?php echo $msgDisplay; ?>>
                  <p><?php echo $row['画像メッセージ']; ?></p>
                </div>
              </div>
            </td>
            <td class="clm-delBtn">
              <input type="checkbox" class="reg_checkbox" id="reg_checkbox<?php echo $row['ID']; ?>" name="reg_削除<?php echo $row['ID']; ?>" value="1" />
            </td>
            <input type="hidden" id="reg_ID<?php echo $row['ID']; ?>" name="reg_ID<?php echo $row['ID']; ?>" value="<?php echo $row['ID']; ?>" />
            <input type="hidden" id="reg_msg<?php echo $row['ID']; ?>" name="reg_msg<?php echo $row['ID']; ?>" value="<?php echo $row['画像メッセージ']; ?>" />
            <input type="hidden" id="reg_imgPath<?php echo $row['ID']; ?>" name="reg_imgPath<?php echo $row['ID']; ?>" value="<?php echo $row['画像パス'] . date("YmdHis"); ?>" />
          </tr>

        <?php
          }
        ?>
      </table>
    </div>

    <div class="item">
      <button id="btn_delete" name="btn_delete" value="<?php echo モードコード['削除']; ?>">削除</button>
    </div>
  </form>
  
</div>
<script>
  if(<?php echo $エラーコード; ?> == <?php echo ERR種類['エラー無し']; ?>){
    IDChange(document.getElementById("ID"));
  }
</script>