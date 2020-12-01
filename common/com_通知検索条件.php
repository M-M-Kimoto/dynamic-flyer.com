<script type="text/javascript" src="./js/screenLockCtrl.js"></script>
<script type="text/javascript" src="./js/createElement.js"></script>
<script type="text/javascript">

    function Fnc_Search() {

        var shopName = document.getElementById( "shopName").value;
        var shopKind = document.getElementById( "shopKind").value;
        var todohuken = document.getElementById( "todohuken").value;
        var sikutyoson = document.getElementById( "sikutyoson").value;
        var tyomeibanti = document.getElementById( "tyomeibanti").value;
        var tatemono = document.getElementById( "tatemono").value;
        var rangeCode = document.getElementById( "range_all").value;
        if (document.getElementById( "range_fav").checked == true){
            rangeCode = document.getElementById( "range_fav").value;
        }
        var tags = document.getElementById( "tags").value;

        // 検索結果表示tableタグを削除してヘッダを作成
        var table =　document.getElementById("notice-table");

        var newTable = table.cloneNode( false ); //ガワだけ複製して…
        table.parentNode.replaceChild( newTable , table ); //すげ替え。

        var th_kbn = document.createElement("th");
        th_kbn.setAttribute("class", "clm-noticeKbn") ;

        var th_name = document.createElement("th");
        th_name.setAttribute("class", "clm-shopName") ;
        th_name.innerText = "店名";

        var th_msg = document.createElement("th");
        th_msg.setAttribute("class", "clm-msg") ;
        th_msg.innerText = "メッセージ";

        var th_time = document.createElement("th");
        th_time.setAttribute("class", "clm-startTime") ;
        th_time.innerText = "通知開始時刻";

        var tr = document.createElement("tr");

        tr.appendChild(th_kbn);　
        tr.appendChild(th_name);　
        tr.appendChild(th_msg);　
        tr.appendChild(th_time);　

        newTable.appendChild(tr);　
        document.getElementById("errMsg").innerText = '';

        screenLock();
        $.ajax({
            type: 'post',
            url: "<?php echo comフォルダ; ?>"+ 'bat_通知検索処理.php',
            data: {"userID":"<?php echo $_SESSION['ID']; ?>", "shopName": shopName, "shopKind":shopKind, "todohuken":todohuken,
                   "sikutyoson":sikutyoson, "tyomeibanti":tyomeibanti, "tatemono":tatemono, "rangeCode":rangeCode,
                   "tags":tags},
            success : function(res){
                console.log("ajax通信に成功しました");
                console.log(res);

                // select結果のJSONを取得
                var jsonData = JSON.parse(res);
                
                // divタグshopsを作成するためにも、取得結果にかかわらず処理する
                Sub_insertNoticeData(jsonData, "<?php echo $_SERVER['HTTP_HOST'] ?>");

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
</script>
<?php
    require comフォルダ . 'com_HTML_検索条件.php';
?>
