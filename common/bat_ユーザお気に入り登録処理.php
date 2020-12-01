<?php

    define('phpフォルダ','../php/');
    include(phpフォルダ.'共通処理.php');

    /* ユーザお気に入り情報の取得 */
    $cls_お気に入り = new TraUserFavorite_Ctrl();

    // 検索条件
    $登録内容= array(
        'ユーザID'=>$_POST["userID"],
        'ショップID'=>$_POST["shopID"]
    );

    global $cls_dbCtrl;
    $cls_dbCtrl->begin_tran();

    if (isset($_POST["delFlg"]) == false) {

        // 登録処理
        $res = proc_Insert($cls_お気に入り, $登録内容);

    }elseif($_POST["delFlg"] != true){

        // 登録処理
        $res = proc_Insert($cls_お気に入り, $登録内容);

    }else{

        // 削除処理
        $res = proc_Delete($cls_お気に入り, $登録内容);

    }

    if($res == true){
        $cls_dbCtrl->commit();
    }else{
        $cls_dbCtrl->rollback();
    }

    return;


    function proc_Insert($PI_clsお気に入り, $PI_登録内容){

        global $cls_dbCtrl;

        $PI_登録内容['処理区分'] = '0';
        $sql_ins = $PI_clsお気に入り->insert($PI_登録内容);
        $res_ins = $cls_dbCtrl->insert($sql_ins);
        if($res_ins['status'] == true && $res_ins['count'] == 1){

            // 未登録からの登録成功
            $res_ins['status'] = proc_InsertHistory($PI_clsお気に入り, $PI_登録内容);
        }

        return $res_ins["status"];

    }


    function proc_Delete($PI_clsお気に入り, $PI_登録内容){

        global $cls_dbCtrl;

        $PI_登録内容['処理区分'] = '1';

        $sql_del = $PI_clsお気に入り->delete($PI_登録内容);
        $res_del = $cls_dbCtrl->delete($sql_del);

        if($res_del['status'] == true && $res_del['count'] == 1){
            // 未登録からの登録成功
            $res_del['status'] = proc_InsertHistory($PI_clsお気に入り, $PI_登録内容);
        }

        return $res_del["status"];

    }

    function proc_InsertHistory($PI_clsお気に入り, $PI_登録内容){

        global $cls_dbCtrl;

        $sql_ins = $PI_clsお気に入り->insert_his($PI_登録内容);
        $res_ins = $cls_dbCtrl->insert($sql_ins);

        return $res_ins["status"];

    }

?>