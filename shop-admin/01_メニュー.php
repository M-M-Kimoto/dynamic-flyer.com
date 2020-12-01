<?php
  /* 変数初期化 */
  $エラーコード = ERR種類['エラー無し'];

  // 入力後の処理であれば、とりあえず連想配列を作成
  $ary_ショップ情報= array(
    'ID'=>$index['ショップID']
  );

  // ショップマスタを検索し、一致するならOK
  $cls_Mstショップ = new MstShop_Ctrl();

  // ショップIDとパスワードを条件に検索
  $sql_select = $cls_Mstショップ->select($ary_ショップ情報);
  $res = $cls_dbCtrl->select($sql_select);

  // 実行失敗
  if ($res["status"] == false){
    $エラーコード = ERR種類['マスタ情報取得失敗'];

  }elseif ($res["count"] < 1){
    /*
      キーで検索しているためありえないが、
      これをいれないと問答無用で配列の先頭をとるというのが意味不明なため
    */
    $エラーコード = ERR種類['マスタ情報取得失敗'];

  }else{
    $エラーコード = ERR種類['エラー無し'];

  }

  // ポストした内容も消す
  $_POST = array();

  // ajaxで使用するbatファイルを定数に
	define('通知取得処理', './bat_運営-ショップ通知取得処理.php');
	define('通知表示最大件数', 5);

  clearstatcache(); 
?>

<link rel="stylesheet" href="./stylesheets/<?php echo $index['Me']['ファイル名']; ?>.css">
<div class="content-menu">

  <div class="content-menu">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo Route; ?>/js/screenLockCtrl.js"></script>
    <script type="text/javascript">

      // ajaxを使用した通知取得処理
      function Sub_Get_News(){
        $.ajax({
          type: 'post',
          url: '<?php echo 通知取得処理; ?>',
          data: {"shopID":"<?php echo $index['ショップID']; ?>", "limit":"<?php echo 通知表示最大件数; ?>"},
          success : function(res){
            console.log("ajax通信に成功しました");
            console.log(res);

            if(res != ''){
              // select結果のJSONを取得
              var jsonData = JSON.parse(res);
              Sub_SetNews(jsonData);
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

      /*
      *
      *通知情報取得
      *
      */
      function Sub_SetNews(PI_JsonData){

        if(PI_JsonData.length == 0){
          var shopDetails =  document.getElementById("news");

          var h4 = document.createElement("h5");
          h4.innerText = "現在お知らせはありません。"
          shopDetails.appendChild(h4);
          return;
        }

        var div_noticeList = document.getElementById("notice-list");
        let idx = 1;
        let maxIdx = <?php echo 通知表示最大件数; ?>;
        PI_JsonData.forEach(function (row){

          var div_item = document.createElement("div");
          div_item.setAttribute("class", "notice-item") ;

          var a_link = document.createElement("a");
          if(row.リンクURL){
            if(decodeURI(location.href) != row.リンクURL){
              a_link.href = row.リンクURL ;
              a_link.style.color = 'blue';
              a_link.setAttribute("target", "_blank") ;
            }else{
              a_link.textDecoration = 'none';
            }
          }

          var label_date = document.createElement("label");
          label_date.setAttribute("class", "notice-date") ;
          label_date.innerText = row.通知開始日;

          var label_msg = document.createElement("label");
          label_msg.setAttribute("class", "notice-msg") ;
          label_msg.innerText = row.通知内容;


          a_link.appendChild(label_date);
          a_link.appendChild(label_msg);
          div_item.appendChild(a_link);

          div_noticeList.appendChild(div_item);

          if(maxIdx == idx){
            return;
          }
          idx += 1;
        });

      }
    </script>
    <?php /*　通知 */ ?>
    <div class="content-shopDetails" id="news">
      <div class="inner" id="news-inner">
        <h5>運営からのお知らせ</h5>
        <div id="notice-list">

        </div>

      </div>
    </div>
  </div>


  <?php if ($エラーコード == ERR種類['エラー無し']) { ?>

    <form class="menu" action="" method="post">

      <h4>トランデータ</h4>
      <div class="content-menu">
        <div class="item">
          <button class="menu-btn" name="menu-btn" value="<?php echo ショップ管理機能['おすすめ紹介']['NO']; ?>">
            <?php echo ショップ管理機能['おすすめ紹介']['機能名']; ?>
          </button>
        </div>
        <div class="item">
          <button class="menu-btn" name="menu-btn" value="<?php echo ショップ管理機能['通知情報']['NO']; ?>">
            <?php echo ショップ管理機能['通知情報']['機能名']; ?>
          </button>
        </div>
        <div class="item">
          <button class="menu-btn" name="menu-btn" value="<?php echo ショップ管理機能['不定休日設定']['NO']; ?>">
            <?php echo ショップ管理機能['不定休日設定']['機能名']; ?>
          </button>
        </div>
        <div class="item">
          <button class="menu-btn" name="menu-btn" value="<?php echo ショップ管理機能['不定営日設定']['NO']; ?>">
            <?php echo ショップ管理機能['不定営日設定']['機能名']; ?>
          </button>
        </div>
        <div class="item">
          <button class="menu-btn" name="menu-btn" value="<?php echo ショップ管理機能['表示ページ設定']['NO']; ?>">
            <?php echo ショップ管理機能['表示ページ設定']['機能名']; ?>
          </button>
        </div>
        <hr>
      </div>

      <h4>マスタデータ</h4>
      <div class="content-menu">
        <div class="item">
          <button class="menu-btn" name="menu-btn" value="<?php echo ショップ管理機能['基本情報']['NO']; ?>">
            <?php echo ショップ管理機能['基本情報']['機能名']; ?>
          </button>
        </div>
        <div class="item">
          <button class="menu-btn" name="menu-btn" value="<?php echo ショップ管理機能['リンク登録']['NO']; ?>">
            <?php echo ショップ管理機能['リンク登録']['機能名']; ?>
          </button>
        </div>
        <div class="item">
          <button class="menu-btn" name="menu-btn" value="<?php echo ショップ管理機能['通常営業時間']['NO']; ?>">
            <?php echo ショップ管理機能['通常営業時間']['機能名']; ?>
          </button>
        </div>
        <div class="item">
            <button class="menu-btn" name="menu-btn" value="<?php echo ショップ管理機能['SNS設定']['NO']; ?>">
              <?php echo ショップ管理機能['SNS設定']['機能名']; ?>
            </button>
        </div>
        <div class="item">
            <button class="menu-btn" name="menu-btn" value="<?php echo ショップ管理機能['基本情報画像設定']['NO']; ?>">
              <?php echo ショップ管理機能['基本情報画像設定']['機能名']; ?>
            </button>
        </div>
        <div class="item">
            <button class="menu-btn" name="menu-btn" value="<?php echo ショップ管理機能['支払方法']['NO']; ?>">
              <?php echo ショップ管理機能['支払方法']['機能名']; ?>
            </button>
        </div>
        <hr>
      </div>

    </form>

  <?php }elseif ($エラーコード == ERR種類['マスタ情報取得失敗']) { ?>
    <h2>マスタ情報取得失敗</h2>
  <?php } ?>
  <script>
    Sub_Get_News();
  </script>
</div>