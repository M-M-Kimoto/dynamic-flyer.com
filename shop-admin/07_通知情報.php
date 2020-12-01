
<?php 

  function update(){

    $エラーコード = ERR種類['エラー無し'];
    $エラーチェック結果 = array();

    $cls_tra通知 = new TraNotice_Ctrl;
    $更新内容 = array();

    /* 配列へ */
      $更新内容 = array(
        'ショップID'=>$_SESSION['shop_id'],
        '開始日時'=> $_POST['開始日'].' '.$_POST['開始時'].':'.$_POST['開始分'].':00',
        '終了日時'=> $_POST['終了日'].' '.$_POST['終了時'].':'.$_POST['終了分'].':00',
        '通知区分コード'=>$_POST['区分コード'],
        'メッセージ'=>$_POST['メッセージ'],
        '表示ページURL'=>$_POST['URL']
      );
      if($更新内容['表示ページURL'] == ""){
        $更新内容['表示ページURL'] = "https://" . $_SERVER["HTTP_HOST"] . "/?shopID=" . $更新内容['ショップID'] . "&page=ショップ基本情報";
      }

    /*  */

    /* エラーチェック */
      $res_check = $cls_tra通知->check($更新内容); 
      if ($res_check['status'] == false){
        $エラーコード = ERR種類['エラーチェック'];
        $エラーチェック結果 = $res_check["msg"];

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
        $return_dateUpdIns = data_Insert($cls_tra通知, $更新内容);
        
        if($return_dateUpdIns['status'] == false){
          $cls_dbCtrl->rollback();
          return array('エラーコード'=>ERR種類['更新失敗'], 'エラーチェック結果'=>$エラーチェック結果);
        }
        
        $sql_ins = $cls_tra通知->insert_his($更新内容, 0);
        $res_ins = $cls_dbCtrl->insert($sql_ins);
        if($res_ins['status'] == false){
          $エラーコード = ERR種類['削除失敗'];
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

  function delete(){

    $エラーコード = ERR種類['エラー無し'];
    $エラーチェック結果 = array();
    
    $cls_tra通知 = new TraNotice_Ctrl;

    /* トランザクション*/
      try{
        global $cls_dbCtrl;
        $cls_dbCtrl->begin_tran();
          
        for ($seq = 1; $seq <= $_POST['reg_MaxSeq']; $seq++){

          if (isset($_POST['reg_削除'.$seq]) == false){
            continue;
          }elseif($_POST['reg_削除'.$seq] != '1'){
            continue;
          }
          $削除内容 = array(
            'ショップID'=>$_SESSION['shop_id'],
            '通知区分コード'=>$_POST['reg_通知区分'.$seq],
            '開始日時'=>$_POST['reg_開始日時'.$seq],
            '終了日時'=>$_POST['reg_終了日時'.$seq],
            'メッセージ'=>$_POST['reg_メッセージ'.$seq],
            '表示ページURL'=>$_POST['reg_表示ページURL'.$seq]
          );

          // エラー無しで更新処理実行

          // 削除前に履歴に登録
          $sql_del = $cls_tra通知->delete($削除内容);
          $res_delete = $cls_dbCtrl->delete($sql_del);
          if($res_delete['status'] == false){
            $エラーコード = ERR種類['削除失敗'];
          }
        
          $sql_ins = $cls_tra通知->insert_his($削除内容, 1);
          $res_ins = $cls_dbCtrl->insert($sql_ins);
          if($res_ins['status'] == false){
            $エラーコード = ERR種類['削除失敗'];
          }
        }

        if($エラーコード != ERR種類['エラー無し']){
          $cls_dbCtrl->rollback();
          return array('エラーコード'=>$エラーコード, 'エラーチェック結果'=>$エラーチェック結果);
        }else{
          $cls_dbCtrl->commit();
        }

      }catch ( Exception $ex ) {
        $cls_dbCtrl->rollback();
        return array('エラーコード'=>$エラーコード, 'エラーチェック結果'=>$エラーチェック結果);
      }
    /*  */

    // エラー無し
    return array('エラーコード'=>ERR種類['エラー無し'], 'エラーチェック結果'=>$エラーチェック結果);

  }

?>
<?php

   
  define('通知登録上限', 5);

  /* 変数初期化 */
  $エラーコード = ERR種類['エラー無し'];
  $モード = モードコード['入力'];
  $エラーチェック結果= array();

  // 遷移内容について
  if( isset($_POST['btn_Update']) ) {
    // エラーチェック処理に飛ばす
    $モード =  $_POST['btn_Update'];
  }elseif( isset($_POST['btn_back']) ) {
    // エラーチェック処理に飛ばす
    $モード =  $_POST['btn_back'];
  }elseif( isset($_POST['btn_delete']) ) {
    // エラーチェック処理に飛ばす
    $モード =  $_POST['btn_delete'];
  }


  if( $モード == モードコード['戻る'] ) {

    $_POST['menu-btn'] = ショップ管理機能['メニュー']['NO'];
    header("Location: index.php");
    exit ;

  }elseif ($モード == モードコード['削除']){

    $return = delete();
    $エラーコード = $return['エラーコード'];
    $エラーチェック結果 = $return['エラーチェック結果'];

  }elseif ($モード == モードコード['更新']){
    
    // 更新処理実行
    $return = update();
    $エラーコード = $return['エラーコード'];
    $エラーチェック結果 = $return['エラーチェック結果'];
  }

  /* tra_取得処理 */
    $cls_tra通知 = new TraNotice_Ctrl;
    $tra通知_検索条件 = array(); // sqlを作るための引数用
    $ary_tra通知 = array(); 

    // ショップIDを条件に検索
    $現在日時 = new DateTime('now');
    $tra通知_検索条件 = array(
      'ショップID'=>$_SESSION['shop_id'],
      '終了日時'=>$現在日時->format('Y-m-d')
      /*
      '終了日時'=>$現在日時->format('Y-m-d H:i:s')
      */
    );
    $sql_select = $cls_tra通知->select($tra通知_検索条件);
    $res_select = $cls_dbCtrl->select($sql_select);
    if ($res_select["status"] == false){
      $エラーコード = ERR種類['マスタ情報取得失敗'];

    }else{
      // 取得レコードを変数へ
      $ary_tra通知 = $res_select["rows"];
    }
  /*  */

  if($エラーコード == ERR種類['エラー無し']){

    // ポストした内容も消す
    $_POST = array();

  }
  clearstatcache(); 

?>

<link rel="stylesheet" href="./stylesheets/<?php echo $index['Me']['ファイル名']; ?>.css">

<script language="javascript" type="text/javascript">

  var window_A;
  function urlClick(){
    if (document.getElementById('URL').value == ""){
      return ;
    }
    window_A = window.open(document.getElementById('URL').value, "google");
  }

</script>

<?php
  // メッセージ
  $メッセージ = '';
  if ($エラーコード == ERR種類['エラー無し'] && $モード == モードコード['更新']) {
    $メッセージ = '更新しました。';
  }elseif ($エラーコード == ERR種類['エラーチェック']) {
      $メッセージ = ERRメッセージ[$エラーコード];
  }elseif ($エラーコード == ERR種類['更新失敗']) {
    $メッセージ = ERRメッセージ[$エラーコード];
  }
?>
<h4 id="top-msg"><?php echo $メッセージ; ?></h4>

<div class="content-item">
<form class="insert" action="" method="post">
  <input type="hidden" name="menu-btn" value="<?php echo $index['Me']['NO']; ?>" />
  <div class="item" id="code">
    
      <label class="itemLabel-primary" >区分</label>
      
        <div class="kind">
          <input type="radio" class="radio" id="section1" name="区分コード" value="1">
          <label class="radio-for-lbl" for="section1">ショップお気に入りユーザ</label>
        </div>
        <div class="kind">
          <input type="radio" class="radio" id="section2" name="区分コード" value="2" checked="checked">
          <label class="radio-for-lbl" for="section2">全ユーザへ通知</label>
        </div>
      
      <br><label class="err">
      <?php
        if (array_key_exists('区分コード', $エラーチェック結果)){
          echo $エラーチェック結果['区分コード'];
        }
      ?>
      </label>
    
  </div>

  <div class="item">
    
      <label class="itemLabel-primary" for="開始日">表示開始日時</label>
      
        <?php
          $開始日 = ''; //初期値
          $開始時 = ''; //初期値
          $開始分 = ''; //初期値
          if($エラーコード != ERR種類['エラー無し']){
            $開始日 = $_POST['開始日'];
            $開始時 = $_POST['開始時'];
            $開始分 = $_POST['開始分'];
          }
        ?>
        <input type="date" class="date" id="開始日" name="開始日" value="<?php echo $開始日; ?>">
        <input type="number" class="number-time" id="開始時" name="開始時" min="0" max="23" value="<?php echo $開始時 ?>" >
        <label class="tillde">：</label>
        <input type="number" class="number-time" id="開始分" name="開始分" min="0" max="59" value="<?php echo $開始分 ?>" >
      
      <br><label class="err">
        <?php
          if (array_key_exists('開始日時', $エラーチェック結果)){
            echo $エラーチェック結果['開始日時'];
          }
        ?>
      </label>
    
  </div>

  <div class="item">
    
      <label class="itemLabel-primary" for="終了日">表示終了日時</label>
      
        <?php
          $終了日 = ''; //初期値
          $終了時 = ''; //初期値
          $終了分 = ''; //初期値
          if($エラーコード != ERR種類['エラー無し']){
            $終了日 = $_POST['終了日'];
            $終了時 = $_POST['終了時'];
            $終了分 = $_POST['終了分'];
          }
        ?>
        <input type="date" class="date" id="終了日" name="終了日" value="<?php echo $終了日; ?>">
        <input type="number" class="number-time" id="終了時" name="終了時" min="0" max="23" value="<?php echo $終了時; ?>" >
        <label class="tillde">：</label>
        <input type="number" class="number-time" id="終了分" name="終了分" min="0" max="59" value="<?php echo $終了分; ?>" >
      
      <br><label class="annotation-lbl">※開始日から1ヶ月以内の日時を指定可能。</label>
      <br><label class="err">
        <?php
          if (array_key_exists('終了日時', $エラーチェック結果)){
            echo $エラーチェック結果['終了日時'];
          }
        ?>
      </label>
    
  </div>

  <div class="item">
    
      <label class="itemLabel-primary" for="メッセージ">メッセージ</label>
      
        <?php
          $メッセージ = ''; //初期値
          if($エラーコード != ERR種類['エラー無し']){
            $メッセージ = $_POST['メッセージ'];
          }
        ?>
        <textarea class="text" id="メッセージ" name="メッセージ" rows="2" placeholder="ここに記入してください" ><?php echo $メッセージ; ?></textarea>
      
      <br><label class="err">
        <?php
        if (array_key_exists('メッセージ', $エラーチェック結果)){
          echo $エラーチェック結果['メッセージ'];
        }
        ?>
      </label>
    
  </div>

  <div class="item">
        <label class="itemLabel-nomal" id="label-URL" for="URL">URL</label>
        <?php
          $URL = "";
          if($エラーコード != ERR種類['エラー無し']){
            $URL = $_POST['URL'];
          }
        ?>
        <input type="text" id="URL" class="text" name="URL" value="<?php echo $URL; ?>" placeholder="<?php echo "https://" . $_SERVER["HTTP_HOST"] . "/?shopID=" . $index['ショップID'] . "&page=ショップ基本情報"; ?>">
        <br><label class="explain">未入力でショップ基本情報ページが設定されます。</label>
        <button type="button" id="url-btn" onClick="urlClick();">URLを開く</button>
        <br><label class="err">
          <?php
          if (array_key_exists('URL', $エラーチェック結果)){
            echo $エラーチェック結果['URL'];
          }
          ?>
        </label>
    </div>


  <div class="item" id="upd-bak-btn">
    <?php

      if(通知登録上限 <= count($ary_tra通知)){
        echo '※登録上限数に達しています。';
      }else{
        echo '<button id="btn_Update" name="btn_Update" value="'.モードコード['更新'].'" >更新</button>';
        echo '<button id="btn_back" name="btn_back" value="'.モードコード['戻る'].'">戻る</button>';
      }
    ?>
  </div>
  </form>

  <hr>
  <label class="title-lbl" id="sub-title-lbl">設定済み一覧</label>

  <?php

    // メッセージ
    $メッセージ = '';
    if ($エラーコード == ERR種類['エラー無し'] && $モード == モードコード['削除']) {
      $メッセージ = '削除しました。';
    }elseif ($エラーコード == ERR種類['削除失敗']) {
      $メッセージ = ERRメッセージ[$エラーコード];
    }elseif ($エラーコード == ERR種類['マスタ情報取得失敗']) {
      $メッセージ = ERRメッセージ[$エラーコード];
    }
  ?>
  <br><br><label id="top-msg"><?php echo $メッセージ; ?></label>

  <form class="delete" action="" method="post">
    <?php // 隠し項目に自分のメニュー番号を 
      echo '<input type="hidden" name="menu-btn" value="'.$index['Me']['NO'].'" />';
      echo '<input type="hidden" name="reg_MaxSeq" value="'.count($ary_tra通知).'" />';
      if (count($ary_tra通知) == 0){
        //処理日以降の不定休設定はない
        echo '<h4>有効な通知内容はありません。</h4>';
      }
    ?>
    <table id="reg-table">
        <tr>
          <th class="clm-dateWhile">通知期間</th>
          <th class="clm-dis">区分</th>
          <th class="clm-msg">メッセージ</th>
          <th class="clm-link">リンク先</th>
          <th class="clm-delBtn">削除</th>
        </tr>

        <?php for($seq = 1; $seq <= count($ary_tra通知); $seq++){

            $row = $ary_tra通知[$seq - 1];

            $開始日時 = new DateTime($row['開始日時']);

            $終了日時 = new DateTime($row['終了日時']);
          ?>
          <tr>
          <td class="clm-dateWhile"><?php echo $開始日時->format('Y/m/d H:i').' 〜 '.$終了日時->format('Y/m/d H:i'); ?></td>

          <?php if($row['通知区分コード'] == 1){ ?>
            <td class="clm-dis">ショップお気に入りユーザ</td>
          <?php }elseif($row['通知区分コード'] == 2){ ?>
            <td class="clm-dis">全ユーザ</td>
          <?php } ?>

          <td class="clm-msg"><?php echo nl2br($row['メッセージ']); ?></td>
          <td class="clm-link"><?php echo $row['表示ページURL']; ?></td>
          <td class="clm-delBtn">
            <input type="checkbox" class="reg_checkbox" id="reg_checkbox<?php echo $seq; ?>" name="reg_削除<?php echo $seq; ?>" value="1" />
          </td>

          <?php /* 隠し項目 */ ?>
          <input type="hidden" name="reg_通知区分<?php echo $seq; ?>" value="<?php echo $row['通知区分コード']; ?>" />
          <input type="hidden" name="reg_開始日時<?php echo $seq; ?>" value="<?php echo $row['開始日時']; ?>" />
          <input type="hidden" name="reg_終了日時<?php echo $seq; ?>" value="<?php echo $row['終了日時']; ?>" />
          <input type="hidden" name="reg_メッセージ<?php echo $seq; ?>" value="<?php echo $row['メッセージ']; ?>" />
          <input type="hidden" name="reg_表示ページURL<?php echo $seq; ?>" value="<?php echo $row['表示ページURL']; ?>" />

          </tr>
        <?php } ?>
    </table>

    <div class="item">
      <button id="btn_delete" name="btn_delete" value="<?php echo モードコード['削除']; ?>">削除</button>
    </div>

  </form>
</div>