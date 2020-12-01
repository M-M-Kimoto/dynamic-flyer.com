<?php

    function recomSearch($検索条件){

        $sql = "";
        $sql = $sql . "select ";
        $sql = $sql . "    `ショップID`, ";
        $sql = $sql . "    `商品名`,";
        $sql = $sql . "    `キャッチコピー`,";
        $sql = $sql . "    `リンクURL`,";
        $sql = $sql . "    `基本ページURL`,";
        $sql = $sql . "    `ショップ種類コード`,";
        $sql = $sql . "    `ショップ種類名称`,";
        $sql = $sql . "    `ショップ種類色`,";
        $sql = $sql . "    `更新日時`";
        $sql = $sql . " from `vie_有効おすすめ設定` ";
        $sql = $sql . " where 1 = 1 ";

        if (array_key_exists('ショップID', $検索条件)) {
            $sql = $sql. ' AND `ショップID` = "'.$検索条件['ショップID'].'"';
        }
        if (array_key_exists('ショップ種類コード', $検索条件)) {
            $sql = $sql. ' AND `ショップ種類コード` = "'.$検索条件['ショップ種類コード'].'"';
        }
        if (array_key_exists('タグ', $検索条件)) {
            foreach($検索条件['タグ'] as $val){
                $sql = $sql. ' AND `タグ` like "% '.trim($val).' %"';
            }
        }

        $sql = $sql . " order by `掲載開始日時` desc , `掲載終了日時` asc, `販売開始日時` desc, `販売終了日時` asc";

        return $sql;
    }

    define('phpフォルダ','../php/');
    include(phpフォルダ.'共通処理.php');

    $Get_viewQuery = new VieSelectQuery();

    // 検索条件
    $検索条件= array();

    if(isset($_POST['shopName']) == true){
        if($_POST["shopName"] != ''){
            $検索条件['ショップID'] = $_POST["shopName"];
        }
    }
    if(isset($_POST['shopKind']) == true){
        if($_POST["shopKind"] != ''){
            $検索条件['ショップ種類コード'] = $_POST["shopKind"];
        }
    }
    if(isset($_POST['tags']) == true){
        if($_POST["tags"] != ''){
            $検索条件['タグ'] = explode(" ", $_POST["tags"]);
        }
    }

    // 全体検索
    $sql_sel = recomSearch($検索条件);
    
    $res_sel = $cls_dbCtrl->select($sql_sel);

    echo json_encode($res_sel['rows'], JSON_UNESCAPED_UNICODE);


?>