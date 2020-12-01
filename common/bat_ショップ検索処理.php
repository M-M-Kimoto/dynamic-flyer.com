<?php

    define('phpフォルダ','../php/');
    include(phpフォルダ.'共通処理.php');

    $Get_viewQuery = new VieSelectQuery();

    // 検索条件
    $検索条件= array();

    $検索条件['ユーザID'] = $_POST["userID"];

    if(isset($_POST['shopID']) == true){
        if($_POST["shopID"] != ''){
            $検索条件['ショップID'] = $_POST["shopID"];
        }
    }
    if(isset($_POST['shopKind']) == true){
        if($_POST["shopKind"] != ''){
            $検索条件['ショップ種類コード'] = $_POST["shopKind"];
        }
    }
    if(isset($_POST['shopName']) == true){
        if($_POST["shopName"] != ''){
            $検索条件['正式名称'] = $_POST["shopName"];
        }
    }
    if(isset($_POST['todohuken']) == true){
        if($_POST["todohuken"] != ''){
            $検索条件['都道府県'] = $_POST["todohuken"];
        }
    }
    if(isset($_POST['sikutyoson']) == true){
        if($_POST["sikutyoson"] != ''){
            $検索条件['市区町村'] = $_POST["sikutyoson"];
        }
    }
    if(isset($_POST['tyomeibanti']) == true){
        if($_POST["tyomeibanti"] != ''){
            $検索条件['町名番地'] = $_POST["tyomeibanti"];
        }
    }
    if(isset($_POST['tatemono']) == true){
        if($_POST["tatemono"] != ''){
            $検索条件['建物等'] = $_POST["tatemono"];
        }
    }
    if(isset($_POST['tags']) == true){
        if($_POST["tags"] != ''){
            $検索条件['タグ'] = explode(" ", $_POST["tags"]);
        }
    }

    $検索条件['オープン中'] = false;
    if(isset($_POST['optionOpen']) == true){
        if($_POST["optionOpen"] == '1'){
            $検索条件['オープン中'] = true;
        }
    }
    
    if(isset($_POST['rangeCode']) == true){

        if($_POST['rangeCode'] == '1'){
            // お気に入り検索
            $sql_sel = $Get_viewQuery->UserFavShopInfo($検索条件, '');
        }else{
            // 全体検索
            $sql_sel = $Get_viewQuery->ShopSearch($検索条件, '');
        }

    }else{

        // 全体検索
        $sql_sel = $Get_viewQuery->ShopSearch($検索条件, '');
        
    }
    
    $res_sel = $cls_dbCtrl->select($sql_sel);

    echo json_encode($res_sel['rows'], JSON_UNESCAPED_UNICODE);

?>