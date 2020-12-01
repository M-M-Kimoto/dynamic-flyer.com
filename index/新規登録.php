
<?php 

  function upload(){

    $エラーコード = ERR種類['エラー無し'];
    $エラーチェック結果 = array();

    $cls_mstショップ = new MstShop_Ctrl;
    $Mstショップ_登録情報 = array();

    $cls_traステータス = new TraShopStatus_Ctrl();
    $Traステータス_登録情報 = array(); // sqlを作るための引数用

    /*
    $cls_mst表示ページ = new MstShopViewerPage_Ctrl;
    $Mst表示ページ_登録情報 = array(); // sqlを作るための引数用
    */

    $cls_tra通知 = new TraNotice_Ctrl;
    $tra通知_更新情報 = array();

    /* ショップマスタエラーチェック */
    
      // 区切り文字を統一するための置換
      $_POST['タグ'] = str_replace("　"," ",$_POST['タグ']);
      $_POST['タグ'] = trim($_POST['タグ']);
      $_POST['タグ'] = " " . $_POST['タグ'] . " ";

      $Mstショップ_登録情報 = array(
        'ID'=>$_POST['ショップID'],
        'パスワード'=>$_POST['パスワード'],
        '正式名称'=>$_POST['正式名称'],
        '略称'=>$_POST['略称'],
        'メッセージ'=>$_POST['メッセージ'],
        'ショップ種類コード'=>$_POST['ショップ種類コード'],
        '都道府県'=>$_POST['都道府県'],
        '市区町村'=>$_POST['市区町村'],
        '町名番地'=>$_POST['町名番地'],
        '建物等'=>$_POST['建物等'],
        'タグ'=>$_POST['タグ']
      );

      // エラーチェック
      $res_check = $cls_mstショップ->check($Mstショップ_登録情報);
      if ($res_check['status'] == false){
        $エラーコード = ERR種類['エラーチェック'];
        $エラーチェック結果 = array_merge($エラーチェック結果, $res_check["msg"]);
      }
    /* */

    /* ステータストランエラーチェック */
      // フラグ項目はチェック意外全て０とする
      if (isset($_POST['お気に入り']) == false){
        $_POST['お気に入り'] = '0';
      }elseif($_POST['お気に入り'] != '1'){
        $_POST['お気に入り'] = '0';
      }

      if (isset($_POST['連絡']) == false){
        $_POST['連絡'] = '0';
      }elseif($_POST['連絡'] != '1'){
        $_POST['連絡'] = '0';
      }

      if (isset($_POST['予約']) == false){
        $_POST['予約'] = '0';
      }elseif($_POST['予約'] != '1'){
        $_POST['予約'] = '0';
      }

      if (isset($_POST['採用活動']) == false){
        $_POST['採用活動'] = '0';
      }elseif($_POST['採用活動'] != '1'){
        $_POST['採用活動'] = '0';
      }

      if (isset($_POST['運営管理フラグ']) == false){
        $_POST['運営管理フラグ'] = '0';
      }elseif($_POST['運営管理フラグ'] != '1'){
        $_POST['運営管理フラグ'] = '0';
      }
      $Traステータス_登録情報 = array(
        'ショップID'=>$_POST['ショップID'],
        'リンク登録可能数'=>$_POST['リンク登録数'],
        'お気に入り機能'=>$_POST['お気に入り'],
        '連絡機能'=>$_POST['連絡'],
        '予約機能'=>$_POST['予約'],
        '採用活動機能'=>$_POST['採用活動'],
        '運営管理フラグ'=>$_POST['運営管理フラグ'],
        '登録ユーザID'=>$_SESSION['ID']
      );

      // エラーチェック
      $res_check = $cls_traステータス->check($Traステータス_登録情報);
      if ($res_check['status'] == false){
        $エラーコード = ERR種類['エラーチェック'];
        $エラーチェック結果 = array_merge($エラーチェック結果, $res_check["msg"]);
      }
    /* */

    /* 表示ページマスタエラーチェック */
        /*
      $Mst表示ページ_登録情報 = array(
        'ショップID'=>$_POST['ショップID'],
        'ID'=>1,
        '名称'=>'基本ページ',
        'URL'=>"https://" . $_SERVER["HTTP_HOST"] . '/?shopID=' . $_POST['ショップID'] . '&page=ショップ基本情報'
      );

      // エラーチェック
      $res_check = $cls_mst表示ページ->check($Mst表示ページ_登録情報);
      if ($res_check['status'] == false){
        $エラーコード = ERR種類['エラーチェック'];
        $エラーチェック結果 = array_merge($エラーチェック結果, $res_check["msg"]);
      }
      */
    /* */

    /* 通知トラン */
      /*
      $now =  new DateTime('now');

      $tra通知_更新情報 = array(
        'ショップID'=>$_POST['ショップID'],
        '開始日時'=> $now->format('Y-m-d H:i:s'),
        '終了日時'=> addmonth($now, 3)->format('Y-m-d H:i:s'),
        '通知区分コード'=>2,
        'メッセージ'=>rtrim($_POST['メッセージ']),
        '表示ページURL'=>"https://dynamic-flyer.com/?shopID=" . $_POST['ショップID'] . "&page=ショップ基本情報"
      );
      if($tra通知_更新情報['メッセージ'] == ""){
        $tra通知_更新情報['メッセージ'] = $Mstショップ_登録情報['市区町村'].'の'.$Mstショップ_登録情報['略称'].'です。';
      }
      */
    /* */

    if($エラーコード != ERR種類['エラー無し']){
        return array('エラーコード'=>ERR種類['エラーチェック'], 'エラーチェック結果'=>$エラーチェック結果);
    }

    /* トランザクション*/
      try{
        global $cls_dbCtrl;
        $cls_dbCtrl->begin_tran();

        $return_dateUpdIns = array();
        $return_dateUpdIns = data_Update_or_Insert($cls_mstショップ, $Mstショップ_登録情報);
        if($return_dateUpdIns['status'] == false){
          $cls_dbCtrl->rollback();
          return array('エラーコード'=>ERR種類['更新失敗'], 'エラーチェック結果'=>$エラーチェック結果);
        }
        $return_dateUpdIns = data_Update_or_Insert($cls_traステータス, $Traステータス_登録情報);
        if($return_dateUpdIns['status'] == false){
          $cls_dbCtrl->rollback();
          return array('エラーコード'=>ERR種類['更新失敗'], 'エラーチェック結果'=>$エラーチェック結果);
        }
        /*
        $return_dateUpdIns = data_Update_or_Insert($cls_mst表示ページ, $Mst表示ページ_登録情報);
        if($return_dateUpdIns['status'] == false){
          $cls_dbCtrl->rollback();
          return array('エラーコード'=>ERR種類['更新失敗'], 'エラーチェック結果'=>$エラーチェック結果);
        }
        */
        /*
        $return_dateUpdIns = data_Insert($cls_tra通知, $tra通知_更新情報);
        if($return_dateUpdIns['status'] == false){
          $cls_dbCtrl->rollback();
          return array('エラーコード'=>ERR種類['更新失敗'], 'エラーチェック結果'=>$エラーチェック結果);
        }
        */
        $cls_dbCtrl->commit();
      
      }catch ( Exception $ex ) {
        $cls_dbCtrl->rollback();
        return array('エラーコード'=>ERR種類['更新失敗'], 'エラーチェック結果'=>$エラーチェック結果);
      }
      // エラー無し
      return array('エラーコード'=>ERR種類['エラー無し'], 'エラーチェック結果'=>$エラーチェック結果);
    /*  */
  }

