<script type="text/javascript" src="./js/screenLockCtrl.js"></script>
<script type="text/javascript" src="./js/createElement.js"></script>
<script type="text/javascript">

    function Fnc_Search() {

        var shopName = document.getElementById( "shopName").value;
        var shopKind = document.getElementById( "shopKind").value;
        var tags = document.getElementById( "tags").value;

        // 検索結果表示divタグを削除
        document.getElementById("shops").remove();
        document.getElementById("errMsg").innerText = '';

        screenLock();
        $.ajax({
            type: 'post',
            url: "<?php echo comフォルダ; ?>" + 'bat_おすすめ検索処理.php',
            data: {"shopName":shopName, "shopKind":shopKind, "tags":tags},
            success : function(res){
                console.log("ajax通信に成功しました");
                console.log(res);

                // select結果のJSONを取得
                var jsonData = JSON.parse(res);
                
                // divタグshopsを作成するためにも、取得結果にかかわらず処理する
                Sub_insertShopData(jsonData, "<?php echo ショップRoute ?>", "<?php echo $_SERVER['HTTP_HOST'] ?>");

                // 取得結果によって分岐
                if(jsonData == ''){
                    // 取得結果が空の場合
                    document.getElementById("errMsg").innerText = '検索結果：０件です。';
                }else{
                    // 検索条件の非表示
                    Sub_ViewChange();
                }

                screenUnLock();

            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
                console.log("ajax通信に失敗しました");
                console.log("XMLHttpRequest : " + XMLHttpRequest.status);
                console.log("textStatus     : " + textStatus);
                console.log("errorThrown    : " + errorThrown.message);


                document.getElementById("errMsg").innerText = '通信に失敗しました。時間を開けてからもう一度実行してください。';

                screenUnLock();
            }
        });
    }

    function Sub_ViewChange(){

        var searchDiv = document.getElementById( "search-Inner");

        if(searchDiv.style.display == 'none'){
            searchDiv.style.display = 'inline';
            document.getElementById( "btn_ViewChange_msg").textContent = '検索条件　非表示';

        }else{
            searchDiv.style.display = 'none';
            document.getElementById( "btn_ViewChange_msg").textContent = '検索条件　表示';
        }
    }
    
    function Sub_insertShopData(PI_JsonData, PI_Dire_ShopFolder, PI_HostName){

        var nowDate = new Date();

        shopsDiv = document.createElement("div");
        shopsDiv.setAttribute("id", "shops");
        shopsDiv.setAttribute("class", "content-shop");
        
        for(let idx = 1; idx <= PI_JsonData.length; idx++){

            let row = PI_JsonData[idx -1];

            // ショップ画像について
            var imgDiv = document.createElement("div");
            imgDiv.setAttribute("class", "img") ;

            var img = document.createElement("img");
            img.setAttribute("class", "thumbnails");
            var imgURL = PI_Dire_ShopFolder + '/' + row.ショップID + '/recommended/img/thumbnail.jpg?'
                 + nowDate.getMinutes() + nowDate.getSeconds() + nowDate.getHours()
                 + nowDate.getMonth() + nowDate.getDate() + nowDate.getFullYear();
            img.setAttribute("src", imgURL) ;

            imgDiv.appendChild(img); // 画像div完成

            // ショップメッセージ
            var textDiv = document.createElement("div");
            textDiv.setAttribute("class", "text") ;

            var nameDiv = document.createElement("div");
            nameDiv.setAttribute("class", "name") ;

            var nameLabel = document.createElement("label");
            nameLabel.setAttribute("class", "name-lbl") ;
            nameLabel.appendChild(document.createTextNode(row.商品名));

            var noticeDiv = document.createElement("div");
            noticeDiv.setAttribute("class", "msg") ;

            var noticeLabel = document.createElement("label");
            noticeLabel.setAttribute("class", "msg-lbl") ;

            if(row.キャッチコピー != ""){
                noticeLabel.appendChild(document.createTextNode(row.キャッチコピー));
            }
            nameDiv.appendChild(nameLabel);　
            noticeDiv.appendChild(noticeLabel);
            textDiv.appendChild(nameDiv);
            textDiv.appendChild(noticeDiv); // 通知div完成

            // リンクタグ
            /*
            var urlA = document.createElement("a");
            urlA.appendChild(imgDiv);
            urlA.appendChild(textDiv); // リンクタグ完成
            */
            
            var shopDiv = document.createElement("div");
            shopDiv.setAttribute("class", "shop") ;
            shopDiv.setAttribute("style", "background-color: " + row.ショップ種類色) ;
            var linkURL='';
            linkURL = row.リンクURL;

            // 新規タブで開くかの判定
            var blank = "";
            var patternURL = "https://" + PI_HostName;
            if(linkURL.indexOf(patternURL) !== 0){
                //  前方一致検索でドメイン内ページかを確認
                blank = '_blank';
            }

            shopDiv.setAttribute("href1", linkURL) ;
            shopDiv.setAttribute("target", blank) ;
            shopDiv.setAttribute("href2", patternURL + "/?shopID=" + row.ショップID + "&page=おすすめ紹介" ) ;
            shopDiv.setAttribute("shopId", row.ショップID) ;
            shopDiv.setAttribute("id", row.ショップID) ;


            /*
            shopDiv.appendChild(urlA); // ショップdiv完成
            */
            shopDiv.appendChild(imgDiv);
            shopDiv.appendChild(textDiv);

            shopsDiv.appendChild(shopDiv); // コンテンツdivに追加
        }

        // 追加要素の親要素
        var body = document.getElementById("body");
                
        // 検索条件divタグ
        var searchDiv = document.getElementById("search");

        // 追加
        body.insertBefore(shopsDiv, searchDiv.nextSibling);

        // bindingの実行
        bind_Event_shops();
    }
</script>
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

    <?php
        $option_display = "display:block;";
        if($_GET['page'] == '新着情報'){
            $option_display = "display:none;";
        }
    ?>
    
    <div class="item" >
        <button type="button" id="btn_Search" onclick="Fnc_Search()">検索</button>
    </div>

</div>
<div class="item">
    <button type="button" id="btn_ViewChange" onclick="Sub_ViewChange()"><a id="btn_ViewChange_msg">検索条件　非表示</a></button>
</div>
<hr width='80%'>
</div>