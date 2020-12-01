
<?php
class VieSelectQuery{

    function UserFavShopInfo($PI_ary, $PI_sortKey) {
        $sql = '';
        $sql = $sql. 'select ';
        $sql = $sql. ' `vie_ユーザお気に入りショップ情報`.`ショップID`,';
        $sql = $sql. ' `vie_ユーザお気に入りショップ情報`.`正式名称`,';
        $sql = $sql. ' `vie_ユーザお気に入りショップ情報`.`略称`,';
        $sql = $sql. ' `vie_ユーザお気に入りショップ情報`.`電話番号`,';
        $sql = $sql. ' `vie_ユーザお気に入りショップ情報`.`FAX番号`,';
        $sql = $sql. ' `vie_ユーザお気に入りショップ情報`.`メールアドレス`,';
        $sql = $sql. ' `vie_ユーザお気に入りショップ情報`.`郵便番号`,';
        $sql = $sql. ' `vie_ユーザお気に入りショップ情報`.`都道府県`,';
        $sql = $sql. ' `vie_ユーザお気に入りショップ情報`.`市区町村`,';
        $sql = $sql. ' `vie_ユーザお気に入りショップ情報`.`町名番地`,';
        $sql = $sql. ' `vie_ユーザお気に入りショップ情報`.`建物等`,';
        $sql = $sql. ' `vie_ユーザお気に入りショップ情報`.`ショップ種類コード`,';
        $sql = $sql. ' `vie_ユーザお気に入りショップ情報`.`ショップ種類名称`,';
        $sql = $sql. ' `vie_ユーザお気に入りショップ情報`.`ショップ種類色`,';
        $sql = $sql. ' `vie_ユーザお気に入りショップ情報`.`喫煙可`,';
        $sql = $sql. ' `vie_ユーザお気に入りショップ情報`.`駐車場有`,';
        $sql = $sql. ' `vie_ユーザお気に入りショップ情報`.`メッセージ`,';
        $sql = $sql. ' `vie_ユーザお気に入りショップ情報`.`通知開始日時`,';
        $sql = $sql. ' `vie_ユーザお気に入りショップ情報`.`通知終了日時`,';
        $sql = $sql. ' `vie_ユーザお気に入りショップ情報`.`通知メッセージ`,';
        $sql = $sql. ' `vie_ユーザお気に入りショップ情報`.`通知リンクURL`,';
        $sql = $sql. ' `vie_ユーザお気に入りショップ情報`.`基本ページURL`,';
        $sql = $sql. ' `vie_ユーザお気に入りショップ情報`.`実行時表示URL`,';
        $sql = $sql. ' `vie_ユーザお気に入りショップ情報`.`タグ`';
        $sql = $sql. '  from ( select `ショップID`, `ユーザID` from `tra_ユーザお気に入り` where 1 = 1 and `ユーザID` = "'.$PI_ary['ユーザID'].'") as `ユーザお気に入り`';
        $sql = $sql. '  join `vie_ユーザお気に入りショップ情報` on (1 = 1';
        $sql = $sql. '    and `ユーザお気に入り`.`ショップID` = `vie_ユーザお気に入りショップ情報`.`ショップID`';
        $sql = $sql. '  )';
        $sql = $sql. ' where 1 = 1';
        /*
        if (array_key_exists('ユーザID', $PI_ary)) {
          $sql = $sql. ' AND `vie_ユーザお気に入りショップ情報`.`ユーザID` = "'.$PI_ary['ユーザID'].'"';
        }
        */
        if (array_key_exists('ショップID', $PI_ary)) {
          $sql = $sql. ' AND `vie_ユーザお気に入りショップ情報`.`ショップID` = "'.$PI_ary['ショップID'].'"';
        }
        if (array_key_exists('正式名称', $PI_ary)) {
          $sql = $sql. ' AND `vie_ユーザお気に入りショップ情報`.`正式名称` collate utf8mb4_0900_ai_ci like "%'.$PI_ary['正式名称'].'%"';
        }

        if (array_key_exists('都道府県', $PI_ary)) {
          $sql = $sql. ' AND `vie_ユーザお気に入りショップ情報`.`都道府県` = "'.$PI_ary['都道府県'].'"';
        }
        if (array_key_exists('市区町村', $PI_ary)) {
          $sql = $sql. ' AND `vie_ユーザお気に入りショップ情報`.`市区町村` = "'.$PI_ary['市区町村'].'"';
        }
        if (array_key_exists('町名番地', $PI_ary)) {
          $sql = $sql. ' AND `vie_ユーザお気に入りショップ情報`.`町名番地` = "'.$PI_ary['町名番地'].'"';
        }
        if (array_key_exists('建物等', $PI_ary)) {
          $sql = $sql. ' AND `vie_ユーザお気に入りショップ情報`.`建物等` = "'.$PI_ary['建物等'].'"';
        }

        if (array_key_exists('ショップ種類コード', $PI_ary)) {
          $sql = $sql. ' AND `vie_ユーザお気に入りショップ情報`.`ショップ種類コード` = "'.$PI_ary['ショップ種類コード'].'"';
        }
        if (array_key_exists('喫煙可', $PI_ary)) {
          $sql = $sql. ' AND `vie_ユーザお気に入りショップ情報`.`喫煙可` = "'.$PI_ary['喫煙可'].'"';
        }
        if (array_key_exists('駐車場有', $PI_ary)) {
          $sql = $sql. ' AND `vie_ユーザお気に入りショップ情報`.`駐車場有` = "'.$PI_ary['駐車場有'].'"';
        }
        if (array_key_exists('タグ', $PI_ary)) {
          foreach($PI_ary['タグ'] as $val){
            $sql = $sql. ' AND `vie_ユーザお気に入りショップ情報`.`タグ` like "% '.trim($val).' %"';
          }
        }

        if($PI_sortKey == ''){
          // デフォルトは表示順位
          $sql = $sql. ' order by `vie_ユーザお気に入りショップ情報`.`通知開始日時` desc, `vie_ユーザお気に入りショップ情報`.`ショップID`';
        }else{
          // 指定があればそれをつける
          $sql = $sql. ' order by '.$PI_sortKey.'';
        }
        $sql = $sql. ';';
        return $sql;
    }

