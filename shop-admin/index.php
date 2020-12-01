<?php
  session_start();

  clearstatcache(); 

  // 共通処理の読み込み
  include('./共通設定.php');

  $index = array('Me'=>ショップ管理機能['メニュー'],
                '表示メニュー番号'=>ショップ管理機能['メニュー']['NO'],
                'ショップID'=>'',
                'パスワード'=>'');

  // セッション・クッキーを確認する
  if (isset($_SESSION["shop_id"]) == True && $_SESSION["shop_id"] != "") {
    $index['ショップID'] = $_SESSION["shop_id"];
    $index['パスワード'] = $_SESSION["shop_passwd"];
    $index['略称'] = $_SESSION["略称"];

  }elseif (isset($_COOKIE["shop_id"]) == True && $_COOKIE["shop_id"] != "") {
    $index['ショップID'] = $_COOKIE["shop_id"];
    $index['パスワード'] = $_COOKIE["shop_passwd"];
    $index['略称'] = $_COOKIE["shop_name"];

  }else{
    // セッションもクッキーもない場合はログイン画面へ
    header("Location: ".ショップ管理機能['ログイン']['ファイル名'].".php");
    exit ;

  }

  // セッションを更新
  $_SESSION["shop_id"] = $index['ショップID'];
  $_SESSION["shop_passwd"] = $index['パスワード'];
  $_SESSION["略称"] = $index['略称'];

  // クッキーを更新
  $bool = setcookie("shop_id", $index['ショップID'], time() + 60*60*24*7);
  if ($bool = false ){
    echo "setcookie.id = false";
  }
  $bool = setcookie("shop_passwd", $index['パスワード'], time() + 60*60*24*7);
  if ($bool = false ){
    echo "setcookie.passwd = false";
  }
  $bool = setcookie("shop_name", $index['略称'], time() + 60*60*24*7);
  if ($bool = false ){
    echo "setcookie.passwd = false";
  }

?>

<?php

  /* メニューボタンクリック時の処理 */
  if( isset($_POST['menu-btn']) == true ) {
    // 選択したメニュー番号を入れる
    $index['表示メニュー番号'] =  $_POST['menu-btn'];
  }elseif(isset($_GET['MenuNo'])== true){
    // URLにメニュー番号の指定があればそれを
    $index['表示メニュー番号'] =  $_GET['MenuNo'];
  }
  // $_POST['menu-btn'] = ショップ管理機能['メニュー']['NO'];
  switch ($index['表示メニュー番号']){
    case ショップ管理機能['通知情報']['NO']:
      $index['Me'] = ショップ管理機能['通知情報'];
      break;
    case ショップ管理機能['不定休日設定']['NO']:
      $index['Me'] = ショップ管理機能['不定休日設定'];
      break;
    case ショップ管理機能['表示ページ設定']['NO']:
      $index['Me'] = ショップ管理機能['表示ページ設定'];
      break;
    case ショップ管理機能['チケット購入']['NO']:
      $index['Me'] = ショップ管理機能['チケット購入'];
      break;
    case ショップ管理機能['基本情報']['NO']:
      $index['Me'] = ショップ管理機能['基本情報'];
      break;
    case ショップ管理機能['通常営業時間']['NO']:
      $index['Me'] = ショップ管理機能['通常営業時間'];
      break;
    case ショップ管理機能['リンク登録']['NO']:
      $index['Me'] = ショップ管理機能['リンク登録'];
      break;
    case ショップ管理機能['通知履歴']['NO']:
      $index['Me'] = ショップ管理機能['通知履歴'];
      break;
    case ショップ管理機能['チケット購入履歴']['NO']:
      $index['Me'] = ショップ管理機能['チケット購入履歴'];
      break;
    case ショップ管理機能['データ分析']['NO']:
      $index['Me'] = ショップ管理機能['データ分析'];
      break;
    case ショップ管理機能['不定営日設定']['NO']:
      $index['Me'] = ショップ管理機能['不定営日設定'];
      break;
    case ショップ管理機能['問合せ']['NO']:
      $index['Me'] = ショップ管理機能['問合せ'];
    case ショップ管理機能['SNS設定']['NO']:
      $index['Me'] = ショップ管理機能['SNS設定'];
      break;
    case ショップ管理機能['基本情報画像設定']['NO']:
      $index['Me'] = ショップ管理機能['基本情報画像設定'];
      break;
    case ショップ管理機能['支払方法']['NO']:
      $index['Me'] = ショップ管理機能['支払方法'];
      break;
    case ショップ管理機能['おすすめ紹介']['NO']:
      $index['Me'] = ショップ管理機能['おすすめ紹介'];
      break;
    case ショップ管理機能['ログイン']['NO']:
      header("Location: ".ショップ管理機能['ログイン']['ファイル名'].".php");
      exit ;
      break;
    default:
      // 未登録 またはメニュー画面
      $index['Me'] = ショップ管理機能['メニュー'];
      break;
  }