?>

<?php // 初期処理

   // ショップ管理画面で使用している定数をコピペ
  define("モードコード", array('入力'=>0,
                                '更新'=>1,
                                'エラー'=>2,
                                '削除' =>3,
                                '戻る'=>9
                              )
  );

  /* 変数初期化 */
  $モード = モードコード['入力'];

  $エラーコード = ERR種類['エラー無し'];
  $エラーチェック結果 = array();

  $ショップID_本処理採番 = '';
  
  if( isset($_POST['btn_Update']) ) {
    // エラーチェック処理に飛ばす
    $モード =  $_POST['btn_Update'];
  }
  
  if ($エラーコード == ERR種類['エラー無し'] && $モード == モードコード['更新']){

    if(!(isset($_FILES)&& isset($_FILES['画像ファイル']) && is_uploaded_file($_FILES['画像ファイル']['tmp_name']))){

      $エラーコード = ERR種類['ファイルアップロード失敗'];
      $エラーチェック結果['画像ファイル'] = 'トップ画像が選択されていません。';

    }else{

      // 更新処理実行
      $return = upload();
      $エラーコード = $return['エラーコード'];
      $エラーチェック結果 = $return['エラーチェック結果'];

      /* 正常処理完了でファイルアップロード処理開始 */
      if($エラーコード == ERR種類['エラー無し']){

        // 画像ファイルパスを作成
        $uploadPath = "./shop/" . $_POST['ショップID'] . "/main/img/"; //ドメイン以下

        // 画像アップロード処理クラスを呼ぶ
        $cls_imgUpload = new imgUpload();
        $result = $cls_imgUpload->top($_FILES['画像ファイル']['tmp_name'], pathinfo($_FILES['画像ファイル']['name'], PATHINFO_EXTENSION), $uploadPath);
        if($result == false){
          $エラーコード = ERR種類['ファイルアップロード失敗'];
        }
      }
    }
  }

