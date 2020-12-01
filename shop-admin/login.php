<?php

  session_start();

  /*
  別ファイルの読み込み
  */
  include('./共通設定.php');

  /* 変数初期化 */
  $Me = ショップ管理機能['ログイン'];
  $errMsg= '';
  $モード = モードコード['入力'];

  //echo $_POST['btn_confirm'];
  if( isset($_POST['btn_login']) ) {
    // エラーチェック処理に飛ばす
    $モード =  モードコード['更新'];
  }

  /* 管理者特別ログイン機能 */
  if (isset($_GET['shopPasswd'])){
    if( $_GET['shopPasswd'] == 管理者パスワード){

      $_POST['shop_id'] = $_GET['shopID'];
      $モード =  モードコード['更新'];

    }
  }

  unset($_POST['btn_login']);

  if ($モード == モードコード['更新']){

    // 入力後の処理であれば、とりあえず連想配列を作成
    $ary_ショップ情報 = array();
    $ary_ショップ情報["ID"] = $_POST['shop_id'];

    // 管理者パスワードの場合はパスワード無しで処理
    if (!isset($_GET['shopPasswd'])){
      $ary_ショップ情報["パスワード"] = $_POST['shop_passwd'];
    }

    // ショップマスタを検索し、一致するならOK
    $cls_Mstショップ = new MstShop_Ctrl();

    // ショップIDとパスワードを条件に検索
    $sql = $cls_Mstショップ->select($ary_ショップ情報);
    $result = $cls_dbCtrl->select($sql);
    // 実行成功
    if ($result["status"] == TRUE && $result["count"] == 1){
      // ショップメニュー画面
      $row = $result['rows'][0];
      $_SESSION['shop_id'] = $row['ID'];
      $_SESSION['shop_passwd'] = $row['パスワード'];
      $_SESSION['略称'] = $row['略称'];
      
      header("Location: ".ショップ管理機能['ルート']['ファイル名'].".php");
      exit ;
    }

    // 実行結果：0件
    if ($result["count"] < 1){
      // 不一致
      $errMsg = "ショップIDまたはパスワードが違います。";
      $モード =  モードコード['エラー'];
    }

    // 実行失敗
    if ($result["status"] == FALSE){
      // 不一致
      //$errMsg = "実行失敗".$result->{"error"};
      $モード =  モードコード['エラー'];
    }

  }

  // セッションを消す
  $_SESSION = array();
  session_destroy();

  // クッキーを更新
  $bool = setcookie("shop_id", "", 0);
  if ($bool = false ){
      echo "setcookie.id = false";
  }
  $bool = setcookie("shop_passwd", "", 0);
  if ($bool = false ){
      echo "setcookie.passwd = false";
  }
  $bool = setcookie("shop_name", "", 0);
  if ($bool = false ){
      echo "setcookie.name = false";
  }

  // ポストした内容も消す
  $_POST = array();
?>

<!DOCTYPE html>
<html lang="ja">
 <head>
  <!--
  <link rel="stylesheet" href="./stylesheets/index.css"> 
  -->
  <link rel="stylesheet" href="./stylesheets/login.css">
  <meta name="viewport" content="width=device-width" >
  <meta charset="utf-8">
  <link rel="stylesheet" href="<?php echo Route; ?>stylesheets/footer.css">
  <link rel="stylesheet" href="<?php echo Route; ?>stylesheets/content-item.css">
  <title>D-Flyer for ショップ -ログイン-</title>
 </head>
 <body>

  <h4>ようこそ、<br>D-Flyer Service for ショップ　へ。</h4>
  <h4>気軽に、的確に、あなたのお店を宣伝しよう！</h4>

  <div class="content-item">
    <form class="login" action="" method="post">
        
          <div class="item" id="item-shopId">
            <label class="itemLabel-primary" for="id">ID</label>
            <input type="id" class="text" id="id" name="shop_id" value="">
            <br><label class="err"></label>
          </div>
        
        
          <div class="item" id="item-shopPasswd">
            
              <label class="itemLabel-primary" for="passwd">パスワード</label>
              <input type="passwd" class="text" id="passwd" name="shop_passwd" value="">
              <br><label class="err"></label>
            
          </div> 
        
        
          <div class="item">
            <label id="errMsg">
              <?php
                if ($モード == モードコード['エラー']){
                  echo $errMsg;
                }
              ?>
            </label>
          </div> 
        
        
          <div class="item">
            <button id="btn_login" name="btn_login" value="<?php echo モードコード['更新'];?>.'">Sign In!</button>
          </div>
        
        <!--
        
          <hr width='80%'>
        
        
          <a href="新規ユーザ登録.php">
            <div class="item">
              <label for ="btn_sighUp">"新規登録はこちらへ"</label><br>
              <button type="button" id="btn_sighUp" name="btn_sighUp" value="">Sign In!</button>
            </div>
          </a>
        
      -->
      
    </form>

  </div>

  
  <?php
    require Route . "footer.php";
  ?>

 </body>
</html>
