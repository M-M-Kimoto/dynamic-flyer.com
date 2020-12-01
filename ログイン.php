<?php

  header("Location: ./");
  exit ;

  session_start();

  define("モードコード", array('入力'=>0,
                               'ログイン'=>1,
                               '質問ログイン'=>2,
                               '戻る'=>9
                              )
  );
  include('./共通設定.php');

  define('救済表示試行回数', 3);


  /* 変数初期化 */
  $カウント = 0;
  $エラーメッセージ= '';
  $エラーフラグ = false;
  $モード = モードコード['入力'];
  //echo $_POST['btn_confirm'];
  if( isset($_POST['btn_login']) ) {
    // エラーチェック処理に飛ばす
    $モード =  $_POST['btn_login'];
  }if( isset($_POST['btn_QAlogin']) ) {
    
    $モード =  $_POST['btn_QAlogin'];

  }

  if($モード == モードコード['質問ログイン']){

    header("Location: ./質問ログイン.php");
    exit ;

  }elseif ($モード == モードコード['ログイン']){

    $カウント = $_POST['カウント'] + 1 ;
    $cls_Mstユーザ = new MstUser_Ctrl();

    // 入力後の処理であれば、とりあえず連想配列を作成
    $msユーザ_検索条件= array(
      'ID'=>$_POST['ID'],
      'パスワード'=>$_POST['パスワード'],
    );

    // ショップIDとパスワードを条件に検索
    $sql_sel = $cls_Mstユーザ->select($msユーザ_検索条件);
    $result = $cls_dbCtrl->select($sql_sel);
    if ($result["status"] == TRUE && $result["count"] == 1){
      // 実行成功
      $_SESSION['ID'] = $msユーザ_検索条件['ID'];
      $_SESSION['パスワード'] = $msユーザ_検索条件['パスワード'];
      $_SESSION['ニックネーム'] = $result["rows"][0]['ニックネーム'];
      header("Location: ./?page=新着情報");
      exit ;

    }elseif ($result["status"] == FALSE){
      // 実行失敗
      $エラーメッセージ = "ログイン処理に失敗しました。申し訳ありませんが、時間を開けてから再度実行願います。";
    }elseif ($result["count"] < 1){
      // 実行結果：0件
      $エラーメッセージ = "ショップIDまたはパスワードが違います。";
    }

  }

  // セッションを消す
  $_SESSION = array();
  session_destroy();

  // クッキーを更新
  $bool = setcookie("id", "", -1);
  if ($bool = false ){
      echo "setcookie.id = false";
  }
  $bool = setcookie("passwd", "", -1);
  if ($bool = false ){
      echo "setcookie.passwd = false";
  }
  $bool = setcookie("name", "", -1);
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
    <link rel="stylesheet" href="./stylesheets/body.css">
    <link rel="stylesheet" href="./stylesheets/content-item.css">
    <link rel="stylesheet" href="./stylesheets/ログイン.css">
    <link rel="stylesheet" href="./stylesheets/footer.css">
    <meta name="viewport" content="width=device-width" >
    <meta charset="utf-8">
    <title>DFサービス</title>
  </head>

  <body>

    <div id="top-text">
      <h3>ようこそ、D-Flyer Serviceへ。</h3><br>
      <h5>お店がリアルタイムで発信する情報を受け取ろう！</h5>
    </div>
    <form class="login" action="" method="POST">

      <input type="hidden" name="カウント" value="<?php echo $カウント; ?>" />

      <div class="content-item" id="items">

        <div class="item" id="item-Id">
          <label class="itemLabel-primary" for="id">ID</label>
          <input type="id" class="text" id="id" name="ID" value="" placeholder="英数字、8文字以上">
          <label class="err"></label>
        </div>

        <div class="item" id="item-Passwd">
          <label class="itemLabel-primary" for="passwd">パスワード</label>
          <input type="passwd" class="text" id="passwd" name="パスワード" value="" placeholder="英数字、8文字以上">
          <label class="err"></label>
        </div> 

        <div class="item">
          <label id="エラーメッセージ">
            <?php
              echo $エラーメッセージ;
            ?>
          </label>
        </div> 
            
        <div class="item">
          <button id="btn_login" name="btn_login" value="<?php echo モードコード['ログイン']; ?>">Sign In!</button>
          <?php if(救済表示試行回数 <= $カウント){ ?>
            <button id="btn_QAlogin" name="btn_QAlogin" value="<?php echo モードコード['質問ログイン']; ?>">質問に回答してログイン</button>
          <?php } ?>
        </div>

        <hr width='80%'>
        <div class="item" id="signUp">
          <a href="./?page=新規ユーザ登録">
            <label for ="btn_signUp">新規登録はこちらへ</label><br>
            <button type="button" id="btn_signUp" name="btn_signUp" value="">Sign Up!</button>
          </a>
        </div>

        <hr width='80%'>
        <div class="item" id="signUp">
          <a href="./?page=新着情報">
            <label for ="btn_gestLogin">少し見てみる方はこちらへ</label><br>
            <button type="button" id="btn_gestLogin" name="btn_gestLogin" value="Gest Login">Gest Sign Up</button>
          </a>
        </div>

        <hr width='80%'>
      </div>
    </form>

  <?php  
    require "./footer.php";
  ?>

  </body>
</html>