?>

<?php

  /*
  ショップ情報取得処理
  */
  $cls_dbCtrl = new DB_Ctrl;
  $エラーコード = ERR種類['エラー無し'];
  $略称 = '';

  // 入力後の処理であれば、とりあえず連想配列を作成
  $ary_ショップ情報= array(
    'ID'=>$index['ショップID'],
    'パスワード'=>$index['パスワード']
  );

  // ショップマスタを検索し、一致するならOK
  $cls_Mstショップ = new MstShop_Ctrl();

  // ショップIDとパスワードを条件に検索
  $sql_select = $cls_Mstショップ->select($ary_ショップ情報);
  $res = $cls_dbCtrl->select($sql_select);

  // 実行失敗
  if ($res["status"] == false){
    $エラーコード = ERR種類['マスタ情報取得失敗'];

  }elseif ($res["count"] < 1){
    /*
      削除した場合など
    */
    $エラーコード = ERR種類['マスタ情報取得失敗'];

  }else{

    // jsonで返してくるのでdecodeして欲しいレコードのみを取得
    $result_rows = array();
    $result_rows = $res["rows"];
    $略称 = $result_rows[0]["略称"];

  }

?>

<!DOCTYPE html>
<html>
    <head>
    <meta charset="UTF-8">
    <title>D-Flyer for ショップ -<?php echo $index['Me']['ファイル名'] ?>-</title>

    <link rel="stylesheet" href="<?php echo Route; ?>stylesheets/body.css">
    <link rel="stylesheet" href="<?php echo Route; ?>stylesheets/footer.css">
    <link rel="stylesheet" href="<?php echo Route; ?>stylesheets/content-item.css">
    <link rel="stylesheet" href="./stylesheets/header.css"> 
    <link rel="stylesheet" href="./stylesheets/index.css"> 

    <script src="https://code.jquery.com/jquery-1.11.1.min.js">
    </script>
    </head>
<body>
 
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
        
<script>
  $(function(){
    $("#menubtn").click(function(){
      $("#menu").slideToggle();
    });
  });
</script>

<!-- enterキーの禁止 -->
<script>
    $(function(){
        $("input"). keydown(function(e) {
            if ((e.which && e.which === 13) || (e.keyCode && e.keyCode === 13)) {
                return false;
            } else {
                return true;
            }
        });
    });
</script>

<div class="head">
	<div id="title">
      <h6>DFサービス for ショップ</h6> 
	</div> 
  <div id="headmenu"> 
    <form id="head-btn" action="" method="post">
      <button class="headmenu-btn" name="menu-btn" value="<?php echo ショップ管理機能['ログイン']['NO']; ?>">ログアウト</button>
      <button class="headmenu-btn" name="menu-btn" value="<?php echo ショップ管理機能['メニュー']['NO']; ?>"><?php echo ショップ管理機能['メニュー']['機能名']; ?></button> 
    </form>
  </div> 
</div> 

<label class="title-lbl" id ="page-title" >
<?php 
  if(ショップ管理機能['メニュー']['NO'] == $index['Me']['NO'] ){
    //メニュー画面の場合は機能名ではなく、ログインユーザの略称を表示
    echo 'ようこそ、'.$_SESSION['略称'].'様';
  }else{
    echo $index['Me']['機能名'] ;
  }
?> 
</label>
<hr>

<?php
  // 表示する機能画面
  require $index['Me']['ファイル名'].'.php';

  require Route . "footer.php";

    
?>

</body>