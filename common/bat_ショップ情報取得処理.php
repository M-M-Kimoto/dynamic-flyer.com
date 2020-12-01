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
    if($_POST["userID"] != ''){
        $検索条件['ユーザID'] = $_POST["userID"];
    }
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
            // 通常営業時間
            $sql_sel = GetSQL_kbn03($検索条件);
            break;

        case '4':
            // 不定休
            $sql_sel = GetSQL_kbn04($検索条件);
            break;

        case '5':
            // 不定営
            $sql_sel = GetSQL_kbn05($検索条件);
            break;

        case '6':
            // リンク
            $sql_sel = GetSQL_kbn06($検索条件);
            break;
        
        case '7':
            // SNS
            $sql_sel = GetSQL_kbn07($検索条件);
            break;
    
        case '8':
            // 基本ページ詳細情報
            $sql_sel = GetSQL_kbn08($検索条件);
            break;
            
        case '9':
            // リンク
            $sql_sel = GetSQL_kbn09($検索条件);
            break;

        case '10':
            // その他
            $sql_sel = GetSQL_kbn10($検索条件);
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
    * 基本情報取得処理
    */
    function GetSQL_kbn01 ($PI_検索条件){

        $sql_sel = '';
        $sql_sel = $sql_sel . 'select';
        $sql_sel = $sql_sel . '     `shop`.`ID` as `ショップID`'; 
        $sql_sel = $sql_sel . '    ,`shop`.`正式名称`';
        $sql_sel = $sql_sel . '    ,`shop`.`略称`';
        $sql_sel = $sql_sel . '    ,`shop`.`電話番号`';
        $sql_sel = $sql_sel . '    ,`shop`.`FAX番号`';
        $sql_sel = $sql_sel . '    ,`shop`.`メールアドレス`';
        $sql_sel = $sql_sel . '    ,`shop`.`郵便番号`';
        $sql_sel = $sql_sel . '    ,`shop`.`都道府県`';
        $sql_sel = $sql_sel . '    ,`shop`.`市区町村`';
        $sql_sel = $sql_sel . '    ,`shop`.`町名番地`';
        $sql_sel = $sql_sel . '    ,`shop`.`建物等`';
        $sql_sel = $sql_sel . '    ,`shop`.`喫煙可`';
        $sql_sel = $sql_sel . '    ,`shop`.`駐車場有`';
        $sql_sel = $sql_sel . '    ,`shop`.`メッセージ`';
        $sql_sel = $sql_sel . '    ,`shop`.`ショップ種類名称`';
        $sql_sel = $sql_sel . '    ,`shop`.`アダルト`';
        $sql_sel = $sql_sel . '    ,`shop`.`お気に入り機能`';
        $sql_sel = $sql_sel . '    ,`shop`.`連絡機能`';
        $sql_sel = $sql_sel . '    ,`shop`.`予約機能`';
        $sql_sel = $sql_sel . '    ,`shop`.`採用活動機能`';
        $sql_sel = $sql_sel . '    ,`shop`.`運営管理フラグ`';
        $sql_sel = $sql_sel . '    ,case when `fav`.`ショップID` is null then 0 else 1 end as `お気に入りフラグ`';
        $sql_sel = $sql_sel . ' from `vie_有効ショップ` as `shop`';
        $sql_sel = $sql_sel . ' left join `tra_ユーザお気に入り` as `fav` on (1 = 1';
        $sql_sel = $sql_sel . ' 	and `shop`.`ID` = `fav`.`ショップID`';
        $sql_sel = $sql_sel . ' 	and `fav`.`ユーザID` = "'.$PI_検索条件['ユーザID'].'"';
        $sql_sel = $sql_sel . ' )';
        $sql_sel = $sql_sel . ' where 1 = 1';
        $sql_sel = $sql_sel . ' and `shop`.`ID` = "'.$PI_検索条件['ショップID'].'"';

        return $sql_sel;
    }

    /*
    * 通知情報取得
    */
    function GetSQL_kbn02($PI_検索条件){

        // 実行時表示設定に従うは、その時表示するページに従う
        $sql_sel = '';
        $sql_sel = $sql_sel . 'select * from (';
        $sql_sel = $sql_sel . ' select';
        $sql_sel = $sql_sel . ' 	`ショップID`,';
        $sql_sel = $sql_sel . ' 	`通知区分コード`,';
        $sql_sel = $sql_sel . " 	date_format(`開始日時`, '%m月%d日 %H:%i') as `開始日時`,";
        $sql_sel = $sql_sel . " 	date_format(`終了日時`, '%m月%d日 %H:%i') as `終了日時`,";
        $sql_sel = $sql_sel . ' 	`メッセージ`,';
        $sql_sel = $sql_sel . '     `表示ページURL`';
        $sql_sel = $sql_sel . ' from `tra_通知`';
        $sql_sel = $sql_sel . ' where 1 = 1';
        $sql_sel = $sql_sel . ' and `ショップID` = "'.$PI_検索条件['ショップID'].'"';
        $sql_sel = $sql_sel . ' and `通知区分コード` = 2';
        $sql_sel = $sql_sel . ' and Now() BETWEEN `開始日時` and `終了日時`';

        if (array_key_exists('ユーザID', $PI_検索条件)) {
        
            $sql_sel = $sql_sel . ' union all ';

            $sql_sel = $sql_sel . ' select';
            $sql_sel = $sql_sel . ' 	`tra_通知`.`ショップID`,';
            $sql_sel = $sql_sel . ' 	`tra_通知`.`通知区分コード`,';
            $sql_sel = $sql_sel . " 	date_format(`tra_通知`.`開始日時`, '%m月%d日 %H:%i') as `開始日時`,";
            $sql_sel = $sql_sel . " 	date_format(`tra_通知`.`終了日時`, '%m月%d日 %H:%i') as `終了日時`,";
            $sql_sel = $sql_sel . ' 	`tra_通知`.`メッセージ`,';
            $sql_sel = $sql_sel . '     `tra_通知`.`表示ページURL`';
            $sql_sel = $sql_sel . ' from `tra_通知`';
            $sql_sel = $sql_sel. '  left join ( select `ショップID`, `ユーザID` from `tra_ユーザお気に入り` where 1 = 1 and `ユーザID` = "'.$PI_検索条件['ユーザID'].'") as `ユーザお気に入り`';
            $sql_sel = $sql_sel . '     on (1 = 1';
            $sql_sel = $sql_sel. '      and `ユーザお気に入り`.`ショップID` = `tra_通知`.`ショップID`';
            $sql_sel = $sql_sel . ' )';
            $sql_sel = $sql_sel . ' where 1 = 1';
            $sql_sel = $sql_sel . ' and `tra_通知`.`ショップID` = "'.$PI_検索条件['ショップID'].'"';
            $sql_sel = $sql_sel . ' and `tra_通知`.`通知区分コード` = 1';
            $sql_sel = $sql_sel . ' and Now() BETWEEN `tra_通知`.`開始日時` and `tra_通知`.`終了日時`';
    
        }

        $sql_sel = $sql_sel . ' ) A';
        $sql_sel = $sql_sel . ' order by `開始日時` desc, `通知区分コード` desc';
        $sql_sel = $sql_sel . ' ;';
        return $sql_sel;
    }

    /*
    * 通常営業時間
    */
    function GetSQL_kbn03($PI_検索条件){

        $sql = '';
        $sql = $sql. 'select ';
        $sql = $sql. ' `sale`.`ショップID`,';
        $sql = $sql. ' concat(lpad(`sale`.`開始時`, 2,0), ":", lpad(`sale`.`開始分`, 2,0)) as `開始時分`,';
        $sql = $sql. ' concat(lpad(`sale`.`終了時`, 2,0), ":", lpad(`sale`.`終了分`, 2,0)) as `終了時分`,';
        $sql = $sql. ' `sale`.`日曜フラグ`,';
        $sql = $sql. ' `sale`.`月曜フラグ`,';
        $sql = $sql. ' `sale`.`火曜フラグ`,';
        $sql = $sql. ' `sale`.`水曜フラグ`,';
        $sql = $sql. ' `sale`.`木曜フラグ`,';
        $sql = $sql. ' `sale`.`金曜フラグ`,';
        $sql = $sql. ' `sale`.`土曜フラグ`,';
        $sql = $sql. ' `sale`.`更新日時`';
        $sql = $sql. ' from `mst_ショップ営業時間` as `sale`';
        $sql = $sql. ' where 1 = 1';
        if (array_key_exists('ショップID', $PI_検索条件)) {
          $sql = $sql. ' AND `sale`.`ショップID` = "'.$PI_検索条件['ショップID'].'"';
        }
        $sql = $sql. ' order by `sale`.`ショップID`, `sale`.`開始時`, `sale`.`開始分`';
        
        return $sql;
    }

    /*
    * 不定休
    */
    function GetSQL_kbn04($PI_検索条件){

        $cls_mst不定休 = new TraHoliday_Ctrl();
        $sql_sel = $cls_mst不定休->select_fromNow($PI_検索条件);

        return $sql_sel;
    }

    /*
    * 不定営
    */
    function GetSQL_kbn05($PI_検索条件){

        $cls_mst不定営 = new TraSale_Ctrl();
        $sql_sel = $cls_mst不定営->select_fromNow($PI_検索条件);

        return $sql_sel;
    }

    /*
    * リンク
    */
    function GetSQL_kbn06($PI_検索条件){

        $cls_mst表示ページ = new MstShopViewerPage_Ctrl();
        $sql_sel = $cls_mst表示ページ->select($PI_検索条件);

        return $sql_sel;
    }

    /*
    * SNS
    */
    function GetSQL_kbn07($PI_検索条件){

        $cls_mstSNS = new MstShopSNS_Ctrl();
        $sql_sel = $cls_mstSNS->select_Join_MstSNS($PI_検索条件);

        return $sql_sel;
    }

    /*
    * 基本ページ詳細情報
    */
    function GetSQL_kbn08($PI_検索条件){

        $cls_mst基本ページ情報 = new MstShopMainPage_Ctrl();
        $sql_sel = $cls_mst基本ページ情報->select($PI_検索条件);

        return $sql_sel;
    }

    /*
    * 支払方法
    */
    function GetSQL_kbn09($PI_検索条件){

        $cls_mst支払方法 = new MstShopPayMent_Ctrl();
        $sql_sel = $cls_mst支払方法->select($PI_検索条件);

        return $sql_sel;
    }

    /*
    * その他
    */
    function GetSQL_kbn10($PI_検索条件){

        $sql_sel = '';
        $sql_sel = $sql_sel . 'select';
        $sql_sel = $sql_sel . '     `shop`.`タグ`'; 
        $sql_sel = $sql_sel . '    ,`shop`.`喫煙可`';
        $sql_sel = $sql_sel . '    ,`shop`.`駐車場有`';
        $sql_sel = $sql_sel . '    ,`shop`.`アダルト`';
        $sql_sel = $sql_sel . ' from `vie_有効ショップ` as `shop`';
        $sql_sel = $sql_sel . ' where 1 = 1';
        $sql_sel = $sql_sel . ' and `shop`.`ID` = "'.$PI_検索条件['ショップID'].'"';

        return $sql_sel;
    }
    
?>