?>

<?php
  // 使用する変数
  $cls_ショップ種類 = new MstShopKindCode_Ctrl();
  $Mstショップ種類_検索情報 = array(); // sqlを作るための引数用
  $ary_ショップ種類 = array(); // ログインショップIDのレコード

  // ショップIDを条件に検索
  $Sql_sel = $cls_ショップ種類->select($Mstショップ種類_検索情報);
  $res_select = $cls_dbCtrl->select($Sql_sel);
  if ($res_select["status"] == false){
    $エラーコード = ERR種類['マスタ情報取得失敗'];
  }else{
    // 取得レコードを変数へ
    $ary_ショップ種類 = $res_select["rows"];
  }

  if($エラーコード == ERR種類['エラー無し']){
    // ポストした内容も消す
    $_POST = array();
  }

?>

<?php

  // メッセージ
  $メッセージ = '';
  if ($エラーコード == ERR種類['エラー無し'] && $モード == モードコード['更新']) {
    $メッセージ = '更新しました。';
  }elseif ($エラーコード == ERR種類['更新失敗']) {
    $メッセージ = '更新に失敗しました。少し時間を開けてから、もう一度実行してください。';
    $メッセージ = $メッセージ.'<br>それでも解決しない場合は、運営まで御連絡ください。';
  }elseif ($エラーコード == ERR種類['マスタ情報取得失敗']) {
    $メッセージ = 'ショップ基本情報の取得に失敗しました。ページの更新をしてください。';
    $メッセージ = $メッセージ.'<br>それでも解決しない場合は、運営まで御連絡ください。';
  }elseif ($エラーコード == ERR種類['ファイルアップロード失敗']) {
    $メッセージ = '画像ファイルのアップロードに失敗しました。';
  }elseif ($エラーコード == ERR種類['エラーチェック']) {
    $メッセージ = '入力内容に誤りを検知しました。';
    $メッセージ = $メッセージ.'<br>内容を確認後、もう一度更新ボタンを押してください。';
  }
  echo '<h4 id="top-msg">'.$メッセージ.'</h4>';

?>


<script type="text/javascript" src="./js/previewImg.js"></script>
<script language="javascript" type="text/javascript">

  function onShopID_Change(obj){

    document.getElementById("shop_passwd").value = obj.value + "_3202";
    document.getElementById("item-ID-err").innerText = "";
    screenLock();
    $.ajax({
        type: 'post',
        url: "<?php echo comフォルダ; ?>" + 'bat_ショップ検索処理.php',
        data: {"userID":"<?php echo $_SESSION['ID']; ?>", "shopID": obj.value, "rangeCode":0},
        success : function(res){
            console.log("ajax通信に成功しました");
            console.log(res);

            // select結果のJSONを取得
            var jsonData = JSON.parse(res);
            
            // 取得結果によって分岐
            if(jsonData != ''){
              // ショップIDを条件に含めて検索した結果、取得出来た
              document.getElementById("item-ID-err").innerText = "既に使われています。";
            }

            screenUnLock();

        },
        error : function(XMLHttpRequest, textStatus, errorThrown) {
            console.log("ajax通信に失敗しました");
            console.log("XMLHttpRequest : " + XMLHttpRequest.status);
            console.log("textStatus     : " + textStatus);
            console.log("errorThrown    : " + errorThrown.message);


            document.getElementById("item-ID-err").innerText = '通信に失敗しました。時間を開けてからもう一度実行してください。';

            screenUnLock();
        }
    });
  }

</script>

