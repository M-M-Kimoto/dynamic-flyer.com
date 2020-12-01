
<div class="content-item" id="search">

<div id="search-Inner">
    
    <div class="item">
        <label id="errMsg"></label>
    </div>

    <div class="item">
      <label class="itemLabel-nomal" for="タグ">タグ</label>
      <input type="text" class="text" id="tags" name="タグ" value="">
      <br><label class="explain">スペーズ区切りで複数ワード設定出来ます。</label>
    </div>

    <div class="item">
        <label class="itemLabel-nomal" for="text">ショップ種類</label>
        <div id="shop-kinds">
            <?php
                // 変数の初期化
                $cls_Mstショップ種類コード = new MstShopKindCode_Ctrl();
                $Mstショップ_検索条件種類コード = array(); // sqlを作るための引数用
                $ログインショップ情報種類コード = array(); // ログインショップIDのmst_ショップのレコード

                // ショップ種類コードの一覧を取得
                $Mstショップ_検索条件種類コード = array();
                $sql_select_MstShopKind = $cls_Mstショップ種類コード->select($Mstショップ_検索条件種類コード);
                $res_select_MstShopKind = $cls_dbCtrl->select($sql_select_MstShopKind);
                if ($res_select_MstShopKind["status"] == true){
                
                    echo '<select id="shopKind">';
                    echo '<option value=""></option>';
                    // 取得レコードを変数へ
                    $初期値_ショップ種類コード="";
                    $ログインショップ情報種類コード = $res_select_MstShopKind["rows"];

                    $val = "";
                    foreach($ログインショップ情報種類コード as $row){
                        echo '<option value="'.$row['ID'].'" >'.$row['名称'].'</option>';
                    }
                    echo '</select>';
                }
            ?>
        </div>
    </div>

    <div class="item">
        <label class="itemLabel-nomal" for="店名">店名</label>
        <input type="text" id="shopName" class="text" name="店名">
    </div>

    <div class="item" style="display:none;">
        <label class="itemLabel-nomal" for="都道府県">都道府県</label>
        <input type="text" id="todohuken" class="text" name="都道府県" value="">
    </div>

    <div class="item" style="display:none;">
        <label class="itemLabel-nomal" for="市区町村">市区町村</label>
        <input type="text" id="sikutyoson" class="text" name="市区町村" value="">
    </div>

    <div class="item">
        <label class="itemLabel-nomal" for="町名番地">町名番地</label>
        <input type="text" id="tyomeibanti" class="text" name="町名番地">
    </div>

    <div class="item">
        <label class="itemLabel-nomal" for="建物等">建物等</label>
        <input type="text" id="tatemono" class="text" name="建物等">
    </div>

    <div class="item"  id="rangeCode"  style="display:none;">
        <label class="itemLabel-nomal" for="検索範囲">検索範囲</label>
        <?php
            $range_all = "checked";
            $range_fav = "";
            if($_GET['page'] == 'お気に入り'){
                $range_all = "";
                $range_fav = "checked";
            }
        ?>
        <p>
          <input type="radio" class="radio" id="range_all" name="全体検索" value="0" <?php echo $range_all; ?> ><label for="全体検索">全体検索</label>
          <input type="radio" class="radio" id="range_fav" name="お気に入り" value="1" <?php echo $range_fav; ?> ><label for="お気に入り">お気に入り</label>
        </p>
    </div>

    <?php
        $option_display = "display:block;";
        if($_GET['page'] == '新着情報'){
            $option_display = "display:none;";
        }
    ?>
    <div class="item" id="option" style=<?php echo $option_display;?>>
        <label class="itemLabel-nomal" for="オプション">オプション</label>
        <p>
          <input type="checkbox" class="checkbox" id="option-open" name="オープン中" value="1"><label for="オープン中">オープン中</label>
        </p>
    </div>

    <div class="item" >
        <button type="button" id="btn_Search" onclick="Fnc_Search()">検索</button>
    </div>

</div>
<div class="item">
    <button type="button" id="btn_ViewChange" onclick="Sub_ViewChange()"><a id="btn_ViewChange_msg">検索条件　非表示</a></button>
</div>
<hr width='80%'>
</div>