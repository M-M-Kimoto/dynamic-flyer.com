<?php

    include('共通設定.php');

    if($_POST['limit'] < 1){
        $_POST['limit'] = 100;
    }

    $sql_sel = "";
    $sql_sel = $sql_sel . "select ";
    $sql_sel = $sql_sel . "     date_format(`通知開始日時`, '%m月%d日') AS `通知開始日`";
    $sql_sel = $sql_sel . "    ,`通知内容`";
    $sql_sel = $sql_sel . "    ,`リンクURL`";
    $sql_sel = $sql_sel . " from `mst_運営-ショップ通知`";
    $sql_sel = $sql_sel . " where 1 = 1";
    $sql_sel = $sql_sel . "   and `通知開始日時` < Now()";
    $sql_sel = $sql_sel . " order by `通知開始日時` desc";
    $sql_sel = $sql_sel . " limit " . $_POST["limit"];
    $sql_sel = $sql_sel . ";";

    $res_sel = $cls_dbCtrl->select($sql_sel);

    echo json_encode($res_sel['rows'], JSON_UNESCAPED_UNICODE);

?>