    function NoticeList($PI_ary){

      // ユーザIDを取得
      $ユーザID = '';
      if (array_key_exists('ユーザID', $PI_ary)) {
        $ユーザID = $PI_ary['ユーザID'];
      }

      $sql = '';
      $sql = $sql. ' select';
      $sql = $sql. '   *';
      $sql = $sql. '   from';
      $sql = $sql. '   (';
      IF($ユーザID != ''){
        // ユーザID指定ありでお気に入りショップからの限定通知を受け取る
        $sql = $sql. '     SELECT ';
        $sql = $sql. '       `vie_ユーザお気に入りショップ情報`.`ショップID`,';
        $sql = $sql. '       `vie_ユーザお気に入りショップ情報`.`正式名称`,';
        $sql = $sql. '       `vie_ユーザお気に入りショップ情報`.`略称`,';
        $sql = $sql. '       `vie_ユーザお気に入りショップ情報`.`都道府県`,';
        $sql = $sql. '       `vie_ユーザお気に入りショップ情報`.`市区町村`,';
        $sql = $sql. '       `vie_ユーザお気に入りショップ情報`.`町名番地`,';
        $sql = $sql. '       `vie_ユーザお気に入りショップ情報`.`建物等`,';
        $sql = $sql . " 	   date_format(`vie_ユーザお気に入りショップ情報`.`通知開始日時`, '%m-%d %H:%i') as `通知開始日時`,";
        $sql = $sql . " 	   date_format(`vie_ユーザお気に入りショップ情報`.`通知終了日時`, '%m-%d %H:%i') as `通知終了日時`,";
        $sql = $sql. '       `vie_ユーザお気に入りショップ情報`.`通知メッセージ`,';
        $sql = $sql. '       `vie_ユーザお気に入りショップ情報`.`通知リンクURL`,';
        $sql = $sql. '       `vie_ユーザお気に入りショップ情報`.`基本ページURL`,';
        $sql = $sql. '       `vie_ユーザお気に入りショップ情報`.`喫煙可`,';
        $sql = $sql. '       `vie_ユーザお気に入りショップ情報`.`駐車場有`,';
        $sql = $sql. '       `vie_ユーザお気に入りショップ情報`.`ショップ種類コード`,';
        $sql = $sql. '       `vie_ユーザお気に入りショップ情報`.`ショップ種類色`,';
        $sql = $sql. '       1 as `通知区分コード`,';
        $sql = $sql. '       `vie_ユーザお気に入りショップ情報`.`タグ`';
        $sql = $sql. '       from ( select `ショップID`, `ユーザID` from `tra_ユーザお気に入り` where 1 = 1 and `ユーザID` = "'.$ユーザID.'") as `ユーザお気に入り`';
        $sql = $sql. '       join `vie_ユーザお気に入りショップ情報` on (1 = 1';
        $sql = $sql. '           and `ユーザお気に入り`.`ショップID` = `vie_ユーザお気に入りショップ情報`.`ショップID`';
        $sql = $sql. '           and `vie_ユーザお気に入りショップ情報`.`通知区分コード` = 1'; // お気に入り通知のみを表示
        $sql = $sql. '       )';
        $sql = $sql. '     where 1 = 1';
        $sql = $sql. '     and `vie_ユーザお気に入りショップ情報`.`通知メッセージ` <> ""';
        $sql = $sql. '     union all';
      }
      $sql = $sql. '     SELECT ';
      $sql = $sql. '       `ショップID`,';
      $sql = $sql. '       `正式名称`,';
      $sql = $sql. '       `略称`,';
      $sql = $sql. '       `都道府県`,';
      $sql = $sql. '       `市区町村`,';
      $sql = $sql. '       `町名番地`,';
      $sql = $sql. '       `建物等`,';
      $sql = $sql . " 	   date_format(`通知開始日時`, '%m-%d %H:%i') as `通知開始日時`,";
      $sql = $sql . " 	   date_format(`通知終了日時`, '%m-%d %H:%i') as `通知終了日時`,";
      $sql = $sql. '       `通知メッセージ`,';
      $sql = $sql. '       `通知リンクURL`,';
      $sql = $sql. '       `基本ページURL`,';
      $sql = $sql. '       `喫煙可`,';
      $sql = $sql. '       `駐車場有`,';
      $sql = $sql. '       `ショップ種類コード`,';
      $sql = $sql. '       `ショップ種類色`,';
      $sql = $sql. '       2 as `通知区分コード`,';
      $sql = $sql. '       `タグ`';
      $sql = $sql. '     FROM `vie_ショップ情報`';

      $sql = $sql. '     where 1 = 1';
      $sql = $sql. '     and `通知メッセージ` <> ""';

      $sql = $sql. '   ) `A`';
      $sql = $sql. '   where 1 = 1';

      if (array_key_exists('正式名称', $PI_ary)) {
        $sql = $sql. ' AND (';
        $sql = $sql. '         `正式名称` collate utf8mb4_0900_ai_ci like "%'.$PI_ary['正式名称'].'%"';
        $sql = $sql. '      OR `略称` collate utf8mb4_0900_ai_ci like "%'.$PI_ary['正式名称'].'%"';
        $sql = $sql. ' )';
      }        

      if (array_key_exists('都道府県', $PI_ary)) {
        $sql = $sql. ' AND `都道府県` like "%'.$PI_ary['都道府県'].'%"';
      }        

      if (array_key_exists('市区町村', $PI_ary)) {
        $sql = $sql. ' AND `市区町村` like "%'.$PI_ary['市区町村'].'%"';
      }        

      if (array_key_exists('町名番地', $PI_ary)) {
        $sql = $sql. ' AND `町名番地` like "%'.$PI_ary['町名番地'].'%"';
      }        

      if (array_key_exists('建物等', $PI_ary)) {
        $sql = $sql. ' AND `建物等` like "%'.$PI_ary['建物等'].'%"';
      }        

      if (array_key_exists('ショップ種類コード', $PI_ary)) {
        if($PI_ary['ショップ種類コード'] != ''){
          $sql = $sql. ' AND `ショップ種類コード` = "'.$PI_ary['ショップ種類コード'].'"';
        }
      }
      if (array_key_exists('喫煙可', $PI_ary)) {
        $sql = $sql. ' AND `喫煙可` = "'.$PI_ary['喫煙可'].'"';
      }
      if (array_key_exists('駐車場有', $PI_ary)) {
        $sql = $sql. ' AND `駐車場有` = "'.$PI_ary['駐車場有'].'"';
      }
      if (array_key_exists('タグ', $PI_ary)) {
        foreach($PI_ary['タグ'] as $val){
          $sql = $sql. ' AND `タグ` like "% '.trim($val).' %"';
        }
      }

      $sql = $sql. '   order by `通知開始日時` desc, `通知区分コード`, `通知終了日時` ';
      $sql = $sql. '   LIMIT 500';
      $sql = $sql. ';';

      return $sql;

    }

