
<?php 

  function update(){

    $エラーコード = ERR種類['エラー無し'];
    $エラーチェック結果 = array();

    $cls_mstショップ = new MstShop_Ctrl;
    $Mstショップ_更新内容 = array();

    /* 配列へ */
      // フラグ項目はチェック意外全て０とする
      if (isset($_POST['喫煙可']) == false){
        $_POST['喫煙可'] = '0';
      }elseif($_POST['喫煙可'] != '1'){
        $_POST['喫煙可'] = '0';
      }
      if (isset($_POST['駐車場有']) == false){
        $_POST['駐車場有'] = '0';
      }elseif($_POST['駐車場有'] != '1'){
        $_POST['駐車場有'] = '0';
      }
      if (isset($_POST['非表示フラグ']) == false){
        $_POST['非表示フラグ'] = '0';
      }elseif($_POST['非表示フラグ'] != '1'){
        $_POST['非表示フラグ'] = '0';
      }

      // 区切り文字を統一するための置換
      $_POST['タグ'] = str_replace("　"," ",$_POST['タグ']);
      $_POST['タグ'] = trim($_POST['タグ']);
      $_POST['タグ'] = " " . $_POST['タグ'] . " ";
      
      // 入力後の処理であれば、とりあえず連想配列を作成
      $Mstショップ_更新内容 = array(
        'ID'=>$_SESSION['shop_id'],
        'パスワード'=>$_POST['パスワード'],
        '正式名称'=>$_POST['正式名称'],
        '略称'=>$_POST['略称'],
        '電話番号'=>$_POST['電話番号'],
        'FAX番号'=>$_POST['FAX番号'],
        'メールアドレス'=>$_POST['メールアドレス'],
        '郵便番号'=>$_POST['郵便番号'],
        '都道府県'=>$_POST['都道府県'],
        '市区町村'=>$_POST['市区町村'],
        '町名番地'=>$_POST['町名番地'],
        '建物等'=>$_POST['建物等'],
        'メッセージ'=>$_POST['メッセージ'],
        'タグ'=>$_POST['タグ'],
        'ショップ種類コード'=>$_POST['ショップ種類コード'],
        '喫煙可'=>$_POST['喫煙可'],
        '駐車場有'=>$_POST['駐車場有']
      );
      
    /*  */

    /* エラーチェック */
      // エラーチェック
      $res_check = $cls_mstショップ->check($Mstショップ_更新内容);
      if ($res_check['status'] == false){
        $エラーコード = ERR種類['エラーチェック'];
        $エラーチェック結果 = $res_check['msg'];
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
        $return_dateUpdIns = data_Update_or_Insert($cls_mstショップ, $Mstショップ_更新内容);
        
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

?>

<?php

   
  /* 変数初期化 */
  $モード = モードコード['入力'];

  $エラーコード = ERR種類['エラー無し'];
  $エラーチェック結果 = array(); 

  $Mstショップ_検索条件 = array(); 

  if( isset($_POST['btn_Update']) ) {
    $モード =  $_POST['btn_Update'];
  }elseif( isset($_POST['btn_back']) ) {
    $モード =  $_POST['btn_back'];
  }

  if( $モード == モードコード['戻る'] ) {
    $_POST['menu-btn'] = ショップ管理機能['メニュー']['NO'];
    header("Location: index.php");
    exit ;

  }elseif ($モード == モードコード['更新']){

    // 更新処理実行
    $return = update();
    $エラーコード = $return['エラーコード'];
    $エラーチェック結果 = $return['エラーチェック結果'];

    /* 正常処理完了でファイルアップロード処理開始 */
    if($エラーコード == ERR種類['エラー無し']){     
      /*
      *
      * ショップトップ画像のアップロード
      *
      */
      if(isset($_FILES)&& isset($_FILES['画像ファイル']) && is_uploaded_file($_FILES['画像ファイル']['tmp_name'])){
        
        // 画像ファイルパスを作成
        $uploadPath = ショップRoute . $_SESSION['shop_id'] . "/main/img/"; 

        // 画像アップロード処理クラスを呼ぶ
        $cls_imgUpload = new imgUpload();
        $result = $cls_imgUpload->top($_FILES['画像ファイル']['tmp_name'], pathinfo($_FILES['画像ファイル']['name'], PATHINFO_EXTENSION), $uploadPath);
        if($result == false){
          $エラーコード = ERR種類['ファイルアップロード失敗'];
        }

        clearstatcache(); 
      }
    }
  }

  /* 最後に必ずマスタ最新値を取得し直す */
    $cls_Mstショップ = new MstShop_Ctrl();
    $ログインショップ情報 = array(); 
    $Mstショップ_検索条件= array(
      'ID'=>$index['ショップID']
    );

    // ショップIDとパスワードを条件に検索
    $sql_select = $cls_Mstショップ->select($Mstショップ_検索条件);
    $res_select = $cls_dbCtrl->select($sql_select);
    if ($res_select["status"] == false){
      $エラーコード = ERR種類['マスタ情報取得失敗'];

    }elseif ($res_select["count"] < 1){
      
      //キーで検索しているためありえないが、
      //これをいれないと問答無用で配列の先頭をとるというのが意味不明なため
      $エラーコード = ERR種類['マスタ情報取得失敗'];

    }else{
      // 取得レコードを変数へ
      $ログインショップ情報 = $res_select["rows"][0];
      
    }
  /* */


  /* ショップ種類コード取得処理 */
    $cls_ショップ種類 = new MstShopKindCode_Ctrl();
    $Mstショップ種類_検索情報 = array(); // sqlを作るための引数用
    $ary_ショップ種類 = array(); // ログインショップIDのレコード

    // ショップIDを条件に検索
    $Sql_sel = $cls_ショップ種類->select($Mstショップ種類_検索情報);
    $res_select = $cls_dbCtrl->select($Sql_sel);
    if ($res_select["status"] == false){
      $エラーコード = ERR種類['マスタ情報取得失敗'];
    }else{
      // 取得レコードを変数へ
      $ary_ショップ種類 = $res_select["rows"];
    }
  /* */

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
  }else{
    $メッセージ = ERRメッセージ[$エラーコード];
  }
?>
<h4 id="top-msg"><?php echo $メッセージ; ?></h4>

<script type="text/javascript" src="<?php echo Route; ?>/js/previewImg.js"></script>

<div class="content-item">

<form class="insert" action="" method="post" enctype="multipart/form-data">
  <?php // 隠し項目に自分のメニュー番号を ?>
  <input type="hidden" name="menu-btn" value="<?php echo $index['Me']['NO']; ?>" />

  <?PHP /* パスワード */ ?>

  <div class="item">
    <label class="itemLabel-primary" for="shop_passwd">パスワード</label>
    <?php
      $val = "";
      if($エラーコード != ERR種類['エラー無し']){
        // エラーがあった場合は入力内容を引き継ぐ
        $val = $_POST['パスワード'];
      }else{
        $val = $ログインショップ情報['パスワード'];
      }
    ?>
    <input type="passwd" class="text" id="shop_passwd" name="パスワード" maxlength="<?php echo 字数上限['パスワード']; ?>" placeholder="英数字記号、8文字以上" value="<?php echo $val; ?>">
    <br><label class="err">
    <?php
    if (array_key_exists('パスワード', $エラーチェック結果)){
      echo $エラーチェック結果['パスワード'];
    }
    ?>
    </label>
  </div>
  <br>
  <div class="item" id="upd-bak-btn">
    <button id="btn_Update" name="btn_Update" value="<?PHP echo モードコード['更新']; ?>">更新</button>
    <button id="btn_back" name="btn_back" value="<?php echo モードコード['戻る']; ?>">戻る</button>
  </div>
  

  <hr style="width: 80%;" ><?PHP /* 店名　トップ画像 */ ?>

    <div class="item">
      <label class="itemLabel-primary" for="seishiki-name">正式名称</label>
      <?php
        $val = "";
        if($エラーコード != ERR種類['エラー無し']){
          // エラーがあった場合は入力内容を引き継ぐ
          $val = $_POST['正式名称'];
        }else{
          $val = $ログインショップ情報['正式名称'];
        }
        echo '<input type="text" class="text" id="seishiki-name" name="正式名称" value="'.$val.'">';
      ?>
      <br><label class="err">
      <?php
      if (array_key_exists('正式名称', $エラーチェック結果)){
        echo $エラーチェック結果['正式名称'];
      }
      ?>
      </label>
    </div>

    <div class="item">
      
      <label class="itemLabel-primary" for="ryaku-name">略称</label>
        <?php
          $val = "";
          if($エラーコード != ERR種類['エラー無し']){
            // エラーがあった場合は入力内容を引き継ぐ
            $val = $_POST['略称'];
          }else{
            $val = $ログインショップ情報['略称'];
          }
          echo '<input type="text" class="text" id="ryaku-name" name="略称" value="'.$val.'">';
        ?>
        
        <br><label class="err">
        <?php
        if (array_key_exists('略称', $エラーチェック結果)){
          echo $エラーチェック結果['略称'];
        }
        ?></label>
      
    </div>

    <div class="item">
      <label class="itemLabel-primary" for="画像ファイル">トップ/サムネ画像</label>
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
      
        <label class="itemLabel-nomal" for="メッセージ">メッセージ</label>
        
          <?php
            $val = ''; //初期値
            if($エラーコード != ERR種類['エラー無し']){
              $val = $_POST['メッセージ'];
            }else{
              $val = $ログインショップ情報['メッセージ'];
            }
          ?>
          <textarea class="text" id="メッセージ" name="メッセージ" rows="2" maxlength="<?php echo 字数上限['メッセージ']; ?>" placeholder="ここに記入してください" 
          onchange="MsgChange(this, document.getElementById('previewText'), document.getElementById('preview-text'));"><?php echo rtrim($val); ?></textarea>
        
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
        <img id="preview-img" src="<?php echo ショップRoute . $_SESSION['shop_id']; ?>/main/img/top.jpg?<?php echo date("YmdHis");?>" />
        <div class="msg" id="previewText" style="display: none;">
          <p id="preview-text"></p>
        </div>
      </div>
    </div>
  <?php /*  */ ?>

  <hr style="width: 80%;"  ><?PHP /* 住所 */ ?>

    <div class="item">
      
        <label class="itemLabel-nomal" for="adr-zip">郵便番号</label>
        
        <?php
          $val = "";
          if($エラーコード != ERR種類['エラー無し']){
            // エラーがあった場合は入力内容を引き継ぐ
            $val = $_POST['郵便番号'];
          }else{
            $val = $ログインショップ情報['郵便番号'];
          }
          echo '<input type="text" class="text" id="adr-zip" name="郵便番号" value="'.$val.'">';
        ?>
        
        <br><label class="err">
        <?php
        if (array_key_exists('郵便番号', $エラーチェック結果)){
          echo $エラーチェック結果['郵便番号'];
        }
        ?></label>
      
    </div>

    <div class="item">
      
        <label class="itemLabel-primary" for="todouhuken">都道府県</label>
        
        <?php
          $val = "";
          if($エラーコード != ERR種類['エラー無し']){
            // エラーがあった場合は入力内容を引き継ぐ
            $val = $_POST['都道府県'];
          }else{
            $val = $ログインショップ情報['都道府県'];
          }
          echo '<input type="text" class="text" id="todouhuken" name="都道府県" value="'.$val.'">';
        ?>
        
        <br><label class="err">
        <?php
        if (array_key_exists('都道府県', $エラーチェック結果)){
          echo $エラーチェック結果['都道府県'];
        }
        ?></label>
      
    </div>

    <div class="item">
      
        <label class="itemLabel-primary" for="sikutyouson">市区町村</label>
        
        <?php
          $val = "";
          if($エラーコード != ERR種類['エラー無し']){
            // エラーがあった場合は入力内容を引き継ぐ
            $val = $_POST['市区町村'];
          }else{
            $val = $ログインショップ情報['市区町村'];
          }
          echo '<input type="text" class="text" id="sikutyouson" name="市区町村" value="'.$val.'">';
        ?>
        
        <br><label class="err">
        <?php
        if (array_key_exists('市区町村', $エラーチェック結果)){
          echo $エラーチェック結果['市区町村'];
        }
        ?></label>
      
    </div>

    <div class="item">
      
        <label class="itemLabel-nomal" for="tyoumeibanti">町名番地</label>
        
        <?php
          $val = "";
          if($エラーコード != ERR種類['エラー無し']){
            // エラーがあった場合は入力内容を引き継ぐ
            $val = $_POST['町名番地'];
          }else{
            $val = $ログインショップ情報['町名番地'];
          }
          echo '<input type="text" class="text" id="tyoumeibanti" name="町名番地" value="'.$val.'">';
        ?>
        
        <br><label class="err">
        <?php
        if (array_key_exists('町名番地', $エラーチェック結果)){
          echo $エラーチェック結果['町名番地'];
        }
        ?></label>
      
    </div>

    <div class="item">
      
        <label class="itemLabel-nomal" for="tatemononado">建物等</label>
        
        <?php
          $val = "";
          if($エラーコード != ERR種類['エラー無し']){
            // エラーがあった場合は入力内容を引き継ぐ
            $val = $_POST['建物等'];
          }else{
            $val = $ログインショップ情報['建物等'];
          }
          echo '<input type="text" class="text" id="tatemononado" name="建物等" value="'.$val.'">';
        ?>
        
        <br><label class="err">
        <?php
        if (array_key_exists('建物等', $エラーチェック結果)){
          echo $エラーチェック結果['建物等'];
        }
        ?></label>
      
    </div>
    
    <?PHP /* 連絡先 */ ?>

    <div class="item">
      
        <label class="itemLabel-nomal" for="tel_no">電話番号</label>
        
        <?php
          $val = "";
          if($エラーコード != ERR種類['エラー無し']){
            // エラーがあった場合は入力内容を引き継ぐ
            $val = $_POST['電話番号'];
          }else{
            $val = $ログインショップ情報['電話番号'];
          }
          echo '<input type="tel" class="text" id="tel_no" name="電話番号" value="'.$val.'">';
        ?>
        
        <br><label class="err">
        <?php
        if (array_key_exists('電話番号', $エラーチェック結果)){
          echo $エラーチェック結果['電話番号'];
        }
        ?></label>
      
    </div>

    <div class="item">
      
        <label class="itemLabel-nomal" for="fax_no">FAX番号</label>
        
        <?php
          $val = "";
          if($エラーコード != ERR種類['エラー無し']){
            // エラーがあった場合は入力内容を引き継ぐ
            $val = $_POST['FAX番号'];
          }else{
            $val = $ログインショップ情報['FAX番号'];
          }
          echo '<input type="tel" class="text" id="fax_no" name="FAX番号" value="'.$val.'">';
        ?>
        
        <br><label class="err">
        <?php
        if (array_key_exists('FAX番号', $エラーチェック結果)){
          echo $エラーチェック結果['FAX番号'];
        }
        ?></label>
      
    </div>

    <div class="item">
      
        <label class="itemLabel-nomal" for="mail-adr">メールアドレス</label>
        
        <?php
          $val = "";
          if($エラーコード != ERR種類['エラー無し']){
            // エラーがあった場合は入力内容を引き継ぐ
            $val = $_POST['メールアドレス'];
          }else{
            $val = $ログインショップ情報['メールアドレス'];
          }
          echo '<input type="mail" class="text" id="mail-adr" name="メールアドレス" value="'.$val.'">';
        ?>
        
        <br><label class="err">
        <?php
        if (array_key_exists('メールアドレス', $エラーチェック結果)){
          echo $エラーチェック結果['メールアドレス'];
        }
        ?></label>
      
    </div>
  <?php /*  */ ?>

  <hr style="width: 80%;" ><?PHP /* 検索条件 */ ?>

    <div class="item">
      <label class="itemLabel-primary" for="text">ショップ種類</label>
        <select name="ショップ種類コード">
          <?php
            // 取得レコードを変数へ
            $初期値_ショップ種類コード="";
            $select_val = "";
            if($エラーコード != ERR種類['エラー無し']){
              // エラーがあった場合は入力内容を引き継ぐ
              $select_val = $_POST['ショップ種類コード'];
            }else{
              $select_val = $ログインショップ情報['ショップ種類コード'];
            }

            for ($idx = 0; $idx <= count($ary_ショップ種類) -1; $idx++){

              $row = $ary_ショップ種類[$idx];
              $selected = "";
              if($select_val == trim($row['ID']) || ($初期値_ショップ種類コード == "" && $idx == count($ary_ショップ種類) -1)){
                $selected = "selected";

                $初期値_ショップ種類コード = $row['ID'];
              }
              ?>
              <option value="<?php echo $row['ID']; ?>" <?php echo $selected; ?>><?php echo $row['名称']; ?></option>
            <?php } ?>
        </select>
        <br><label class="err">
          <?php
            if (array_key_exists('ショップ種類コード', $エラーチェック結果)){
              echo $エラーチェック結果['ショップ種類コード'];
            }
          ?>
        </label>
    </div>

    <div class="item">
      <label class="itemLabel-nomal" for="タグ">タグ</label>
      <?php
        $val = "";
        if($エラーコード != ERR種類['エラー無し']){
          // エラーがあった場合は入力内容を引き継ぐ
          $val = $_POST['タグ'];
        }else{
          $val = $ログインショップ情報['タグ'];
        }
      ?>
      <input type="text" class="text" name="タグ" value="<?php echo trim($val); ?>">
      <br><label class="explain">スペーズ区切りで複数ワード設定出来ます。</label>
      <br><label class="err">
      <?php
      if (array_key_exists('タグ', $エラーチェック結果)){
        echo $エラーチェック結果['タグ'];
      }
      ?></label>
    </div>

    <div class="item">
      
        <label class="itemLabel-nomal" for="kituen-ka">その他</label>
        <div id="options">
          <div class="option">
            <?php

              if($エラーコード != ERR種類['エラー無し']){
                // エラーがあった場合は入力内容を引き継ぐ
                $val = $_POST['喫煙可'];
              }else{
                $val = $ログインショップ情報['喫煙可'];
              }

              $喫煙可_初期値 = '';
              if ($val == true ){
                $喫煙可_初期値 = 'checked="checked"';
              }
              echo '<input type="checkbox" class="checkbox" id="kituen-ka" name="喫煙可" value="1" '.$喫煙可_初期値.'>';
            ?>
            <label class="check-for-lbl" for="kituen-ka" >喫煙可</label>
          </div>
          <div class="option">
            <?php
              if($エラーコード != ERR種類['エラー無し']){
                // エラーがあった場合は入力内容を引き継ぐ
                $val = $_POST['駐車場有'];
              }else{
                $val = $ログインショップ情報['駐車場有'];
              }

              $駐車場有_初期値 = '';
              if ($val == true ){
                $駐車場有_初期値 = 'checked="checked"';
              }
              echo '<input type="checkbox" class="checkbox" id="tyusyazyou-ari" name="駐車場有" value="1" '.$駐車場有_初期値.'>';
            ?>
            <label class="check-for-lbl" for="tyusyazyou-ari" >駐車場有</label>
          </div>
        </div>
        <br><label class="err">
        <?php
        if (array_key_exists('喫煙可', $エラーチェック結果)){
          echo $エラーチェック結果['喫煙可'];
        }
        if (array_key_exists('駐車場有', $エラーチェック結果)){
          echo $エラーチェック結果['駐車場有'];
        }
        ?></label>
      
    </div>
  <?php /*  */ ?>

  <br>
  <div class="item" id="upd-bak-btn">
    <button id="btn_Update" name="btn_Update" value="<?PHP echo モードコード['更新']; ?>">更新</button>
    <button id="btn_back" name="btn_back" value="<?php echo モードコード['戻る']; ?>">戻る</button>
  </div>
  
</form>
</div>
<script>
  if(<?php echo $エラーコード; ?> == <?php echo ERR種類['エラー無し']; ?>){
    MsgChange(document.getElementById('メッセージ'), document.getElementById('previewText'), document.getElementById('preview-text'));
    //OnFileSelect(document.getElementById('file-img'),document.getElementById('preview-img'));
  }
</script>