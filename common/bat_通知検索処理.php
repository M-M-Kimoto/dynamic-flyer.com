<?php

define('phpフォルダ','../php/');
include(phpフォルダ.'共通処理.php');

    $Get_viewQuery = new VieSelectQuery();

    // 検索条件
    $検索条件= array();

    $検索条件['ユーザID'] = $_POST["userID"];

    if($_POST["shopKind"] != ''){
        $検索条件['ショップ種類コード'] = $_POST["shopKind"];
    }
    if($_POST["shopName"] != ''){
        $検索条件['正式名称'] = $_POST["shopName"];
    }
    if($_POST["todohuken"] != ''){
        $検索条件['都道府県'] = $_POST["todohuken"];
    }
    if($_POST["sikutyoson"] != ''){
        $検索条件['市区町村'] = $_POST["sikutyoson"];
    }
    if($_POST["tyomeibanti"] != ''){
        $検索条件['町名番地'] = $_POST["tyomeibanti"];
    }
    if($_POST["tatemono"] != ''){
        $検索条件['建物等'] = $_POST["tatemono"];
    }
    if(isset($_POST['tags']) == true){
        if($_POST["tags"] != ''){
            $検索条件['タグ'] = explode(" ", $_POST["tags"]);
        }
    }

    /*
    if($_POST['rangeCode'] == '0'){
        // 全体検索
        $sql_sel = $Get_viewQuery->ShopSearch($検索条件, '');
    }elseif($_POST['rangeCode'] == '1'){
        // 全体検索
        $sql_sel = $Get_viewQuery->UserFavShopInfo($検索条件, '');
    }else{
        exit;
    }
    */
    $sql_sel = $Get_viewQuery->NoticeList($検索条件);

    $res_sel = $cls_dbCtrl->select($sql_sel);

    echo json_encode($res_sel['rows'], JSON_UNESCAPED_UNICODE);

?>