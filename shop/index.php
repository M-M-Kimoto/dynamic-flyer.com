<?php

    // 使用する定数や外部ファイルについて
    define('エラーページ', 'https://' . $_SERVER["HTTP_HOST"] . "/");
    define('Route', '../');
    include(Route .'php/vie_Select.php');
    include(Route .'php/DB_Ctrl.php');
    include(Route .'php/mysqlCtrl.php');

    // 使用する変数
    $ショップID = $_GET['shopID']; //GetでショップIDを取得
    $cls_dbCtrl = new DB_Ctrl();
    $cls_sql = new VieSelectQuery(); 

    $sql_sel = $cls_sql->NowPage($ショップID);
    $res_sel = $cls_dbCtrl->select($sql_sel);

    // エラー時は設定しているページへ
    if ($res_sel["status"] == false){
        header("Location: " . エラーページ);
        exit ;
    }elseif ($res_sel["count"] < 1){
        header("Location: " . エラーページ);
        exit ;
    }

    // 取得レコードを変数へ
    $URL = $res_sel["rows"][0]['実行時表示URL'];

    if(!$URL){
        header("Location: " . $res_sel["rows"][0]['基本ページ']);
        exit ;
    }else{
        header("Location: " . $URL);
        exit ;
    }

?>