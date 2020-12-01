

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
            var imgURL = PI_Dire_ShopFolder + '/' + row.ショップID + '/main/img/thumbnail.jpg?'
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
            nameLabel.appendChild(document.createTextNode(row.略称));

            var noticeDiv = document.createElement("div");
            noticeDiv.setAttribute("class", "msg") ;

            var noticeLabel = document.createElement("label");
            noticeLabel.setAttribute("class", "msg-lbl") ;

            var shopMsg ='';
            if(!row.通知メッセージ == false){
                shopMsg = row.通知メッセージ;
            }else{
                shopMsg = row.メッセージ;
            }
            noticeLabel.appendChild(document.createTextNode(shopMsg));


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
            if(!row.通知メッセージ == false){
                linkURL = row.通知リンクURL;
            }else if(!row.実行時表示URL == false){
                linkURL = row.実行時表示URL;
            }else{
                linkURL = row.基本ページURL;
            }

            // 新規タブで開くかの判定
            var blank = "";
            var patternURL = "https://" + PI_HostName;
            if(linkURL.indexOf(patternURL) !== 0){
                //  前方一致検索でドメイン内ページかを確認
                blank = '_blank';
            }

            shopDiv.setAttribute("href1", linkURL) ;
            shopDiv.setAttribute("target", blank) ;
            shopDiv.setAttribute("href2", row.基本ページURL) ;
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


    function Sub_insertNoticeData(PI_JsonData, PI_HostName){

        var table = document.getElementById("notice-table");

        for(let idx = 1 ; idx <= PI_JsonData.length; idx++){
        
            row = PI_JsonData[idx-1];

            var td_kbn = document.createElement("td");
            td_kbn.setAttribute("class", "clm-noticeKbn") ;
            if(row.通知区分コード == 1){
                td_kbn.innerText = '★';
            }

            var td_name = document.createElement("td");
            td_name.setAttribute("class", "clm-shopName") ;
            td_name.innerText = row.略称;

            var td_msg = document.createElement("td");
            td_msg.setAttribute("class", "clm-msg") ;
            td_msg.innerText = row.通知メッセージ;

            var td_time = document.createElement("td");
            td_time.setAttribute("class", "clm-startTime") ;
            td_time.innerText = row.通知開始日時;

            var tr = document.createElement("tr");
            tr.setAttribute("class", row.ショップ種類コード) ;
            tr.setAttribute("id", row.通知区分コード) ;
            tr.setAttribute("shopId", row.ショップID) ;
            tr.setAttribute("href1", row.通知リンクURL) ;
            tr.setAttribute("href2", row.基本ページURL) ;

            /*
            if(row.通知リンクURL.indexOf("https://" + document.domain) !== 0){
                tr.setAttribute("target", "_blank") ;
            }*/
            /*
             ブラウザバックの度に再検索は面倒のため、常に新規タブで表示
            */
            tr.setAttribute("target", "_blank") ; 

            /*
            td_kbn.style.borderColor = row.ショップ種類色;
            td_name.style.borderColor = row.ショップ種類色;
            td_msg.style.borderColor = row.ショップ種類色;
            td_time.style.borderColor = row.ショップ種類色;
            */
           tr.style.backgroundColor = row.ショップ種類色;

            tr.appendChild(td_kbn);　
            tr.appendChild(td_name);　
            tr.appendChild(td_msg);　
            tr.appendChild(td_time);　

            table.appendChild(tr);　
        }
        bind_Event_noticeTabel();
    }
