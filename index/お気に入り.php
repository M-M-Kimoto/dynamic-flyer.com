
<hr width='80%'>
    <div id="pageTitle"> 
        <label id="pageTitle-lbl"><?php echo $_GET['page']; ?></label>
    </div> 
<hr width='80%'>

<?php

    // データ取得
    $Get_viewQuery = new VieSelectQuery();
    $res_sel = array();

    if($_SESSION["ID"] != ゲストユーザ ['ID']){
        
        // 検索条件
        $検索条件= array(
            'ユーザID'=>$_SESSION["ID"],
            'パスワード'=>$_SESSION["パスワード"],
        );
        $sql_sel = $Get_viewQuery->UserFavShopInfo($検索条件, '');
        $res_sel = $cls_dbCtrl->select($sql_sel);

    }else{

        // ゲストユーザ で見ている場合
        $res_sel['status'] = false;
?>

<div class="content-item" id="msg" style="width: 80%;">
    <div class="item" style="width: 100%;">
        <label id="errMsg">
            <?php
                echo 'ユーザ登録していただくと、よく見るお店や気になるお店をお気に入り登録することですぐ見れるようになります。';
                echo 'またお店からお気に入り登録してくれているユーザのみへの情報もある、、、かも？';
            ?>
        </label>
    </div>
</div>

<?php
        exit();
    }
?>

<script type="text/javascript" src="./js/tapCtrl.js"></script>
<script type="text/javascript">

    // 規程のイベント処理を実行しないにしているため、リンクタグなどがある場合に注意
    function bind_Event_shops() {
        
        var touched = false;
        var touch_time = 0;
        var timer='';
        
        $("#shops .shop").bind('touchstart',function(e) {
            clickStart(e);
        });
        $("#shops .shop").bind('touchend',function(e) {
            clickEnd(e);
        });
        $("#shops .shop").bind('touchmove',function(e) {
            clickMove(e);
        });
        $("#shops .shop").bind('gesturestart',function(e) {
            clickMove(e);
        });
        $("#shops .shop").bind('gestureend',function(e) {
            clickMove(e);
        });


        $("#shops .shop").bind('mousedown',function(e) {
            clickStart(e);
        });
        $("#shops .shop").bind('mouseup',function(e) {
            clickEnd(e);
        });
        $("#shops .shop").bind('mousemove',function(e) {
            clickMove(e);
        });

        function clickStart(e){
            
            touched = true;
            touch_time = 0;
            timer = setInterval(function(){

                touch_time += 100;
                if(touch_time == 2000){
                    clickEnd(e);
                }
                console.log('setInterval：' + touch_time);
            }, 100);

            // e.preventDefault();
        }
        function clickEnd(e){
            if (touched) {
                if (touch_time < 500 ) {
                    // 短いタップでの処理
                    var newTabFlg = "";
                    if(e.currentTarget.getAttribute('target') == '_blank'){
                        newTabFlg = true;
                    }else{
                        newTabFlg = false;
                    }
                    Sub_SingleTap(e.currentTarget.getAttribute('href1'), newTabFlg);

                }else if(touch_time < 2000 ){
                    // ミドルタップ処理
                    Sub_middleTap(e.currentTarget.getAttribute('href2'), true);
                
                }else{
                    // 長押し お気に入り削除処理無し
                    /* ユーザ登録機能を制限するため、お気に入り登録機能も制限　2020/10/31
                    Sub_longTap("<?php echo $_SESSION['ID']; ?>", 
                                 e.currentTarget.getAttribute('shopId'), 
                                 '<?php echo ゲストユーザ['ID']; ?>', 1);

                    document.getElementById(e.currentTarget.getAttribute('id')).remove();
                    */

                }
            }
            touched = false;
            clearInterval(timer);
            touch_time = 0;
            e.preventDefault();

        }
        function clickMove(e){
            touched = false;
            clearInterval(timer);
            touch_time = 0;
        }
    }
</script>

<?php
    require comフォルダ . "com_shop検索条件.php"
?>

<div class="content-shop" id="shops">
</div>
 <script>
    bind_Event_shops();
    Fnc_Search();
</script>
