
<?php 

  function update(){

    $エラーコード = ERR種類['エラー無し'];
    $エラーチェック結果 = array();

    $cls_tra紹介 = new TraShopRecom_Ctrl;
    $更新内容 = array();

    /* 配列へ */
      // 区切り文字を統一するための置換
      $_POST['タグ'] = str_replace("　"," ",$_POST['タグ']);
      $_POST['タグ'] = trim($_POST['タグ']);
      $_POST['タグ'] = " " . $_POST['タグ'] . " ";
      
      // 入力後の処理であれば、とりあえず連想配列を作成
      $更新内容 = array(
        'ショップID'=>$_SESSION['shop_id'],
        '掲載開始日時'=> $_POST['掲載開始日'].' '.$_POST['掲載開始時'].':'.$_POST['掲載開始分'].':00',
        '掲載終了日時'=> $_POST['掲載終了日'].' '.$_POST['掲載終了時'].':'.$_POST['掲載終了分'].':00',
        '区分'=>$_POST['区分'],
        '商品名'=>$_POST['商品名'],
        'キャッチコピー'=>$_POST['キャッチコピー'],
        '詳細'=>$_POST['詳細'],
        '値段'=>$_POST['値段'],
        '販売開始日時'=>$_POST['販売開始日'],
        '販売終了日時'=>$_POST['販売終了日'],
        '数量制限区分'=>$_POST['数量制限区分'],
        '販売数量'=>$_POST['販売数量'],
        '残数'=>$_POST['残数'],
        'リンクURL'=>$_POST['リンクURL'],
        'タグ'=>$_POST['タグ']
      );
      
    /*  */

    /* エラーチェック */
      // エラーチェック
      $res_check = $cls_tra紹介->check($更新内容);
      if ($res_check['status'] == false){
        $エラーコード = ERR種類['エラーチェック'];
        $エラーチェック結果 = $res_check['msg'];
      }
    /* */

    if($エラーコード != ERR種類['エラー無し']){
        // 引っ掛かった場合
        return array('エラーコード'=>ERR種類['エラーチェック'], 'エラーチェック結果'=>$エラーチェック結果);
    }

    /* トランザクション*/
      try{
        global $cls_dbCtrl;
        $cls_dbCtrl->begin_tran();

        $return_dateUpdIns = array();
        $return_dateUpdIns = data_Update_or_Insert($cls_tra紹介, $更新内容);
        
        if($return_dateUpdIns['status'] == false){
          $cls_dbCtrl->rollback();
          return array('エラーコード'=>ERR種類['更新失敗'], 'エラーチェック結果'=>$エラーチェック結果);
        }

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

<?php

   
  /* 変数初期化 */
  $モード = モードコード['入力'];

  $エラーコード = ERR種類['エラー無し'];
  $エラーチェック結果 = array(); 

  $Mstショップ_検索条件 = array(); 

  if( isset($_POST['btn_Update']) ) {
    $モード =  $_POST['btn_Update'];
  }elseif( isset($_POST['btn_back']) ) {
    $モード =  $_POST['btn_back'];
  }

  if( $モード == モードコード['戻る'] ) {
    $_POST['menu-btn'] = ショップ管理機能['メニュー']['NO'];
    header("Location: index.php");
    exit ;

  }elseif ($モード == モードコード['更新']){

    // 更新処理実行
    $return = update();
    $エラーコード = $return['エラーコード'];
    $エラーチェック結果 = $return['エラーチェック結果'];

    /* 正常処理完了でファイルアップロード処理開始 */
    if($エラーコード == ERR種類['エラー無し']){     
      /*
      *
      * ショップトップ画像のアップロード
      *
      */
      if(isset($_FILES)&& isset($_FILES['画像ファイル']) && is_uploaded_file($_FILES['画像ファイル']['tmp_name'])){
        
        // 画像ファイルパスを作成
        $uploadPath = ショップRoute . $_SESSION['shop_id'] . "/recommended/img/"; 

        // 画像アップロード処理クラスを呼ぶ
        $cls_imgUpload = new imgUpload();
        // 仕様として基本情報のtopと同じでいい
        $result = $cls_imgUpload->top($_FILES['画像ファイル']['tmp_name'], pathinfo($_FILES['画像ファイル']['name'], PATHINFO_EXTENSION), $uploadPath);
        if($result == false){
          $エラーコード = ERR種類['ファイルアップロード失敗'];
        }

        clearstatcache(); 
      }
    }
  }
  /* 最後に必ずマスタ最新値を取得し直す */
    $cls_tra紹介 = new TraShopRecom_Ctrl();
    $ログインショップ情報 = array(); 
    $tra紹介_検索条件= array(
      'ID'=>$index['ショップID']
    );

    // ショップIDとパスワードを条件に検索
    $sql_select = $cls_tra紹介->select_01($tra紹介_検索条件);
    $res_select = $cls_dbCtrl->select($sql_select);
    if ($res_select["status"] == false){
      $エラーコード = ERR種類['マスタ情報取得失敗'];

    }elseif ($res_select["count"] < 1){
      
      // 入力後の処理であれば、とりあえず連想配列を作成
      $ログインショップ情報 = array(
        '掲載開始日'=>"",
        '掲載開始時'=>"",
        '掲載開始分'=>"",
        '掲載終了日'=>"",
        '掲載終了時'=>"",
        '掲載終了分'=>"",
        '区分'=>0,
        '商品名'=>"",
        'キャッチコピー'=>"",
        '詳細'=>"",
        '値段'=>"",
        '販売開始日'=>"",
        '販売終了日'=>"",
        '数量制限区分'=>"0",
        '販売数量'=>"",
        '残数'=>"",
        'リンクURL'=>"",
        'タグ'=>""
      );

    }else{
      // 取得レコードを変数へ
      $ログインショップ情報 = $res_select["rows"][0];
      
    }
  /* */

  if($エラーコード == ERR種類['エラー無し']){

    // ポストした内容も消す
    $_POST = array();

  }

  clearstatcache(); 
?>
<link rel="stylesheet" href="./stylesheets/<?php echo $index['Me']['ファイル名']; ?>.css">

<?php
  // メッセージ
  $メッセージ = '';
  if ($エラーコード == ERR種類['エラー無し'] && $モード == モードコード['更新']) {
    $メッセージ = '更新しました。';
  }else{
    $メッセージ = ERRメッセージ[$エラーコード];
  }
?>
<h4 id="top-msg"><?php echo $メッセージ; ?></h4>

<script type="text/javascript" src="<?php echo Route; ?>/js/previewImg.js"></script>
<script language="javascript" type="text/javascript">

  var window_A;
  function urlClick(){
    if (document.getElementById('URL').value == ""){
      return ;
    }
    window_A = window.open(document.getElementById('URL').value, "google");
  }

  function suKBN_Change(selectKBN){
    /*
    var selectKBN = obj.value;
    */
    if(selectKBN == "0"){
      document.getElementById("label-販売数量").setAttribute("class", "itemLabel-noedit") ;
      document.getElementById("label-残数").setAttribute("class", "itemLabel-noedit") ;
      document.getElementById("販売数量").readOnly  = true ;
      document.getElementById("残数").readOnly  = true ;

      document.getElementById("販売数量").value = '';
      document.getElementById("残数").value = '';

      return ;

    }else{
      document.getElementById("label-販売数量").setAttribute("class", "itemLabel-primary") ;
      document.getElementById("label-残数").setAttribute("class", "itemLabel-nomal") ;
      document.getElementById("販売数量").readOnly  = false ;
      document.getElementById("残数").readOnly  = false ;

      if(document.getElementById("販売数量").value == "" && document.getElementById("残数").value == ""){
        document.getElementById("販売数量").value = '<?php echo $ログインショップ情報['販売数量']; ?>';
        document.getElementById("残数").value = '<?php echo $ログインショップ情報['残数']; ?>';
      }
    }
    
  }

</script>

<div class="content-item">

<form class="insert" action="" method="post" enctype="multipart/form-data">
  <?php // 隠し項目に自分のメニュー番号を ?>
  <input type="hidden" name="menu-btn" value="<?php echo $index['Me']['NO']; ?>" />

  <?PHP /* キー情報 */ ?>

    <div class="item" >
      <label class="itemLabel-primary" >区分</label>
    
      <?php
        $val = '0'; //初期値
        if($エラーコード != ERR種類['エラー無し']){
          $val = $_POST['区分'];
        }else{
          $val = $ログインショップ情報['区分'];
        }
        $区分0 = "";
        $区分1 = "";
        if($val == 0){
          $区分0 = "checked";
        }else{
          $区分1 = "checked";
        }

      ?>
      <div class="kind">
        <input type="radio" class="radio" id="section1" name="区分" value="0" <?php echo $区分0; ?>>
        <label class="radio-for-lbl" for="section1">おすすめ</label>
      </div>
      <div class="kind">
        <input type="radio" class="radio" id="section2" name="区分" value="1" <?php echo $区分1; ?>>
        <label class="radio-for-lbl" for="section2">新商品</label>
      </div>
      <label class="err">
      <?php
        if (array_key_exists('区分', $エラーチェック結果)){
          echo $エラーチェック結果['区分'];
        }
      ?>
      </label>
    </div>

    <div class="item">
      
        <label class="itemLabel-primary" >掲載開始日時</label>
      
        <?php
          $掲載開始日 = ''; //初期値
          $掲載開始時 = ''; //初期値
          $掲載開始分 = ''; //初期値
          if($エラーコード != ERR種類['エラー無し']){
            $掲載開始日 = $_POST['掲載開始日'];
            $掲載開始時 = $_POST['掲載開始時'];
            $掲載開始分 = $_POST['掲載開始分'];
          }else{
            $掲載開始日 = $ログインショップ情報['掲載開始日'];
            $掲載開始時 = $ログインショップ情報['掲載開始時'];
            $掲載開始分 = $ログインショップ情報['掲載開始分'];
          }
        ?>
        <input type="date" class="date" id="掲載開始日" name="掲載開始日" value="<?php echo $掲載開始日; ?>">
        <input type="number" class="number-time" id="掲載開始時" name="掲載開始時" min="0" max="23" value="<?php echo $掲載開始時 ?>" >
        <label class="tillde">：</label>
        <input type="number" class="number-time" id="掲載開始分" name="掲載開始分" min="0" max="59" value="<?php echo $掲載開始分 ?>" >
        <br><label class="annotation-lbl">※登録日から1ヶ月以内の日時を指定可能。</label>
        <br><label class="err">
          <?php
            if (array_key_exists('掲載開始日時', $エラーチェック結果)){
              echo $エラーチェック結果['掲載開始日時'];
            }
          ?>
        </label>
      
    </div>

    <div class="item">
      
        <label class="itemLabel-primary" for="掲載終了日">掲載終了日時</label>
        
          <?php
            $掲載終了日 = ''; //初期値
            $掲載終了時 = ''; //初期値
            $掲載終了分 = ''; //初期値
            if($エラーコード != ERR種類['エラー無し']){
              $掲載終了日 = $_POST['掲載終了日'];
              $掲載終了時 = $_POST['掲載終了時'];
              $掲載終了分 = $_POST['掲載終了分'];
            }else{
              $掲載終了日 = $ログインショップ情報['掲載終了日'];
              $掲載終了時 = $ログインショップ情報['掲載終了時'];
              $掲載終了分 = $ログインショップ情報['掲載終了分'];
            }
          ?>
          <input type="date" class="date" name="掲載終了日" value="<?php echo $掲載終了日; ?>">
          <input type="number" class="number-time" name="掲載終了時" min="0" max="23" value="<?php echo $掲載終了時; ?>" >
          <label class="tillde">：</label>
          <input type="number" class="number-time" name="掲載終了分" min="0" max="59" value="<?php echo $掲載終了分; ?>" >
        
        <br><label class="annotation-lbl">※掲載開始日時から1ヶ月以内の日時を指定可能。</label>
        <br><label class="err">
          <?php
            if (array_key_exists('掲載終了日時', $エラーチェック結果)){
              echo $エラーチェック結果['掲載終了日時'];
            }
          ?>
        </label>
      
    </div>
    <br>
    <div class="item" id="upd-bak-btn">
      <button id="btn_Update" name="btn_Update" value="<?PHP echo モードコード['更新']; ?>">更新</button>
      <button id="btn_back" name="btn_back" value="<?php echo モードコード['戻る']; ?>">戻る</button>
    </div>
  <?PHP /*  */ ?>
  

  <hr style="width: 80%;" ><?PHP /* 商品・サービス情報 */ ?>

    <div class="item">
        <label class="itemLabel-primary" for="商品名">商品名</label>
        <?php
          $val = ''; //初期値
          if($エラーコード != ERR種類['エラー無し']){
            $val = $_POST['商品名'];
          }else{
            $val = $ログインショップ情報['商品名'];
          }
        ?>
        <input type="text" class="text" id="商品名" name="商品名" maxlength="<?php echo 字数上限['メッセージ']; ?>" value="<?php echo rtrim($val); ?>"
        onchange="MsgChange(this, document.getElementById('previewText'), document.getElementById('preview-text'));" />
        
        <br><label class="err">
          <?php
          if (array_key_exists('商品名', $エラーチェック結果)){
            echo $エラーチェック結果['商品名'];
          }
          ?>
        </label>
      
    </div>

    <div class="item">
      <label class="itemLabel-primary" for="画像ファイル">トップ/サムネ画像</label>
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

    <div class="item" id="preview">
      <label class="itemLabel-noedit" for="imgFile">イメージ</label>
      <div class="ImgMsg" id="previewImg">
        <img id="preview-img" src="<?php echo ショップRoute . $_SESSION['shop_id']; ?>/recommended/img/top.jpg?<?php echo date("YmdHis");?>" />
        <div class="msg" id="previewText" style="display: none;">
          <p id="preview-text"></p>
        </div>
      </div>
    </div>

    <div class="item">
      
      <label class="itemLabel-nomal" for="キャッチコピー">キャッチコピー</label>
        <?php
          $val = "";
          if($エラーコード != ERR種類['エラー無し']){
            // エラーがあった場合は入力内容を引き継ぐ
            $val = $_POST['キャッチコピー'];
          }else{
            $val = $ログインショップ情報['キャッチコピー'];
          }
        ?>
        <input type="text" class="text" name="キャッチコピー" value="<?php echo $val?>" />        
        <br><label class="err">
        <?php
        if (array_key_exists('キャッチコピー', $エラーチェック結果)){
          echo $エラーチェック結果['キャッチコピー'];
        }
        ?></label>
      
    </div>

    <div class="item">
      
      <label class="itemLabel-nomal" for="詳細">詳細</label>
        <?php
          $val = "";
          if($エラーコード != ERR種類['エラー無し']){
            // エラーがあった場合は入力内容を引き継ぐ
            $val = $_POST['詳細'];
          }else{
            $val = $ログインショップ情報['詳細'];
          }
        ?>
        <textarea class="text" name="詳細" ><?php echo $val?></textarea>
        
        <br><label class="err">
        <?php
        if (array_key_exists('詳細', $エラーチェック結果)){
          echo $エラーチェック結果['詳細'];
        }
        ?></label>
      
    </div>

  <?php /*  */ ?>

  <hr style="width: 80%;"  ><?PHP /* 詳細情報 */ ?>

    <div class="item">
      <label class="itemLabel-primary" for="販売開始日">販売開始日</label>
        <?php
          $val = ''; //初期値
          if($エラーコード != ERR種類['エラー無し']){
            $val = $_POST['販売開始日'];
          }else{
            $val = $ログインショップ情報['販売開始日'];
          }

          if($val == ""){
            $val = date("Y-m-d");
          }
        ?>
      <input type="date" class="date" name="販売開始日" value="<?php echo $val; ?>">
      <br><label class="annotation-lbl">※登録日から3ヶ月以内の日時を指定可能。</label>  
      <br><label class="err">
        <?php
          if (array_key_exists('販売開始日時', $エラーチェック結果)){
            echo $エラーチェック結果['販売開始日時'];
          }
        ?>
      </label>
    </div>
    <div class="item">
      <label class="itemLabel-nomal" for="販売終了日">販売終了日</label>
        <?php
          $val = ''; //初期値
          if($エラーコード != ERR種類['エラー無し']){
            $val = $_POST['販売終了日'];
          }else{
            $val = $ログインショップ情報['販売終了日'];
          }
        ?>
      <input type="date" class="date" name="販売終了日" value="<?php echo $val; ?>">
      <br><label class="annotation-lbl">※販売開始日から6ヶ月以内の日時を指定可能。</label>
      <br><label class="err">
        <?php
          if (array_key_exists('販売終了日時', $エラーチェック結果)){
            echo $エラーチェック結果['販売終了日時'];
          }
        ?>
      </label>
    </div>

    <div class="item">
      <label class="itemLabel-nomal" for="値段">値段</label>
        <?php
          $val = ''; //初期値
          if($エラーコード != ERR種類['エラー無し']){
            $val = $_POST['値段'];
          }else{
            $val = $ログインショップ情報['値段'];
          }
        ?>
      <input type="number" class="number" name="値段" value="<?php echo $val; ?>">
        
      <br><label class="err">
        <?php
          if (array_key_exists('値段', $エラーチェック結果)){
            echo $エラーチェック結果['値段'];
          }
        ?>
      </label>
    </div>

    <div class="item" >
      <label class="itemLabel-primary" >数量制限区分</label>
      
      <?php
        $val = '0'; //初期値
        if($エラーコード != ERR種類['エラー無し']){
          $val = $_POST['数量制限区分'];
        }else{
          $val = $ログインショップ情報['数量制限区分'];
        }
        $数量制限区分val = $val;
        $区分0 = "";
        $区分1 = "";
        $区分2 = "";
        if($val == 0){
          $区分0 = "checked";
        }elseif($val == 1){
          $区分1 = "checked";
        }elseif($val == 2){
          $区分2 = "checked"; 
        }

      ?>
      <div class="kind">
        <input type="radio" class="radio" name="数量制限区分" value="0" <?php echo $区分0; ?> onClick="suKBN_Change(0);" />
        <label class="radio-for-lbl" for="数量制限区分">設定無</label>
      </div>
      <div class="kind">
        <input type="radio" class="radio" name="数量制限区分" value="1" <?php echo $区分1; ?> onClick="suKBN_Change(1);" />
        <label class="radio-for-lbl" for="数量制限区分">全数</label>
      </div>
      <div class="kind">
        <input type="radio" class="radio" name="数量制限区分" value="2" <?php echo $区分2; ?> onClick="suKBN_Change(2);" />
        <label class="radio-for-lbl" for="数量制限区分">１日</label>
      </div>
      <label class="err">
      <?php
        if (array_key_exists('数量制限区分', $エラーチェック結果)){
          echo $エラーチェック結果['数量制限区分'];
        }
      ?>
      </label>
    </div>

    <div class="item">
      <label class="itemLabel-noedit" id="label-販売数量" for="販売数量">販売数量</label>
      <?php
        $val = "";
        if($エラーコード != ERR種類['エラー無し']){
          // エラーがあった場合は入力内容を引き継ぐ
          $val = $_POST['販売数量'];
        }else{
          $val = $ログインショップ情報['販売数量'];
        }
        ?>
      <input type="text" class="text" id="販売数量" name="販売数量" value="<?php echo $val; ?>" />
      
      <br><label class="err">
      <?php
      if (array_key_exists('販売数量', $エラーチェック結果)){
        echo $エラーチェック結果['販売数量'];
      }
      ?></label>
      
    </div>

    <div class="item">
      <label class="itemLabel-noedit" id="label-残数" for="残数">残数</label>
      <?php
        $val = "";
        if($エラーコード != ERR種類['エラー無し']){
          // エラーがあった場合は入力内容を引き継ぐ
          $val = $_POST['残数'];
        }else{
          $val = $ログインショップ情報['残数'];
        }
        ?>
      <input type="text" class="text" id="残数" name="残数" value="<?php echo $val; ?>" />
      
      <br><label class="err">
      <?php
      if (array_key_exists('残数', $エラーチェック結果)){
        echo $エラーチェック結果['残数'];
      }
      ?></label>
      
    </div>

    <hr style="width: 80%;"  ><?PHP /* 詳細情報 */ ?>

      <div class="item">      
        <label class="itemLabel-nomal" for="リンクURL">リンクURL</label>
        <?php
          $val = "";
          if($エラーコード != ERR種類['エラー無し']){
            // エラーがあった場合は入力内容を引き継ぐ
            $val = $_POST['リンクURL'];
          }else{
            $val = $ログインショップ情報['リンクURL'];
          }
        ?>
        <input type="text" class="text" id="URL" name="リンクURL" value="<?php echo $val; ?>" placeholder="<?php echo "https://" . $_SERVER["HTTP_HOST"] . "/?shopID=" . $index['ショップID'] . "&page=商品紹介"; ?>">
        <br><label class="explain">未入力で商品紹介ページが設定されます。</label>
        <button type="button" id="url-btn" onClick="urlClick();">URLを開く</button>
        <br><label class="err">
        <?php
        if (array_key_exists('リンクURL', $エラーチェック結果)){
          echo $エラーチェック結果['リンクURL'];
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
          }else{
            $val = $ログインショップ情報['タグ'];
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

    <?php /*  */ ?>
  <?php /*  */ ?>

  <br>
  <div class="item" id="upd-bak-btn">
    <button id="btn_Update" name="btn_Update" value="<?PHP echo モードコード['更新']; ?>">更新</button>
    <button id="btn_back" name="btn_back" value="<?php echo モードコード['戻る']; ?>">戻る</button>
  </div>
  
</form>
</div>
<script>
  MsgChange(document.getElementById('商品名'), document.getElementById('previewText'), document.getElementById('preview-text'));
  suKBN_Change(<?php echo $数量制限区分val; ?>);
</script>