    function ShopSearch($PI_ary, $PI_sortKey){

      // ユーザIDを取得
      $ユーザID = '';
      if (array_key_exists('ユーザID', $PI_ary)) {
        $ユーザID = $PI_ary['ユーザID'];
      }

      $sql = '';
      $sql = $sql. 'select ';
      $sql = $sql. '  *';
      $sql = $sql. 'from';
      $sql = $sql. '(';

      $sql = $sql.'     	select    ';    
      $sql = $sql.'            `vie_ショップ情報`.`ショップID`,'; 
      $sql = $sql. '           `vie_ショップ情報`.`正式名称`,';
      $sql = $sql.'            `vie_ショップ情報`.`略称`,'; 
      $sql = $sql. '           `vie_ショップ情報`.`都道府県`,';
      $sql = $sql. '           `vie_ショップ情報`.`市区町村`,';
      $sql = $sql. '           `vie_ショップ情報`.`町名番地`,';
      $sql = $sql. '           `vie_ショップ情報`.`建物等`,';
      $sql = $sql."            date_format(`vie_ショップ情報`.`通知開始日時`, '%m-%d %H:%i') as `通知開始日時`,";
      $sql = $sql."            date_format(`vie_ショップ情報`.`通知終了日時`, '%m-%d %H:%i') as `通知終了日時`,";     
      $sql = $sql.'            `vie_ショップ情報`.`メッセージ`,'; 
      $sql = $sql.'            `vie_ショップ情報`.`通知メッセージ`,';
      $sql = $sql.'            `vie_ショップ情報`.`通知リンクURL`,';       
      $sql = $sql.'            `vie_ショップ情報`.`実行時表示URL`,';       
      $sql = $sql.'            `vie_ショップ情報`.`基本ページURL`,';       
      $sql = $sql.'            `vie_ショップ情報`.`喫煙可`,';       
      $sql = $sql.'            `vie_ショップ情報`.`駐車場有`,';
      $sql = $sql.'            `vie_ショップ情報`.`ショップ種類コード`,';       
      $sql = $sql.'            `vie_ショップ情報`.`ショップ種類色`,';     
      $sql = $sql.'            `vie_ショップ情報`.`営フラグ`,'; 
      $sql = $sql.'            `vie_ショップ情報`.`休フラグ`,'; 
      $sql = $sql.'            `vie_ショップ情報`.`通営フラグ`,';   
      $sql = $sql.'            1 as `通知区分コード`,';   
      $sql = $sql.'            `vie_ショップ情報`.`タグ`'; 
      $sql = $sql.'       from `vie_ショップ情報`';
      $sql = $sql. '      left join ( select `ショップID`, `ユーザID` from `tra_ユーザお気に入り` where 1 = 1 and `ユーザID` = "'.$ユーザID.'") as `ユーザお気に入り` on (1 = 1';
      $sql = $sql. '           and `ユーザお気に入り`.`ショップID` = `vie_ショップ情報`.`ショップID`';
      $sql = $sql. '       )';
      $sql = $sql. '       where 1 = 1 ';
      $sql = $sql. '         and `ユーザお気に入り`.`ショップID` is null';

      $sql = $sql. '       union all ';
      
      $sql = $sql. '       select ';
      $sql = $sql. '             `vie_ユーザお気に入りショップ情報`.`ショップID`,';
      $sql = $sql. '             `vie_ユーザお気に入りショップ情報`.`正式名称`,';
      $sql = $sql. '             `vie_ユーザお気に入りショップ情報`.`略称`,';
      $sql = $sql. '             `vie_ユーザお気に入りショップ情報`.`都道府県`,';
      $sql = $sql. '             `vie_ユーザお気に入りショップ情報`.`市区町村`,';
      $sql = $sql. '             `vie_ユーザお気に入りショップ情報`.`町名番地`,';
      $sql = $sql. '             `vie_ユーザお気に入りショップ情報`.`建物等`,';
      $sql = $sql."              date_format(`vie_ユーザお気に入りショップ情報`.`通知開始日時`, '%m-%d %H:%i') as `通知開始日時`,";
      $sql = $sql."              date_format(`vie_ユーザお気に入りショップ情報`.`通知終了日時`, '%m-%d %H:%i') as `通知終了日時`,";   
      $sql = $sql. '             `vie_ユーザお気に入りショップ情報`.`メッセージ`,';
      $sql = $sql.'              `vie_ユーザお気に入りショップ情報`.`通知メッセージ`,';
      $sql = $sql.'              `vie_ユーザお気に入りショップ情報`.`通知リンクURL`,';   
      $sql = $sql. '             `vie_ユーザお気に入りショップ情報`.`実行時表示URL`,';
      $sql = $sql. '             `vie_ユーザお気に入りショップ情報`.`基本ページURL`,';
      $sql = $sql. '             `vie_ユーザお気に入りショップ情報`.`喫煙可`,';
      $sql = $sql. '             `vie_ユーザお気に入りショップ情報`.`駐車場有`,';
      $sql = $sql. '             `vie_ユーザお気に入りショップ情報`.`ショップ種類コード`,';
      $sql = $sql. '             `vie_ユーザお気に入りショップ情報`.`ショップ種類色`,';  
      $sql = $sql.'              `vie_ユーザお気に入りショップ情報`.`営フラグ`,'; 
      $sql = $sql.'              `vie_ユーザお気に入りショップ情報`.`休フラグ`,'; 
      $sql = $sql.'              `vie_ユーザお気に入りショップ情報`.`通営フラグ`,';   
      $sql = $sql.'              2 as `通知区分コード`,'; 
      $sql = $sql.'              `vie_ユーザお気に入りショップ情報`.`タグ`'; 
      $sql = $sql. '       from ( select `ショップID`, `ユーザID` from `tra_ユーザお気に入り` where 1 = 1 and `ユーザID` = "'.$ユーザID.'") as `ユーザお気に入り`';
      
      $sql = $sql. '       join `vie_ユーザお気に入りショップ情報` on (1 = 1';
      $sql = $sql. '           and `ユーザお気に入り`.`ショップID` = `vie_ユーザお気に入りショップ情報`.`ショップID`';
      $sql = $sql. '       )';
      $sql = $sql. ' ) `A`';
      $sql = $sql. ' where 1 = 1';
      if (array_key_exists('ショップID', $PI_ary)) {
        // 大文字小文字を区別しない
        $sql = $sql. ' AND `ショップID` = "'.$PI_ary['ショップID'].'"';
      }
      if (array_key_exists('正式名称', $PI_ary)) {
        // 大文字小文字を区別しない
        $sql = $sql. ' AND (';
        $sql = $sql. '         `正式名称` collate utf8mb4_0900_ai_ci like "%'.$PI_ary['正式名称'].'%"';
        $sql = $sql. '      OR `略称` collate utf8mb4_0900_ai_ci like "%'.$PI_ary['正式名称'].'%"';
        $sql = $sql. ' )';
      }

      if (array_key_exists('都道府県', $PI_ary)) {
        $sql = $sql. ' AND `都道府県` like "%'.$PI_ary['都道府県'].'%"';
      }        

      if (array_key_exists('市区町村', $PI_ary)) {
        $sql = $sql. ' AND `市区町村` like "%'.$PI_ary['市区町村'].'%"';
      }        

      if (array_key_exists('町名番地', $PI_ary)) {
        $sql = $sql. ' AND `町名番地` like "%'.$PI_ary['町名番地'].'%"';
      }        

      if (array_key_exists('建物等', $PI_ary)) {
        $sql = $sql. ' AND `建物等` like "%'.$PI_ary['建物等'].'%"';
      }        

      if (array_key_exists('ショップ種類コード', $PI_ary)) {
        $sql = $sql. ' AND `ショップ種類コード` = "'.$PI_ary['ショップ種類コード'].'"';
      }
      if (array_key_exists('喫煙可', $PI_ary)) {
        $sql = $sql. ' AND `喫煙可` = "'.$PI_ary['喫煙可'].'"';
      }
      if (array_key_exists('駐車場有', $PI_ary)) {
        $sql = $sql. ' AND `駐車場有` = "'.$PI_ary['駐車場有'].'"';
      }
      if (array_key_exists('オープン中', $PI_ary)) {
        if($PI_ary['オープン中'] == true){
          $sql = $sql. ' AND (`営フラグ` = 1 or `通営フラグ` = 1)';
          $sql = $sql. ' AND `休フラグ` = 0 ';
        }
      }
      if (array_key_exists('タグ', $PI_ary)) {
        foreach($PI_ary['タグ'] as $val){
          $sql = $sql. ' AND `タグ` like "% '.trim($val).' %"';
        }
      }

      $sql = $sql. '   order by `通知開始日時` desc, `通知終了日時` ';
      $sql = $sql. '   LIMIT 100';
      $sql = $sql. ';';

      return $sql;

    }

    function NowPage($ショップID){

      $sql = "";
      $sql = $sql. " SELECT * FROM `vie_実行時表示ページ`";
      $sql = $sql. ' where 1 = 1';
      $sql = $sql. ' AND `ショップID` = "'.$ショップID.'"';
      $sql = $sql. ';';

      return $sql;
    }
 
}

?>