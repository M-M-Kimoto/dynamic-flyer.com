<?php
    session_start();
    // echo var_dump($_SESSION)."<br>"; 

    include('共通設定.php');

    $ID = '';
    $パスワード = '';
    $ニックネーム = '';
    // セッション・クッキーを確認する
    // セッション・クッキーを確認する
    if (isset($_SESSION["ID"]) == false && isset($_COOKIE["id"]) == false) {

        // セッションもクッキーもないない場合はゲスト
        $ID = ゲストユーザ['ID'];
        $パスワード = ゲストユーザ['パスワード'];
        $ニックネーム = ゲストユーザ['ニックネーム'];

        $_SESSION["ID"] = $ID;
        $_SESSION["パスワード"] = $パスワード;
        $_SESSION["ニックネーム"] = $ニックネーム;
        if (isset($_SESSION["ニックネーム"]) == false){
            $_SESSION["ニックネーム"] = '名無し';
        }elseif($_SESSION["ニックネーム"] == ""){
            $_SESSION["ニックネーム"] = '名無し';
        }

    }else{

        if (isset($_SESSION["ID"]) == true) {
            $ID = $_SESSION["ID"];
            $パスワード = $_SESSION["パスワード"];
            $ニックネーム = $_SESSION["ニックネーム"];

        }elseif (isset($_COOKIE["id"]) == true) {
            $ID = $_COOKIE["id"];
            $パスワード = $_COOKIE["passwd"];
            $ニックネーム = $_COOKIE["name"];

        }

        //　セッションを更新
        $_SESSION["ID"] = $ID;
        $_SESSION["パスワード"] = $パスワード;
        $_SESSION["ニックネーム"] = $ニックネーム;
        if (isset($_SESSION["ニックネーム"]) == false){
            $_SESSION["ニックネーム"] = '名無し';
        }elseif($_SESSION["ニックネーム"] == ""){
            $_SESSION["ニックネーム"] = '名無し';
        }

        // クッキーを更新
        $bool = setcookie("id", $_SESSION["ID"], time() + 60*60*24*7);
        if ($bool = false ){
            echo "setcookie.id = false";
        }
        $bool = setcookie("passwd", $_SESSION["パスワード"], time() + 60*60*24*7);
        if ($bool = false ){
            echo "setcookie.passwd = false";
        }
        $bool = setcookie("name", $_SESSION["ニックネーム"], time() + 60*60*24*7);
        if ($bool = false ){
            echo "setcookie.name = false";
        }
    
    }
    if(isset($_GET['page']) == false){
        $_GET['page'] = "おすすめ一覧";
    }

?>

<!DOCTYPE html>

    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
        <title id="page-title">D-Flyer サービス -<?php echo $_GET['page']; ?>-</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <link rel="stylesheet" href="./stylesheets/body.css">
        <link rel="stylesheet" href="./stylesheets/content-item.css">
        <link rel="stylesheet" href="./stylesheets/content-item-search.css">
        <link rel="stylesheet" href="./stylesheets/content-dropdownList.css">
        <link rel="stylesheet" href="./stylesheets/content-shop.css">
        <link rel="stylesheet" href="./stylesheets/header.css"> 
        <link rel="stylesheet" href="./stylesheets/footer.css">
        <link rel="stylesheet" href="./stylesheets/<?php echo $_GET['page']; ?>.css">

        <script type="text/javascript" src="./js/screenLockCtrl.js"></script>
    </head>

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

    <body id="body">

        <?php
            // ヘッダ
            require "./header.php"
        ?>

        <?php
            if(isset($_GET['page']) == false){
                header("Location: ログイン.php");
                exit ();
            }else{
                // ヘッダ
                require './index/' . $_GET['page'].'.php';
            }
        ?>
        <?php
            // ヘッダ
            require "./footer.php";

        ?>

    </body>
</html>