<h4>新規登録</h4>
<hr>
<div class="content-item">

  <form class="insert" action="" method="post" enctype="multipart/form-data">

    <hr>
    <h4>ショップマスタ</h4>
    <hr>
    <div class="item" >
      <label class="itemLabel-primary" for="ショップID">ショップID</label>
        <?php
          $val = "";
          if($エラーコード != ERR種類['エラー無し']){
            // エラーがあった場合は入力内容を引き継ぐ
            $val = $_POST['ショップID'];
          }
        ?>
      <input type="text" class="text" id="ID" name="ショップID" value="<?php echo $val; ?>" onchange="onShopID_Change(this);">
      <br><label id="item-ID-err" class="err">
        <?php
          if (array_key_exists('ショップID', $エラーチェック結果)){
            echo $エラーチェック結果['ショップID'];
          }
          if (array_key_exists('ID', $エラーチェック結果)){
            echo $エラーチェック結果['ID'];
          }
        ?>
      </label>
      
    </div>

    <div class="item">
      <label class="itemLabel-primary" for="shop_passwd">パスワード</label>
      <?php
        $val = "";
        if($エラーコード != ERR種類['エラー無し']){
          // エラーがあった場合は入力内容を引き継ぐ
          $val = $_POST['パスワード'];
        }
      ?>
      <input type="passwd" class="text" id="shop_passwd" name="パスワード" maxlength="<?php echo 字数上限['パスワード']; ?>" placeholder="英数字記号、8文字以上" value="<?php echo $val; ?>">
      <br><label class="err">
      <?php
      if (array_key_exists('パスワード', $エラーチェック結果)){
        echo $エラーチェック結果['パスワード'];
      }
      ?>
      </label>
    </div>

    <div class="item">
      <label class="itemLabel-primary" for="画像ファイル">画像ファイル</label>
      <input type="file" class="file" id="file-img" name="画像ファイル" accept="image/*"
       onchange="OnFileSelect(this,document.getElementById('preview-img'));">
      <br>
      <label class="err">
        <?php
          if (array_key_exists('画像ファイル', $エラーチェック結果)){
            echo $エラーチェック結果['画像ファイル'];
          }
        ?>
      </label>
    </div>

    <div class="item">
      
      <label class="itemLabel-nomal" for="メッセージ">メッセージ</label>
      
        <?php
          $val = ''; //初期値
          if($エラーコード != ERR種類['エラー無し']){
            $val = $_POST['メッセージ'];
          }
        ?>
        <textarea class="text" id="メッセージ" name="メッセージ" rows="2" maxlength="<?php echo 字数上限['メッセージ']; ?>" placeholder="ここに記入してください" 
        onchange="MsgChange(this, document.getElementById('previewText'), document.getElementById('preview-text'));"><?php echo rtrim($val); ?></textarea>
      
      <br><label class="err">
        <?php
        if (array_key_exists('メッセージ', $エラーチェック結果)){
          echo $エラーチェック結果['メッセージ'];
        }
        ?>
      </label>
    
    </div>

  <div class="item" id="preview">
    <label class="itemLabel-noedit" for="imgFile">イメージ</label>
    <div class="ImgMsg" id="previewImg">
      <?php
        $val = "";
        if($エラーコード != ERR種類['エラー無し']){
          // エラーがあった場合は入力内容を引き継ぐ
          $val = $_FILES['画像ファイル']['tmp_name'];
        }
      ?>
      <img id="preview-img" src="<?php echo $val; ?>" />
      <div class="msg" id="previewText" style="display: none;">
        <p id="preview-text"></p>
      </div>
    </div>
  </div>

  <div class="item">
    <script>
      function onChange_seishikiName(obj){
        document.getElementById("ryaku-name").value = obj.value.trimEnd();
      }
    </script>
    <label class="itemLabel-primary" for="seishiki-name">正式名称</label>
    <?php
      $val = "";
      if($エラーコード != ERR種類['エラー無し']){
        // エラーがあった場合は入力内容を引き継ぐ
        $val = $_POST['正式名称'];
      }
    ?>
    <input type="text" class="text" id="seishiki-name" name="正式名称" value="<?php echo $val; ?>" onchange="onChange_seishikiName(this);">
    <br><label class="err">
    <?php
    if (array_key_exists('正式名称', $エラーチェック結果)){
      echo $エラーチェック結果['正式名称'];
    }
    ?>
    </label>
  </div>

  <div class="item">
    <label class="itemLabel-primary" for="ryaku-name">略称</label>
      <?php
        $val = "";
        if($エラーコード != ERR種類['エラー無し']){
          // エラーがあった場合は入力内容を引き継ぐ
          $val = $_POST['略称'];
        }
      ?>
      <input type="text" class="text" id="ryaku-name" name="略称" value="<?php echo $val; ?>">
      <br><label class="err">
      <?php
      if (array_key_exists('略称', $エラーチェック結果)){
        echo $エラーチェック結果['略称'];
      }
      ?></label>
    
  </div>

    <div class="item">
      
      <label class="itemLabel-primary" for="都道府県">都道府県</label>
      
      <?php
        $val = "大阪府";
        if($エラーコード != ERR種類['エラー無し']){
          // エラーがあった場合は入力内容を引き継ぐ
          $val = $_POST['都道府県'];
        }
      ?>
      <input type="text" class="text" name="都道府県" value="<?php echo $val; ?>">
      
      <br><label class="err">
      <?php
      if (array_key_exists('都道府県', $エラーチェック結果)){
        echo $エラーチェック結果['都道府県'];
      }
      ?></label>
    
    </div>

    <div class="item">
      
        <label class="itemLabel-primary" for="市区町村">市区町村</label>
        
        <?php
          $val = "";
          if($エラーコード != ERR種類['エラー無し']){
            // エラーがあった場合は入力内容を引き継ぐ
            $val = $_POST['市区町村'];
          }
        ?>
        <input type="text" class="text" name="市区町村" value="<?php echo $val; ?>">
        
        <br><label class="err">
        <?php
        if (array_key_exists('市区町村', $エラーチェック結果)){
          echo $エラーチェック結果['市区町村'];
        }
        ?></label>
      
    </div>
      
    <div class="item">
      
        <label class="itemLabel-nomal" for="tyoumeibanti">町名番地</label>
        
        <?php
          $val = "";
          if($エラーコード != ERR種類['エラー無し']){
            // エラーがあった場合は入力内容を引き継ぐ
            $val = $_POST['町名番地'];
          }
          echo '<input type="text" class="text" id="tyoumeibanti" name="町名番地" value="'.$val.'">';
        ?>
        
        <br><label class="err">
        <?php
        if (array_key_exists('町名番地', $エラーチェック結果)){
          echo $エラーチェック結果['町名番地'];
        }
        ?></label>
      
    </div>

    <div class="item">
        <label class="itemLabel-nomal" for="tatemononado">建物等</label>
        <?php
          $val = "";
          if($エラーコード != ERR種類['エラー無し']){
            // エラーがあった場合は入力内容を引き継ぐ
            $val = $_POST['建物等'];
          }
        ?>
        <input type="text" class="text" id="tatemononado" name="建物等" value="<?php echo $val; ?>">
        <br><label class="err">
        <?php
        if (array_key_exists('建物等', $エラーチェック結果)){
          echo $エラーチェック結果['建物等'];
        }
        ?></label>
      
    </div>
      
    <div class="item">
      <label class="itemLabel-nomal" for="タグ">タグ</label>
      <?php
        $val = "";
        if($エラーコード != ERR種類['エラー無し']){
          // エラーがあった場合は入力内容を引き継ぐ
          $val = $_POST['タグ'];
        }
      ?>
      <input type="text" class="text" name="タグ" value="<?php echo trim($val); ?>">
      <br><label class="explain">スペーズ区切りで複数ワード設定出来ます。</label>
      <br><label class="err">
      <?php
      if (array_key_exists('タグ', $エラーチェック結果)){
        echo $エラーチェック結果['タグ'];
      }
      ?></label>
    </div>

    <div class="item">
      <label class="itemLabel-primary" for="ショップ種類コード">ショップ種類</label>
        <select name="ショップ種類コード">
          <?php
            // 取得レコードを変数へ
            $初期値_ショップ種類コード="";
            $select_val = "";
            if($エラーコード != ERR種類['エラー無し']){
              // エラーがあった場合は入力内容を引き継ぐ
              $select_val = $_POST['ショップ種類コード'];
            }

            for ($idx = 0; $idx <= count($ary_ショップ種類) -1; $idx++){

              $row = $ary_ショップ種類[$idx];
              $selected = "";
              if($select_val == trim($row['ID']) || ($初期値_ショップ種類コード == "" && $idx == count($ary_ショップ種類) -1)){
                $selected = "selected";
                $初期値_ショップ種類コード = $row['ID'];
              }
              ?>
              <option value="<?php echo $row['ID']; ?>" <?php echo $selected; ?>><?php echo $row['名称']; ?></option>
            <?php } ?>
        </select>
        <br><label class="err">
          <?php
            if (array_key_exists('ショップ種類コード', $エラーチェック結果)){
              echo $エラーチェック結果['ショップ種類コード'];
            }
          ?>
        </label>
    </div>

    <hr>
    <h4>ショップステータス</h4>
    <hr>


    <div class="item">
      
      <label class="itemLabel-primary" for="リンク登録数">リンク登録数</label>
        <?php
          $val = "5";
          if($エラーコード != ERR種類['エラー無し']){
            // エラーがあった場合は入力内容を引き継ぐ
            $val = $_POST['リンク登録数'];
          }
        ?>
        <input type="number" class="number" name="リンク登録数" value="<?php echo $val; ?>">
        <br><label class="err">
        <?php
        if (array_key_exists('リンク登録数', $エラーチェック結果)){
          echo $エラーチェック結果['リンク登録数'];
        }
        ?></label>
      
    </div>

    <!--
    <div class="item">
      
      <label class="itemLabel-nomal" >有効機能</label>
      <div id="options">
        <div class="option">
          <?php

            if($エラーコード != ERR種類['エラー無し']){
              // エラーがあった場合は入力内容を引き継ぐ
              $val = $_POST['お気に入り'];
            }

            $お気に入り_初期値 = '';
            if ($val == true ){
              $お気に入り_初期値 = 'checked="checked"';
            }
          ?>
          <input type="checkbox" class="checkbox" name="お気に入り" value="1" <?php echo $お気に入り_初期値; ?>>
          <label class="check-for-lbl" for="お気に入り" >お気に入り</label>
        </div>
        <div class="option">
          <?php

            $連絡_初期値 = '';
            if($エラーコード != ERR種類['エラー無し']){
              // エラーがあった場合は入力内容を引き継ぐ
              if (array_key_exists('連絡', $_POST)){
                $連絡_初期値 = 'checked="checked"';
              }
            }

          ?>
          <input type="checkbox" class="checkbox" name="連絡" value="1" <?php echo $連絡_初期値; ?>>
          <label class="check-for-lbl" for="連絡" >連絡機能</label>
        </div>
        <div class="option">
          <?php
            $予約_初期値 = '';
            if($エラーコード != ERR種類['エラー無し']){
              // エラーがあった場合は入力内容を引き継ぐ
              if (array_key_exists('予約', $_POST)){
                $予約_初期値 = 'checked="checked"';
              }
            }
          ?>
          <input type="checkbox" class="checkbox" name="予約" value="1" <?php echo $予約_初期値; ?>>
          <label class="check-for-lbl" for="予約">予約機能</label>
        </div>
        <div class="option">
          <?php
            $採用活動_初期値 = '';
            if($エラーコード != ERR種類['エラー無し']){
              // エラーがあった場合は入力内容を引き継ぐ
              if (array_key_exists('採用活動', $_POST)){
                $採用活動_初期値 = 'checked="checked"';
              }
            }
          ?>
          <input type="checkbox" class="checkbox" name="採用活動" value="1" <?php echo $採用活動_初期値; ?>>
          <label class="check-for-lbl" for="採用活動">採用活動</label>
        </div>
      </div>
      <br><label class="err">
      <?php
      if (array_key_exists('お気に入り', $エラーチェック結果)){
        echo $エラーチェック結果['お気に入り'];
      }
      if (array_key_exists('連絡', $エラーチェック結果)){
        echo $エラーチェック結果['連絡'];
      }
      if (array_key_exists('予約', $エラーチェック結果)){
        echo $エラーチェック結果['予約'];
      }
      if (array_key_exists('採用活動', $エラーチェック結果)){
        echo $エラーチェック結果['採用活動'];
      }
      ?></label>
    
    </div>
    -->
    <hr >
    <div class="item" id="upd-bak-btn">
      <button id="btn_Update" name="btn_Update" value="<?php echo モードコード['更新']; ?>">更新</button>
      <button id="btn_back" name="btn_back" value="<?php echo モードコード['戻る']; ?>">戻る</button>
    </div>

  </form>

</div>
