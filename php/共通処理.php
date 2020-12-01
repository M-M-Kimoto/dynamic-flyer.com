<?php

    
    // ステータスコード
    define('ERR種類', array('エラー無し'=>0,
                            'マスタ情報取得失敗'=>1, 
                            'エラーチェック'=>2, 
                            '更新失敗'=>3,
                            '削除失敗'=>4,
                            'ファイルアップロード失敗'=>5
                        )
    );
    define('ERRメッセージ', array(ERR種類['エラー無し']               =>""
                                 ,ERR種類['マスタ情報取得失敗']       =>"データ取得処理でエラーが発生しました。<br>時間を置いてもう一度お試しください。"
                                 ,ERR種類['エラーチェック']           =>"登録データに誤りを検知しました。"
                                 ,ERR種類['更新失敗']                 =>"登録・更新処理に失敗しました。<br>入力内容を御確認ください。"
                                 ,ERR種類['削除失敗']                 =>"削除処理に失敗しました。<br>入力内容を御確認ください。"
                                 ,ERR種類['ファイルアップロード失敗'] =>"ファイルアップロード処理に失敗しました。<br>大変申し訳ありませんが、時間を置いてもう一度お試しください。"
                        )
    );

    define("ゲストユーザ", array('ID'=>'ゲスト',
                                'パスワード'=>'ゲスト', 
                                'ニックネーム'=>'ゲスト')
    );

    define('字数上限', array('パスワード'=>20,
                             'メッセージ'=>50
    ));

    /* phpディレクトリ以下のファイル */
    // DB接続からクエリ実行
    include(phpフォルダ .'DB_Ctrl.php');
    include(phpフォルダ .'mysqlCtrl.php');

    include(phpフォルダ .'mst_SNS.php');
    include(phpフォルダ .'mst_ショップ.php');
    include(phpフォルダ .'mst_ショップSNS.php');
    include(phpフォルダ .'mst_ショップ基本ページ詳細情報.php');
    include(phpフォルダ .'mst_ショップ種類コード.php');
    include(phpフォルダ .'mst_ショップ表示ページ.php');
    include(phpフォルダ .'mst_ショップ支払方法.php');

    include(phpフォルダ .'mst_ユーザ.php');
    include(phpフォルダ .'mst_リンク区分コード.php');
    include(phpフォルダ .'mst_ショップ営業時間.php');
    include(phpフォルダ .'mst_通知区分コード.php');
    include(phpフォルダ .'mst_曜日.php');
    

    include(phpフォルダ .'tra_ショップステータス.php');
    include(phpフォルダ .'tra_ショップ表示ページ設定.php');
    include(phpフォルダ .'tra_ユーザお気に入り.php');
    include(phpフォルダ .'tra_通知.php');
    include(phpフォルダ .'tra_不定営設定.php');
    include(phpフォルダ .'tra_不定休設定.php');
    include(phpフォルダ .'tra_ショップ紹介.php');

    include(phpフォルダ .'vie_Select.php');
    include(phpフォルダ .'エラーチェック.php');
    include(phpフォルダ .'upload_img.php');
    
    /*
    共通な変数
    */
    $cls_dbCtrl = new DB_Ctrl;


    /*
    * 指定された月数分加算する
    *
    * @param DateTimeInterface $before 加算前のDateTimeオブジェクト
    * @param int 月数（指定ない場合は1ヶ月）
    * @return DateTime DateTimeオブジェクト
    */
    function addMonth(DateTimeInterface $before, int $month = 1) {
        $beforeMonth = $before->format("n");
    
        // 加算する
        $after       = $before->add(new DateInterval("P" . $month . "M"));
        $afterMonth  = $after->format("n");
    
        // 加算結果が期待値と異なる場合は、前月の最終日に修正する
        $tmpAfterMonth = $beforeMonth + $month;
        $expectAfterMonth = $tmpAfterMonth > 12 ? $tmpAfterMonth - 12 : $tmpAfterMonth;
    
        if ($expectAfterMonth != $afterMonth) {
        $after = $after->modify("last day of last month");
        }
    
        return $after;
    }
    function addDay(DateTimeInterface $before, int $day = 1) {
        $beforeday = $before->format("n");
    
        // 加算する
        $after       = $before->add(new DateInterval("P" . $month . "M"));
        $afterMonth  = $after->format("n");
    
        // 加算結果が期待値と異なる場合は、前月の最終日に修正する
        $tmpAfterMonth = $beforeMonth + $month;
        $expectAfterMonth = $tmpAfterMonth > 12 ? $tmpAfterMonth - 12 : $tmpAfterMonth;
    
        if ($expectAfterMonth != $afterMonth) {
        $after = $after->modify("last day of last month");
        }
    
        return $after;
    }

    function data_Update_or_Insert($PI_cls_tableCtrl, $PI_登録情報){

        global $cls_dbCtrl;
        
        $return = array('status'=>false, 'count'=>-1);
        
        // 更新処理を実行する
        $sql_upd = $PI_cls_tableCtrl->update($PI_登録情報);
        $res_upd = $cls_dbCtrl->update($sql_upd);

        if($res_upd['status'] == false){
            // sqlエラーなど
            $return = $res_upd;

        }elseif($res_upd['status'] == true && $res_upd['count'] == 0){
         
            // sql実行成功、対象件数０件
            $sql_ins = $PI_cls_tableCtrl->insert($PI_登録情報);
            $res_insert = $cls_dbCtrl->insert($sql_ins);
            
            $return = $res_insert;
            
        }else{

            // 正常終了
            $return = $res_upd;
        }
        
        return $return;
    }
    
    function data_Insert($PI_cls_tableCtrl, $PI_登録情報){
        
        global $cls_dbCtrl;
        
        $return = array('status'=>false, 'count'=>-1);
        
        $sql_ins = $PI_cls_tableCtrl->insert($PI_登録情報);
        $res_insert = $cls_dbCtrl->insert($sql_ins);

        return $res_insert;
    }
    function data_Update($PI_cls_tableCtrl, $PI_登録情報){

        global $cls_dbCtrl;
        
        $return = array('status'=>false, 'count'=>-1);
        
        // 更新処理を実行する
        $sql_upd= $PI_cls_tableCtrl->update($PI_登録情報);
        $res_upd = $cls_dbCtrl->update($sql_upd);
        
        return $res_upd;
    }
    
?>