<?php

    define('phpフォルダ','../php/');
    include(phpフォルダ.'共通処理.php');

    $Get_viewQuery = new VieSelectQuery();
    $rtn_rows=array();

    // 何を取るかを指定する区分コードが空の場合は処理を終了する
    if(isset($_POST["kbn"]) == false){
        return;
    }

    // 検索条件
    $検索条件= array();
    if($_POST["shopID"] != ''){
        $検索条件['ショップID'] = $_POST["shopID"];
    }

    // POSTされた区分によって処理を分岐。select文を返してもらう
    $sql_sel = '';
    switch($_POST["kbn"]){
        case '1':
            // mstショップ情報（＋　ショップステータス）（＋　基本情報詳細）（＋　お気に入り）
            $sql_sel = GetSQL_kbn01($検索条件);
            break;

        case '2':
            // 通知情報
            $sql_sel = GetSQL_kbn02($検索条件);
            //echo $sql_sel;
            break;
        
        case '3':
            // リンク
            $sql_sel = GetSQL_kbn03($検索条件);
            break;

        case '4':
            // SNS
            $sql_sel = GetSQL_kbn04($検索条件);
            break;
    
        default:

            return ;
    }

    $res_sel = $cls_dbCtrl->select($sql_sel);
    $rtn_rows = array();
    if($res_sel['status'] = false){

    }elseif($res_sel['count'] < 1){

    }else{
        $rtn_rows = $res_sel['rows'];
    }
    echo json_encode($rtn_rows, JSON_UNESCAPED_UNICODE);
    return;

    /*
    * おすすめ紹介情報
    */
    function GetSQL_kbn01($PI_検索条件){

        $sql = "";
        $sql = $sql . "select ";
        $sql = $sql . "    `ショップID`, ";
        $sql = $sql . "    `商品名`,";
        $sql = $sql . "    `キャッチコピー`,";
        $sql = $sql . "    `詳細`,";
        $sql = $sql . "    date_format(`販売開始日時`, '%Y-%m-%d') as `販売開始日`,";
        $sql = $sql . "    date_format(`販売終了日時`, '%Y-%m-%d') as `販売終了日`,";
        $sql = $sql . "    `値段`,";
        $sql = $sql . "    `数量制限区分`,";
        $sql = $sql . "    `販売数量`,";
        $sql = $sql . "    `残数`,";
        $sql = $sql . "    `リンクURL`,";
        $sql = $sql . "    `基本ページURL`,";
        $sql = $sql . "    `ショップ種類コード`,";
        $sql = $sql . "    `ショップ種類名称`,";
        $sql = $sql . "    `ショップ種類色`,";
        $sql = $sql . "    `更新日時`";
        $sql = $sql . " from `vie_有効おすすめ設定` ";
        $sql = $sql . " where 1 = 1 ";

        if (array_key_exists('ショップID', $PI_検索条件)) {
            $sql = $sql. ' AND `ショップID` = "'.$PI_検索条件['ショップID'].'"';
        }

        $sql = $sql . " order by `掲載開始日時` desc , `掲載終了日時` asc, `販売開始日時` desc, `販売終了日時` asc";

        return $sql;
    }

    /*
    * 基本情報取得処理
    */
    function GetSQL_kbn02($PI_検索条件){

        $sql_sel = '';
        $sql_sel = $sql_sel . 'select';
        $sql_sel = $sql_sel . '     `shop`.`ID` as `ショップID`'; 
        $sql_sel = $sql_sel . '    ,`shop`.`正式名称`';
        $sql_sel = $sql_sel . '    ,`shop`.`略称`';
        $sql_sel = $sql_sel . '    ,`shop`.`電話番号`';
        $sql_sel = $sql_sel . '    ,`shop`.`メールアドレス`';
        $sql_sel = $sql_sel . '    ,`shop`.`郵便番号`';
        $sql_sel = $sql_sel . '    ,concat(`shop`.`都道府県`, `shop`.`市区町村`, `shop`.`町名番地`) as `住所`';
        $sql_sel = $sql_sel . '    ,`shop`.`市区町村`';
        $sql_sel = $sql_sel . '    ,`shop`.`町名番地`';
        $sql_sel = $sql_sel . '    ,`shop`.`建物等`';
        $sql_sel = $sql_sel . ' from `vie_有効ショップ` as `shop`';
        $sql_sel = $sql_sel . ' where 1 = 1';
        $sql_sel = $sql_sel . ' and `shop`.`ID` = "'.$PI_検索条件['ショップID'].'"';

        return $sql_sel;
    }

    /*
    * リンク
    */
    function GetSQL_kbn03($PI_検索条件){

        $cls_mst表示ページ = new MstShopViewerPage_Ctrl();
        $sql_sel = $cls_mst表示ページ->select($PI_検索条件);

        return $sql_sel;
    }

    /*
    * SNS
    */
    function GetSQL_kbn04($PI_検索条件){

        $cls_mstSNS = new MstShopSNS_Ctrl();
        $sql_sel = $cls_mstSNS->select_Join_MstSNS($PI_検索条件);

        return $sql_sel;
    }

?>