
<?php 

  function update(){

    $エラーコード = ERR種類['エラー無し'];
    $エラーチェック結果 = array();

    $cls_MST支払方法 = new MstShopPayMent_Ctrl;
    $更新内容 = array();

    /* 配列へ */
      // フラグ項目はチェック意外全て０とする
      $val_name = "Visa";
      if (isset($_POST[$val_name ]) == false or $_POST[$val_name ] != '1'){
        $_POST[$val_name ] = '0';
      }

      $val_name = "JCB";
      if (isset($_POST[$val_name ]) == false or $_POST[$val_name ] != '1'){
        $_POST[$val_name ] = '0';
      }

      $val_name = "Mastercard";
      if (isset($_POST[$val_name ]) == false or $_POST[$val_name ] != '1'){
        $_POST[$val_name ] = '0';
      }

      $val_name = "American_Express";
      if (isset($_POST[$val_name ]) == false or $_POST[$val_name ] != '1'){
        $_POST[$val_name ] = '0';
      }

      $val_name = "Diners_Club";
      if (isset($_POST[$val_name ]) == false or $_POST[$val_name ] != '1'){
        $_POST[$val_name ] = '0';
      }

      $val_name = "LINE_Pay";
      if (isset($_POST[$val_name ]) == false or $_POST[$val_name ] != '1'){
        $_POST[$val_name ] = '0';
      }

      $val_name = "PayPay";
      if (isset($_POST[$val_name ]) == false or $_POST[$val_name ] != '1'){
        $_POST[$val_name ] = '0';
      }

      $val_name = "楽天ペイ";
      if (isset($_POST[$val_name ]) == false or $_POST[$val_name ] != '1'){
        $_POST[$val_name ] = '0';
      }

      $val_name = "d払い";
      if (isset($_POST[$val_name ]) == false or $_POST[$val_name ] != '1'){
        $_POST[$val_name ] = '0';
      }

      $val_name = "au_PAY";
      if (isset($_POST[$val_name ]) == false or $_POST[$val_name ] != '1'){
        $_POST[$val_name ] = '0';
      }

      $val_name = "メルペイ";
      if (isset($_POST[$val_name ]) == false or $_POST[$val_name ] != '1'){
        $_POST[$val_name ] = '0';
      }

      // 入力後の処理であれば、とりあえず連想配列を作成
      $更新内容 = array(
        'ショップID'=>$_SESSION['shop_id'],
        'Visa'=>$_POST['Visa'],
        'JCB'=>$_POST['JCB'],
        'Mastercard'=>$_POST['Mastercard'],
        'American_Express'=>$_POST['American_Express'],
        'Diners_Club'=>$_POST['Diners_Club'],
        'LINE_Pay'=>$_POST['LINE_Pay'],
        'PayPay'=>$_POST['PayPay'],
        '楽天ペイ'=>$_POST['楽天ペイ'],
        'd払い'=>$_POST['d払い'],
        'au_PAY'=>$_POST['au_PAY'],
        'メルペイ'=>$_POST['メルペイ']
      );
      
    /*  */

    /* エラーチェック */
      // エラーチェック
      $res_check = $cls_MST支払方法->check($更新内容);
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
        $return_dateUpdIns = data_Update_or_Insert($cls_MST支払方法, $更新内容);
        
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
  }

  /* 最後に必ずマスタ最新値を取得し直す */
    $cls_Mst支払方法 = new MstShopPayMent_Ctrl();
    $支払方法 = array(); 
    $検索条件= array(
      'ショップID'=>$index['ショップID']
    );

    // ショップIDとパスワードを条件に検索
    $sql_select = $cls_Mst支払方法->select($検索条件);
    $res_select = $cls_dbCtrl->select($sql_select);
    if ($res_select["status"] == false){
      $エラーコード = ERR種類['マスタ情報取得失敗'];

    
    }elseif ($res_select["count"] < 1){
      
      //キーで検索しているためありえないが、
      //これをいれないと問答無用で配列の先頭をとるというのが意味不明なため
      $エラーコード = ERR種類['マスタ情報取得失敗'];

    }else{
      // 取得レコードを変数へ
      $支払方法 = $res_select["rows"][0];
      
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

<div class="content-item">

<form class="insert" action="" method="post" enctype="multipart/form-data">
  <?php // 隠し項目に自分のメニュー番号を ?>
  <input type="hidden" name="menu-btn" value="<?php echo $index['Me']['NO']; ?>" />
  
  <div class="item">
    
      <label class="itemLabel-nomal" >クレジットカード</label>
      <div id="options">
        <div class="option"><?php $val_name = "American_Express" ; ?> 
          <?php
            if($エラーコード != ERR種類['エラー無し']){
              // エラーがあった場合は入力内容を引き継ぐ
              $val = $_POST[$val_name];
            }else{
              $val = $支払方法[$val_name];
            }

            $初期値 = '';
            if ($val == true ){
              $初期値 = 'checked="checked"';
            }
          ?>
          <input type="checkbox" class="checkbox" id="<?php echo $val_name; ?>" name="<?php echo $val_name; ?>" value="1" <?php echo $初期値; ?>>
          <label class="check-for-lbl" for="<?php echo $val_name; ?>" ><?php echo str_replace("_", " ", $val_name); ?></label>
        </div>
        <div class="option"><?php $val_name = "Diners_Club" ; ?> 
          <?php
            if($エラーコード != ERR種類['エラー無し']){
              // エラーがあった場合は入力内容を引き継ぐ
              $val = $_POST[$val_name];
            }else{
              $val = $支払方法[$val_name];
            }

            $初期値 = '';
            if ($val == true ){
              $初期値 = 'checked="checked"';
            }
          ?>
          <input type="checkbox" class="checkbox" id="<?php echo $val_name; ?>" name="<?php echo $val_name; ?>" value="1" <?php echo $初期値; ?>>
          <label class="check-for-lbl" for="<?php echo $val_name; ?>" ><?php echo str_replace("_", " ", $val_name); ?></label>
        </div>
        <div class="option"><?php $val_name = "JCB" ; ?> 
          <?php
            if($エラーコード != ERR種類['エラー無し']){
              // エラーがあった場合は入力内容を引き継ぐ
              $val = $_POST[$val_name];
            }else{
              $val = $支払方法[$val_name];
            }

            $初期値 = '';
            if ($val == true ){
              $初期値 = 'checked="checked"';
            }
          ?>
          <input type="checkbox" class="checkbox" id="<?php echo $val_name; ?>" name="<?php echo $val_name; ?>" value="1" <?php echo $初期値; ?>>
          <label class="check-for-lbl" for="<?php echo $val_name; ?>" ><?php echo str_replace("_", " ", $val_name); ?></label>
        </div>
        <div class="option"><?php $val_name = "Mastercard" ; ?> 
          <?php
            if($エラーコード != ERR種類['エラー無し']){
              // エラーがあった場合は入力内容を引き継ぐ
              $val = $_POST[$val_name];
            }else{
              $val = $支払方法[$val_name];
            }

            $初期値 = '';
            if ($val == true ){
              $初期値 = 'checked="checked"';
            }
          ?>
          <input type="checkbox" class="checkbox" id="<?php echo $val_name; ?>" name="<?php echo $val_name; ?>" value="1" <?php echo $初期値; ?>>
          <label class="check-for-lbl" for="<?php echo $val_name; ?>" ><?php echo str_replace("_", " ", $val_name); ?></label>
        </div>
        <div class="option"><?php $val_name = "Visa" ; ?> 
          <?php
            if($エラーコード != ERR種類['エラー無し']){
              // エラーがあった場合は入力内容を引き継ぐ
              $val = $_POST[$val_name];
            }else{
              $val = $支払方法[$val_name];
            }

            $初期値 = '';
            if ($val == true ){
              $初期値 = 'checked="checked"';
            }
          ?>
          <input type="checkbox" class="checkbox" id="<?php echo $val_name; ?>" name="<?php echo $val_name; ?>" value="1" <?php echo $初期値; ?>>
          <label class="check-for-lbl" for="<?php echo $val_name; ?>" ><?php echo str_replace("_", " ", $val_name); ?></label>
        </div>
      </div>
      <br><label class="err">
      <?php
        if (array_key_exists('American_Express', $エラーチェック結果)){
          echo $エラーチェック結果['American_Express'];
        }
        if (array_key_exists('Diners_Club', $エラーチェック結果)){
          echo $エラーチェック結果['Diners_Club'];
        }
        if (array_key_exists('JCB', $エラーチェック結果)){
          echo $エラーチェック結果['JCB'];
        }
        if (array_key_exists('Mastercard', $エラーチェック結果)){
          echo $エラーチェック結果['Mastercard'];
        }
        if (array_key_exists('Visa', $エラーチェック結果)){
          echo $エラーチェック結果['Visa'];
        }
      ?></label>
  </div>

  <div class="item">
    
      <label class="itemLabel-nomal" >QR・バーコード決済</label>
      <div id="options">
        <div class="option"><?php $val_name = "au_PAY" ; ?> 
          <?php
            if($エラーコード != ERR種類['エラー無し']){
              // エラーがあった場合は入力内容を引き継ぐ
              $val = $_POST[$val_name];
            }else{
              $val = $支払方法[$val_name];
            }

            $初期値 = '';
            if ($val == true ){
              $初期値 = 'checked="checked"';
            }
          ?>
          <input type="checkbox" class="checkbox" id="<?php echo $val_name; ?>" name="<?php echo $val_name; ?>" value="1" <?php echo $初期値; ?>>
          <label class="check-for-lbl" for="<?php echo $val_name; ?>" ><?php echo str_replace("_", " ", $val_name); ?></label>
        </div>
        <div class="option"><?php $val_name = "d払い" ; ?> 
          <?php
            if($エラーコード != ERR種類['エラー無し']){
              // エラーがあった場合は入力内容を引き継ぐ
              $val = $_POST[$val_name];
            }else{
              $val = $支払方法[$val_name];
            }

            $初期値 = '';
            if ($val == true ){
              $初期値 = 'checked="checked"';
            }
          ?>
          <input type="checkbox" class="checkbox" id="<?php echo $val_name; ?>" name="<?php echo $val_name; ?>" value="1" <?php echo $初期値; ?>>
          <label class="check-for-lbl" for="<?php echo $val_name; ?>" ><?php echo str_replace("_", " ", $val_name); ?></label>
        </div>
        <div class="option"><?php $val_name = "LINE_Pay" ; ?> 
          <?php
            if($エラーコード != ERR種類['エラー無し']){
              // エラーがあった場合は入力内容を引き継ぐ
              $val = $_POST[$val_name];
            }else{
              $val = $支払方法[$val_name];
            }

            $初期値 = '';
            if ($val == true ){
              $初期値 = 'checked="checked"';
            }
          ?>
          <input type="checkbox" class="checkbox" id="<?php echo $val_name; ?>" name="<?php echo $val_name; ?>" value="1" <?php echo $初期値; ?>>
          <label class="check-for-lbl" for="<?php echo $val_name; ?>" ><?php echo str_replace("_", " ", $val_name); ?></label>
        </div>
        <div class="option"><?php $val_name = "PayPay" ; ?> 
          <?php
            if($エラーコード != ERR種類['エラー無し']){
              // エラーがあった場合は入力内容を引き継ぐ
              $val = $_POST[$val_name];
            }else{
              $val = $支払方法[$val_name];
            }

            $初期値 = '';
            if ($val == true ){
              $初期値 = 'checked="checked"';
            }
          ?>
          <input type="checkbox" class="checkbox" id="<?php echo $val_name; ?>" name="<?php echo $val_name; ?>" value="1" <?php echo $初期値; ?>>
          <label class="check-for-lbl" for="<?php echo $val_name; ?>" ><?php echo str_replace("_", " ", $val_name); ?></label>
        </div>
        <div class="option"><?php $val_name = "メルペイ" ; ?> 
          <?php
            if($エラーコード != ERR種類['エラー無し']){
              // エラーがあった場合は入力内容を引き継ぐ
              $val = $_POST[$val_name];
            }else{
              $val = $支払方法[$val_name];
            }

            $初期値 = '';
            if ($val == true ){
              $初期値 = 'checked="checked"';
            }
          ?>
          <input type="checkbox" class="checkbox" id="<?php echo $val_name; ?>" name="<?php echo $val_name; ?>" value="1" <?php echo $初期値; ?>>
          <label class="check-for-lbl" for="<?php echo $val_name; ?>" ><?php echo str_replace("_", " ", $val_name); ?></label>
        </div>
        <div class="option"><?php $val_name = "楽天ペイ" ; ?> 
          <?php
            if($エラーコード != ERR種類['エラー無し']){
              // エラーがあった場合は入力内容を引き継ぐ
              $val = $_POST[$val_name];
            }else{
              $val = $支払方法[$val_name];
            }

            $初期値 = '';
            if ($val == true ){
              $初期値 = 'checked="checked"';
            }
          ?>
          <input type="checkbox" class="checkbox" id="<?php echo $val_name; ?>" name="<?php echo $val_name; ?>" value="1" <?php echo $初期値; ?>>
          <label class="check-for-lbl" for="<?php echo $val_name; ?>" ><?php echo str_replace("_", " ", $val_name); ?></label>
        </div>
      </div>
      <br><label class="err">
      <?php
        if (array_key_exists('au_PAY', $エラーチェック結果)){
          echo $エラーチェック結果['au_PAY'];
        }
        if (array_key_exists('d払い', $エラーチェック結果)){
          echo $エラーチェック結果['d払い'];
        }
        if (array_key_exists('PayPay', $エラーチェック結果)){
          echo $エラーチェック結果['PayPay'];
        }
        if (array_key_exists('メルペイ', $エラーチェック結果)){
          echo $エラーチェック結果['メルペイ'];
        }
        if (array_key_exists('楽天ペイ', $エラーチェック結果)){
          echo $エラーチェック結果['楽天ペイ'];
        }
      ?></label>
  </div>

  <br>
  <div class="item" id="upd-bak-btn">
    <button id="btn_Update" name="btn_Update" value="<?php echo モードコード['更新']; ?>">更新</button>
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